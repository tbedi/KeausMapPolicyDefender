<?php
/*GLOBAL*/
/*configuration*/
setlocale(LC_MONETARY, 'en_US');
include_once '../db.php';
include_once '../db_login.php';
/*Login check*/
if (!isset($_SESSION['username']))
	header('Location: login.php');

/*configuration*/
include_once '../db_class.php';
$db_resource = new DB ();
include_once '../toMoney.php';
/*GLOBAL*/
$website_id=(isset($_REQUEST['website_id']) ? $_REQUEST['website_id'] : 0);// get website id
//Declarations
$where = "";
$limit = 15;
$limitvcon="";
$searchven="";
//Declarations

//pagination
if (isset($_GET['limit2']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$limit=$_GET['limit2'];
	$_GET['page2']=1;
}

/*second grid pagination*/
if (isset($_GET['page2']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$page = $_GET['page2']; //$page_param should have same value
	$start = ($page - 1) * $limit;
} else {
	$start = 0;
	$page = 1;
}
$limitvcon = "  LIMIT $start, $limit ";
/*second grid pagination*/

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
/* Rows conditions*/
if (isset($_REQUEST['row_ids'])) {
	if  ($_REQUEST['row_ids']=="all")
		$limitvcon="";
	if  ($_REQUEST['row_ids']!="all" && $_REQUEST['row_ids']!="limit") {
		$where.=" AND crawl_results.id IN (".$_REQUEST['row_ids'].")";
		$limitvcon="";
	}
}
/*Rows conditions*/
$sql = "select SQL_CALC_FOUND_ROWS distinct crawl_results.website_id,date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
website.name as wname,crawl_results.id as id,
catalog_product_flat_1.entity_id,
catalog_product_flat_1.name as name,
catalog_product_flat_1.sku,
crawl_results.vendor_price ,
crawl_results.map_price ,
crawl_results.violation_amount ,
crawl_results.website_product_url
from crawl_results
inner join crawl  on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where   crawl_results.violation_amount>0.05
and
website.excluded=0
and website_id = $website_id " . $where .  $searchven. "
     ".$order_by." " .$limitvcon;
$violators_array=$db_resource->GetResultObj($sql);

$name_sql="SELECT name from website  WHERE id=".$_REQUEST['website_id'];
$name=$db_resource->GetResultObj($name_sql);
$name=$name[0]->name;
/*XLS*/

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=' . "violations_by_" . $name . "_" . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');
echo '<table border=1><tr>';
echo '<td>SKU </td>';
echo '<td>Dealer Price </td>';
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
