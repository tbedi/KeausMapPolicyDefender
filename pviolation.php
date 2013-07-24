<?php
/*where*/
$where = "";

if (isset($_GET['action']) && $_GET['action'] == 'search2' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
	$field = strtolower($_GET['field']);
	$value = strtolower($_GET['value']);
	$where = "  AND  " . $field . "  LIKE '%" . $value . "%'";
}
/*where*/
$sql = "SELECT  distinct w.`name` as vendor ,
    format(r.violation_amount,2) as violation_amount,
    format( r.vendor_price,2) as vendor_price,
    format(r.map_price,2) as map_price,
    r.website_product_url,
	p.sku as sku
    FROM crawl_results  r
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    WHERE r.crawl_id= (SELECT id  FROM crawl  ORDER BY id DESC  LIMIT 1)  AND r.violation_amount>0.05 AND w.excluded=0 " . $where ;
//pagination
$limit = 10;

$violators_all_array=$db_resource->GetResultObj($sql);
$total_pages =  count($violators_all_array);
//pagination
/*second grid pagination*/
if (isset($_GET['second_grid_page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
    $page = $_GET['second_grid_page']; //$page_param should have same value
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
/*second grid pagination*/

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vproduct_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vproduct_2_dir']=$_GET['dir'];
	$_SESSION['sort_vproduct_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vproduct_2_field']) && isset($_SESSION['sort_vproduct_2_dir']) ) {
	$direction = $_SESSION['sort_vproduct_2_dir'];
	$order_field =$_SESSION['sort_vproduct_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vproduct_2_dir'] = "desc";
	$_SESSION['sort_vproduct_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */

$sql = "SELECT  distinct w.`name` as vendor , r.violation_amount as violation_amount, r.vendor_price as vendor_price, r.map_price as map_price, r.website_product_url, p.sku as sku
    FROM crawl_results  r
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    WHERE r.crawl_id=" . $last_crawl['id'] . " AND r.violation_amount>0.05 AND w.excluded=0  " . $where . " 
    ".$order_by." LIMIT $start, $limit";
 
$violators_array=$db_resource->GetResultObj($sql);
 
// Initial page num setup
$tab_name = 'violation-by-product';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$page_param = "second_grid_page"; //variable used for pagination
$additional_params = "&product_id=" . $product_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page']; //here it should 
}

include_once 'template/product_violation_detail.phtml';
?>
  
<div class="page2" >
	<?php include ('page2.php'); ?>
</div>	
 
<div>
    <?php include_once 'charts/a3.php'; ?>
</div>
