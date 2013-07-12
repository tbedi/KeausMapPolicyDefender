<?php
$sql = "select max(date_executed) as maxd from crawl";
$result = mysql_query($sql);
while ($row = mysql_fetch_assoc($result)) {
    $str = $row['maxd'];
}
?>	

<?php
////pagination


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
crawl.id = 
(select max(crawl.id) from crawl)
";

$total_pages = mysql_fetch_assoc(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;

if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}



$query1 = "select catalog_product_flat_1.sku,
website.name as wname, 
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
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
order by violation_amount desc LIMIT $start, $limit";
$result = mysql_query($query1);

// Initial page num setup
//if (!$page){$page = 1;}
$tab_name = 'recent';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$additional_params = ""; //addtiion params to pagination url;
?>

<h3 align="center">Recent Violations( <?php echo $str; ?>)</h3>
<table align="center"  >
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
        <td> Export To
            <select  name="a" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:2;" onchange="document.getElementById('displayValue').value = this.options[this.selectedIndex].text;
                    document.getElementById('idValue').value = this.options[this.selectedIndex].value;">
                <option value="xls" selected="xls">xls</option>
                <option value="pdf">PDF</option>
            </select>
            <?php
            if(isset($displayvalue)=="xls" )
            {
                include_once 'export_recent.php';
            }
            else
            {
               
            }
                    ?>
            
            <input name="idValue" id="idValue" type="hidden">
           <!-- <script type="text/javascript">
                $("#link").click(function() {
                    $.ajax({
                        url: "export_recent.php"
                    });
                });
            </script>-->
        </td>-->
    <tr>
        <td>
            <table class="GrayBlack" align="center">
                <tbody id="data">
                    <tr> 
                        <td>
                            SKU
                        </td>	

                        <td>
                            Seller
                        </td>
                        <td>
                            Seller price
                        </td>	

                        <td>
                            MAP
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
                        ?>
                    <td ><?php echo $row['sku']; ?></td>
                    <td ><?php echo $row['wname']; ?></td>
                    <td ><?php echo "$" . $row['vendor_price']; ?></td>
                    <td ><?php echo "$" . $row['map_price']; ?></td>
                    <td ><?php echo "$" . $row['violation_amount']; ?></td>
                    <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ?></td>
    <?php
    echo "</tr>";
}
echo "</table>";

//  mysql_close($con); 
?> 

                <div align="right" style="display:block;">
                <?php include ('page2.php'); ?>
                </div>			


        </td>  


    </tr>       
</tbody></table> 

<div  style="display:table-row-group;">
    <table>
        <tr>
            <td>
<?php include_once 'charts/a1.php'; ?>
            </td>
            <td>
<?php include_once 'charts/a2.php'; ?>
            </td>
        </tr>

    </table>
</div>  
