<?php
/*where*/
$where = "";

if (isset($_GET['action']) && $_GET['action'] == 'searchv2' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
	$field = strtolower($_GET['field']);
	$value = strtolower($_GET['value']);
	$where = "  AND  " . $field . "  LIKE '%" . $value . "%'";
}
/*where*/
$sql = 
        
        "select distinct crawl_results.website_id,
domain,
website.name as wname,
catalog_product_flat_1.entity_id,
catalog_product_flat_1.name as name,
catalog_product_flat_1.sku,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
website_id,
crawl_results.website_product_url
from crawl_results
inner join
website
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and website_id = '" .$website_id. "'
  " . $where ;
        
        
      
//pagination
$limit = 10;

$violators_all_array=$db_resource->GetResultObj($sql);
$total_pages =  count($violators_all_array);
//pagination
/*second grid pagination*/
if (isset($_GET['second_grid_page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $page = $_GET['second_grid_page']; //$page_param should have same value
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
/*second grid pagination*/

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vvendor_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vvendor_2_dir']=$_GET['dir'];
	$_SESSION['sort_vvendor_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vvendor_2_field']) && isset($_SESSION['sort_vvendor_2_dir']) ) {
	$direction = $_SESSION['sort_vvendor_2_dir'];
	$order_field =$_SESSION['sort_vvendor_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vvendor_2_dir'] = "desc";
	$_SESSION['sort_vvendor_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */

$sql = "select distinct crawl_results.website_id,
domain,
website.name as wname,
catalog_product_flat_1.entity_id,
catalog_product_flat_1.name as name,
catalog_product_flat_1.sku,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
website_id,
crawl_results.website_product_url
from crawl_results
inner join
website
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and website_id = $website_id " . $where . " 
     ".$order_by." LIMIT $start, $limit";

 
$violators_array=$db_resource->GetResultObj($sql);
 
// Initial page num setup
$tab_name = 'violation-by-seller';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$page_param = "second_grid_page"; //variable used for pagination
$additional_params = "&website_id=" . $website_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page']; //here it should 
}

include_once 'template/vendor_violation_detail.phtml';
?>
  
<div align="left" style="display:block;" >
	<?php include ('page2.php'); ?>
</div>	
 
<div>
    <?php include_once 'charts/a4.php'; ?>
</div>
