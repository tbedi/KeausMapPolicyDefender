<?php

include_once './db_class.php';
include_once '../toMoney.php';
include_once './db.php';
session_start();
$sku="";
$product_id="";
 $conProductExport="";
if(isset($_SESSION['pviolationTitle']))
{
$sku=$_SESSION['pviolationTitle'];
//print_r($sku);
}



if(isset($_POST['listp']))
 $_SESSION['listp'] = $_POST['listp'];
if(isset($_POST['selectallproduct']))
 $_SESSION['selectallproduct'] = $_POST['selectallproduct'];

if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id'];
}
//echo $_SESSION['selectallproduct'];
//data collection
$db_resource = new DB ();
//$limit=15;
//$start=0;
//$limitpcon="";
//
//
//if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
//	$limit=$_GET['limit2'];
//} 
//if  (!isset($_SESSION['selectallproduct']))
//{ 
//       $limitpcon = "  LIMIT $start, $limit ";
//}


if (isset( $_SESSION['listp']) and $_SESSION['listp']!=0 )
{
    $arrExportProduct=  $_SESSION['listp'];
    
    // print_r($arrExportRecent);
    $conProductExport=" and r.id in (". $arrExportProduct. ")" ;
    
}
 else {
     $conProductExport="";
}
     if  (isset($_SESSION['selectallproduct']) and $_SESSION['selectallproduct']=='1')
{
    $limitpcon="";
      $conProductExport="";
}
/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vproduct_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vproduct_2_dir']=$_GET['dir'];
	$_SESSION['sort_vproduct_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vproduct_2_field']) && isset($_SESSION['sort_vproduct_2_dir']) ) {
	$direction = $_SESSION['sort_vproduct_2_dir'];
	$order_field =$_SESSION['sort_vproduct_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vproduct_2_dir'] = "desc";
	$_SESSION['sort_vproduct_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */
//$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
//$result = mysql_query($sql);
//$last_crawl = mysql_fetch_assoc($result);

$sql = "SELECT  distinct w.`name` as vendor ,date_format(c.date_executed,'%m-%d-%Y') as date_executed,
    r.violation_amount as violation_amount,r.id as id,
    w.id as website_id,
    r.vendor_price as vendor_price,
    r.map_price ,
    r.website_product_url,
    p.sku as sku
    FROM crawl_results  r
    inner join crawl c on c.id=r.crawl_id
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    where r.violation_amount>0.05  and w.excluded=0  " . $conProductExport . " 
   " . $order_by   ;
 
$violators_array=$db_resource->GetResultObj($sql);




//$violators_array = $_SESSION['product2Array'];
//if (isset($_SESSION['product2Array'])) {
//    //   print_r($violators_array);
//}


header('Content-Type: application/vnd.ms-excel'); //define header info for browser    
header('Content-Disposition: attachment; filename=' . "Dealers_Violated_" . $sku . "_" . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');

echo '<table border=1><tr>';
echo '<td>Dealers </td>';
echo '<td>Dealers Price </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Amount </td>';
echo '<td>Date </td>';

print('</tr>');

foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->vendor . "</td>";
    $output .= "<td>" . toMoney($violators_array->vendor_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->violation_amount) . "</td>";
     $output .= "<td>" . $violators_array->date_executed . "</td>";


    print(trim($output)) . "</tr>\t\n";
}

echo "</table>";

//unset($_SESSION['listp']);
//unset($_SESSION['selectallproduct']);

?>
