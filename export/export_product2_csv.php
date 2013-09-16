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

/*Prepare values for grid*/
$product_id =(isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 0);//get product id
//Declarations
$where = "";
$limitpcon="";
$searchpro="";
//Declarations

//pagination
$limit = 15;
if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
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

$limitpcon = "  LIMIT $start, $limit ";
/*second grid pagination*/

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
/* Rows conditions*/
if (isset($_REQUEST['row_ids'])) {
	if  ($_REQUEST['row_ids']=="all")
		$limitpcon="";
	if  ($_REQUEST['row_ids']!="all" && $_REQUEST['row_ids']!="limit") {
		$searchpro.=" AND r.id IN (".$_REQUEST['row_ids'].")";
		$limitpcon="";
	}
}
/*Rows conditions*/
 
$sql = "SELECT  SQL_CALC_FOUND_ROWS distinct w.`name` as vendor ,date_format(c.date_executed,'%m-%d-%Y') as date_executed,
    r.violation_amount as violation_amount,r.id as id,
    w.id as website_id,
    r.vendor_price as vendor_price,
    r.map_price ,
    r.website_product_url,
    p.sku as sku
    FROM crawl_results  r
    inner join crawl c on c.id=r.crawl_id
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id
    WHERE  r.violation_amount>0.05  " .$searchpro. " AND r.product_id='" . $product_id . "' and w.excluded=0  " . $where . "
   " . $order_by . " $limitpcon "  ;
 
$violators_array=$db_resource->GetResultObj($sql);

/*CSV ONLY*/
/*getting sku*/
$name_sql="SELECT sku from catalog_product_flat_1 WHERE entity_id=".$_REQUEST['product_id'];
$name=$db_resource->GetResultObj($name_sql);
$sku=$name[0]->sku;
/*getting sku*/
/*CSV*/

$filename="violations-".$sku."-".date('d-m-y').".csv";
header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="'.$filename.'"');

/* columns */
$arr_columns = array(
    'Dealers',
    'Dealers Price',
    'MAP',
    'Violation',
    'Date'        
);
$arr_data = array();

foreach ($violators_array as $violators_array ){
$arr_data_row = array($violators_array->vendor,toMoney($violators_array->vendor_price),toMoney($violators_array->map_price),toMoney($violators_array->violation_amount),$violators_array->date_executed);
/* push data to array */
array_push($arr_data, $arr_data_row);
} 

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

