<?php
include_once '../toMoney.php';
include_once './db_class.php';
session_start();
if(isset($_SESSION['listh']))
 $_SESSION['listh'];
if(isset($_SESSION['selectallhistory']))
 $_SESSION['selectallhistory'];
//echo $_SESSION['selectallhistory'];

$db_resource = new DB ();
$limit=15;
$start=0;
$limithcon="";
//$_SESSION['limit'] = $limit;
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ) {
    $limit = $_GET['limit'];
}
if  (!isset($_SESSION['selectallhistory']) and $_SESSION['selectallhistory']!=='1')
{ 
       $limithcon = "  LIMIT $start, $limit ";
}


if (isset( $_SESSION['listh']) and  $_SESSION['listh']!="")
{
    $arrExportHistory=  $_SESSION['listh'];
    
    // print_r($arrExportHistory);
    $conHistoryExport=" and crawl_results.id in (". $arrExportHistory. ")" ;
    
}
 else {
     $conRecentExport="";
}
     if  (isset($_SESSION['selectallhistory']) and $_SESSION['selectallhistory']=='1')
{
    $limithcon="";
      $conHistoryExport="";
}


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


 $sql = "SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,
    date_format(crawl.date_executed,'%Y-%m-%d %H:%i:%s') as date_executed,
catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id,
website.name , 
crawl_results.vendor_price ,
crawl_results.map_price ,
crawl_results.violation_amount ,
crawl_results.website_product_url
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where 
crawl_results.violation_amount>0.05   
and website.excluded=0 " . $conHistoryExport . " 
" . $order_by . "$limithcon " ;

$violators_array = $db_resource->GetResultObj($sql);





//$violators_array=$_SESSION['historyArray'];
//if(isset($_SESSION['historyArray']))
//{
//      // print_r($violators_array);
//}

$filename="Violation_History-".date('d-m-y').".csv";




header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');
//header('Content-Disposition: attachment; filename="Violation_History.csv"');
//header('Content-Disposition: attachment; filename='."Violation_History".'_'.date('Y-m-d'));


/* columns */
$arr_columns = array(
   
    'SKU',
    'Dealers',
    'Dealers Price',
    'MAP Price',
    'Violation Amount',
   
    
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ) {
    //print_r($row);die();
$arr_data_row = array($violators_array->sku,$violators_array->name,toMoney($violators_array->vendor_price),toMoney($violators_array->map_price),toMoney($violators_array->violation_amount)) ;
/* push data to array */
array_push($arr_data, $arr_data_row);
} 
exportCSV($arr_data, $arr_columns);


function exportCSV($data, $col_headers = array(), $return_string = false) {
    $stream = ($return_string) ? fopen('php://temp/maxmemory', 'w+') : fopen('php://output', 'w');

    if (!empty($col_headers)) {
        fputcsv($stream, $col_headers);
    }
    
    foreach ($data as $record) {
        fputcsv($stream, $record);
    }

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
