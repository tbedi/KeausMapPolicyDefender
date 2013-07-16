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


$where="";

if (isset($_GET['action']) && $_GET['action']=='search'  && isset($_GET['tab']) && $_GET['tab'] == 'recent' ) {
	$field=strtolower($_GET['field']);
	$value=strtolower($_GET['value']);
	$where="  AND  catalog_product_flat_1.".$field."  LIKE '%".$value."%'";
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
crawl.id = 
(select max(crawl.id) from crawl) ".$where." 
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
(select max(crawl.id) from crawl) ".$where." 
order by violation_amount desc LIMIT $start, $limit";
$result = mysql_query($query1);

// Initial page num setup
//if (!$page){$page = 1;}
$tab_name = 'recent';
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages / $limit);
$LastPagem1 = $lastpage - 1;
$page_param="page"; //add it to each pagination
$additional_params = ""; //addtiion params to pagination url;
if (isset($_GET['action']) && $_GET['action']   && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
	$additional_params.="&action=".$_GET['action']."&field=".$_GET['field']."&value=".$_GET['value'];
}
?>

<h3 align="center">Recent Violations( <?php echo $str; ?>)</h3>
<table align="center"  >
    <tr>
        <td >


            <input  class="recent_search" 	placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if   (isset($_GET['action']) && $_GET['action']   && isset($_GET['tab']) && $_GET['tab'] == 'recent') echo  $_GET['value']; ?>" id="textBoxSearch" onkeyup="recent_search();"  
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
            <!-- <a href="javascript:void(0);" onclick="tableSearch.runSearch();" style="padding-top:0px;"> -->
            <a href="javascript:void(0);" class="myButton"  onclick="recent_search();">Search</a>
                      




        </td>
        <td> Export To

            <select  id="export" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:2;" >
                <option value="csv" name="csv" selected  >Excel</option>
                <option value="pdf" >PDF</option>

                 </select>
               

           
            <a href="" id="1" class="myButton" onclick="exportto();">Export</a>
    </td> 
    </tr>

   <?php 
   // $("select").each(function() {
  // alert(this.options[this.selectedIndex].value);
    //}); 
?>
<!-- <script type="text/javascript">

 <script type="text/javascript">
 	function recent_search() {
 	 	var field="sku";
 	 	var value=$(".recent_search").val();
 	 	var search_url_additional_params="<?php if ( isset($_GET['page']) && $_GET['page']) echo '&page='.$_GET['page'];  if ( isset($_GET['tab']) && $_GET['tab']) echo '&tab='.$_GET['tab']; ?>";
 	 	 
 	 	var search_link="/index.php?action=search&field="+field+"&value="+value+search_url_additional_params;
 	 
 	 		window.open(search_link,"_self");
 	 
 	}
 	
    function exportto()
                {
					var mode=$("#export").val();
					if (mode)
						open ("export_recent_"+mode+".php");
           
                    //document.write("xls export");
                   //  open('export_recent.php');
                  /*  var i = document.selected_tab.getElementById(choice).value;
                    if(i==="xls")
                        {
                           open('export_recent.php');
                           document.write("xls export");
                        }
                    else if(i==="csv")
                        {

                            document.write("pdf export");
                        }*/

                }


             



                </script>-->



    
</table>

<table>

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
                    if (mysql_num_rows($result)==0) {
						echo "<tr><td colspan='6'>No Records Found.</td></tr>";
					}
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
