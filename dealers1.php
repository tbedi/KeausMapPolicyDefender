 <?php
include_once 'db_login.php';
include_once 'db.php';
$title = "Kraus Price Defender | dealers1.php";
////pagination
$tableName = "website";
$targetpage = "dealers.php";
$limit = 10;
$page_name="dealers.php"; 
////// starting of drop down to select number of records per page /////

@$limit=$_GET['limit']; // Read the limit value from query string. 
if(strlen($limit) > 0 and !is_numeric($limit)){
echo "Data Error";
exit;
}
// If there is a selection or value of limit then the list box should show that value , so we have to lock that options //
// Based on the value of limit we will assign selected value to the respective option//
switch($limit)
{
case 40:
$select40="selected";
$select10="";
$select25="";
break;

case 25:
$select25="selected";
$select10="";
$select40="";
break;

default:
$select10="selected";
$select25="";
$select40="";
break;
}
$eu = (0); 

if(!$limit > 0 ){ // if limit value is not available then let us use a default value
$limit = 10;    // No of records to be shown per page by default.
}                             
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit; 

$where = "";
$column = $_SESSION['col'];
$desc = $_SESSION['dir'];
$query = "SELECT COUNT(*) as num FROM $tableName";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;
if (isset($_GET['page'])) {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
?>

<form action="dealers.php" method="POST">
    <table class="table1" align="center" style="width: 50%;">
        <tr>
      <td width="10">
                <div class="divt1">
                    <input  class="searchsize" name="websearch" placeholder="Search name..." type="text" size="40"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'search') echo $_GET['value']; ?>"  id="textBoxSearch"    /> 
                </div>
                <div class="divt222211">
                    <input  class="btn-search" name="submit" type="submit" value="Search">
                </div>
                <div class="divt222211">
                    <button href="javascript:void(0);" class="btn-search"  onclick="show_all();" >Show all</button>
                </div>
            </td>
            
            <td width="10">
               <?php
                echo "<form method=get action=$page_name> 
                <div class="."results-per-page"." style="."float:right;padding-top:10px;"." >
                   <div class="."divt2222".">
                <div style="."padding:5px; padding-top: 6px;"."> Show </div>
            </div> 
              <select name=limit class="."dropdown"." onchange="."form.submit()".">
               <option value=10 $select10>10 </option>
               <option value=25 $select25>25 </option>
               <option value=40 $select40>40 </option>
               </select>
               <div class="."divt2222>
                <div style="."padding:5px; padding-top: 6px;> Records</div>
            </div>
               <input type=hidden value=hidden class="."btn-search"."></div></form>"; 
               ?>
            </td>  
        </tr>
    </table>
<div class="cleaner1" ></div>
<table class="GrayBlack" align="center" style="width: 50%;">
    <tbody id="data">
        <tr>
<!--            sorting coloumn code used in dealer.php-->
            <td><a href="/dealers.php?col=name&dir=<?php echo $desc; ?>">Dealers Name<?php
            if ($desc === 'desc' && $_SESSION['col'] == 'name')
                         echo "<img  style=" . "float:right;" . " width=" . "22" . " visibility:hidden src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc' && $_SESSION['col'] == 'name') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "../images/arrow_asc_1.png" . " />";
                    }
                       elseif($_SESSION['col'] !== 'name'){
                       echo "<img  style=visibility:hidden;" . " />";
                       }
                    ?></a></td> 
            <td><a href="/dealers.php?col=excluded&dir=<?php echo $desc; ?>">Excluded<?php
        if ($desc === 'desc' && $_SESSION['col'] == 'excluded')
         echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
        elseif ($desc === 'asc' && $_SESSION['col'] == 'excluded') {
            echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
        }
        elseif($_SESSION['col'] !== 'excluded'){
            echo "<img  style=visibility:hidden;" . " />";
                       }
        ?></a></td>
            <td><a href="/dealers.php?col=date_created&dir=<?php echo $desc; ?>">Data Created<?php
          
                    if ($desc === 'desc' && $_SESSION['col'] == 'date_created')
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc' && $_SESSION['col'] == 'date_created') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
                    }
                    elseif($_SESSION['col'] !== 'date_created'){
                       echo "<img  style=visibility:hidden;" . " />";
                       }
                    ?></a></td>
<!--            sorting coloumn code used in dealer.php-->
            <td>Edit</td>
        </tr>

    <?php
    if (isset($_POST['websearch'])) {
        
        $var1 = $_POST['websearch'];
        $sql = "SELECT id, name, domain, date_created, case excluded when 0 then 'No' when 1 then 'Yes' end as excluded from website where name like '%" . "$var1" . "%' LIMIT $start, $limit";

        $result = mysql_query($sql);
        if ($result && mysql_num_rows($result) <= 0) {
            ?><table class="GrayBlack" align="center">
                <tr align="center"><td width="750"> No Records Found  </td> </tr></table><?php } ?>
        <?php
        if ($page == 0) {
            $page = 1;
        }
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages / $limit);
        $LastPagem1 = $lastpage - 1;


        $paginate = '';

        if ($lastpage > 1) {
            while ($row = mysql_fetch_array($result)) { 
                ?>
   
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['excluded']; ?></td>
                    <td><?php echo date('m-d-yy', strtotime($row['date_created'])); ?></td>
                    <td><a href="dealer_edit.php?name=<?php echo($row['name']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a> </td>

                </tr>
                <?php
            }
        }
    } elseif (!isset($_POST['websearch'])) {
        $query1 = "SELECT
website.name,
website.domain,
website.date_created,
case excluded when 0 then 'No' when 1 then 'Yes' end as excluded
from
website
order by $column $desc
LIMIT $start, $limit";
        $result = mysql_query($query1);

        // Initial page num setup
        if ($page == 0) {
            $page = 1;
        }
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages / $limit);
        $LastPagem1 = $lastpage - 1;


        $paginate = '';

        if ($lastpage > 1) {
            while ($row = mysql_fetch_array($result)) {
                ?>

                <tr>
                    <td ><?php echo $row['name']; ?></td> 
                    <td ><?php echo $row['excluded']; ?></td>
                    <td ><?php echo date('m-d-Y', strtotime($row['date_created'])); ?></td>
                    <td ><a href="dealer_edit.php?name=<?php echo($row['name']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a> </td>
                </tr>
            <?php
        }
    }
}
?>
</tbody>
</table>

<?php
$paginate .= "<div class='paginate' align='center' style="." padding-right:465px;".">";
// Previous
if ($page > 1) {
    $paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
} else {
    $paginate.= "<span class='disabled'>previous</span>";
}
// Pages
if ($lastpage < 7 + ($stages * 2)) { // Not enough pages to breaking it up
    for ($counter = 1; $counter <= $lastpage; $counter++) {
        if ($counter == $page) {
            $paginate.= "<span class='current'>$counter</span>";
        } else {
            $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
        }
    }
} elseif ($lastpage > 5 + ($stages * 2)) { // Enough pages to hide a few?
    // Beginning only hide later pages
    if ($page < 1 + ($stages * 2)) {
        for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
            if ($counter == $page) {
                $paginate.= "<span class='current'>$counter</span>";
            } else {
                $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
            }
        }
        $paginate.= "...";
        $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
        $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
    }
    // Middle hide some front and some back
    elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
        $paginate.= "<a href='$targetpage?page=1'>1</a>";
        $paginate.= "<a href='$targetpage?page=2'>2</a>";
        $paginate.= "...";
        for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
            if ($counter == $page) {
                $paginate.= "<span class='current'>$counter</span>";
            } else {
                $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
            }
        }
        $paginate.= "...";
        $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
        $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
    }
    // End only hide early pages
    else {
        $paginate.= "<a href='$targetpage?page=1'>1</a>";
        $paginate.= "<a href='$targetpage?page=2'>2</a>";
        $paginate.= "...";
        for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
            if ($counter == $page) {
                $paginate.= "<span class='current'>$counter</span>";
            } else {
                $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
            }
        }
    }
}
// Next
if ($page < $counter - 1) {
    $paginate.= "<a href='$targetpage?page=$next'>next</a>";
} else {
    $paginate.= "<span class='disabled'>next</span>";
}
$paginate.= "</div>";
?>

<div style="margin-left:58px; margin-right:90px; padding:6px; background-color:#FFF; border:#999 1pxsolid;"><?php echo $paginate; ?></div>

<div class="cleaner1" ></div>

