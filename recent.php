<?php
$sql = "select max(DATE_FORMAT(crawl.date_executed, '%d %b %Y')) as maxd
from crawl;";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
    $str = $row['maxd'];
} 

 

$limit=15;

//$_SESSION['limit'] = $limit;
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab']=='recent') {
	$limit=$_GET['limit'];
} 

   // print_r($_SESSION['limit']);


 $targetpage="index.php";
/*where*/
$where = "";
if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
}
/*where*/

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

/* Pagination */
if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */


// Get page data
$sql = "SELECT SQL_CALC_FOUND_ROWS 
    catalog_product_flat_1.sku,
website.name as name, 
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
(select max(crawl.id) from crawl) " . $where . " 
" . $order_by . " LIMIT $start, $limit";

$violators_array=$db_resource->GetResultObj($sql);

$_SESSION['recentArray']=$violators_array;
if(isset($_SESSION['recentArray']))
{
   // print_r($_SESSION['recentArray']); 
  
}


/*Pagination*/
$sql1=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql1);
$total_pages=$total_pages[0]->total;
$tab_name = 'recent';
$page_param = "page"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/

/*For sorting using*/
$additional_params = "&limit=".$limit; //addtiion params to sorting;
if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
} 
if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
/*For sorting using*/

include_once 'template/recent_tab.phtml';
?>
 
<?php

function toMoney($val,$symbol='$',$decimal=2)
{


    $no = $val; 
    $c = is_float($no) ? 1 : number_format($no,$decimal);
    $d = '.';
    $t = ',';
    $sign = ($no < 0) ? '-' : '';
    $i = $no=number_format(abs($no),$decimal); 
    $j = (($j = count($i)) > 3) ? $j % 3 : 0; 

   return  $symbol.$sign .($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;

}




?>
