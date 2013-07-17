<?php
////pagination

include 'db.php';
$tableName = "website";
$targetpage = "websites.php";
$limit = 10;


$query = "SELECT COUNT(*) as num FROM $tableName";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;
if (isset($_GET['page'])){
$page = mysql_escape_string($_GET['page']);
$start = ($page - 1) * $limit;
}else{
$start = 0;
 $page = 1;
}


?>
    <html>

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <script type="text/javascript" src="js/search.js"></script> 
            <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  
            <link rel="stylesheet" type="text/css" href="css/paginator.css" />
            <link href="css/style.css" rel="stylesheet" type="text/css" />
        </head>
        <body><br>
            <table align="center" width="1000px" >
                <tr>
                    <td >



                        <input  class="recent_search" 	placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action'] ) == 'recent') echo $_GET['value']; ?>" id="textBoxSearch"   
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

                        <a href="javascript:void(0);" class="myButton"  onclick="recent_search();">Search</a>





                    </td>

                    <td>
            </table>

           
      
     
            
 <table class="GrayBlack" align="center">
               
                    <tr>

                        <th width="352" >WEBSITES</th>
                    </tr>
                
                <tbody id="data">
                    <?php
                    // Get page data
$query1 = "SELECT domain from $tableName LIMIT $start, $limit";
$result = mysql_query($query1);

// Initial page num setup
if ($page == 0){$page = 1;}
$prev = $page - 1;
$next = $page + 1;
$lastpage = ceil($total_pages/$limit);
$LastPagem1 = $lastpage - 1;


$paginate = '';
if($lastpage > 1)
{




$paginate .= "<div class='paginate'>";
// Previous
if ($page > 1){
$paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
}else{
$paginate.= "<span class='disabled'>previous</span>"; }
// Pages
if ($lastpage < 7 + ($stages * 2)) // Not enough pages to breaking it up
{
for ($counter = 1; $counter <= $lastpage; $counter++)
{
if ($counter == $page){
$paginate.= "<span class='current'>$counter</span>";
}else{
$paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
}
}
elseif($lastpage > 5 + ($stages * 2)) // Enough pages to hide a few?
{
// Beginning only hide later pages
if($page < 1 + ($stages * 2))
{
for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
{
if ($counter == $page){
$paginate.= "<span class='current'>$counter</span>";
}else{
$paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
}
$paginate.= "...";
$paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
$paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
}
// Middle hide some front and some back
elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
{
$paginate.= "<a href='$targetpage?page=1'>1</a>";
$paginate.= "<a href='$targetpage?page=2'>2</a>";
$paginate.= "...";
for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
{
if ($counter == $page){
$paginate.= "<span class='current'>$counter</span>";
}else{
$paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
}
$paginate.= "...";
$paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
$paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
}
// End only hide early pages
else
{
$paginate.= "<a href='$targetpage?page=1'>1</a>";
$paginate.= "<a href='$targetpage?page=2'>2</a>";
$paginate.= "...";
for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
{
if ($counter == $page){
$paginate.= "<span class='current'>$counter</span>";
}else{
$paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
}
}
}

// Next
if ($page < $counter - 1){
$paginate.= "<a href='$targetpage?page=$next'>next</a>";
}else{
$paginate.= "<span class='disabled'>next</span>";
}

$paginate.= "</div>";



echo $total_pages.' Results';
// pagination
echo $paginate;
          while ($row = mysql_fetch_array($result)) {
                ?>

                <tr>

                      <td ><?php echo $row['domain']; ?></td> 

                </tr>
<?php }}?>
            </tbody>
        </table>
                    
    </body>

</html>