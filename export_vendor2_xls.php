<html>
<body>

<?php
include('db.php');
$web_id = $_REQUEST['website_id'];
$dbTable="";
	$sql = 
"select distinct 
catalog_product_flat_1.sku,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
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
	$result = mysql_query($sql)	or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());

	header('Content-Type: application/vnd.ms-excel');	
	
	header('Content-Disposition: attachment; filename='."Products_Violated_by_".$web_id.'-'.date('d-m-y'));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<table><tr>';
	for ($i = 0; $i < mysql_num_fields($result); $i++)	 
		echo '<th>'.mysql_field_name($result, $i).'</th>';
	print('</tr>');

	while($row = mysql_fetch_row($result))
	{
		
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