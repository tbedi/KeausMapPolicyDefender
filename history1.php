<h3 align="center"	>Violations History</h3>

<?php
//pagination
	
	$tableName="crawl_results";		
	$targetpage = "index.php"; 	
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
order by sku asc
";
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	
	$stages = 3;
	 $page=1;

	if(isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab']=='violations-history' ){
		$page = mysql_escape_string($_GET['page']);
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		$page=1;
		}	
		
		$query1="select catalog_product_flat_1.sku,
catalog_product_flat_1.name as pname,
website.name as wname, 
crawl_results.vendor_price,
crawl_results.map_price,
crawl_results.violation_amount,
crawl_results.website_product_url,
crawl.date_executed
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
order by sku asc LIMIT $start, $limit";
$result=mysql_query($query1);
      
	  // Initial page num setup
	//if (!$page){$page = 1;}
	$tab_name='violations-history';
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;	
        $additional_params="";
?>

<table align="center">
<tr>
<td >
  

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
 		 Product
                         </td>	
   
 		 <td>
  		SKU
 		  </td>
   <td>
 		Seller
 		  </td>	
   
 		 <td>
  		Vendor price
 		  </td>
           <td>
  		MAP price
 		  </td>
            <td>
  		Violation amt
 		  </td>
            <td>
  		Screenshot
 		  </td>
		</tr>
      
<?php
            while($row = mysql_fetch_array($result)) 
       
	   { 
	        echo "<tr>";
            ?>
            <td ><?php echo $row['pname']; ?></td>
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
    
    <div  style="display:block;">
  <?php include ('page2.php');?>
</div>			
 
   <div  style="display: table-row-group;">
        <table>
            <tr>
                <td>
                    <?php include_once 'charts/a5.php'; ?>
                </td>
               <td>
                    <?php include_once 'charts/a6.php'; ?>
                </td>
            </tr>
           
        </table>
</div>  