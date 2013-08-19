
<?php
include_once '../toMoney.php';
include_once './db_class.php';
session_start();
if(isset($_SESSION['listr']))
 $_SESSION['listr'];
if(isset($_SESSION['selectallRecent']))
 $_SESSION['selectallRecent'];
echo $_SESSION['selectallRecent'];
//data collection
$db_resource = new DB ();
$limit=15;
$start=0;
$limitrcon="";
if ((isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab']=='recent' ) ) {
	$limit=$_GET['limit'];
}
if  (!isset($_SESSION['selectallRecent']) and $_SESSION['selectallRecent']!=='1')
{ 
       $limitrcon = "  LIMIT $start, $limit ";
}

//else     

//export selected
//if (isset($_REQUEST['listr']) and $_REQUEST['listr']!="" and ( !isset($_REQUEST['selectallRecent']) or $_REQUEST['selectallRecent']!="1"))
if (isset( $_SESSION['listr']) and  $_SESSION['listr']!="")
{
    $arrExportRecent=  $_SESSION['listr'];
    
    // print_r($arrExportRecent);
    $conRecentExport=" and crawl_results.id in (". $arrExportRecent. ")" ;
    
}
 else {
     $conRecentExport="";
}
     if  (isset($_SESSION['selectallRecent']) and $_SESSION['selectallRecent']=='1')
{
    $limitrcon="";
      $conRecentExport="";
}

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="recent"  ) {  
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_recent_dir']=$_GET['dir'];
	$_SESSION['sort_recent_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_recent_field']) && isset($_SESSION['sort_recent_dir']) ) {
	$direction = $_SESSION['sort_recent_dir'];
	$order_field =$_SESSION['sort_recent_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_recent_dir'] = "desc";
	$_SESSION['sort_recent_field'] = "violation_amount";
}

$order_by = "order by " . $order_field . " " . $direction . " ";
/* sorting */
$sql = "SELECT SQL_CALC_FOUND_ROWS 
    catalog_product_flat_1.sku,catalog_product_flat_1.entity_id as product_id,
website.name as name, crawl_results.id as id,
website.id as website_id,
crawl_results.vendor_price  as vendor_price,
crawl_results.map_price  as map_price,
crawl_results.violation_amount   as violation_amount,
crawl_results.website_product_url
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join
crawl 
on crawl.id=crawl_results.crawl_id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
AND crawl.id = (SELECT id  FROM crawl  ORDER BY id DESC  LIMIT 1) 
and
crawl.id = 
(select max(crawl.id) from crawl)  " . $conRecentExport . " 
" . $order_by . " $limitrcon";




$violators_array=$db_resource->GetResultObj($sql);

header('Content-Type: application/vnd.ms-excel'); //define header info for browser
header('Content-Disposition: attachment; filename=' . "Recent_Violations" . '-' . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');

echo '<table border=1><tr>';
echo '<td>SKU </td>';
echo '<td>Dealers </td>';
echo '<td>Dealers Price </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Amount </td>';

print('</tr>');

foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->sku . "</td>";
    $output .= "<td>" . $violators_array->name . "</td>";
    $output .= "<td>" . toMoney($violators_array->vendor_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->violation_amount) . "</td>";


    print(trim($output)) . "</tr>\t\n";
}

echo "</table>";
?>
