

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
                     width: 200px; "/> 
            
            <a href="javascript:void(0);" class="myButton"  onclick="tableSearch.runSearch();">Search</a>
                
        </td>
        <td> Export To
            <select  id="choice" name="choice" style=" widht:100px; height:25px; line-height:20px;margin:0;padding:2;" onchange="document.getElementById('displayValue').value = this.options[this.selectedIndex].text;
                    document.getElementById('idValue').value = this.options[this.selectedIndex].value;">
                <option value="xls" name="xls" selected="xls" >xls</option>
                <option value="pdf" >PDF</option>
                 </select>
               

           
            <a href="export_vendor1.php" id="1" class="myButton" >Export</a>

        </td>
    </tr>
</table>
<table align="center">
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

            