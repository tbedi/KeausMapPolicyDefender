<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product</title>

<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> 
 
<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
<script type="text/javascript" src="js/showhide.js"></script> 
<script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 

<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/ddsmoothmenu.js">
</script>

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "templatemo_menu", 
	orientation: 'h', 
	classname: 'ddsmoothmenu', 
	
	contentsource: "markup"
})

</script> 



<link rel="stylesheet" type="text/css" href="css/paginator.css" />
<link href="css/TBLCSS.css" rel="stylesheet" type="text/css" />
<link href="css/div.css" rel="stylesheet" type="text/css" />
<?php 


include "db.php";
$sql="select max(date_executed) as maxd from crawl";
$result=mysql_query($sql);
     while($row = mysql_fetch_array($result)) 
       	   {   $str=$row['maxd'];
		   }
	
?>

</head>
<?php


	include('connect.php');	

	$tableName="crawl_results";		
	$targetpage = "recent.php"; 	
	$limit = 10; 
	
	$query = "SELECT COUNT(catalog_product_flat_1.sku) as num FROM website
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
crawl.id = 
(select max(crawl.id) from crawl)
";
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	
	$stages = 3;
	 $page=1;

	if(isset($_GET['page'])){
		$page = mysql_escape_string($_GET['page']);
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
							
 
	 
	
//include('db.php');
       
$query1="select catalog_product_flat_1.sku,
website.name as wname, 
crawl_results.vendor_price,
crawl_results.map_price,
crawl_results.violation_amount,
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
crawl.id = 
(select max(crawl.id) from crawl)
order by sku asc  LIMIT $start, $limit";
$result=mysql_query($query1);

// Initial page num setup
	if (!$page){$page = 1;}//now error here
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;	
?>
<body id="home" onload="tableSearch.init();">

<div id="templatemo_main">
<div id="divp">
<h3 align="center"	>Recent Violations( <?php echo $str; ?>)</h3>
<table align="center" >
<tr>
<td>
  
  <div align="right"><input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;
	
	border:2px solid #456879;
	border-radius:10px;
	height: 22px;
	width: 230px; "/> <a href="" onclick="tableSearch.runSearch();"><img src="images/sr.png" style="height:20; width:20;"></a>
     <a class="button_example"  href="export_recent.php"> <img src="images/dn.png" width="20" height="20" /> </a>
               
</div>
	</td>
</tr>
<tr>
<td>
   
    
    <div class="GrayBlack">
  		<table align="center" class="GrayBlack">
        	<tbody id="data">
 		<tr> 
 			 <td>
 		 <strong>SKU</strong>
 		  </td>	
   
 		 <td>
  		<strong>Seller</strong>
 		  </td>
   <td>
 		 <strong>Seller Price</strong>
 		  </td>	
   
 		 <td>
  		<strong>MAP</strong>
 		  </td>
           <td>
  		<strong>Violation Amt</strong>
 		  </td>
            <td>
  		<strong>Screenshot</strong>
 		  </td>
		</tr>
       
      
  <?php
        while($row = mysql_fetch_array($result)) 
       
	   { 
	        echo "<tr>";
            ?>
        	<td ><?php echo $row['sku']; ?></td>
     	 	<td ><?php echo $row['wname']; ?></td>
     	    <td ><?php echo $row['vendor_price']; ?></td>
			 <td ><?php echo $row['map_price']; ?></td>
     	 	<td ><?php echo $row['violation_amount']; ?></td>
     	  <td ><?php echo "<a target=".'_blank'." href =".$row['website_product_url']. ">"."Link". "</a>" ?></td>
        <?php echo "</tr>";
            
	   }
		 echo "</table>";
      
     //  mysql_close($con); 
 include ('page2.php');
 ?>


 
 
 

 
 
</div>

</td>  
       
   
</tr>
 </tbody></table> 
 <table align="center">
<tr>
<td >

<!--<div style="hight=250px width=250px">-->
<?php  
include_once 'chart/A1pie_gradient.php';?>
<!--<div id="container" style="min-width: 250px; height: 250px; margin: 0 auto"></div>-->

<!--<iframe src="chart/A1pie_gradient.php" style="hight=300px; width=300px;  overflow:hidden;  frameborder=0; " >
</iframe>-->

</td>
<td>
<!--<iframe src="chart/A2pie_gradient.php" style=" hight=300px; width=300px; overflow:hidden; frameborder=0; " >
</iframe>-->
<?php  
include_once 'chart/A2pie_gradient.php';
?>
</td>
</tr>
</table>

       

 </div>   
</div>

</body></html>