<?php

include_once '../toMoney.php';
include_once './db_class.php';
include_once './db.php';
session_start();

$vviolationTitle=$_SESSION['vviolationTitle'];

if(isset($_SESSION['vviolationTitle']))
$_SESSION['vviolationTitle'];

if(isset($_POST['listv']))
 $_SESSION['listv']=$_POST['listv'];
if(isset($_POST['selectallvendor']))
 $_SESSION['selectallvendor']=$_POST['selectallvendor'];

if (isset($_SESSION['website_id'])) {
    $web_id = $_SESSION['website_id'];
}
$db_resource = new DB ();
//$limit=15;
//$start=0;
//$limitvcon="";
   $conVendorExport="";

//if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
//	$limit=$_GET['limit2'];
//} 
//if  (!isset($_SESSION['selectallvendor']) and $_SESSION['selectallvendor']!=='1')
//{ 
//       $limitvcon = "  LIMIT $start, $limit ";
//}

 
if (isset($_SESSION['listv']) and $_SESSION['listv']!=0)
{
    $arrExportVendor;
    $arrExportVendor=$_SESSION['listv'];
    
    // print_r($arrExportRecent);
    $conVendorExport=" and crawl_results.id in (". $_SESSION['listv']. ")" ;
    
}
 else {
    $conVendorExport="";
}
     if  (isset($_SESSION['selectallvendor']) )
{
//    $limitvcon="";
      $conVendorExport="";
}

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


//$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
//$result = mysql_query($sql);
//$last_crawl = mysql_fetch_assoc($result);


$sql = "select distinct crawl_results.website_id,date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
website.name as wname,crawl_results.id as id,
catalog_product_flat_1.entity_id,
 catalog_product_flat_1.name as name,
 catalog_product_flat_1.sku, 
crawl_results.vendor_price ,
 crawl_results.map_price ,
 crawl_results.violation_amount ,
crawl_results.website_product_url 
from crawl_results
 inner join crawl on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where  crawl_results.violation_amount>0.05 
and
website.excluded=0 " . $conVendorExport . " 
and website_id = $web_id  ".$order_by; 

 
$violators_array=$db_resource->GetResultObj($sql);
//echo $sql;
//$violators_array=$_SESSION['vendor2Array'];
//if(isset($_SESSION['vendor2Array']))
//{
//      // print_r($violators_array);
//}


$filename="Products_Violated_By-".$_SESSION['vviolationTitle']."-".date('d-m-y').".csv";

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
//header('Content-Disposition: attachment; filename="Seller_Violation.csv"');
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'SKU',
    'Dealers Price',
    'MAP Price',
    'Violation Amount',
    'Date'
    
    
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ) {
    //print_r($row);die();
$arr_data_row = array($violators_array->sku,toMoney($violators_array->vendor_price),toMoney($violators_array->map_price),toMoney($violators_array->violation_amount),$violators_array->date_executed);
/* push data to array */
array_push($arr_data, $arr_data_row);
} //do it here
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
