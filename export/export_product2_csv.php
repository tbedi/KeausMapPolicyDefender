<?php

include_once './db_class.php';
include_once '../toMoney.php';
include_once './db.php';
$sku="";
$product_id="";

if(isset($_SESSION['pviolationTitle']))
{
$sku=$_SESSION['pviolationTitle'];
}

session_start();
//if(isset($_SESSION['listp']))
// $_SESSION['listp'];
//if(isset($_SESSION['selectallproduct']))
// $_SESSION['selectallproduct'];

if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id'];
}
//echo $_SESSION['selectallproduct'];
//data collection
$db_resource = new DB ();
//$limit=15;
//$start=0;
//$limitpcon="";
//
//if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
//	$limit=$_GET['limit2'];
//} 
//if  (!isset($_SESSION['selectallproduct']) )
//{ 
//       $limitpcon = "  LIMIT $start, $limit ";
//}
// else {
//     $limitpcon="";
//}


if (isset( $_SESSION['listp']) and $_SESSION['listp']!=0 )
{
    $arrExportProduct=  $_SESSION['listp'];
    
    // print_r($arrExportRecent);
    $conProductExport=" and r.id in (". $_SESSION['listp']. ")" ;
  //  echo $_SESSION['listp'];
}
 else {
     $conProductExport="";
    
}
     if  (isset($_SESSION['selectallproduct']) and $_SESSION['selectallproduct']=='1')
{
//    $limitpcon="";
      $conProductExport="";
}
//echo $_REQUEST['selectallproduct'];
echo $conProductExport;
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


$sql = "SELECT  distinct w.`name` as vendor ,date_format(c.date_executed,'%m-%d-%Y') as date_executed,
    r.violation_amount as violation_amount,r.id as id,
    w.id as website_id,
    r.vendor_price as vendor_price,
    r.map_price ,
    r.website_product_url,
    p.sku as sku
    FROM crawl_results  r
    inner join crawl c on c.id=r.crawl_id
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    where r.violation_amount>0.05  and w.excluded=0  " . $conProductExport . " 
   " . $order_by   ;
 
$violators_array=$db_resource->GetResultObj($sql);
//print_r($sql);

//session_start();
//$violators_array=$_SESSION['product2Array'];
//
//if(isset($_SESSION['product2Array']))
//{
//    //   print_r($violators_array);
//}




$filename="Dealers_Violated-".$sku."-".date('d-m-y').".csv";
header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');
//header('Content-Disposition: attachment; filename="Product_Violation.csv"');
//header('Content-Disposition: attachment; filename='."Sellers_Violated_".$product_id.'_'.date('Y-m-d'));

/* columns */
$arr_columns = array(
    'Dealers',
    'Dealers Price',
    'MAP',
    'Violation',
    'Date'
    
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ){
    //print_r($row);die();
$arr_data_row = array($violators_array->vendor,toMoney($violators_array->vendor_price),toMoney($violators_array->map_price),toMoney($violators_array->violation_amount),$violators_array->date_executed);
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
  
 

//unset($_SESSION['listp']);
//unset($_SESSION['selectallproduct']);


?>

