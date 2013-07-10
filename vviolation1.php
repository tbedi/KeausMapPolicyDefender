
<?php

$web_id = $_REQUEST['website_id'];
$result= mysql_query("select website.name as wname 
from website
inner join
crawl_results
on website.id = crawl_results.website_id
where website_id=$web_id");


  while($row = mysql_fetch_array($result)) 
 {      
      $str= $row['wname'];
 }
?>
  

	 <h3 align="center"> Products Violated by <?php echo $str; ?> <h3> 
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
where crawl_results.violation_amount>0.05 
and website_id = $web_id
order by violation_amount desc";
	
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	
	$stages = 3;
	 $page=1;

	if(isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab']=='vviolation' ){
		$page = mysql_escape_string($_GET['page']);
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		$page=1;
		}	
		
		
		
		$query1="select distinct crawl_results.website_id,
domain,
website.name as wname,
catalog_product_flat_1.entity_id,
catalog_product_flat_1.name,
catalog_product_flat_1.sku,
crawl_results.vendor_price,
crawl_results.map_price,
crawl_results.violation_amount,
website_id,
crawl_results.website_product_url
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where crawl_results.violation_amount>0.05 
and website_id = $web_id
order by violation_amount desc LIMIT $start, $limit";
$result=mysql_query($query1);
      
	  // Initial page num setup
		//if (!$page){$page = 1;}
	$tab_name='vviolation';
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
?>

  
      <div align="right"><input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onKeyUp="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;
	
	border:2px solid #456879;
	border-radius:10px;
	height: 22px;
	width: 230px; "/> <a href="" onClick="tableSearch.runSearch();"><img src="images/sr.png" style="height:20; width:20;"></a>

                    <?php echo "<a class="."button_example"." href="."export_vendor.php?website_id=".$web_id.">" ?> <img src="images/dn.png" width="20" height="20" /> </a> </div>
	 
             
     <div class="GrayBlack">
  
 <table  align="center" border="2" cellpadding="0" cellspacing="0">
<tbody id="data"> 
<tr  align="center" >
  
        <td bgcolor="#CCCCCC">SKU</td>
       <td bgcolor="#CCCCCC"> Vendor price</td>
       <td bgcolor="#CCCCCC"> Map price</td>
       <td bgcolor="#CCCCCC"> Violation amount</td>
       <td bgcolor="#CCCCCC"> Link</td>
      </tr>
   <?php
if(!$result)
{
	echo "error";
}
else
{
        while($row = mysql_fetch_array($result)) 
       { 
       echo "<tr>";
       	  if($row['violation_amount']>10)
		  {
		 
       echo "<td>".$row['sku']."</td>"."<td>".$row['vendor_price']."</td>"."<td>".$row['map_price']."</td>"."<td  id=".'vioR'."  ".">".$row

['violation_amount']."</td>"."<td >"."<a target=".'_blank'." href =".$row['website_product_url']. ">" ." Product Link". "</a></td>";      
			
		  }
		   else if($row['violation_amount']>=5 && $row['violation_amount']<10)
		  {
		echo "<td>".$row['sku']."</td>"."<td>".$row['vendor_price']."</td>"."<td>".$row['map_price']."</td>"."<td id=".'vioO'."  ".">".$row

['violation_amount']."</td>"."<td>"."<a target=".'_blank'." href =".$row['website_product_url']. ">" ." Product Link". "</a></td>";      
		  }
		  
		   else if($row['violation_amount']<5)
		  {
		echo "<td>".$row['sku']."</td>"."<td>".$row['vendor_price']."</td>"."<td>".$row['map_price']."</td>"."<td id=".'vio'."  ".">".$row

['violation_amount']."</td>"."<td>"."<a target=".'_blank'." href =".$row['website_product_url']. ">" ." Product Link". "</a></td>";      
		  }
       echo "</tr>";  
       } 
}
	
 ?>	
 <div  style="display:block;">
  <?php include_once ('page2.php');?>
</div>			
 

</tbody>
  </table>
<div  style="display: table-row-group;">
        <table>
            <tr>
                <td>
                    <?php include_once 'charts/a4.php'; ?>
                </td>
               
            </tr>
           
        </table>
</div>  