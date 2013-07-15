

<?php
//pagination


$tableName = "crawl_results";
$targetpage = "index.php";
$limit = 10;
$query = "SELECT COUNT(crawl_results.website_id) as num FROM website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on
crawl_results.crawl_id = crawl.id

where crawl_results.violation_amount>0.05 
and
crawl.id =
(select max(crawl.id) from crawl)
group by website.name , crawl_results.website_id
order by count(crawl_results.website_id) desc
";


/*Pagination*/
		 $result = mysql_query($query);
	 $total_pages = mysql_num_rows($result);  

	 
	 $stages = 3;
	 $page=1;
	 
	 if(isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab']=='violation-by-seller' )
    {
	 	$page = mysql_escape_string($_GET['page']);
	 	$start = ($page - 1) * $limit;
	 }else{
	 	$start = 0;
	 	$page=1;
	 }	 
	 /*Pagination*/

?>


<h3 align="center"	>Seller Violations</h3>
<table align="center"   >
    <tr>
        <td >

            <input  type="text" size="30" width="300" hight="40" maxlength="1000" value="" id="textBoxSearch" onkeyup="tableSearch.search(event);"  style="background-image:url(images/sr.png) no-repeat 4px 4px;	
                    border:2px solid #456879;
                    border-radius:10px;float:left;
                    height: 22px;
                    width: 230px; "/> 
            <a href="" onclick="tableSearch.runSearch();" style="padding-top:0px;">
                <img src="images/sr.png" style="height:20px; width:20px; float:left; "></a>
            <a  style="float:left;padding-top:0px;"  href="export_recent.php"> <img src="images/dn.png" width="20" height="20" /> </a>

        </td>
    </tr>
    <tr>
        <td>

            <table class="GrayBlack" align="center">
                <tbody id="data">
                    <tr> 
                        <td>
                            Seller
                        </td>	

                        <td >
                            Violation count
                        </td>
                        <td >
                            Max violation
                        </td>
                        <td >
                            Min violation
                        </td>
                    </tr>	




                    <?php
                    $query1 = "select website.name,
crawl_results.website_id,
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
crawl.id = 
(select max(crawl.id) from crawl)
group by website.name , crawl_results.website_id
order by count(crawl_results.website_id) desc LIMIT $start, $limit";
                    $result = mysql_query($query1);

                    // Initial page num setup
                    //if (!$page){$page = 1;}
                    $tab_name = 'violation-by-seller';
                    $prev = $page - 1;
                    $next = $page + 1;
                    $lastpage = ceil($total_pages / $limit);
                    $LastPagem1 = $lastpage - 1;


                    while ($row = mysql_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>";

                        echo "<a href=" . "?tab=violation-by-seller&website_id=" . $row['website_id'] . "&showclicked" . ">" . $row['name'] . "</td>" . "<td>" . $row['wi_count'] . "</td>" . "<td>" . "$".$row['maxvio'] . "</td>" . "<td>" . "$".$row['minvio'] . "</td>" . "</tr>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";

                    // mysql_close($con); 
                    ?>	 

               	 
  <div align="right" style="display:block;">
                    <?php include ('page2.php'); ?>
                </div>
        </td>  


    </tr>       
</tbody></table> 
            
          
<?php
if(isset($_GET['website_id']) && isset($_GET['tab']) &&  $_GET['tab']=="violation-by-seller" )
{
    include_once 'vviolation.php';
}
?>

            