<h3 align="center"	>Violations History</h3>

<?php
//pagination
$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 10;

$where="";
if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['option']) && $_GET['option'] == 'show_dates') {
	 
	$to = $_POST["to"];
	$from = $_POST["from"];
	$where.=" and crawl.date_executed between '". $from."' and '".$to."' ";

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
where crawl_results.violation_amount>0.05   ".$where."
and
website.excluded=0
 
-- and crawl.id =  (select max(crawl.id) from crawl)
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

/* sorting */

if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['sort'])) {
    $direction = $_GET['sort'];
    $order_field = $_GET['sort_column'];
    
} else {
    $direction = "desc";
   $order_field="crawl_results.violation_amount";
}

$order_by = "order by " . $order_field . " " . $direction . " ";

/* sorting */










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
where  
crawl_results.violation_amount>0.05 ".$where."
and
website.excluded=0
-- and crawl.id =  (select max(crawl.id) from crawl)
".$order_by." LIMIT $start, $limit";

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
    
    $additional_params = ""; //addtiion params to pagination url;
if (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') {
    $additional_params.="&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'];
}

if (isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['sort']) ) {
	$additional_params.="&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'];
}

    
    
    
    
}

?>


<table align="center" width="1000px;"  >
    <tr>
        <td >
            <div style="padding-right: 0px;padding-left:0px; float: left">
                <input class="history_search search" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history') echo $_GET['value']; ?>" 
                        id="textBoxSearch" onkeyup="tableSearch.search(event);"  
                         /> </div>
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

                <select  id="exporth" name="export_to" class="dropdown" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
        </td>
<td>
    <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exporttoh();">Export</a>
            </div>
    </td>
</tr>


<script type="text/javascript">
                                                                       
                                function history_search() {
                                var field = "sku";
                                var value = $(".history_search").val();
                                var url_options= "<?php echo ( isset($_GET['tab']) && $_GET['tab'] == 'violations-history' && isset($_GET['sort']) ? "&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'] : "" );   ?>"
                                
                        		if (value.length) {                        			
                        			url_options+="&action=search&field=" + field + "&value=" + value;
                        		}
                            			
                                var search_link = "index.php?tab=violations-history" + url_options;

                                window.open(search_link, "_self");
                                tableSearch.runSearch();

                            }
                            
                            
                            function exporttoh()
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
 
if (isset($to) && isset($from)) {     
    echo "<br/> Violations from " . $from . " to " . $to;
}  


    ?>
     
    
                <table class="GrayBlack" align="center">
                    <tbody id="data">
                        <tr> 
                            <td>
                                Product
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=pname&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="pname" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>	

                            <td>
                                SKU
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=sku&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="sku" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>
                            <td>
                                Seller
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=wname&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="wname" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>	

                            <td>
                                Vendor price
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=vendor_price&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="vendor_price" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>
                            <td>
                                MAP price
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=map_price&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="map_price" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>
                            <td>
                                Violation amt
                                
                               <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=crawl_results.violation_amount&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="crawl_results.violation_amount" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                            </td>
                            <td>Date
                                 <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=date_executed&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violations-history' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="date_executed" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                                
                            </td>
                            <td>
                                Screenshot
                            </td>
                        </tr>

    <?php
    
     if (mysql_num_fields($result) == 0) {
                        echo "<tr><td colspan='6'>No Records Found</td></tr>";
                    }
                    
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
                           <td><?php echo  $row['date_executed']; ?></td>
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
                        <td><?php echo  $row['date_executed']; ?></td>
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
                         <td><?php echo  $row['date_executed']; ?></td>
                        <td ><?php echo "<a target=" . '_blank' . " href =" . $row['website_product_url'] . ">" . "Link" . "</a>" ;?></td>
                        
                         <?php
                    }
                        
                        
                        
                        
  
                    }

                    ?>
                        
 
     

    </tr>       
</tbody></table> 

                <div align="left" style="display:block;">
<?php include ('page2.php'); ?>
                </div>		

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