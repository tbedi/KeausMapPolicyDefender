<link href="css/TBLCSS.css" rel="stylesheet" type="text/css">
 
 <?php

//pagination
	include('connect.php');	

	$tableName="crawl_results";		
	$targetpage = "vendor1.php"; 	
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
	
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages['num'];
	$stages = 3;
	$page=1;

	if(isset($_GET['page'])){
		$page = mysql_escape_string($_GET['page']);
		$start = ($page - 1) * $limit; 
	}else{
		$start = 0;	
		}	
		?>
 
 
<h3 align="center"	>Seller Violations</h3>
<table align="center"   >
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
</tr>
<tr>
<td>
       
<table class="GrayBlack" align="center">
        	<tbody id="data">
 		<tr> 
 			 <td>
 		 <strong>Seller</strong>
 		  </td>	
   
 		 <td >
  		<strong>Violation Count</strong>
 		  </td>
          <td >
  		<strong>Max Violation</strong>
 		  </td>
           <td >
  		<strong>Min Violation</strong>
 		  </td>
		</tr>	
       


					
 <?php
include('db.php');

$query1="select website.name,
crawl_results.website_id,
max(crawl_results.violation_amount) as maxvio,
min(crawl_results.violation_amount) as minvio,
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
   $result=mysql_query($query1);
      
	  // Initial page num setup
	if (!$page){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;			
       
	  
        while($row = mysql_fetch_array($result)) 
       { 
       echo "<tr>";
       echo "<td>";
 
       echo "<a href="."?website_id=".$row['website_id']."&showclicked".">".$row['name']."</td>"."<td>".$row['wi_count']."</td>"."<td>".$row['maxvio']."</td>"."<td>".$row['minvio']."</td>"	."</tr>";
       echo "</td>";
       echo "</tr>";  
       } 
         echo "</table>";
       
    // mysql_close($con); 
 ?>	 
 <div  style="display:block;">
  <?php include_once ('page2.php');?>
</div>

</td>  
       
   
</tr>       
 </tbody></table> 
  <?php
if(isset($_GET['showclicked']))
{
	
	    include_once 'vviolation1.php';
}
 ?>