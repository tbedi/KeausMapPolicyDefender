<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 15;
$flagfrom=0;
$flagto=0;

//$_SESSION['limit'] = $limit;
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
	$limit=$_GET['limit'];
}  

static $to ;
static $from;
/*where*/

$where = "";
if (isset($_GET['action']) && $_GET['action'] == 'searchh' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
}
/*where*/


/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="history"  ) {  
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_history_dir']=$_GET['dir'];
	$_SESSION['sort_history_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_history_field']) && isset($_SESSION['sort_history_dir']) ) {
	$direction = $_SESSION['sort_history_dir'];
	$order_field =$_SESSION['sort_history_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_history_dir'] = "desc";
	$_SESSION['sort_history_field'] = "violation_amount";
}
$order_by = "order by " . $order_field . " " . $direction . " ";
/* sorting */

/* Pagination */
if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */

/* calender dates */
 
if ( isset($_POST["to"]) && ($_POST["from"]) && isset($_GET['option']) && $_GET['option'] == 'show_dates' ) {  
	$to =$_POST["to"];
	$from =$_POST["from"];
	$_SESSION['t']=$_POST["to"];
	$_SESSION['fr']=$_POST["from"];
} else if (isset($_SESSION['t']) && isset($_SESSION['fr']) ) {
	$to = $_SESSION['t'];
	$from =$_SESSION['fr'];
} else {
	 $to= date("Y-m-d");
         $from= date('Y-m-d',strtotime("-1 days"));
	$_SESSION['t'] = date("Y-m-d");
	$_SESSION['fr'] = date('Y-m-d',strtotime("-1 days"));
}        
/* calender dates */




                   if( $to==NULL or $from==NULL or $from > $to )
                       {
                         echo '<script type="text/javascript">alert("Enter correct dates!");</script>';
                       }

                if( $to == $from) 
{
    $from=$to;
    $sql="SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku,
    date_format(crawl.date_executed,'%d-%m-%Y %H:%i:%s') as date_executed,
catalog_product_flat_1.name as pname,
website.name as wname, 
cast(crawl_results.vendor_price as decimal(10,2)) as vendor_price,
cast(crawl_results.map_price as decimal(10,2)) as map_price,
cast(crawl_results.violation_amount as decimal(10,2)) as violation_amount,
crawl_results.website_product_url
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where date_format(date_executed, '%Y-%m-%d' )='$from'   
and
crawl_results.violation_amount>0.05 ".$where." 
and website.excluded=0 
" . $order_by . " LIMIT $start, $limit "; 
   
}


else
{
$sql="SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku,
    date_format(crawl.date_executed,'%d-%m-%Y %H:%i:%s') as date_executed,
catalog_product_flat_1.name as pname,
website.name as wname, 
cast(crawl_results.vendor_price as decimal(10,2)) as vendor_price,
cast(crawl_results.map_price as decimal(10,2)) as map_price,
cast(crawl_results.violation_amount as decimal(10,2)) as violation_amount,
crawl_results.website_product_url
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where  (crawl.date_executed between '$from' and '$to' )  
and
crawl_results.violation_amount>0.05 ".$where." 
and website.excluded=0 
" . $order_by . " LIMIT $start, $limit ";
}


$violators_array=$db_resource->GetResultObj($sql);

$_SESSION['historyArray']=$violators_array;
if(isset($_SESSION['historyArray']))
{
   // print_r($_SESSION['historyArray']); 
}

?>
<script type="text/javascript">
    
//document.getElementById(inputFieldfrom).value= <?php $from ;?>
//document.getElementById(inputFieldto).value= <?php $to ;?>
document.getElementById(inputFieldfrom).setAttribute(value, <?php $from ; ?> );
//document.getElementById(inputFieldto).setAttribute(value, <?php $from ; ?> );

</script>
    <?php
// Initial page num setup
$sql1=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql1);
$total_pages=$total_pages[0]->total;

$tab_name = 'violations-history';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;

$page_param = "page"; //variable used for pagination
$additional_params = "&limit=".$limit;  //addtiion params to pagination url;



if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
 

include_once 'template/history_tab.phtml';



?>

