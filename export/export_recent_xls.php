
 <?php
 session_start();
$violators_array=$_SESSION['recentArray'];
if(isset($_SESSION['recentArray']))
{
       print_r($violators_array);
}

 // $limitex = $_REQUEST['limit'];




	header('Content-Type: application/vnd.ms-excel');	//define header info for browser
	header('Content-Disposition: attachment; filename='."Recent_Violations".'-'.date('d-m-y'));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<table><tr>';
	echo '<td>SKU </td>'  ;
       echo '<td>Seller </td>';    
        echo '<td>Vendor Price </td>';    
        echo '<td>Map Price </td>';    
        echo '<td>Violation Amount </td>';   
        
        
        
	print('</tr>');

	foreach ($violators_array as $violators_array ) {
	{
		//set_time_limit(60); // you can enable this if you have lot of data
		$output = '<tr>';
		for($j=0; $j< count($violators_array); $j++)
		{
			if(!isset($violators_array[$j]))
				$output .= '<td>&nbsp;</td>';
			else
				//$output .= "<td>$violators_array[$j]</td>";
                        $output .= "<td>".$violators_array[$j]->sku."</td>";
                          $output .= "<td>".$violators_array[$j]->name."</td>";
                            $output .= "<td>".$violators_array[$j]->vendor_price."</td>";
                              $output .= "<td>".$violators_array[$j]->map_price."</td>";
                                $output .= "<td>".$violators_array[$j]->violation_amount."</td>";
                                
                        
                        
                        
                     
                        
                        
		}
		print(trim($output))."</tr>\t\n";
	}
	echo('</table>');
        }
?>
