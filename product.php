<?php
$limitp = 15;

$_SESSION['limitp'] = $limitp;
if (isset($_GET['limitp'])) {
	$limitp=$_GET['limitp'];
} 

$product_id=0;

if (isset($_REQUEST['product_id'])) {
	$product_id = $_REQUEST['product_id'];
}

 /*where*/
$where = "";
if (isset($_GET['action']) && $_GET['action'] == 'searchfirst' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  " . $field . "  LIKE '%" . $value . "%'";
}

if ($product_id) {
 $where = "  AND  entity_id  = '" . $product_id . "'"; ;
}
/*where*/

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vproduct"  ) {  
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vproduct_dir']=$_GET['dir'];
	$_SESSION['sort_vproduct_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vproduct_field']) && isset($_SESSION['sort_vproduct_dir']) ) {
	$direction = $_SESSION['sort_vproduct_dir'];
	$order_field =$_SESSION['sort_vproduct_field'];
} else {
	$direction = "desc";
	$order_field = "maxvio";
	$_SESSION['sort_vproduct_dir'] = "desc";
	$_SESSION['sort_vproduct_field'] = "maxvio";
}

$order_by = "order by " . $order_field . " " . $direction . " ";

/* sorting */

/* Pagination */
if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limitp;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */


// Get page data
if ((isset ($_GET['flag']) && $_GET['flag'] == '1') || (isset($_GET['action']) && $_GET['action'] == "searchfirst")  )
 {
 $sql=    "SELECT SQL_CALC_FOUND_ROWS  catalog_product_flat_1.sku, 
     catalog_product_flat_1.entity_id as product_id,website.excluded,
     catalog_product_flat_1.name, website.name as wname, 
     crawl_results.vendor_price as vendor_price,
     cast(crawl_results.map_price as decimal(10,2)) as map_price, 
      cast(max(crawl_results.violation_amount) as decimal(10,2)) as maxvio,
cast(min(crawl_results.violation_amount) as decimal(10,2)) as minvio,
         count(crawl_results.product_id) as i_count
		   FROM prices.catalog_product_flat_1 
		   INNER JOIN prices.crawl_results ON catalog_product_flat_1.entity_id = crawl_results.product_id 
		   INNER JOIN crawl ON crawl_results.crawl_id = crawl.id
                   INNER JOIN website ON website.id=crawl_results.website_id 
		   WHERE crawl_results.violation_amount>0.05 AND website.excluded=0 
                   AND crawl.id = (SELECT id  FROM crawl  ORDER BY id DESC  LIMIT 1) " . $where . "
 		   GROUP BY catalog_product_flat_1.sku, catalog_product_flat_1.name 
		   ".$order_by." LIMIT $start, $limitp";  
}

 else {
    
$sql=    "SELECT SQL_CALC_FOUND_ROWS  catalog_product_flat_1.sku,  
    catalog_product_flat_1.entity_id as product_id,website.excluded,
    catalog_product_flat_1.name,  
    crawl_results.vendor_price as vendor_price,
    cast(crawl_results.map_price as decimal(10,2)) as map_price,
  cast(max(crawl_results.violation_amount) as decimal(10,2)) as maxvio,
cast(min(crawl_results.violation_amount) as decimal(10,2)) as minvio,
    count(crawl_results.product_id) as i_count
		   FROM prices.catalog_product_flat_1 
		   INNER JOIN prices.crawl_results ON catalog_product_flat_1.entity_id = crawl_results.product_id 
		   INNER JOIN crawl ON crawl_results.crawl_id = crawl.id 
                    INNER JOIN website ON website.id=crawl_results.website_id  
		   WHERE crawl_results.violation_amount>0.05 AND website.excluded=0 
                   AND crawl.id = (SELECT id  FROM crawl  ORDER BY id DESC  LIMIT 1)
 		   GROUP BY catalog_product_flat_1.sku, catalog_product_flat_1.name 
		   ".$order_by." LIMIT $start, $limitp";  
}



$page_violated_products=$db_resource->GetResultObj($sql);

$_SESSION['productArray']=$page_violated_products;
if(isset($_SESSION['productArray']))
{
   // print_r($_SESSION['recentArray']); 
  
}


//$result = mysql_query($query1);

// Initial page num setup
$sql=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql);
$total_pages=$total_pages[0]->total;

$tab_name = 'violation-by-product';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limitp);
$LastPagem1 = $lastpage - 1;

$page_param = "page"; //variable used for pagination
//$additional_params = ""; //addtiion params to pagination url;
$additional_params = "&limitp=".$limitp;


if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
    $additional_params.="&second_grid_page=" . $_GET['second_grid_page'];
}
if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
}
if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
 

include_once 'template/product_violation_tab.phtml';
?>
 

<?php
if ($total_pages == 1) {
	$product_id = $page_violated_products[0]->product_id;
}

if ($product_id && isset($_GET['tab']) && $_GET['tab'] == "violation-by-product") {
    include_once 'pviolation.php';
}
?>