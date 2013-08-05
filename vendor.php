<?php
$limit = 15;

 
if (isset($_GET['limit']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
	$limit=$_GET['limit'];
} 
$website_id=0;

if (isset($_REQUEST['website_id'])) {
	$website_id = $_REQUEST['website_id'];
}


/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vvendor"  ) {  
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vvendor_dir']=$_GET['dir'];
	$_SESSION['sort_vvendor_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vvendor_field']) && isset($_SESSION['sort_vvendor_dir']) ) {
	$direction = $_SESSION['sort_vvendor_dir'];
	$order_field =$_SESSION['sort_vvendor_field'];
} else {
	$direction = "desc";
	$order_field = "maxvio";
	$_SESSION['sort_vvendor_dir'] = "desc";
	$_SESSION['sort_vvendor_field'] = "maxvio";
}
$order_by = "order by " . $order_field . " " . $direction . " ";
/* sorting */



 /*where*/
$where = "";
if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  " . $field . "  LIKE '%" . mysql_real_escape_string(trim($value)) . "%'";
}

if ($website_id) {
    $website_id=mysql_real_escape_string($website_id); 
 $where = "  AND  website_id  = " ." $website_id ". ""; ;
}
/*where*/


/* Pagination */
if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
/* Pagination */


// Get page data
if ((isset ($_GET['flag']) && $_GET['flag'] == '1') || (isset($_GET['action']) && $_GET['action'] == "search")  )
 {
    
 } 
 else
 {
 $where="";
 }
$sql = "SELECT SQL_CALC_FOUND_ROWS  
website.name as name,
crawl_results.website_id as website_id,
cast(max(crawl_results.violation_amount) as decimal(10,2)) as maxvio,
cast(min(crawl_results.violation_amount) as decimal(10,2)) as minvio,
count(crawl_results.website_id) as wi_count
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on
crawl_results.crawl_id = crawl.id

where crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl)  " . $where . "
group by website.name , crawl_results.website_id 
".$order_by." LIMIT $start, $limit";


$page_violated_seller=$db_resource->GetResultObj($sql);

 

$_SESSION['vendorArray']=$page_violated_seller;
if(isset($_SESSION['vendorArray']))
{
   // print_r($_SESSION['vendorArray']); 
  
}


 

/*Pagination*/
$sql=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql);
$total_pages=$total_pages[0]->total;
 
$tab_name = 'violation-by-seller';
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
if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=website_id&value=" . $_GET['value'];
}
/*For sorting using*/

include_once 'template/vendor_violation_tab.phtml';
?>
 
	

<?php
if ($total_pages == 1) {
	$website_id = $page_violated_seller[0]->website_id;
}

if ($website_id && isset($_GET['tab']) && $_GET['tab'] == "violation-by-seller") {
    include_once 'vviolation.php';
}
?>