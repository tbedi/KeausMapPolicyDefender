<?php
include_once '../toMoney.php';
session_start();
$violators_array=$_SESSION['productArray'];
if(isset($_SESSION['productArray']))
{
     //  print_r($violators_array);
}




$filename="Product_Violations-".date('d-m-y').".csv";



header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');


/* columns */
$arr_columns = array(
    'SKU',
    'MAP',
    'Total Violations',
    'Max Violation Amount',
    'Min Violation Amount'
    
);
$arr_data = array();

foreach ($violators_array as $violators_array ){
    //print_r($row);die();
$arr_data_row = array($violators_array->sku,toMoney($violators_array->map_price),$violators_array->i_count,toMoney($violators_array->maxvio),toMoney($violators_array->minvio)) ;
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
