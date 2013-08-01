<?php


$sku=$_REQUEST['sku'];
session_start();
$violators_array=$_SESSION['product2Array'];

if(isset($_SESSION['product2Array']))
{
    //   print_r($violators_array);
}




$filename="Vendors_Violated-".$sku."-".date('d-m-y').".csv";


header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');
//header('Content-Disposition: attachment; filename="Product_Violation.csv"');
//header('Content-Disposition: attachment; filename='."Sellers_Violated_".$product_id.'_'.date('Y-m-d'));

/* columns */
$arr_columns = array(
    'Website',
    'Seller Price',
    'MAP',
    'Violation'
    
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ){
    //print_r($row);die();
$arr_data_row = array($violators_array->vendor,$violators_array->vendor_price,$violators_array->map_price,$violators_array->violation_amount);
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
