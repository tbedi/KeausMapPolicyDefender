<?php
include('db.php');
$sku_name = $_REQUEST['sku_id'];
?>

<h3 align="center"> Sellers Violated  <?php echo $sku_name; ?> </h3> 
<table align="center"   >
<tr>
<td >
  
 <!-- transfer all inline styles into style.css -->
  <input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;	
	border:2px solid #456879;
	border-radius:10px;float:left;
	height: 22px;
	width: 230px; "/> 
	<a href="" onclick="tableSearch.runSearch();" style="padding-top:0px;">
	<img src="images/sr.png" style="height:20px; width:20px; float:left; "></a>
     <a  style="float:left;padding-top:0px;"  href="export_product.php"> <img src="images/dn.png" width="20" height="20" /> </a>
 
	</td>
</tr>
<tr>
<td>
       
  		<table class="GrayBlack" align="center">
        	<tbody id="data">
 		<tr> 
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
 
 

</td>  
       
   
</tr>       
 </tbody></table> 
 