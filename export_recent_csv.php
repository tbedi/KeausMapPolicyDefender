<?php

include('db.php');

$dbTable = "";
$sql = "select catalog_product_flat_1.sku,
website.name as wname, 
crawl_results.vendor_price,
crawl_results.map_price,
crawl_results.violation_amount,
crawl_results.website_product_url
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join
crawl 
on crawl.id=crawl_results.crawl_id
where crawl_results.violation_amount>0.05 
and
crawl.id = 
(select max(crawl.id) from crawl)
order by sku asc";

$result = mysql_query($sql) or die("Couldn't execute query:<br>" . mysql_error() . '<br>' . mysql_errno());





header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="filename.csv"');

/* columns */
$arr_columns = array(
    'SKU',
    'Seller Name',
    'Seller Price',
    'Map Price',
    'Violation Amount',
    'URL'
);
$arr_data = array();

while ($row=  mysql_fetch_assoc($result)) {
    //print_r($row);die();
$arr_data_row = array($row['sku'],$row['wname'],$row['vendor_price'],$row['map_price'],$row['violation_amount'],$row['website_product_url']) ;
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
