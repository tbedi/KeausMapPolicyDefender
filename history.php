

<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 10;
$to= date("Y-m-d");
    $from= date('Y-m-d',strtotime("-1 days"));
/*where*/
$where = "";

   


/*where*/

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="history"  ) {  
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_history_dir']=$_GET['dir'];
	$_SESSION['sort_history_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_history_field']) && isset($_SESSION['sort_history_dir']) ) {
	$direction = $_SESSION['sort_history_dir'];
	$order_field =$_SESSION['sort_history_field'];
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
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */





//if (isset($_GET['action']) && $_GET['action'] == 'searchh' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['option']) && $_GET['option'] == 'show_dates') {
	 
	$to = $_POST["to"];
	$from = $_POST["from"];
	

}
else if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' )
{
    $to= date("Y-m-d");
    $from= date('Y-m-d',strtotime("-1 days"));
}
    

$sql="SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku,
catalog_product_flat_1.name as pname,
website.name as wname, 
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
crawl_results.website_product_url,
crawl.date_executed
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where crawl.date_executed between '$from' and '$to'  
and
crawl_results.violation_amount>0.05 
and website.excluded=0 
" . $order_by . " LIMIT $start, $limit ";



$violators_array=$db_resource->GetResultObj($sql);

//$result = mysql_query();




// Initial page num setup
$sql1=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql1);
$total_pages=$total_pages[0]->total;

$tab_name = 'violations-history';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;

$page_param = "page"; //variable used for pagination
$additional_params = ""; //addtiion params to pagination url;



if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
 

include_once 'template/history_tab.phtml';



?>

