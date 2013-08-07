<?php
include_once 'db_login.php';
include_once 'db.php';
$title = "Kraus Price Defender | websites1.php";
////pagination
$tableName = "website";
$targetpage = "websites.php";
$limit = 10;

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
<form action="websites.php" method="POST">
    <table class="table1" >
        <tr>
            <td width="285" >
                <div class="divt1">

                    <input  class="recent_violation_search search" name="websearch" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'search') echo $_GET['value']; ?>"  id="textBoxSearch"    /> 
                </div>
                <div class="divt22 search-btn-container">
                    <input  class="btn-search" type="submit" value="Search">
                </div>
                <div class="divt22">
                  <button href="javascript:void(0);" class="btn-search"  onclick="show_all();" >Show all</button>
                </div>
            </td>
            <td width="20">
                <div class="results-per-page" style="float:left;padding-top:6px;" >
                    <select name="recent-limit" class="recent-res-per-page dropdown" onclick="" >
                        <option <?php echo ($limit == 10) ? "selected" : ""; ?> value="10" selected>10</option>
                        <option  <?php echo ($limit == 15) ? "selected" : ""; ?> value="25">25</option>
                        <option  <?php echo ($limit == 20) ? "selected" : ""; ?>  value="50">50</option>
                        <option  <?php echo ($limit == 15) ? "selected" : ""; ?> value="75">75</option>
                    </select>
                </div>
            </td>  
        </tr>
    </table>
</form>
<div class="cleaner1" ></div>
<table class="GrayBlack" align="center">
    <tbody id="data">
        <tr>
            <td>Website Name<a href="websites.php?col=name&dir=<?php echo $desc;?>"><?php if($desc==='desc')
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_desc_1.png"." />";
                    elseif($desc==='asc') {
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_asc_1.png"." />";} ?></a></td>
            <td>Website Link<a href="websites.php?col=domain&dir=<?php echo $desc;?>"><?php if($desc==='desc')
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_desc_1.png"." />";
                    elseif($desc==='asc') {
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_asc_1.png"." />";} ?></a></td>
            <td>Data Created<a href="websites.php?col=date_created&dir=<?php echo $desc;?>"><?php if($desc==='desc')
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_desc_1.png"." />";
                    elseif($desc==='asc') {
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_asc_1.png"." />";} ?></a></td>
            <td>Excluded<a href="websites.php?col=excluded&dir=<?php echo $desc;?>"><?php if($desc==='desc')
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_desc_1.png"." />";
                    elseif($desc==='asc') {
                    echo "<img  style="."float:right;"." width="."22"." src="."images/arrow_asc_1.png"." />";} ?></a></td>
            <td>Edit</td>
        </tr>
        <?php
        if (isset($_POST['websearch'])) {
            $var1 = $_POST['websearch'];
            $var2 = $_POST['recent-limit'];
            $sql = "SELECT id, name, domain, date_created, case excluded when 0 then 'No' when 1 then 'Yes' end as excluded from website where name like '%" . "$var1" . "%' LIMIT $start, $var2";

            $result = mysql_query($sql);
            if ($result && mysql_num_rows($result) <= 0) {
                ?><table class="GrayBlack" align="center">
                <tr align="center"><td width="500"> No Records Found  </td> </tr></table><?php } ?>
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
                    <td ><?php echo $row['name']; ?></td> 
                    <?php echo "<td>" . "<a href =" . "http://www." . $row['domain'] . " target=" . "_blank" . ">" . $row['domain'] . "</a></td>"; ?> 
                    <td ><?php echo $row['date_created']; ?></td>
                    <td ><?php echo $row['excluded']; ?></td>
                    <td ><a href="/website_edit.php?name=<?php echo($row['name']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a> </td>

                </tr>
                <?php
            }
        }
    } 
 elseif (!isset($_POST['websearch'])) 
 {
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
                    <?php echo "<td>" . "<a href =" . "http://www." . $row['domain'] . " target=" . "_blank" . ">" . $row['domain'] . "</a></td>"; ?> 
                    <td ><?php echo $row['date_created']; ?></td>
                    <td ><?php echo $row['excluded']; ?></td>
                    <td ><a href="/website_edit.php?name=<?php echo($row['name']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a> </td>

                </tr>
                <?php
            }
        }
    }
    ?>
</tbody>
</table>
<?php
$paginate .= "<div class='paginate' align='left' >";
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

//echo $total_pages . ' Results';
// pagination
echo $paginate;
?>

<div class="cleaner1" >

</div>