 <?php
	
	$tableName="crawl_results";		
	$targetpage = "index.php"; 	
	$limit = 10; 
	
	 $query = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.entity_id as product_id,
catalog_product_flat_1.name,
format(crawl_results.vendor_price,2) as vendor_price ,
format(crawl_results.map_price,2)as map_price,
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
order by maxvio desc "; 
	 
	 /*Pagination*/
	 $result = mysql_query($query);
	 $total_pages = mysql_num_rows($result);  
 
	 
	 $stages = 3;
	 $page=1;
	 
	 if(isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab']=='violation-by-product' ){
	 	$page = mysql_escape_string($_GET['page']);
	 	$start = ($page - 1) * $limit;
	 }else{
	 	$start = 0;
	 	$page=1;
	 }
	 
	 /*Pagination*/
   
    // Get page data
	$query1 = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.entity_id as product_id,
catalog_product_flat_1.name,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(max(crawl_results.violation_amount),2) as maxvio,
format(min(crawl_results.violation_amount),2) as minvio,
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
order by maxvio desc LIMIT $start, $limit";
	$result = mysql_query($query1);
 
	// Initial page num setup
//if (!$page){$page = 1;}
	$tab_name='violation-by-product';
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;				
	$additional_params=""; //addtiion params to pagination url;
	?>
	
<h3 align="center"	>Product Violations</h3>
<table align="center"   >
<tr>
<td>
  

  <input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;	
	border:2px solid #456879;
	border-radius:10px;float:left;
	height: 22px;
	width: 230px; "/> 
	<a href="" onclick="tableSearch.runSearch();" style="padding-top:0px;">
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
 		 SKU ID
 		  </td>	
   
 		 <td>
  		MAP
 		  </td>
   <td>
 		 Total violations
 		  </td>	
   
 		 <td>
  		Max Violation amount
 		  </td>
           <td>
  		Min Violation amount
 		  </td>
		</tr>
       


					
<?php

        while($row = mysql_fetch_assoc($result)) 
       
	   { 
	        echo "<tr>";
            echo "<td>";
               
			   
			    echo "<a href="."?tab=violation-by-product&product_id=".$row['product_id'].">".$row['sku']."</td>"."<td>"."$".$row['map_price']."</td>"."<td>".$row['i_count']."</td>"."<td>"."$".$row['maxvio']."</td>"."<td>"."$".$row['minvio']."</td>"."</tr>";   
				
            
	   }
		 echo "</table>";
    
     //  mysql_close($con); 
 ?>
 

 		
  <div align="right" style="display:block; " >
  <?php include ('page2.php');?>
</div>		

</td>  
       
   
</tr>       
 </tbody></table> 
   	
 
 
 
 <?php
if(isset($_GET['product_id']) && isset($_GET['tab']) &&  $_GET['tab']=="violation-by-product" )
{
	
	    include_once 'pviolation.php';
}
 ?>