 <?php
 
 session_start();
$violators_array=$_SESSION['vendorArray'];
if(isset($_SESSION['vendorArray']))
{
      // print_r($violators_array);
}
	header('Content-Type: application/vnd.ms-excel');	//define header info for browser
	header('Content-Disposition: attachment; filename='."Seller_Violations".'-'.date('d-m-y'));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo '<table border=1><tr>';
	echo '<td>Seller </td>';    
        echo '<td>Violation Count </td>';    
        echo '<td>Max Violation </td>';    
        echo '<td>Min Violation </td>';   
                      
	print('</tr>');

        foreach ($violators_array as $violators_array ){
   
	 
	$output = "<tr>";

         
          $output .=  "<td>". $violators_array->name ."</td>";
          $output .=  "<td>".  $violators_array->wi_count ."</td>";
          $output .=  "<td>". "$ ".  $violators_array->maxvio ."</td>";
          $output .=  "<td>". "$ ".$violators_array->minvio ."</td>";   
            
    
         print(trim($output))."</tr>\t\n";
	  

}
       
	echo "</table>";
        
?>
