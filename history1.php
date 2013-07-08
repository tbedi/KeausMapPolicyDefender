<h3 align="center"	>Violations History</h3>
<table align="center"   >
<tr>
<td >
  
 <!-- transfer all inline styles into style.css -->
  <input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onKeyUp="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;	
	border:2px solid #456879;
	border-radius:10px;float:left;
	height: 22px;
	width: 230px; "/> 
	<a href="" onClick="tableSearch.runSearch();" style="padding-top:0px;">
	<img src="images/sr.png" style="height:20px; width:20px; float:left; "></a>
     <a  style="float:left;padding-top:0px;"  href="export_recent.php"> <img src="images/dn.png" width="20" height="20" /> </a>
 
	</td>
</tr>
<tr>
<td>
       
  		<table class="GrayBlack" align="center">
        	<tbody id="data">
 		<tr> 
 			 <td>
 		 <strong>Product</strong>
 		  </td>	
   
 		 <td>
  		<strong>SKU</strong>
 		  </td>
   <td>
 		 <strong>Seller</strong>
 		  </td>	
   
 		 <td>
  		<strong>Vendor Price</strong>
 		  </td>
           <td>
  		<strong>MAP Price</strong>
 		  </td>
            <td>
  		<strong>Violation Amt</strong>
 		  </td>
            <td>
  		<strong>Screenshot</strong>
 		  </td>
		</tr>
       


					
<?php
       
$sql="select catalog_product_flat_1.sku,
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
order by sku asc";
$result=mysql_query($sql);
      
  
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
 ?>
 
 

</td>  
       
   
</tr>       
 </tbody></table> 
 