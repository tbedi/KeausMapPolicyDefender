 <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
          <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
         <link href="css/kraus.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />   
        <link href="css/div.css" rel="stylesheet" type="text/css" />  
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <link href="css/login.css" rel="stylesheet" type="text/css" />
</head>

<?php 
/*configuration*/
setlocale(LC_MONETARY, 'en_US');
include_once 'db.php';
include_once 'db_login.php';

/*configuration*/
include_once 'db_class.php';
include_once 'pagination_class.php';

$db_resource = new DB ();
$pagination= new Pagination();

 /*Get data*/
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
/*$violators_array*/
/*Get data*/
include_once 'grid_class.php';


$grid=new Grid();
 
$grid_properties=array('name'=>'recent','class'=>'GrayBlack','id'=>'','align'=>'left', 'export_enabled'=>1);
 
$grid->NewGrid($grid_properties);
$grid_column=array("title"=>"Sku", "key"=>"sku","sortable"=>1,"search"=>1, "order"=>0, "type"=>"product", "search_placeholder"=>"Enter SKU..."); //"search_value"=>"KBU22"
$grid->AddColumn($grid_column);
$grid_column=array("title"=>"Map Price", "key"=>"map_price","sortable"=>1,"search"=>0, "order"=>3, "type"=>"price");
$grid->AddColumn($grid_column);
$grid_column=array("title"=>"Dealer Price", "key"=>"vendor_price","sortable"=>0,"search"=>0, "order"=>2, "type"=>"price");
$grid->AddColumn($grid_column);
$grid_column=array("title"=>"Dealer", "key"=>"name","sortable"=>0,"search"=>1, "order"=>1, "type"=>"dealer", "search_placeholder"=>"Enter Dealer...");
$grid->AddColumn($grid_column);
$grid_column=array("title"=>"Violation", "key"=>"violation_amount","sortable"=>1,"search"=>0, "order"=>4, "sorted"=>"desc", "type"=>"violation");
$grid->AddColumn($grid_column);
$grid_column=array("title"=>"View",   "key"=>"website_product_url","sortable"=>0,"search"=>0, "order"=>5, "type"=>"url", "url_label"=>"View");
$grid->AddColumn($grid_column);

//set information for the grid
$grid->setGridData($violators_array);

echo $grid->getHTML();

//print_r($violators_array);

/*
$rows=array('sku','name','vendor_price','map_price','violation_amount','website_product_url'); 

foreach ($violators_array as $row) {
?>

<tr>
<?php 
	foreach ($row as $columns) {
		
		 
		
		if ($columns['link']) {
			
			$target="";
			$url=$columns['link']['url'];
			
			if ($columns['link']['url']) 
					$target=$columns['link']['target'];
			
			$value='<a href="'.$url.'" '.$target.'  >'.$columns['value'].'</a>';
		}else {
			$value=$columns['value'];
		}
?>

<td class="<?php echo $class; ?>" id="<?php echo $id; ?>"><?php echo $value; ?></td>

<?php } ?>
</tr>
<?php } */?>
 
</html>