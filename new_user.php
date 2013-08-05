<?php
include_once 'db_login.php';
include_once 'db.php';
$title = "Kraus Price Defender | usernew.php";
            ////pagination
$tableName = "admin_users";
$targetpage = "users.php";
$limit = 5;

$where = "";


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
<form action="users.php" method="POST">
<table class="table1" >
    <tr>
        <td >
            <div class="divt1">

               <input  class="recent_violation_search search" placeholder="Enter name ..." name="websearch" type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action']=="search" && isset($_GET['tab']) && $_GET['tab'] == 'recent') echo $_GET['value']; ?>"  id="textBoxSearch"    /> 
</div>
            <div class="divt22 search-btn-container">
                <input  class="btn-search" type="submit" value="Search">
            </div>
             <div class="divt22">
                 <button href="javascript:void(0);" class="btn-search"  onclick="show_all();" >Show all</button>
            </div>
        </td>
</tr>
</table>
    </form>
<div class="cleaner1" ></div>
<table class="GrayBlack" align="center">
    <tbody id="data">
        <tr>
            <td>Username</td>
            <td>Email</td>
            <td>Name</td>
            <td>Role</td>
            <td>Edit</td>
        </tr>
        <?php
           
 if(isset($_POST['websearch'])){
     $var1 = $_POST['websearch'];
     $sql = "SELECT * from admin_users where name like '%"."$var1"."%' LIMIT $start, $limit" ;
     $result = mysql_query($sql);
     if ($result && mysql_num_rows($result) <= 0){
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
                <td ><?php echo $row['username']; ?></td>
                <td ><?php echo $row['email']; ?></td>
                <td ><?php echo $row['name']; ?></td>
                <td ><?php echo $row['role']; ?></td>
                <td ><a href="user_edit.php?userid=<?php echo($row['user_id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a>
                    <a href="user_delete.php?userid=<?php echo($row['user_id']); ?>" title="Delete" onclick ="return confirm('Delete user?');" > <img src="images/icon_delete.png" /> </a> </td>
            </tr>
             <?php     

     
 }}
 
 }

                 elseif(!isset($_POST['websearch']))
                  
                        {
        $query1 = "SELECT * from admin_users LIMIT $start, $limit";
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
                <td ><?php echo $row['username']; ?></td>
                <td ><?php echo $row['email']; ?></td>
                <td ><?php echo $row['name']; ?></td>
                <td ><?php echo $row['role']; ?></td>
                <td ><a href="user_edit.php?userid=<?php echo($row['user_id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a>
                    <a href="user_delete.php?userid=<?php echo($row['user_id']); ?>" title="Delete" onclick ="return confirm('Delete user?');" > <img src="images/icon_delete.png" /> </a> </td>
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


    
