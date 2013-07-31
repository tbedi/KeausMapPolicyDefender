<?php
$limit=15;
session_start();
$violators_array=$_SESSION['recentArray'];
//if(isset($_SESSION['recentArray']))
//{
      // print_r($violators_array);
//}




$filename="Recent_Violations-".date('d-m-y').".csv";


header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'SKU',
    'Seller Name',
    'Seller Price',
    'Map Price',
    'Violation Amount'
  
);
$arr_data = array();

foreach ($violators_array as $violators_array ) {
    //print_r($row);die();
$arr_data_row = array($violators_array->sku,$violators_array->name,$violators_array->vendor_price,$violators_array->map_price,$violators_array->violation_amount) ;
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
