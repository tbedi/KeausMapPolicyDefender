<?php
 $web_name = $_REQUEST['wname'];
session_start();
$violators_array=$_SESSION['vendor2Array'];
if(isset($_SESSION['vendor2Array']))
{
      // print_r($violators_array);
}

	header('Content-Type: application/vnd.ms-excel');	
	header('Content-Disposition: attachment; filename='."Products_Violated_by_".$web_name.'-'.date('d-m-y'));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<table border=1><tr>';
	echo '<td>SKU </td>'  ;
        echo '<td>Vendor Price </td>';    
        echo '<td>Map Price </td>';    
        echo '<td>Violation Amount </td>';   
                      
	print('</tr>');

        foreach ($violators_array as $violators_array ){
   
	 
	$output = "<tr>";

          $output .=  "<td>" . $violators_array->sku . "</td>";
          $output .=  "<td>". "$ ".  $violators_array->vendor_price ."</td>";
          $output .=  "<td>". "$ ".  $violators_array->map_price ."</td>";
          $output .=  "<td>". "$ ".$violators_array->violation_amount ."</td>";   
              
           print(trim($output))."</tr>\t\n";
	  
}
 	echo "</table>";
        
?>
