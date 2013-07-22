<?php

include('db.php');
$web_id = $_REQUEST['website_id'];
$dbTable = "";
$sql = "select distinct 
catalog_product_flat_1.sku,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
crawl_results.website_product_url
from crawl_results
inner join
website
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where crawl_results.violation_amount>0.05 
and
website.excluded = 0
and crawl_results.website_id = $web_id
order by violation_amount desc";

$result = mysql_query($sql) or die("Couldn't execute query:<br>" . mysql_error() . '<br>' . mysql_errno());

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="Seller_Violation.csv"');

/* columns */
$arr_columns = array(
    'SKU',
    'Vendor Price',
    'MAP Price',
    'Violation Amount',
    'Link'
    
);
$arr_data = array();

while ($row=  mysql_fetch_assoc($result)) {
    //print_r($row);die();
$arr_data_row = array($row['sku'],$row['vendor_price'],$row['map_price'],$row['violation_amount'],$row['website_product_url']) ;
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
