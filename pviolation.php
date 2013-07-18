<?php
 

//pagination


$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 10;
 
$query = "SELECT  COUNT(*) as num
				  FROM crawl_results  r
    INNER JOIN website w
    ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p
    ON p.entity_id=r.product_id
    AND p.entity_id='" . $product_id . "'
    WHERE r.crawl_id=" . $last_crawl['id'] . "
		    AND r.violation_amount>0.05
                    and w.excluded=0 
		    ORDER BY r.violation_amount DESC";

$total_pages = mysql_fetch_assoc(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;

if (isset($_GET['second_grid_page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') {
    $page = mysql_escape_string($_GET['second_grid_page']); //$page_param should have same value
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}


/* sorting */

if (isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' && isset($_GET['sort'])) {
    $direction = $_GET['sort'];
    $order_field = $_GET['sort_column'];
    
} else {
    $direction = "desc";
    $order_field = "max(crawl_results.violation_amount)";
}

$order_by = "order by " . $order_field . " " . $direction . " ";

/* sorting */




$sql = "SELECT  distinct w.`name` as vendor ,
    format(r.violation_amount,2) as violation_amount,
    format( r.vendor_price,2) as vendor_price,
    format(r.map_price,2) as map_price,
    r.website_product_url
    FROM crawl_results  r
    INNER JOIN website w
    ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p
    ON p.entity_id=r.product_id
    AND p.entity_id='" . $product_id . "'
    WHERE r.crawl_id=" . $last_crawl['id'] . "
		    AND r.violation_amount>0.05
                    and w.excluded=0
		    ".$order_by." LIMIT $start, $limit";

$result = mysql_query($sql);

// Initial page num setup
//if (!$page){$page = 1;}
$tab_name = 'violation-by-product';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$page_param = "second_grid_page"; //variable used for pagination
$additional_params = "&product_id=" . $product_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page']; //here it should 
}
$sql="SELECT sku FROM catalog_product_flat_1 WHERE entity_id=".$product_id;
$sku_result = mysql_query($sql);
$sku=mysql_fetch_assoc($sku_result);



if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
		$additional_params.="&second_grid_page=".$_GET['second_grid_page'];
	}
	if (isset($_GET['product_id']) && $_GET['product_id']) { //adding support for product
		$additional_params.="&product_id=".$_GET['product_id'];
	}





if (isset($_GET['action']) && $_GET['action']) { // search 
		$additional_params.="&action=".$_GET['action']."&field=vendor&value=".$_GET['value'];
	}


        
        //sort
if (isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' && isset($_GET['sort']) ) {
	$additional_params.="&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'];
}

//sort
        
?>
 

<h3 align="center"> Sellers Violated  <?php echo $sku['sku']; ?> </h3> 


<table align="center"  width="1000px" >
    <tr>
        <td >
            <div style="padding-right: 20px;padding-left:0px; float: left">

                <input  class="product-violation-search2" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action']=="search2" && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product') echo $_GET['value']; ?>" 
                         id="textBoxSearch" onkeyup="tableSearch.search(event);"  
                         style="padding:5px;
                         padding-right: 40px;
                         background-image:url(images/sr.png); 
                         background-position: 100% -5px; 
                         background-repeat: no-repeat;
                         border:2px solid #456879;
                         border-radius:10px;float:left;
                         height: 15px;
                         outline:none; 
                         width: 200px; "/> </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="javascript:void(0);" class="myButton"  onclick="product_violation_search2();">Search</a>
            </div>
        </td>
        <td> 
            <div style="padding-right: 20px;padding-left:0px; float: left">
                Export To

                <select  id="exportp2" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:4;" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exporttop2();">Export</a>
            </div>
    </td>
</tr>


<script type="text/javascript">
               
               
               function product_violation_search2() {
            		 var field = "vendor";
                     var value = $(".product-violation-search2").val();
                     
                     var search_url_additional_params = "<?php if (isset($_GET['page']) && $_GET['page']) echo '&page=' . $_GET['page']; if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) echo '&second_grid_page=' . $_GET['second_grid_page'];  ?>";

                     var search_link = "/index.php?action=search2&field=" + field + "&value=" + value +"&tab=violation-by-product"+ search_url_additional_params;

                     window.open(search_link, "_self");
                     tableSearch.runSearch();
                 }
               
               /*
                             function product_violation_search2() {
                                var field = "vendor";
                                var value = $(".product-violation-search2").val();
                                var url_options= "<?php echo ( isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' && isset($_GET['sort']) ? "&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'] : "" );   ?>"
                                
                        		if (value.length) {                        			
                        			url_options+="&action=search2&field=" + field + "&value=" + value;
                        		}
                            			
                                var search_link = "index.php?tab=violation-by-product" + url_options;

                                window.open(search_link, "_self");
                                tableSearch.runSearch();


                            }
    */                       
    function exporttop2()
                            {
                                var mode = $("#exportp2").val();
                                var url_options= window.location.search.substring(1);
                                
                                if (url_options.length)
                                		url_options='?'+url_options;
                        		
                                if (mode)                                    
                                    open("export_product2_" + mode + ".php"+url_options);



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
                        <td >Website
                        <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=vendor&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="vendor" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                        <td >Vendor price
                        <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=vendor_price&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="vendor_price" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                        <td >Map
                        <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=map_price&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="map_price" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                        <td >Violation
                        <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=violation_amount&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-product' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="violation_amount" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                        <td >Link
                        
                        </td>
                    </tr>




                    <?php
                    while ($row = mysql_fetch_assoc($result)) {
                        ?> <tr>
                            <?php
                            if ($row['violation_amount'] > 10) {
                                ?>



                                <td ><?php echo $row['vendor']; ?></td>
                                <td ><?php echo "$" . $row['vendor_price']; ?></td>
                                <td ><?php echo "$" . $row['map_price']; ?></td>
                                <td id="vioR"><?php echo "$" . $row['violation_amount']; ?></td>
                                <td ><?php echo "<a target=" . '_blank' . " href=" . $row['website_product_url'] . ">" . " Product Link" . "</a>" ?></td>

                                <?php
                            } else if ($row['violation_amount'] >= 5 && $row['violation_amount'] < 10) {
                                ?>


                                <td ><?php echo $row['vendor']; ?></td>
                                <td ><?php echo $row['vendor_price']; ?></td>
                                <td ><?php echo $row['map_price']; ?></td>
                                <td   id="vioO"><?php echo $row['violation_amount']; ?></td>
                                <td ><?php echo "<a target=" . '_blank' . " href=" . $row['website_product_url'] . ">" . " Product Link" . "</a>" ?></td>
                                <?php
                            } else if ($row['violation_amount'] < 5) {
                                ?>


                                <td ><?php echo $row['vendor']; ?></td>
                                <td ><?php echo $row['vendor_price']; ?></td>
                                <td ><?php echo $row['map_price']; ?></td>
                                <td td id="vio"><?php echo $row['violation_amount']; ?></td>
                                <td ><?php echo "<a target=" . '_blank' . " href=" . $row['website_product_url'] . ">" . " Product Link" . "</a>" ?></td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
// close while loop 
                    }
                    ?>


                </tbody></table> 

            <div align="left" style="display:block;" >
                <?php include ('page2.php'); ?>
            </div>	
        </td>  

    </tr>       
</table> 


<div>

    <?php include_once 'charts/a3.php'; ?>
</div>
