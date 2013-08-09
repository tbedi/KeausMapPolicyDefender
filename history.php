<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 15;
$flagfrom = 0;
$flagto = 0;

$_SESSION['limit'] = $limit;
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $limit = $_GET['limit'];
}

 static $to;
static $from;
/* where */

$where = "";
if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
}
/* where */


/* sorting */
if (isset($_GET['sort']) && isset($_GET['dir']) && isset($_GET['grid']) && $_GET['grid'] == "history") {
    $direction = $_GET['dir'];
    $order_field = $_GET['sort'];
    $_SESSION['sort_history_dir'] = $_GET['dir'];
    $_SESSION['sort_history_field'] = $_GET['sort'];
} else if (isset($_SESSION['sort_history_field']) && isset($_SESSION['sort_history_dir'])) {
    $direction = $_SESSION['sort_history_dir'];
    $order_field = $_SESSION['sort_history_field'];
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




$sql3 = "SELECT min(date_format(crawl.date_executed,'%Y-%m-%d')) as mindate from crawl";
 $violators_array3=$db_resource->GetResultObj($sql3);
$mindate=$violators_array3[0]->mindate;
/* calender dates */

if (isset($_POST["to"]) && ($_POST["from"]) && isset($_GET['option']) && $_GET['option'] == 'show_dates') {
    $to = $_POST["to"];
    $from = $_POST["from"];
    $_SESSION['t'] = $_POST["to"];
    $_SESSION['fr'] = $_POST["from"];
     $_SESSION['tc'] = $_POST["to"];
    $_SESSION['frc'] = $_POST["from"];
}
 
else {
    $to= date("Y-m-d");
         $from= date('Y-m-d',strtotime("-1 days"));
	$_SESSION['t'] = date("Y-m-d");
	$_SESSION['fr'] = date('Y-m-d',strtotime("-1 days"));
        $_SESSION['tc'] = date("Y-m-d");
	$_SESSION['frc'] = $mindate;
}
/* calender dates */

if (isset($_REQUEST['value']))
{
    $sku=$_REQUEST['value'];
    $condition_sku=" and sku like '".$sku."' ";
}
else
{
    $condition_sku="";
}


    $sql = "SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku, crawl_results.website_id,
    date_format(crawl.date_executed,'%Y-%m-%d %H:%i:%s') as date_executed,
catalog_product_flat_1.name as pname,
website.name as wname, 
crawl_results.vendor_price ,
crawl_results.map_price ,
crawl_results.violation_amount ,
crawl_results.website_product_url
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where (date_format(crawl.date_executed,'%Y-%m-%d') between '$from' and '$to' )  
 ".$condition_sku." and 
crawl_results.violation_amount>0.05 " . $where . " 
and website.excluded=0 
" . $order_by . " LIMIT $start, $limit ";

$violators_array = $db_resource->GetResultObj($sql);

$_SESSION['historyArray'] = $violators_array;
if (isset($_SESSION['historyArray'])) {
    // print_r($_SESSION['historyArray']); 
}
?>
<script type="text/javascript">
  //  document.getElementById(inputFieldfrom).setAttribute(value, <?php $from; ?>); it showed js error
 	/*Jquery alernative*/
 	//$("#inputFieldfrom").val('<?php $from; ?>');
 	//but it will work only after document is rendered. in your case 2 ways: place it into template after input with this id or 
 	// put inside document.ready jquery event 
</script>
<?php
/*Pagination*/

$sql1 = " SELECT FOUND_ROWS() as total;";
$total_pages = $db_resource->GetResultObj($sql1);
$total_pages = $total_pages[0]->total;

$tab_name = 'violations-history';
$page_param = "page"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/

/*For sorting using*/
$additional_params = "&limit=" . $limit;  //addtiion params to pagination url;

if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
/*For sorting using*/

include_once 'template/history_tab.phtml';
?>

