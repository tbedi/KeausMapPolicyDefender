 <?php
	
	$tableName="crawl_results";		
	$targetpage = "index.php"; 	
	$limit = 10; 
	
	$where="";
	
	if (isset($_GET['action']) && $_GET['action'] == 'searchfirst' &&isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
		$field = strtolower($_GET['field']);
		$value = strtolower($_GET['value']);
		$where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
	}
	
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
(select max(crawl.id) from crawl) " . $where . " 
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
(select max(crawl.id) from crawl) " . $where . " 
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
       
	$page_param="page";//variable used for pagination
	$additional_params=""; //addtiion params to pagination url;
	if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
		$additional_params.="&second_grid_page=".$_GET['second_grid_page'];
	}
	if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
		$additional_params.="&product_id=".$_GET['product_id'];
	}
	if (isset($_GET['action']) && $_GET['action']) { // search 
		$additional_params.="&action=".$_GET['action']."&field=sku&value=".$_GET['value'];
	}
	
	?>
	
<h3 align="center"	>Product Violations</h3>
<table align="center"  width="1000px" >
<tr>
<td>
  
<td >
 <div style="padding-right: 20px;padding-left:0px; float: left">

            <input  	class="product-violation-search" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action']=="searchfirst" && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') echo $_GET['value']; ?>" id="textBoxSearch"   
                     style="padding:5px;
                     padding-right: 40px;
                     background-image:url(images/sr.png); 
                     background-position: 100% -5px; 
                     background-repeat: no-repeat;
                     border:2px solid #456879;
                     border-radius:10px;float:left;
                     height: 15px;
                     outline:none; 
                     width: 200px; "/> 
            </div>
            <div style="padding-right: 20px;padding-left:20px;">
            
            <a href="javascript:void(0);" class="myButton"  onclick="product_violation_search();" >Search</a>
            </div>
             <script type="text/javascript">
             	function product_violation_search() {
            		 var field = "sku";
                     var value = $(".product-violation-search").val();
                     
                     var search_url_additional_params = "<?php if (isset($_GET['page']) && $_GET['page']) echo '&page=' . $_GET['page']; if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) echo '&second_grid_page=' . $_GET['second_grid_page'];  ?>";

                     var search_link = "/index.php?action=searchfirst&field=" + field + "&value=" + value +"&tab=violation-by-product"+ search_url_additional_params;

                     window.open(search_link, "_self");
             	}
             </script>   
        </td>
        <td>
            <div style="padding-right: 20px;padding-left:0px; float: left">
                Export To

                <select  id="exportp" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:4;" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exportto();">Export</a>
            </div>
    </td>
</tr>


<script type="text/javascript">
               
                            
                            function exportto()
                            {
                                var mode = $("#exportp").val();
                                var url_options= window.location.search.substring(1);
                                
                                if (url_options.length)
                                		url_options='?'+url_options;
                        		
                                if (mode)                                    
                                    open("export_product_" + mode + ".php"+url_options);



                            }

    </script>
 




</table>

<div class="cleaner" style="padding-top: 15px; ">
    
 </div>
<table align="center">
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
 
            $product_link="?tab=violation-by-product&product_id=".$row['product_id'];            
 
	   		if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
				$product_link.="&page=".$_GET['page'];
			}
			
			if (isset($_GET['action']) && $_GET['action']=="searchfirst") { //added that search  was included
				$product_link.="&action=".$_GET['action']."&field=sku&value=".$_GET['value'];
			}
			
			    echo "<a href='".$product_link."'>".$row['sku']."</td> <td> $".$row['map_price']."</td> <td>".$row['i_count']."</td> <td> $ ".$row['maxvio']."</td> <td> $".$row['minvio']."</td> </tr>";   
				
            
	   }
		 echo "</table>";
    
     //  mysql_close($con); 
 ?>
 

 		
  <div align="left" style="display:block; " >
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