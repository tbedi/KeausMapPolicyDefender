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
<link rel="stylesheet" type="text/css" href="css/paginator.css" />
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




<link href="css/TBLCSS.css" rel="stylesheet" type="text/css" />
<link href="css/div.css" rel="stylesheet" type="text/css" />



<?php
	include('connect.php');	

	$tableName="crawl_results";		
	$targetpage = "product.php"; 	
	$limit = 10; 
	
	$query = "SELECT COUNT(catalog_product_flat_1.sku) as num FROM prices.catalog_product_flat_1
inner join
prices.crawl_results
on catalog_product_flat_1.entity_id = crawl_results.product_id 
inner join crawl
on
crawl_results.crawl_id = crawl.id
where crawl_results.violation_amount>0.05
 and 
crawl.id = 
(select max(crawl.id) from crawl)
group by prices.catalog_product_flat_1.sku,
prices.catalog_product_flat_1.name
order by count(crawl_results.product_id) desc";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	
	$stages = 3;
	$page = mysql_escape_string($_GET['page']);
	if($page){
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
	
    // Get page data
	$query1 = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.name,
crawl_results.vendor_price,
crawl_results.map_price,
max(crawl_results.violation_amount) as maxvio,
min(crawl_results.violation_amount) as minvio,
count(crawl_results.product_id) as i_count
FROM
prices.catalog_product_flat_1
inner join
prices.crawl_results
on catalog_product_flat_1.entity_id = crawl_results.product_id 
inner join crawl
on
crawl_results.crawl_id = crawl.id
where crawl_results.violation_amount>0.05
 and 
crawl.id = 
(select max(crawl.id) from crawl)
group by prices.catalog_product_flat_1.sku,
prices.catalog_product_flat_1.name
order by count(crawl_results.product_id) desc LIMIT $start, $limit";
	$result = mysql_query($query1);
	
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	

	?>
	





</head>

<body id="home" onload="tableSearch.init();">

<div id="templatemo_main">
<div id="divp">
<h3 align="center"	>Violation By Product</h3>
<table align="center" >
<tr>

<td>
  
 <div align="right"><input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;
	
	border:2px solid #456879;
	border-radius:10px;
	height: 22px;
	width: 230px; "/> <a href="" onclick="tableSearch.runSearch();"><img src="images/sr.png" style="height:20; width:20;"></a>
   <a class="button_example"  href="export_product1.php"> <img src="images/dn.png" width="20" height="20" /> </a>
               
</div>
	</td>
</tr>
<tr>
<td>
   
    
    <div class="GrayBlack">
  		<table align="center">
        	<tbody id="data">
 		<tr> 
 			 <td>
 		 <strong>SKU ID</strong>
 		  </td>	
   
 		 <td>
  		<strong>MAP</strong>
 		  </td>
   <td>
 		 <strong>Total Vioations</strong>
 		  </td>	
   
 		 <td>
  		<strong>Max Violation Amount</strong>
 		  </td>
           <td>
  		<strong>Min Violation Amount</strong>
 		  </td>
		</tr>
       


					
<?php
//include('db.php');
       

      
  
        while($row = mysql_fetch_array($result)) 
       
	   { 
	        echo "<tr>";
            echo "<td>";
               
			   
			    echo "<a  href="."pviolation.php?sku_id=".$row['sku'].">".$row['sku']."</td>"."<td>".$row['map_price']."</td>"."<td>".$row['i_count']."</td>"."<td>".$row['maxvio']."</td>"."<td>".$row['minvio']."</td>"."</tr>";   
				
            
	   }
		 echo "</table>";
		   include ('page2.php');
      
     //  mysql_close($con); 	
 ?>
 
</div>

</td>  
       
   
</tr>       
 </tbody></table> 
 </div>   
</div>



<?php
function show()
{ ?>
<div id="templatemo_main">
 <div id="divp">   
 <?php
include('db.php');
$sku_name = $_REQUEST['sku_id'];
?>   
	 <h3 align="center"> Sellers Violated  <?php echo $sku_name; ?> </h3> 
    <a href="product.php"><img align="left" src="images/back.png"/> </a>
      <div align="right"><input  type="text" size="30" width="300" maxlength="1000" value="" id="textBoxSearch" onKeyUp="tableSearch.search(event);" />
                    <a href="" onClick="tableSearch.runSearch();" class="button_example"> <img src="images/search2.jpg" width="66" height="39"  /> </a>
                    <?php echo "<a class="."button_example"." href="."export_product.php?sku_id=".$sku_name.">" ?> <img src="images/save.jpg" width="66" height="39" /> </a> </div>
  
  <div class="GrayBlack">
 <table  align="center" border="2" cellpadding="0" cellspacing="0">
<tbody id="data"> 
<tr  align="center" >
  
        
        <td bgcolor="#CCCCCC"><b>Website</b></td>
        <td bgcolor="#CCCCCC"><b>Vendor Price</b></td>
        <td bgcolor="#CCCCCC"><b>Map</b></td>
        <td bgcolor="#CCCCCC"><b>Violation</b></td>
        <td bgcolor="#CCCCCC"><b>Link</b></td>
      </tr>
    <?php
$sql1="SELECT distinct 
catalog_product_flat_1.sku, website.name as wname,
website.domain,
crawl_results.vendor_price,
crawl_results.map_price,
crawl_results.violation_amount,
crawl_results.website_product_url
FROM
crawl_results
inner join catalog_product_flat_1
on
crawl_results.product_id= catalog_product_flat_1.entity_id
inner join website
on
crawl_results.website_id= website.id
where crawl_results.violation_amount>0.05 and
sku like '$sku_name'
order by violation_amount desc";

$result1=mysql_query($sql1);

while($row=mysql_fetch_array($result1))
{
?>
    <?php
     echo "<tr>";
	   
	   if($row['violation_amount']>10)
		  {
    ?>
  
	
	
      <td ><?php echo $row['wname']; ?></td>
      <td ><?php echo $row['vendor_price']; ?></td>
      <td ><?php echo $row['map_price']; ?></td>
      <td id="vioR"><?php echo $row['violation_amount']; ?></td>
      <td ><?php echo "<a target=".'_blank'." href =".$row['website_product_url']. ">" ." Product Link". "</a>" ?></td>
	
	<?php
	
		  }
		  else if($row['violation_amount']>=5 && $row['violation_amount']<10)
    {
    
    ?>
    
  
      <td ><?php echo $row['wname']; ?></td>
      <td ><?php echo $row['vendor_price']; ?></td>
      <td ><?php echo $row['map_price']; ?></td>
      <td td id="vioO"><?php echo $row['violation_amount']; ?></td>
      <td ><?php echo "<a target=".'_blank'." href =".$row['website_product_url']. ">"." Product Link". "</a>" ?></td>
     <?php
	  }
	  
	  
	   else if($row['violation_amount']<5)
    {
    
    ?>
    
  
      <td ><?php echo $row['wname']; ?></td>
      <td ><?php echo $row['vendor_price']; ?></td>
      <td ><?php echo $row['map_price']; ?></td>
      <td td id="vio"><?php echo $row['violation_amount']; ?></td>
      <td ><?php echo "<a target=".'_blank'." href =".$row['website_product_url']. ">"." Product Link". "</a>" ?></td>
     <?php
	  }
	  
?>
    </tr>
    <?php
// close while loop 
}
?>
</tbody>
  </table>
</div>

    </div>
   
 </div>   
<?php } ?>








</body></html>