<?php
/*where*/
$where = "";
$limitpcon="";
if (isset($_SESSION['listp']))
unset($_SESSION['listp']);
if (isset($_SESSION['selectallproduct']))
unset($_SESSION['selectallproduct']);

if (isset($_GET['action']) && $_GET['action'] == 'search2' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$field = strtolower($_GET['field']);
	$value = strtolower($_GET['value']);
	$where = "  AND  " . $field . "  LIKE '%" . $value . "%'";
}
/*where*/

$to=$_SESSION['tc'];
$from=$_SESSION['frc'] ;
//pagination
$limit = 15;
//$_SESSION['limitp2'] = $limit;
if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
	$limit=$_GET['limit2'];
} 

//pagination
$sql = "SELECT  distinct w.`name` as vendor ,c.date_executed,
    w.id as website_id,
    r.violation_amount  ,
     r.vendor_price ,
    r.map_price  ,
    r.website_product_url,
	p.sku as sku
    FROM crawl_results  r
     inner join crawl c on c.id=r.crawl_id
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    WHERE (date_format(c.date_executed,'%Y-%m-%d') between '$from' and '$to' ) and r.violation_amount>0.05 AND w.excluded=0 " . $where ;
//echo $sql;
//pagination


$violators_all_array=$db_resource->GetResultObj($sql);
$total_pages =  count($violators_all_array);
//pagination
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

//chk


if (isset($_REQUEST['selectallproduct']))
{
     $_SESSION['selectallproduct'] = $_REQUEST['selectallproduct'];
     
}


if (isset($_REQUEST['listp']) ) 
{
    $_SESSION['listp'] = $_REQUEST['listp'];
//    print_r ($_REQUEST['listp']);
}



//chk



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
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  
    WHERE (date_format(c.date_executed,'%Y-%m-%d') between '$from' and '$to' ) AND r.violation_amount>0.05 AND r.product_id='" . $product_id . "' and w.excluded=0  " . $where . " 
   " . $order_by . " $limitpcon";
 
$violators_array=$db_resource->GetResultObj($sql);

//echo $sql;

$sql3 = "SELECT   c.date_executed,
       p.sku as sku
    FROM crawl_results  r
    inner join crawl c on c.id=r.crawl_id
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  
    WHERE (date_format(c.date_executed,'%Y-%m-%d') between '$from' and '$to' ) AND r.violation_amount>0.05 AND r.product_id='" . $product_id . "' and w.excluded=0  ";

 
$violators_array3=$db_resource->GetResultObj($sql3);

 $_SESSION['pviolationTitle']=$violators_array3[0]->sku;


$_SESSION['product2Array']=$violators_array;
if(isset($_SESSION['product2Array']))
{
  //  print_r($_SESSION['product2Array']); 
  
}

 
/*Pagination*/
$tab_name = 'violations-history';
$page_param = "page2"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/

/*For sorting using*/
$additional_params = "&product_id=" . $product_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page']; //here it should 
}
/*For sorting using*/

include_once 'template/product_violation_detail.phtml';
?>
  
