 <?php
	include('connect.php');	

	$tableName="crawl_results";		
	$targetpage = "product1.php"; 	
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
order by count(crawl_results.product_id)";
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
	
<h3 align="center"	>Product Violations</h3>
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
     <a  style="float:left;padding-top:0px;"  href="export_recent.php"> <img src="images/dn.png" width="20" height="20" /> </a>
 
	</td>
</tr>
<tr>
<td>
       
  		<table class="GrayBlack" align="center">
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
               
			   
			    echo "<a href="."?sku_id=".$row['sku']."&showclicked".">".$row['sku']."</td>"."<td>".$row['map_price']."</td>"."<td>".$row['i_count']."</td>"."<td>".$row['maxvio']."</td>"."<td>".$row['minvio']."</td>"."</tr>";   
				
            
	   }
		 echo "</table>";
       include ('page2.php');
     //  mysql_close($con); 
 ?>
 
 

</td>  
       
   
</tr>       
 </tbody></table> 
 
 
 
 <?php
if(isset($_GET['showclicked']))
{
	
	echo "heloooooo";
    include_once 'pviolation1.php';
}
 