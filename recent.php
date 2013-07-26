<?php
$sql = "select max(DATE_FORMAT(crawl.date_executed, '%d %b %Y')) as maxd
from crawl;";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
    $str = $row['maxd'];
} 


$limit = 15;

if (isset($_GET['limit'])) {
	$limit=$_GET['limit'];
} 

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
cast(crawl_results.vendor_price as decimal(10,2)) as vendor_price,
cast(crawl_results.map_price as decimal(10,2)) as map_price,
cast(crawl_results.violation_amount as decimal(10,2)) as violation_amount,
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
and
crawl.id = 
(select max(crawl.id) from crawl) " . $where . " 
" . $order_by . " LIMIT $start, $limit";

$violators_array=$db_resource->GetResultObj($sql);




// Initial page num setup
$sql1=" SELECT FOUND_ROWS() as total;";
$total_pages=$db_resource->GetResultObj($sql1);
$total_pages=$total_pages[0]->total;

$tab_name = 'recent';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;

$page_param = "page"; //variable used for pagination
$additional_params = "&limit=".$limit; //addtiion params to pagination url;


/*
if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
    $additional_params.="&product_id=" . $_GET['product_id'];
}*/
if (isset($_GET['action']) && $_GET['action']) { // search 
    $additional_params.="&action=" . $_GET['action'] . "&field=sku&value=" . $_GET['value'];
}
 

include_once 'template/recent_tab.phtml';
?>
 


<script language="javascript" type="text/javascript">

    function popitup(url) {
        newwindow = window.open(url, 'name', 'height=200,width=150');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }


</script>


