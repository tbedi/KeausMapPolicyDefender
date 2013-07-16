
<?php
$web_id = $_REQUEST['website_id'];
$result = mysql_query("select website.name as wname 
from website
inner join
crawl_results
on website.id = crawl_results.website_id
where website_id=$web_id");


while ($row = mysql_fetch_assoc($result)) {
    $str = $row['wname'];
}
?>

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
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and website_id = $web_id
order by violation_amount desc";


$total_pages = mysql_fetch_assoc(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;

if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}



$query1 = "select distinct crawl_results.website_id,
domain,
website.name as wname,
catalog_product_flat_1.entity_id,
catalog_product_flat_1.name,
catalog_product_flat_1.sku,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
website_id,
crawl_results.website_product_url
from crawl_results
inner join
website
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and website_id = $web_id
order by violation_amount desc LIMIT $start, $limit";
$result = mysql_query($query1);

// Initial page num setup
//if (!$page){$page = 1;}
$tab_name = 'violation-by-seller';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$additional_params = "website_id=" . $web_id;
?>

<h3 align="center"> Products Violated by <?php echo $str; ?> <h3> 

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
                        <select  id="choice" name="choice" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:2;" onchange="document.getElementById('displayValue').value = this.options[this.selectedIndex].text;
                document.getElementById('idValue').value = this.options[this.selectedIndex].value;">
                            <option value="xls" name="xls" selected="xls" >xls</option>
                            <option value="pdf" >PDF</option>
                        </select></div>

                    <div style="padding-right: 20px;padding-left:0px; ">

                        <?php echo "<a href=" . "export_vendor.php?website_id=" . $web_id . "  " . "class=myButton" . " >Export</a>";
                        ?>
                    </div>
                </td>
            </tr>
        </table>

        <div class="cleaner" style="padding-top: 15px; ">

        </div>
        <table align="center">
            <tr>
                <td>

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
                                if (!$result) {
                                    echo "error";
                                } else {
                                    while ($row = mysql_fetch_assoc($result)) {
                                        echo "<tr>";
                                        if ($row['violation_amount'] > 10) {

                                            echo "<td>" . $row['sku'] . "</td>" . "<td>" . "$" . $row['vendor_price'] . "</td>" . "<td>" . "$" . $row['map_price'] . "</td>" . "<td  id=" . 'vioR' . "  " . ">" . "$" . $row

                                            ['violation_amount'] . "</td>" . "<td >" . "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . " Product Link" . "</a></td>";
                                        } else if ($row['violation_amount'] >= 5 && $row['violation_amount'] < 10) {

                                            echo "<td>" . $row['sku'] . "</td>" . "<td>" . "$" . $row['vendor_price'] . "</td>" . "<td>" . "$" . $row['map_price'] . "</td>" . "<td id=" . 'vioO' . "  " . ">" . "$" . $row

                                            ['violation_amount'] . "</td>" . "<td>" . "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . " Product Link" . "</a></td>";
                                        } else if ($row['violation_amount'] < 5) {

                                            echo "<td>" . $row['sku'] . "</td>" . "<td>" . "$" . $row['vendor_price'] . "</td>" . "<td>" . "$" . $row['map_price'] . "</td>" . "<td id=" . 'vio' . "  " . ">" . "$" . $row

                                            ['violation_amount'] . "</td>" . "<td>" . "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . " Product Link" . "</a></td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                                ?>	

                            </tbody>
                        </table>


                        <div align="left"   style="display:block;">
                            <?php include ('page2.php'); ?>
                        </div>



            </tr>       
        </table>

        <div>

            <?php include_once 'charts/a4.php'; ?>

        </div>  