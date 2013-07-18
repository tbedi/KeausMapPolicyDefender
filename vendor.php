

<?php
//pagination


$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 10;

$where = "";

if (isset($_GET['action']) && $_GET['action'] == 'searchfirst1' && isset($_GET['value']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $field = strtolower($_GET['field']);
    $value = strtolower($_GET['value']);
    $where = "  AND  website." . $field . "  LIKE '%" . $value . "%'";
}



$query = "SELECT COUNT(crawl_results.website_id) as num FROM website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on
crawl_results.crawl_id = crawl.id
where crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id =
(select max(crawl.id) from crawl) " . $where . "
group by website.name , crawl_results.website_id
order by count(crawl_results.website_id) desc
";



/* Pagination */
$result = mysql_query($query);
$total_pages = mysql_num_rows($result);


$stages = 3;
$page = 1;

if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller') {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}



/* sorting */

if (isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' && isset($_GET['sort'])) {
    $direction = $_GET['sort'];
    $order_field = $_GET['sort_column'];
    
} else {
    $direction = "desc";
    $order_field = "count(crawl_results.website_id)";
}

$order_by = "order by " . $order_field . " " . $direction . " ";

/* sorting */


                    $query1 = "select website.name as name,
crawl_results.website_id as website_id,
format(max(crawl_results.violation_amount),2) as maxvio,
format(min(crawl_results.violation_amount),2) as minvio,
count(crawl_results.website_id) as wi_count
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on
crawl_results.crawl_id = crawl.id

where crawl_results.violation_amount>0.05 
and
website.excluded=0
and
crawl.id = 
(select max(crawl.id) from crawl) " . $where . "
group by website.name , crawl_results.website_id
".$order_by." LIMIT $start, $limit";
                
                    $result = mysql_query($query1);

// Initial page num setup
//if (!$page){$page = 1;}
                    $tab_name = 'violation-by-seller';
                    $prev = $page - 1;
                    $next = $page + 1;
                   $lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;	
	$page_param="page";//variable used for pagination
	$additional_params=""; //addtiion params to pagination url;
	if (isset($_GET['second_grid_page']) && $_GET['second_grid_page']) { //adding pagination for second grid/table
		$additional_params.="&second_grid_page=".$_GET['second_grid_page'];
	}
	if (isset($_GET['website_id']) && $_GET['website_id']) { //adding support for product
		$additional_params.="&website_id=".$_GET['website_id'];
	}

        //sort
if (isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' && isset($_GET['sort']) ) {
	$additional_params.="&sort=".$_GET['sort']."&sort_column=".$_GET['sort_column'];
}

//sort









/* Pagination */
?>


<h3 align="center"	>Seller Violations</h3>
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

                <select  id="exportv" name="export_to" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:4;" >
                    <option value="csv" name="csv" selected  >Excel csv</option>
                    <option value="xls" >Excel xls</option>
                    <option value="pdf" >PDF</option>

                </select>
            </div>
            <div style="padding-right: 20px;padding-left:0px; ">
                <a href="" id="1" class="myButton" onclick="exporttov();">Export</a>
            </div>
    </td>
</tr>


<script type="text/javascript">
               
                            
                            function exporttov()
                            {
                                document.write("Hello");
                                var mode = $("#exportv").val();
                                var url_options= window.location.search.substring(1);
                                
                                if (url_options.length)
                                		url_options='?'+url_options;
                        		
                                if (mode)                                    
                                    open("export_vendor_" + mode + ".php"+url_options);

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
                            Seller
                            
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=name&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="name" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>	

                        <td >
                            Violation count
                              <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=wicount&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="wicount" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                            
                        </td>
                        <td >
                            Max violation
                            
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=maxvio&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="maxvio" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                        <td >
                            Min violation
                            
                            <a href="index.php?tab=<?php echo $tab_name; ?>&sort=<?php echo ($direction=="asc"? "desc" : "asc")?>&sort_column=minvio&<?php echo  $page_param?>=<?php echo $page ?><?php echo (isset($_GET['action']) && $_GET['action'] && isset($_GET['tab']) && $_GET['tab'] == 'violation-by-seller' ? "&action=" . $_GET['action'] . "&field=" . $_GET['field'] . "&value=" . $_GET['value'] :"" ); ?>" >
                           		<img  style="float:right;" width="22" src="img/arrow_<?php echo ( $order_field=="minvio" ? $direction : "asc" ); ?>_1.png" />
                           </a>
                        </td>
                    </tr>	




                    <?php

                    
                    
                    
                    while ($row = mysql_fetch_assoc($result)) {
                        
                              echo "<tr>";
                        echo "<td>";
                         $website_link="?tab=violation-by-seller&website_id=".$row['website_id'];            
	   		
                         if (isset($_GET['page']) && $_GET['page']) { //adding pagination for first grid/table
				$product_link.="&page=".$_GET['page'];
			}
                        
                         if (isset($_GET['action']) && $_GET['action'] == "searchfirst1") { //added that search  was included
        $product_link.="&action=" . $_GET['action'] . "&field=id&value=" . $_GET['value'];
    }
                        
                  
                        echo "<a href='".$website_link."'>" . $row['name'] . "</td>" . "<td>" . $row['wi_count'] . "</td>" . "<td>" . "$" . $row['maxvio'] . "</td>" . "<td>" . "$" . $row['minvio'] . "</td>" . "</tr>";
                        echo "</td>";
                        echo "</tr>";
                        
                         $vid = $row['website_id'];
                    }
                    echo "</table>";

// mysql_close($con); 
                    ?>	 


                <div align="left" style="display:block;">
                    <?php include ('page2.php'); ?>
                </div>
        </td>  


    </tr>       
</tbody></table> 


<?php




if (!isset($_REQUEST['website_id'])) {
    $website_id = 0;
}
if (isset($_REQUEST['website_id'])) {
    $website_id = $_REQUEST['website_id'];
} elseif (isset($vid) && $total_pages == 1) {
    $website_id = $vid;
}

if ($website_id && isset($_GET['tab']) && $_GET['tab'] == "violation-by-seller") {
    include_once 'vviolation.php';
}
?>

