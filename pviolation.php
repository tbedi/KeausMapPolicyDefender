<?php
$product_id = $_REQUEST['product_id'];

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
		    ORDER BY r.violation_amount DESC LIMIT $start, $limit";

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
?>
 

<h3 align="center"> Sellers Violated  <?php echo $sku['sku']; ?> </h3> 


<table align="center"  width="1000px" >
    <tr>
        <td >
            <div style="padding-right: 20px;padding-left:0px; float: left">

                <input  	placeholder="Search here..." type="text" size="30"  maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  
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
                <a href="javascript:void(0);" class="myButton"  onclick="tableSearch.runSearch();">Search</a>
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
                        <td bgcolor="#CCCCCC">Website</td>
                        <td bgcolor="#CCCCCC">Vendor price</td>
                        <td bgcolor="#CCCCCC">Map</td>
                        <td bgcolor="#CCCCCC">Violation</td>
                        <td bgcolor="#CCCCCC">Link</td>
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
