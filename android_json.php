<?php
include './db.php';


$sql1="SELECT   
     crawl_results.website_id,
   crawl_results.violation_amount 
from crawl_results
where   
crawl_results.violation_amount>0.05   
order by violation_amount desc limit 1";

$result1=mysql_query($sql1);
//echo $result1;
while($row=mysql_fetch_array($result1))
{

$output[]=$row;

}
print(json_encode($output));
mysql_close();

?>
