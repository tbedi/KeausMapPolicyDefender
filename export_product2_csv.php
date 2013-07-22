<?php

include('db.php');
$product_id = $_REQUEST['product_id'];
$dbTable = "";
$sql = "SELECT  distinct w.`name` as vendor , crawl.id,
    format(r.violation_amount,2) as violation_amount,
    format( r.vendor_price,2) as vendor_price,
    format(r.map_price,2) as map_price,
    r.website_product_url
    FROM crawl_results  r
    INNER JOIN website w
    ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p
    ON p.entity_id=r.product_id
    AND p.entity_id='" . $product_id . "'
        inner join crawl
on crawl.id=r.crawl_id
    WHERE crawl.id = 
(select max(crawl.id) from crawl) 
		    AND r.violation_amount>0.05
                    and w.excluded=0
		    ORDER BY r.violation_amount DESC";

$result = mysql_query($sql) or die("Couldn't execute query:<br>" . mysql_error() . '<br>' . mysql_errno());





header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="Product_Violation.csv"');
//header('Content-Disposition: attachment; filename='."Sellers_Violated_".$product_id.'_'.date('Y-m-d'));

/* columns */
$arr_columns = array(
    'Website',
    'Seller Price',
    'MAP',
    'Violation',
    'Link'
    
);
$arr_data = array();

while ($row=  mysql_fetch_assoc($result)) {
    //print_r($row);die();
$arr_data_row = array($row['vendor'],$row['vendor_price'],$row['map_price'],$row['violation_amount'],$row['website_product_url']) ;
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
