<?php
$sql = "select max(DATE_FORMAT(crawl.date_executed, '%d %b %Y')) as maxd
from crawl;";
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


$where = "";

if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
}


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
(select max(crawl.id) from crawl) " . $where . " 
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

/* sorting */

if (isset($_GET['tab']) && $_GET['tab'] == 'recent' && isset($_GET['sort'])) {
    $direction = $_GET['sort'];
    $order_field = $_GET['sort_column'];
} else {
    $direction = "desc";
    $order_field = "crawl_results.violation_amount";
}

$order_by = " order by " . $order_field . " " . $direction . " ";

/* sorting */
$query1 = "select catalog_product_flat_1.sku,
website.name as name, 
website.id as website_id,
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
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl) " . $where . " 
" . $order_by . " LIMIT $start, $limit";
$result = mysql_query($query1);

// Initial page num setup
//if (!$page){$page = 1;}
$tab_name = 'recent';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$page_param = "page"; //add it to each pagination
$additional_params = ""; //addtiion params to pagination url;
if (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
    $additional_params.="&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'];
}

//sort
if (isset($_GET['tab']) && $_GET['tab'] == 'recent' && isset($_GET['sort'])) {
    $additional_params.="&sort=" . $_GET['sort'] . "&sort_column=" . $_GET['sort_column'];
}
//sort
?>

<h3 align="center">Violations as of the day ( <?php echo $str; ?>)</h3>
<table align="center" width="1000px" >
    <tr>
        <td >
            <div style="padding-right: 20px;padding-left:0px; float: left">


                <input  class="recent_search search" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent') echo $_GET['value']; ?>"
                        id="textBoxSearch"  onkeyup="tableSearch.search(event);" 
                       /> </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <!-- <a href="javascript:void(0);" onclick="tableSearch.runSearch();" style="padding-top:0px;"> -->
                <a href="javascript:void(0);" class="myButton"  onclick="recent_search();">Search</a>

            </div> 



        </td>

        <td>
            <div style="padding-right: 20px;padding-left:0px; float: left">
                Export To

                <select  id="export" name="export_to" class="dropdown" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exporttor();">Export</a>
            </div>
        </td> 
    </tr>


    <script type="text/javascript">


                            function recent_search() {
                                var field = "sku";
                                var value = $(".recent_search").val();
                                var url_options = "<?php echo ( isset($_GET['tab']) && $_GET['tab'] == 'recent' && isset($_GET['sort']) ? "&sort=" . $_GET['sort'] . "&sort_column=" . $_GET['sort_column'] : "" ); ?>"

                                if (value.length) {
                                    url_options += "&action=search&field=" + field + "&value=" + value;
                                }

                                var search_link = "index.php?tab=recent" + url_options;

                                window.open(search_link, "_self");
                                tableSearch.runSearch();

                            }

                            function exporttor()
                            {
                                var mode = $("#export").val();
                                var url_options = window.location.search.substring(1);

                                if (url_options.length)
                                    url_options = '?' + url_options;

                                if (mode)
                                    open("export_recent_" + mode + ".php" + url_options);



                            }

    </script>

</table>

<div class="cleaner" style="padding-top: 15px; ">

</div>

<table>

    <tr>
        <td>
            <table class="GrayBlack" align="center">
                <tbody id="data">
                    <tr> 
                        <td>
                            SKU                                 
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction == "asc" ? "desc" : "asc") ?>&sort_column=sku&<?php echo $page_param ?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] : "" ); ?>" >
                                <img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field == "sku" ? $direction : "asc" ); ?>_1.png" />
                            </a>
                        </td>	

                        <td>
                            Seller
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction == "asc" ? "desc" : "asc") ?>&sort_column=wname&<?php echo $page_param ?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] : "" ); ?>" >
                                <img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field == "name" ? $direction : "asc" ); ?>_1.png" />
                            </a>
                        </td>
                        <td>
                            Seller price
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction == "asc" ? "desc" : "asc") ?>&sort_column=crawl_results.vendor_price&<?php echo $page_param ?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] : "" ); ?>" >
                                <img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field == "crawl_results.vendor_price" ? $direction : "asc" ); ?>_1.png" />
                            </a>
                        </td>	

                        <td>
                            MAP
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction == "asc" ? "desc" : "asc") ?>&sort_column=crawl_results.map_price&<?php echo $page_param ?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] : "" ); ?>" >
                                <img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field == "crawl_results.map_price" ? $direction : "asc" ); ?>_1.png" />
                            </a>
                        </td>
                        <td>
                            Violation amt
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction == "asc" ? "desc" : "asc") ?>&sort_column=crawl_results.violation_amount&<?php echo $page_param ?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'recent' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] : "" ); ?>" >
                                <img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field == "crawl_results.violation_amount" ? $direction : "asc" ); ?>_1.png" />
                            </a>
                        </td>
                        <td>
                            Screenshot
                        </td>
                    </tr>

                    <?php
                    if (mysql_num_rows($result) == 0) {
                        echo "<tr><td colspan='6'>No Records Found</td></tr>";
                    }
                    while ($row = mysql_fetch_assoc($result)) {
                        echo "<tr>";


                        if ($row['violation_amount'] > 10) {
                            ?>
                        <td ><a href="index.php?tab=violation-by-product&action=searchfirst&field=sku&value=<?php echo $row['sku']; ?>"><?php echo $row['sku']; ?></a></td>

                        <td ><a href="index.php?&tab=violation-by-seller&action=searchfirst1&field=website_id&value=<?php echo $row['website_id']; ?>&website_id=<?php echo $row['website_id']; ?>"><?php echo $row['name']; ?></td>

                        <td ><?php echo "$" . $row['vendor_price']; ?></td>

                        <td ><?php echo "$" . $row['map_price']; ?></td>

                        <td id="vioR"><?php echo "$" . $row['violation_amount']; ?></td>

                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ?></td>

                        <?php
                    } else if ($row['violation_amount'] >= 5 && $row['violation_amount'] < 10) {
                        ?>

                        <td ><a href="index.php?tab=violation-by-product&action=searchfirst&field=sku&value=<?php echo $row['sku']; ?>"><?php echo $row['sku']; ?></a></td>

        <!-- <td ><a href="index.php?tab=violation-by-seller&action=searchfirst1&field=website_id&value=<?php //echo $row['website_id'];   ?>"><?php //echo $row['name'];   ?></td>-->

                        <td ><a href="index.php?&tab=violation-by-seller&action=searchfirst1&field=website_id&value=<?php echo $row['website_id']; ?>&website_id=<?php echo $row['website_id']; ?>"><?php echo $row['name']; ?></td>                      

        <!-- <td ><?php //echo $row['wname'];   ?></td> -->

                        <td ><?php echo "$" . $row['vendor_price']; ?></td>

                        <td ><?php echo "$" . $row['map_price']; ?></td>

                        <td id="vioO"><?php echo "$" . $row['violation_amount']; ?></td>

                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ?></td>

                        <?php
                    } else if ($row['violation_amount'] < 5) {
                        ?>

                        <td ><a href="index.php?tab=violation-by-product&action=searchfirst&field=sku&value=<?php echo $row['sku']; ?>"><?php echo $row['sku']; ?></a></td>

                        <td ><a href="index.php?&tab=violation-by-seller&action=searchfirst1&field=website_id&value=<?php echo $row['website_id']; ?>&website_id=<?php echo $row['website_id']; ?>"><?php echo $row['name']; ?></td>

                        <td ><?php echo "$" . $row['vendor_price']; ?></td>

                        <td ><?php echo "$" . $row['map_price']; ?></td>

                        <td id="vio"><?php echo "$" . $row['violation_amount']; ?></td>

                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ?></td>
                        <?php
                    }



                    echo "</tr>";
                }

                echo "</table>";

//  mysql_close($con); 
                ?> 

                <div align="left" style="display:block;">
                    <?php include ('page2.php'); ?>
                </div>			


        </td>  


    </tr>       
</tbody></table> 

<script language="javascript" type="text/javascript">

    function popitup(url) {
        newwindow = window.open(url, 'name', 'height=200,width=150');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }


</script>

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
