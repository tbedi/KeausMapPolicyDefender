<html>
<body>
 <?php
include('db.php');

$dbTable="";
	$sql = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.entity_id as product_id,
catalog_product_flat_1.name,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(max(crawl_results.violation_amount),2) as maxvio,
format(min(crawl_results.violation_amount),2) as minvio,
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

	$result = mysql_query($sql)	or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());
$filename="Product_Violations-".date('d-m-y').".xls";
	header('Content-Type: application/vnd.ms-excel');	//define header info for browser
	header('Content-Disposition: attachment; filename="'.$filename.'"');
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