<?php
//Declarations
$where = "";
$limitpcon="";
$searchpro="";
//Declarations

//pagination

//search
if (isset($_GET['searchvendor']))
{
   $searchpro="  AND  w.name   like '%". mysql_real_escape_string(trim($_GET["searchvendor"])) . "%' ";
        
}    
//search

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
 
//chk
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

//pagination
$sql1 = " SELECT FOUND_ROWS() as total;";
$total_pages = $db_resource->GetResultObj($sql1);
$total_pages = $total_pages[0]->total;
$tab_name = 'violations-history';
$page_param = "page2"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
//pagination
 
/*getting sku*/
$name=$db_resource->GetResultObj($name_sql);
$sku=$name[0]->sku;
/*getting sku*/
 
/*For sorting using*/
$additional_params = "&product_id=" . $product_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page']; //here it should 
}
/*For sorting using*/
include_once 'template/product_violation_detail.phtml';
?>
  
