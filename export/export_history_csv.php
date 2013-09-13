<?php 
/*GLOBAL*/

/*configuration*/
setlocale(LC_MONETARY, 'en_US');
include_once '../db.php';
include_once '../db_login.php';
 
/*Login check*/
if (!isset($_SESSION['username']))
	header('Location: login.php');

/*configuration*/
include_once '../db_class.php';
$db_resource = new DB ();
include_once '../toMoney.php';
/*GLOBAL*/

/*Prepare values for grid*/
$limit = 15;
$product_id =(isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 0);
$website_id=(isset($_REQUEST['website_id']) ? $_REQUEST['website_id'] : 0);

//Calendar
$to=date("Y-m-d 23:59:59",strtotime($_GET['to']));
$from=date("Y-m-d 00:00:00",strtotime($_GET['from']));
//Sorting 
$direction = $_GET['order_direction'];
$order_field = $_GET['order_field'];
$order_by = "order by " . $order_field . " " . $direction . " ";

/* Pagination */
if (isset($_GET['limit']) ) {
	$limit = $_GET['limit'];
}

if (isset($_GET['page'])) {
	// echo $_GET['page'];
	$page = mysql_escape_string($_GET['page']);
	$start = ($page - 1) * $limit;
	// echo $start;
} else {
	$start = 0;
	$page = 1;
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
if ($website_id) {
	$website_id=mysql_real_escape_string($website_id);
	$search_condition.= "  AND  website_id  = " ." $website_id ". "";
}


if ($product_id) {
	$product_id=mysql_real_escape_string($product_id);
	$search_condition.= "  AND  product_id  = " ." $product_id ". "";

}
/* Search Condition*/

/****QUERY****/

$sql = "SELECT SQL_CALC_FOUND_ROWS   catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,  date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
			 	catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id, website.name as dealer,  crawl_results.vendor_price , crawl_results.map_price ,
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
/*CSV*/
$filename="violation_history-".date('d-m-y').".csv";

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array('SKU','Dealers','Dealers Price','MAP Price','Violation Amount','Date');
$arr_data = array();

foreach ($violators_array as $violator ) {
$arr_data_row = array($violator->sku,$violator->dealer,toMoney($violator->vendor_price),toMoney($violator->map_price),toMoney($violator->violation_amount),$violator->date_executed) ;
/* push data to array */
array_push($arr_data, $arr_data_row);
} 
exportCSV($arr_data, $arr_columns);

function exportCSV($data, $col_headers = array(), $return_string = false) {
    $stream = ($return_string) ? fopen('php://temp/maxmemory', 'w+') : fopen('php://output', 'w');
    if (!empty($col_headers)) { fputcsv($stream, $col_headers); }    
    foreach ($data as $record) { fputcsv($stream, $record);}
    if ($return_string) {
        rewind($stream);
        $retVal = stream_get_contents($stream);
        fclose($stream);
        return $retVal;
    } else {
        fclose($stream);
    }
}

?>
