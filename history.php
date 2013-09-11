<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";

$limit = 15;
$start = 0;
 
$additional_params;
//$searchfield;
// Product
$product_id =(isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 0);
 
$website_id=(isset($_REQUEST['website_id']) ? $_REQUEST['website_id'] : 0);
 
/////////


$_SESSION['limit'] = $limit; // ???
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ) {
    $limit = $_GET['limit'];
}

/* where */
$where = "";

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
   // echo $_GET['page'];
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
   // echo $start;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */

$limithcon = "  LIMIT $start, $limit ";

 
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
 /*Add selected ids */

 
if ($website_id) {
    $website_id=mysql_real_escape_string($website_id); 
    $search_condition.= "  AND  website_id  = " ." $website_id ". ""; 
}
 
 
if ($product_id) {
    $product_id=mysql_real_escape_string($product_id); 
     $search_condition.= "  AND  product_id  = " ." $product_id ". ""; 
}
/*Add selected ids */

 /* ?? */
if (isset($_REQUEST['selectallhistory']))
{
     $_SESSION['selectallhistory'] = $_REQUEST['selectallhistory'];
     //echo      $_SESSION['selectallRecent'];
}
/* ?? */

if (isset($_REQUEST['listh']) ) 
{
    $_SESSION['listh'] = $_REQUEST['listh'];
}
if (isset($_REQUEST['selectallproduct']))
{
     $_SESSION['selectallproduct'] = $_REQUEST['selectallproduct'];
     
}
/* ?? */

if (isset($_REQUEST['listp']) ) 
{
    $_SESSION['listp'] = $_REQUEST['listp'];
}

/*Limit ??? *//*
if(isset($_GET['limit']))
        {
            $urls.="&limit=".$limit;
        }
        
        
        if (isset($_REQUEST['action']) and isset($_REQUEST['value'])) {
            
            $urls = "?tab=violations-history&option=show_dates&value=" . $_REQUEST['value'];
        }
        
         if (isset($_REQUEST['product_id']) ){
                $urls.="&product_id=".$_REQUEST['product_id']; 
                $limit = 15;
        }
        if (isset($_REQUEST['website_id']) ){
                $urls.="&website_id=".  $_REQUEST['website_id'];  
                 $limit = 15;
        }*/
 /*Limit  ???*/
                           

$sql = "SELECT SQL_CALC_FOUND_ROWS   catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,  date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
			 	catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id, website.name as vendor,  crawl_results.vendor_price , crawl_results.map_price ,
				crawl_results.violation_amount , crawl_results.website_product_url
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
if (isset($_GET['action']) && $_GET['action'] && isset($_GET['website_id'])) { // search w
    $additional_params.="&action=" . $_GET['action'] . "&field=website_id&value=" . $_GET['value'];
}


if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
}
if (isset($_GET['action']) && $_GET['action']  && isset($_GET['product_id'])) { // search s
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
/* For sorting using */



/*For sorting using*/

include_once 'template/history_tab.phtml';
?>

<?php


if ($product_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'pviolation.php';
}


if ($website_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'vviolation.php';
}


?>
