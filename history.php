<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 15;
$flagfrom = 0;
$flagto = 0;
 $start = 0;
 
$additional_params;
$searchfield;
// Product
$product_id = 0;
$conHistoryExport;
if (isset($_SESSION['listh']))
unset($_SESSION['listh']);
if (isset($_SESSION['selectallhistory']))
unset($_SESSION['selectallhistory']);

if (isset($_REQUEST['product_id'])) {
    $product_id = $_REQUEST['product_id'];
    $_SESSION['product_id'] = $product_id;
}


//url calender form

        
        $searchfield;
        $urls = "?tab=violations-history&option=show_dates";
        if (isset($_REQUEST['action']) and isset($_REQUEST['value'])) {
            
            $urls = "?tab=violations-history&option=show_dates&value=" . $_REQUEST['value'];
        }
         if (isset($_REQUEST['sku']) ){
                $urls.="&sku=".  $_REQUEST['sku']."&product_id=".$_REQUEST['product_id']; ;       
        }
        if (isset($_REQUEST['website_id']) ){
                $urls.="&website_id=".  $_REQUEST['website_id'] ."&wname=".$_REQUEST['wname'];       
        }
//        else {
//            $urls = "?tab=violations-history&option=show_dates";
//        }
    //   echo $urls;
        



//


/* where */
//$wherep = "";
//if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
//    $field = strtolower($_GET['field']);
//    $value = strtolower($_GET['value']);
//    $wherep = "  AND  " . $field . "  LIKE '%" . $value . "%'";
//}
//
//if ($product_id) {
//    $wherep= "  AND  entity_id  = '" . $product_id . "'";
//    
//}
/* where */


//Product

//vendor

$website_id=0;

if (isset($_REQUEST['website_id'])) {
	$website_id = $_REQUEST['website_id'];
         $_SESSION['website_id'] = $website_id;
}


 /*where*/
//$wherev = "";
//if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
//     $field = strtolower($_GET['field']);
//    $value = strtolower($_GET['value']);
//    $wherev = "  AND  website." . $field . "  LIKE '%" . mysql_real_escape_string(trim($value)) . "%'";
//}
//
//if ($website_id) {
//    $website_id=mysql_real_escape_string($website_id); 
// $wherev = "  AND  website_id  = " ." $website_id ". ""; 
//}
/*where*/


//vendor



$_SESSION['limit'] = $limit;
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ) {
    $limit = $_GET['limit'];
}

 static $to;
static $from;
/* where */

$where = "";
//if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
//    $field = strtolower($_GET['field']);
//    $value = strtolower($_GET['value']);
//    if(isset($_REQUEST['field']) and $_REQUEST['field']=='sku')
//    { $where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";}
//    else if(isset($_REQUEST['field']) and $_REQUEST['field']=='name')
//    {  $where = "  AND  website." . $field . " = " . $value . "";}
//}
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
   // echo $_GET['page'];
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
   // echo $start;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */

$limithcon = "  LIMIT $start, $limit ";


$sql3 = "SELECT min(date_format(crawl.date_executed,'%Y-%m-%d')) as mindate from crawl";
 $violators_array3=$db_resource->GetResultObj($sql3);
$mindate=$violators_array3[0]->mindate;
/* calender dates */
////////////////////////
$sqldate="select date_format(crawl.date_executed,'%Y-%m-%d') as date_executed from crawl
where id in(select  max(id) from crawl); "; 

$violators_array_date=$db_resource->GetResultObj($sqldate);
if (!isset($_REQUEST['option']) && !isset($_POST['to']) && !isset($_POST['from'])) {
 $to= $violators_array_date[0]->date_executed;
        $from= $violators_array_date[0]->date_executed;
	$_SESSION['t'] = $to;
	$_SESSION['fr'] = $from;
        $_SESSION['tc'] = date("Y-m-d");
	$_SESSION['frc'] = $mindate;
}
//  $_SESSION['t'] = $_POST["to"];
//    $_SESSION['fr'] = $_POST["from"];
elseif(isset($_POST["to"]) && ($_POST["from"])) 
    {
    $_SESSION['t'] = $_POST['to'];
    $_SESSION['fr'] = $_POST['from'];
    $to = $_SESSION['t'];
    $from = $_SESSION['fr'];
}


if(isset($_SESSION['t']) && isset($_SESSION['fr']))
{
    $to = $_SESSION['t'];
    $from = $_SESSION['fr'];
}
//print_r($_SESSION);
//echo $to."-".$from."-";

/* calender dates */
//new changes





if (isset($_REQUEST['value']) and isset($_REQUEST['field']) and $_REQUEST['field']=='sku' )
{
    $sku=$_REQUEST['value'];
    $condition_sku=" and sku like '".$sku."' ";
}
else
{
    $condition_sku="";
}
//if (isset($_REQUEST['website_id'])and isset($_REQUEST['wname']) and isset($_REQUEST['field']) and $_REQUEST['field']=='website_id' and !isset($_POST['info']))
if (  isset($_REQUEST['value']) and isset($_REQUEST['field']) and $_REQUEST['field']=='website_id' )
{    
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $condition_wname = "  AND  " . $field . "  LIKE '%" . mysql_real_escape_string(trim($value)) . "%'";
    
  //  $wname=$_REQUEST['value'];
   // $condition_wname=" and website.name like '".$wname."' ";
    if ($website_id) {
    $website_id=mysql_real_escape_string($website_id); 
    $condition_wname = "  AND  website_id  = " ." $website_id ". ""; 
}
    
    
}
if ($website_id) {
    $website_id=mysql_real_escape_string($website_id); 
    $condition_wname = "  AND  website_id  = " ." $website_id ". ""; 
}
else
{
    $condition_wname="";
}


if ($product_id) {
    $product_id=mysql_real_escape_string($product_id); 
    $condition_sku = "  AND  product_id  = " ." $product_id ". ""; 
}
else
{
    $condition_sku="";
}
//new changes


//checkbox
//if  (!isset($_REQUEST['selectallhistory']) or $_REQUEST['selectallhistory']="1")
//{ 
//       $limitcon = "  LIMIT $start, $limit ";
//}
// else 
//{
//    $limitcon="";
//}
//
//if (isset($_REQUEST['listh']) or ( isset($_REQUEST['listh']) and $_REQUEST['listh']!=""))
//{
//    $arrExportHistory= $_REQUEST['listh'];
//    echo $arrExportHistory;
//    $conHistoryExport=" and crawl_results.id in (". $arrExportHistory. ")" ;
//    
//}
// else {
//     $conHistoryExport="";
//}

//checkbox

if (isset($_REQUEST['selectallhistory']))
{
     $_SESSION['selectallhistory'] = $_REQUEST['selectallhistory'];
     //echo      $_SESSION['selectallRecent'];
}


if (isset($_REQUEST['listh']) ) 
{
    $_SESSION['listh'] = $_REQUEST['listh'];
}
if (isset($_REQUEST['selectallproduct']))
{
     $_SESSION['selectallproduct'] = $_REQUEST['selectallproduct'];
     
}


if (isset($_REQUEST['listp']) ) 
{
    $_SESSION['listp'] = $_REQUEST['listp'];
}




    $sql = "SELECT SQL_CALC_FOUND_ROWS  
    catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,
    date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id,
website.name as vendor, 
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
 ".$condition_wname." ".$condition_sku."  and
crawl_results.violation_amount>0.05   
and website.excluded=0 
" . $order_by . "$limithcon " ;

$violators_array = $db_resource->GetResultObj($sql);

//echo "---".$sql;
//
//$_SESSION['historyArray'] = $violators_array;
//if (isset($_SESSION['historyArray'])) {
//    // print_r($_SESSION['historyArray']); 
//}
?>
<script type="text/javascript">
  //  document.getElementById(inputFieldfrom).setAttribute(value, <?php $from; ?>); it showed js error
 	/*Jquery alernative*/
 	//$("#inputFieldfrom").val('<?php $from; ?>');
 	//but it will work only after document is rendered. in your case 2 ways: place it into template after input with this id or 
 	// put inside document.ready jquery event 
</script>
<?php

//pagination
$sql1 = " SELECT FOUND_ROWS() as total;";
$total_pages = $db_resource->GetResultObj($sql1);
$total_pages = $total_pages[0]->total;

$tab_name = 'violations-history';
$page_param = "page"; //variable used for pagination
$pagination_html=$pagination->GenerateHTML($page,$total_pages,$limit,$page_param);
/*Pagination*/




    

/*For sorting using*/
$additional_params = "&limit=".$limit;
if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
    $additional_params.="&second_grid_page=" . $_GET['second_grid_page'];
}

if (isset($_GET['website_id']) && $_GET['website_id']) { //adding support for website
    $additional_params.="&website_id=" . $_GET['website_id'];
}
if (isset($_GET['action']) && $_GET['action'] && isset($_GET['website_id'])) { // search w
    $additional_params.="&action=" . $_GET['action'] . "&field=website_id&value=" . $_GET['value'];
}


if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
}
if (isset($_GET['action']) && $_GET['action']  && isset($_GET['product_id'])) { // search s
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
/* For sorting using */



/*For sorting using*/

include_once 'template/history_tab.phtml';
?>

<?php
//if ($total_pages == 1) {
//    $product_id = $page_violated_products[0]->product_id;
//}

if ($product_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'pviolation.php';
}


if ($website_id && isset($_GET['tab']) && $_GET['tab'] == "violations-history") {
    include_once 'vviolation.php';
}
//include_once 'template/product_violation_detail.phtml';

?>
