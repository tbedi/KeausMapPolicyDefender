<?php

include('db.php');

$dbTable = "";
$sql = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.entity_id as product_id,
catalog_product_flat_1.name,
format(crawl_results.vendor_price,2) as vendor_price ,
format(crawl_results.map_price,2)as map_price,
max(crawl_results.violation_amount) as maxvio,
min(crawl_results.violation_amount) as minvio,
count(crawl_results.product_id) as i_count
FROM
prices.catalog_product_flat_1
inner join
prices.crawl_results
on catalog_product_flat_1.entity_id = crawl_results.product_id 
inner join crawl
on
crawl_results.crawl_id = crawl.id 
where crawl_results.violation_amount>0.05
 and 
crawl.id = 
(select max(crawl.id) from crawl) 
group by prices.catalog_product_flat_1.sku,
prices.catalog_product_flat_1.name
order by maxvio desc";

$result = mysql_query($sql) or die("Couldn't execute query:<br>" . mysql_error() . '<br>' . mysql_errno());

$filename="Product_Violations-".date('d-m-y').".csv";



header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');
//header('Content-Disposition: attachment; filename="Product_Violation.csv"');
//header('Content-Disposition: attachment; filename="Recent_Violations"'.'_'.date('Y-m-d'));
//header('Content-Disposition: attachment; filename='."Product_Violations_".'_'.date('Y-m-d'));
//header('Content-Disposition: attachment; filename='."Product_Violations".'_'.date('Y-m-d'));


/* columns */
$arr_columns = array(
    'SKU',
    'MAP',
    'Total Violations',
    'Max Violation Amount',
    'Min Violation Amount'
    
);
$arr_data = array();

while ($row=  mysql_fetch_assoc($result)) {
    //print_r($row);die();
$arr_data_row = array($row['sku'],$row['map_price'],$row['i_count'],$row['maxvio'],$row['minvio']) ;
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
