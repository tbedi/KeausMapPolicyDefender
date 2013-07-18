
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
//pagination starts here

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

if (isset($_GET['second_grid_page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-sellers') {
    $page = mysql_escape_string($_GET['second_grid_page']); //$page_param should have same value
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
$page_param = "second_grid_page"; //variable used for pagination
$additional_params = "&website_id=" . $web_id; //addtiion params to pagination url;
if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
    $additional_params.="&page=" . $_GET['page'];
}


if (isset($_GET['action']) && $_GET['action']) { // search 
		$additional_params.="&action=".$_GET['action']."&field=sku&value=".$_GET['value'];
	}
?>

<h3 align="center"> Products Violated by <?php echo $str; ?> <h3> 

        <table align="center"  width="1000px" >
            <tr>
                <td >

                    <div style="padding-right: 20px;padding-left:0px; float: left">
                        <input  class="seller-violation-search"	placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action']=="searchfirstv2" && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') echo $_GET['value']; ?>" 
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
                        <a href="javascript:void(0);" class="myButton"  onclick="seller_violation_search();">Search</a>
                    </div>   
                </td>
                <td> 
                        <div style="padding-right: 20px;padding-left:0px; float: left">
                Export To

                <select  id="exportv2" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:4;" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exporttov2();">Export</a>
            </div>
    </td>
</tr>


<script type="text/javascript">
               
               
                 function seller_violation_search() {
                                var field = "sku";
                                var value = $(".seller-violation-search").val();
                                var url_options= "<?php echo ( isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' && isset($_GET['sort']) ? "&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'] : "" );   ?>"
                                
                        		if (value.length) {                        			
                        			url_options+="&action=searchfirstv2&field=" + field + "&value=" + value;
                        		}
                            			
                                var search_link = "index.php?tab=violation-by-seller" + url_options;

                                window.open(search_link, "_self");
                                tableSearch.runSearch();

                            }
                            
               
               
               
                            
                            function exporttov2()
                            {
                                var mode = $("#exportv2").val();
                                var url_options= window.location.search.substring(1);
                                
                                if (url_options.length)
                                		url_options='?'+url_options;
                        		
                                if (mode)                                    
                                    open("export_vendor2_" + mode + ".php"+url_options);



                            }

    </script>
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