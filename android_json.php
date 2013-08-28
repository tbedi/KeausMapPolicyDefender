<?php
include './db.php';


$sql1="SELECT   
    catalog_product_flat_1.sku , crawl_results.website_id,
    date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
catalog_product_flat_1.entity_id as product_id,
website.name as vendor, 
crawl_results.vendor_price ,
crawl_results.map_price ,
crawl_results.violation_amount 
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where   
crawl_results.violation_amount>0.05   
and website.excluded=0 
order by violation_amount desc limit 15";

$result1=mysql_query($sql1);
//echo $result1;
while($row=mysql_fetch_array($result1))
{

$output[]=$row;

}
print(json_encode($output));
mysql_close();

?>
