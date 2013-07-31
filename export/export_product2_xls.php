 <?php
include('db.php');
$product_id = $_REQUEST['product_id'];
$dbTable = "";

$sql = "SELECT  distinct w.`name` as vendor ,
    format(r.violation_amount,2) as violation_amount,
    format( r.vendor_price,2) as vendor_price,
    format(r.map_price,2) as map_price
        FROM crawl_results  r
    INNER JOIN website w
    ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p
    ON p.entity_id=r.product_id
    where p.entity_id='".$product_id."'
    		    AND r.violation_amount>0.05
                    and
w.excluded = 0
		    ORDER BY r.violation_amount DESC";

	$result = mysql_query($sql)	or die("Couldn't execute query:<br>".mysql_error().'<br>'.mysql_errno());

	header('Content-Type: application/vnd.ms-excel');	//define header info for browser
	//header('Content-Disposition: attachment; filename='.$dbTable.'-'.date('Ymd'));
       
	header('Content-Disposition: attachment; filename='."Sellers_Violated_".$product_id."_".date('d-m-y'));
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
