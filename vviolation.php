<?php
/*where*/
$where = "";
$limit = 15;
$limitvcon="";
$searchven="";

if (isset($_GET['searchproduct']))
{
   $searchven="  AND  sku  = '" .  $_GET['searchproduct'] . "'";
}

if (isset($_SESSION['listv']))
unset($_SESSION['listv']);
if (isset($_SESSION['selectallvendor']))
unset($_SESSION['selectallvendor']);
if (isset($_GET['limit2']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$limit=$_GET['limit2'];
        $_GET['page2']=1;
  
} 

if (isset($_REQUEST['selectallvendor']))
{
     $_SESSION['selectallvendor'] = $_REQUEST['selectallvendor'];
  }


if (isset($_REQUEST['listv']) ) 
{
    $_SESSION['listv'] = $_REQUEST['listv'];
}


if (isset($_GET['action']) && $_GET['action'] == 'search2' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$field = strtolower($_GET['field']);
	$value = strtolower($_GET['value']);
	$where = "  AND  " . $field . "  LIKE '%" . $value . "%'";
}
/*where*/
$to=$_SESSION['tc'];
$from=$_SESSION['frc'] ;

      $sql ="select *
from crawl_results
inner join crawl  on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where (date_format(crawl.date_executed,'%Y-%m-%d') between '$from' and '$to' ) and  crawl_results.violation_amount>0.05 
and
website.excluded=0  " . $searchven . "
and website_id = $website_id " . $where  ;
  
        
        

      
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
inner join crawl  on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where (date_format(crawl.date_executed,'%Y-%m-%d') between '$from' and '$to' ) and crawl_results.violation_amount>0.05 
and
website.excluded=0 
and website_id = $website_id " . $where .  $searchven. "
     ".$order_by." " .$limitvcon; 

 
$violators_array=$db_resource->GetResultObj($sql);
    

$_SESSION['vendor2Array']=$violators_array;

 
 
/*Pagination*/
$tab_name = 'violations-history';
$page_param = "page2"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/

/*For sorting using*/
$additional_params = ""; //addtiion params to pagination url;
/*For sorting using*/

$sql3 = "select  name as wname from   website where  id = ".$website_id. " limit 1";



$violators_array3=$db_resource->GetResultObj($sql3);


$_SESSION['vviolationTitle']=$violators_array3[0]->wname;


include_once 'template/vendor_violation_detail.phtml';
?>
  

