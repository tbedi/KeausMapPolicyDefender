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
FROM crawl_results
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

/*CSV*/
$filename="violations-by-".$name."-".date('d-m-y').".csv";

header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'SKU',
    'Dealer Price',
    'MAP Price',
    'Violation Amount',
    'Date'           
);
$arr_data = array();

foreach ($violators_array as $violators_array ) {
$arr_data_row = array($violators_array->sku,toMoney($violators_array->vendor_price),toMoney($violators_array->map_price),toMoney($violators_array->violation_amount),$violators_array->date_executed);
/* push data to array */
array_push($arr_data, $arr_data_row);
} //do it here
exportCSV($arr_data, $arr_columns);

function exportCSV($data, $col_headers = array(), $return_string = false) {
    $stream = ($return_string) ? fopen('php://temp/maxmemory', 'w+') : fopen('php://output', 'w');

    if (!empty($col_headers)) {
        fputcsv($stream, $col_headers);
    }
    
    foreach ($data as $record) {
        fputcsv($stream, $record);
    }

    if ($return_string) {
        rewind($stream);
        $retVal = stream_get_contents($stream);
        fclose($stream);
        return $retVal;
    } else {
        fclose($stream);
    }
}
  
 


?>
