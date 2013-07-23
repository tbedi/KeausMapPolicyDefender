<?php

include('db.php');

$dbTable = "";
$sql = "select website.name as wname,
crawl_results.website_id,
format(max(crawl_results.violation_amount),2) as maxvio,
format(min(crawl_results.violation_amount),2) as minvio,
count(crawl_results.website_id) as wi_count
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on
crawl_results.crawl_id = crawl.id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl)
group by website.name , crawl_results.website_id
order by crawl_results.website_id desc";

$result = mysql_query($sql) or die("Couldn't execute query:<br>" . mysql_error() . '<br>' . mysql_errno());
$filename="PVendor_Violations-".date('d-m-y').".csv";

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
//header('Content-Disposition: attachment; filename="Seller_Violation.csv"');
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'Seller',
    'Violation Count',
    'Max Violation Amount',
    'Min Violation Amount'
    
);
$arr_data = array();

while ($row=  mysql_fetch_assoc($result)) {
    //print_r($row);die();
$arr_data_row = array($row['wname'],$row['wi_count'],$row['maxvio'],$row['minvio']) ;
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
