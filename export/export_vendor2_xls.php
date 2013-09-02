<?php




include_once '../toMoney.php';
include_once './db_class.php';
include_once './db.php';
session_start();
 $conVendorExport="";
$vviolationTitle=$_SESSION['vviolationTitle'];

if(isset($_SESSION['vviolationTitle']))
$_SESSION['vviolationTitle'];

if(isset($_SESSION['listv']))
 $_SESSION['listv'];
if(isset($_SESSION['selectallvendor']))
 $_SESSION['selectallvendor'];

if (isset($_SESSION['website_id'])) {
    $web_id = $_SESSION['website_id'];
}
$db_resource = new DB ();
//$limit=15;
//$start=0;
//$limitvcon="";
//
//if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
//	$limit=$_GET['limit2'];
//} 
//if  (!isset($_SESSION['selectallvendor']) and $_SESSION['selectallvendor']!=='1')
//{ 
//       $limitvcon = "  LIMIT $start, $limit ";
//}

 
if (isset( $_SESSION['listv']) and  $_SESSION['listv']!=0)
{
    $arrExportVendor=  $_SESSION['listv'];
    
    // print_r($arrExportRecent);
    $conVendorExport=" and crawl_results.id in (". $arrExportVendor. ")" ;
    
}
 else {
     $conVendorExport="";
}
     if  (isset($_SESSION['selectallvendor']) and $_SESSION['selectallvendor']=='1')
{
//    $limitvcon="";
      $conVendorExport="";
}

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vvendor_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vvendor_2_dir']=$_GET['dir'];
	$_SESSION['sort_vvendor_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vvendor_2_field']) && isset($_SESSION['sort_vvendor_2_dir']) ) {
	$direction = $_SESSION['sort_vvendor_2_dir'];
	$order_field =$_SESSION['sort_vvendor_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vvendor_2_dir'] = "desc";
	$_SESSION['sort_vvendor_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */


$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);


$sql = "select distinct crawl_results.website_id,date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
website.name as wname,crawl_results.id as id,
catalog_product_flat_1.entity_id,
 catalog_product_flat_1.name as name,
 catalog_product_flat_1.sku, 
crawl_results.vendor_price ,
 crawl_results.map_price ,
 crawl_results.violation_amount ,
crawl_results.website_product_url 
from crawl_results
 inner join crawl on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where  crawl_results.violation_amount>0.05 
and
website.excluded=0 " . $conVendorExport . " 
and website_id = $web_id  ".$order_by; 
 
$violators_array=$db_resource->GetResultObj($sql);




//$violators_array = $_SESSION['vendor2Array'];
//if (isset($_SESSION['vendor2Array'])) {
//    // print_r($violators_array);
//}


header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename=' . "Products_Violated_by_" . $_SESSION['vviolationTitle'] . '-' . date('d-m-y'));
header('Content-Disposition: attachment; filename=' . "Products_Violated_by_" . $_SESSION['vviolationTitle'] . "_" . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');
echo '<table border=1><tr>';
echo '<td>SKU </td>';
echo '<td>Dealers Price </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Amount </td>';
echo '<td>Date </td>';

print('</tr>');

foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->sku . "</td>";
    $output .= "<td>" . toMoney($violators_array->vendor_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->violation_amount) . "</td>";
     $output .= "<td>" . $violators_array->date_executed . "</td>";

    print(trim($output)) . "</tr>\t\n";
}
echo "</table>";
?>
