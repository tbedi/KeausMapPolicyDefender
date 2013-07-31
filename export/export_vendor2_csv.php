<?php
session_start();
$violators_array=$_SESSION['vendor2Array'];
if(isset($_SESSION['vendor2Array']))
{
      // print_r($violators_array);
}
 $web_name = $_REQUEST['wname'];

$filename="Products_Violated_By-".$web_name."-".date('d-m-y').".csv";

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
//header('Content-Disposition: attachment; filename="Seller_Violation.csv"');
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'SKU',
    'Vendor Price',
    'MAP Price',
    'Violation Amount'
    
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ) {
    //print_r($row);die();
$arr_data_row = array($violators_array->sku,$violators_array->vendor_price,$violators_array->map_price,$violators_array->violation_amount);
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
