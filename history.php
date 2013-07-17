<h3 align="center"	>Violations History</h3>

<?php
//pagination
$tableName = "crawl_results";
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
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl)
order by sku asc
";


$total_pages = mysql_fetch_assoc(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;

if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
?>


<table align="center" width="1000px;"  >
    <tr>
        <td >
            <div style="padding-right: 0px;padding-left:0px; float: left">
                <input  placeholder="Search here..." type="text" size="30"  maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  
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
                          </td>
        <td>
           
            <div style="padding-right: 7px;padding-left:0px;">
                <a href="javascript:void(0);" class="myButton"  onclick="tableSearch.runSearch();">Search</a>
                            
            </div>
        </td>
        <td>
            <form action=" ?tab=violations-history&option=show_dates" method="post"> 
                <div style="padding-right: 7px;padding-left:0px; float: left">
                    To   <input type="text" size="12" name="to" id="inputFieldto"  style=" background: white url(img/cal2.png) right no-repeat;"/>
                    From   <input type="text" size="12" name="from" id="inputFieldfrom" style=" background: white url(img/cal2.png) right no-repeat;"/>
                    <input type="submit" class="myButton" name="submit" value="show" /> </div>
            </form>

        </td>





        <td> 
           <div style="padding-right: 20px;padding-left:0px; float: left">
                Export To

                <select  id="exporth" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:4;" >
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
                                var mode = $("#exporth").val();
                                var url_options= window.location.search.substring(1);
                                
                                if (url_options.length)
                                		url_options='?'+url_options;
                        		
                                if (mode)                                    
                                    open("export_history_" + mode + ".php"+url_options);



                            }

    </script>

</table>


<div class="cleaner" style="padding-top: 15px; ">
    
 </div>
<?php
if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['option']) && $_GET['option'] == 'show_dates') {
    // print_r($_POST);
    // print_r($_POST["to"]);
    // die();
    $to = $_POST["to"];
    $from = $_POST["from"];
    echo "<br>                      Violations from " . $from . " to " . $to;
} else {
    $to = '2013-07-10';
    $from = '2013-06-12';
}




$query1 = "select catalog_product_flat_1.sku,
catalog_product_flat_1.name as pname,
website.name as wname, 
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
crawl_results.website_product_url,
crawl.date_executed
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join crawl
on crawl.id=crawl_results.crawl_id
where crawl.date_executed between '$to' and '$from'  
and
crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl)
order by sku asc LIMIT $start, $limit";
$result = mysql_query($query1);
if (!$result) {
    echo mysql_error();
} else {


// Initial page num setup
//if (!$page){$page = 1;}
    $tab_name = 'violations-history';
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_pages / $limit);
    $LastPagem1 = $lastpage - 1;
    $additional_params = "";
    $page_param = "page";
}
    ?>
     
    
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
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        
                        
         if ($row['violation_amount'] > 10) {
                            ?>                
                        
                        
                        <td ><?php echo $row['pname']; ?></td>
                        <td ><?php echo $row['sku']; ?></td>
                        <td ><?php echo $row['wname']; ?></td>
                        <td ><?php echo "$" . $row['vendor_price']; ?></td>
                        <td ><?php echo "$" . $row['map_price']; ?></td>
                        <td id="vioR"><?php echo "$" . $row['violation_amount']; ?></td>
                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ;?></td>
                         

  <?php
                    } else if ($row['violation_amount'] >= 5 && $row['violation_amount'] < 10) {
?>
                         <td ><?php echo $row['pname']; ?></td>
                        <td ><?php echo $row['sku']; ?></td>
                        <td ><?php echo $row['wname']; ?></td>
                        <td ><?php echo "$" . $row['vendor_price']; ?></td>
                        <td ><?php echo "$" . $row['map_price']; ?></td>
                        <td id="vioO"><?php echo "$" . $row['violation_amount']; ?></td>
                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ;?></td>
                        
                     
                         <?php
                    } else  if ($row['violation_amount'] < 5)
                        {
                        
                         ?>
                         <td ><?php echo $row['pname']; ?></td>
                        <td ><?php echo $row['sku']; ?></td>
                        <td ><?php echo $row['wname']; ?></td>
                        <td ><?php echo "$" . $row['vendor_price']; ?></td>
                        <td ><?php echo "$" . $row['map_price']; ?></td>
                        <td id="vio"><?php echo "$" . $row['violation_amount']; ?></td>
                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ;?></td>
                        
                         <?php
                    }
                        
                        
                        
                        
  
                    }

                    ?>
                        
                <div align="left" style="display:block;">
<?php include ('page2.php'); ?>
                </div>		
     

    </tr>       
</tbody></table> 



<div  style="display: table-row-group;" >
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