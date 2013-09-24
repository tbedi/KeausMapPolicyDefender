<?php
//pagination


$limit = 20;
$start = 0;

// Product
$product_id =(isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 0);//get product id
$website_id=(isset($_REQUEST['website_id']) ? $_REQUEST['website_id'] : 0);// get website id
 
$_SESSION['limit'] = $limit;  //storing limit in session to work with pagination n all

if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ) {
    $limit = $_GET['limit']; //get limit param
}
 

/* Calendar*/
//$datecp - From dashboard.php
$to=date("Y-m-d 23:59:59",strtotime($datecp[0]->date_executed)); 
$from=date("Y-m-d 00:00:00",strtotime($datecp[0]->date_executed));
if ( isset($_REQUEST['to'])  ||  isset($_REQUEST['from'])) {
	
	if (isset($_REQUEST['to'])) 
		$to=date("Y-m-d  23:59:59",strtotime($_REQUEST['to']));
			
	if (isset($_REQUEST['from']))
		$from=date("Y-m-d 00:00:00",strtotime($_REQUEST['from'])); 	
}
/* Calendar*/
/* sorting */
if (isset($_GET['sort']) && isset($_GET['dir']) && isset($_GET['grid']) && $_GET['grid'] == "history") {
    $direction = $_GET['dir'];
    $order_field = $_GET['sort'];
    $_SESSION['sort_history_dir'] = $_GET['dir'];
    $_SESSION['sort_history_field'] = $_GET['sort'];
} else if (isset($_SESSION['sort_history_field']) && isset($_SESSION['sort_history_dir'])) {
    $direction = $_SESSION['sort_history_dir'];
    $order_field = $_SESSION['sort_history_field'];
} else {
    $direction = "desc";
    $order_field = "violation_amount";
    $_SESSION['sort_history_dir'] = "desc";
    $_SESSION['sort_history_field'] = "violation_amount";
}
$order_by = "order by " . $order_field . " " . $direction . " ";
/* sorting */

/* Pagination */
if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
     $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
  } 
else{
       $page = 1;
       $start = 0;
}
$limithcon = "  LIMIT $start, $limit ";
/* Pagination */

/* Search Condition*/
$search_condition="";
if (isset($_REQUEST['action']) && $_REQUEST['action']=="search")
{
	$searched_sku=(isset($_REQUEST['sku']) ? $_REQUEST['sku'] : "" );
    $searched_dealer=(isset($_REQUEST['dealer']) ? $_REQUEST['dealer'] : "" );
   
    
    if ($searched_sku)
		$search_condition.=" AND sku = '".$searched_sku."' ";
    if ($searched_dealer)
    	$search_condition.=" AND website.name  LIKE '".$searched_dealer."%' ";    
}
/* Search Condition*/


/* Search Condition*/
 /*Add selected ids */

 // condition fot get selected website id
if ($website_id) {
    $website_id=mysql_real_escape_string($website_id); 
    $search_condition.= "  AND  website_id  = " ." $website_id ". ""; 
}
 
  // condition fot get selected product id
if ($product_id) {
    $product_id=mysql_real_escape_string($product_id); 
     $search_condition.= "  AND  product_id  = " ." $product_id ". ""; 
}
/*Add selected ids */
                          

$sql = "SELECT SQL_CALC_FOUND_ROWS   catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,  date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
			 	catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id, website.name as vendor,  crawl_results.vendor_price , crawl_results.map_price ,
				crawl_results.violation_amount , crawl_results.website_product_url, crawl_results.seller as seller
				FROM website
				INNER JOIN crawl_results ON website.id = crawl_results.website_id
				INNER JOIN catalog_product_flat_1 ON catalog_product_flat_1.entity_id=crawl_results.product_id
				INNER JOIN crawl ON crawl.id=crawl_results.crawl_id
				WHERE  crawl.date_executed  BETWEEN '".$from."'  AND  '".$to."'
 				".$search_condition."
				AND	crawl_results.violation_amount>0.05   
				AND website.excluded=0  
				" . $order_by . "$limithcon " ;
$violators_array = $db_resource->GetResultObj($sql);


//pagination
$sql1 = " SELECT FOUND_ROWS() as total;";
$total_pages = $db_resource->GetResultObj($sql1);
$total_pages = $total_pages[0]->total;

$tab_name = 'violations-history';
$page_param = "page"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/


/*For sorting using*/
$additional_params = "&limit=".$limit;
if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
    $additional_params.="&second_grid_page=" . $_GET['second_grid_page'];
}

if (isset($_GET['website_id']) && $_GET['website_id']) { //adding support for website
    $additional_params.="&website_id=" . $_GET['website_id'];
}



if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
}

/* For sorting using */



/*For sorting using*/

include_once 'template/history_tab.phtml';
?>

<?php

//get details or second grid if product or vendor selected
if ($product_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'pviolation.php';
}


if ($website_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'vviolation.php';
}
//get details or second grid if product or vendor selected

?>
