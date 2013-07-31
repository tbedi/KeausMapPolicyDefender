<html>
<body>
 <?php
include('db.php');
//$from = $_REQUEST['from'];
//$to = $_REQUEST['to'];
$dbTable="";
	$sql = "select catalog_product_flat_1.sku,
catalog_product_flat_1.name as pname,
website.name as wname, 
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
crawl.date_executed
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where 
crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl)
order by sku asc";

	$result = mysql_query($sql)	or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());

	header('Content-Type: application/vnd.ms-excel');	//define header info for browser
	
        header('Content-Disposition: attachment; filename='."Violation_History".'_'.date('d-m-y'));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<table><tr>';
	for ($i = 0; $i < mysql_num_fields($result); $i++)	 // show column names as names of MySQL fields
		echo '<th>'.mysql_field_name($result, $i).'</th>';
	print('</tr>');

	while($row = mysql_fetch_row($result))
	{
		//set_time_limit(60); // you can enable this if you have lot of data
		$output = '<tr>';
		for($j=0; $j<mysql_num_fields($result); $j++)
		{
			if(!isset($row[$j]))
				$output .= '<td>&nbsp;</td>';
			else
				$output .= "<td>$row[$j]</td>";
		}
		print(trim($output))."</tr>\t\n";
	}
	echo('</table>');
?>
</body>
</html>