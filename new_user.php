<?php
	include_once 'db_login.php';
include_once 'db.php';
$title = "Kraus Price Defender | usernew.php";
$tableName = "admin_users";
$targetpage = "users.php";
$page_name="users.php"; 
@$limit=$_GET['limit']; // Read the limit value from query string. 
if(strlen($limit) > 0 and !is_numeric($limit)){
echo "Data Error";
exit;
}
// If there is a selection or value of limit then the list box should show that value , so we have to lock that options //
// Based on the value of limit we will assign selected value to the respective option//
switch($limit)
{
case 30:
$select30="selected";
$select15="";
$select20="";
break;

case 20:
$select20="selected";
$select15="";
$select30="";
break;

default:
$select15="selected";
$select20="";
$select30="";
break;
}
$eu = (0); 

if(!$limit){ // if limit value is not available then let us use a default value
$limit = 15;    // No of records to be shown per page by default.
}      


$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit; 

//	$limit = 15;  
     $where = "";
$column = $_SESSION['col'];
$desc = $_SESSION['dir'];
$dt = date('Y/m/d');

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
<table class="table1" align="center"  style="width: 50%;">
    <tr>
        <td width="10">
            <div class="divt1">

               <input  class="searchsize" placeholder="Search username ..." name="websearch" type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) && $_GET['action']=="search" && isset($_GET['tab']) && $_GET['tab'] == 'recent') echo $_GET['value']; ?>"  id="textBoxSearch"    /> 
</div>
            <div class="divt222211">
                <input  class="btn-search" type="submit" value="Search">
            </div>
             <div class="divt222211">
                 <button href="javascript:void(0);" class="btn-search"  onclick="show_all();" >Show all</button>
            </div>
        </td>
          </form>
<td width="10">
               <?php
            echo "<form method=get action=$page_name> 
                <div class="."results-per-page"." style="."float:right; >
                   <div class="."divt2222>
                <div style="."padding:5px; padding-top: 6px;> Show </div>
            </div> 
              <select name=limit class="."dropdown"." onchange="."form.submit()".">
               <option value=15 $select15>15 </option>
               <option value=20 $select20>20 </option>
               <option value=30 $select30>30 </option>
               </select>
               <div class="."divt2222>
                <div style="."padding:5px; padding-top: 6px;> Records</div>
            </div>
               <input type=hidden value=hidden class="."btn-search></div></form>"; 
               ?>
            </td>  
</tr>
</table>
<div class="cleaner1" ></div>
<table class="GrayBlack" align="center" style="width: 50%;">
    <tbody id="data">
        <tr>
            <td>Username<a href="/users.php?col=username&dir=<?php echo $desc; ?>"><?php
                    if ($desc === 'desc')
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
                    }
                    ?></a></td>
            <td>Email<a href="/users.php?col=username&dir=<?php echo $desc; ?>"><?php
                    if ($desc === 'desc')
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
                    }
                    ?></a></td>
            <td>Name<a href="/users.php?col=username&dir=<?php echo $desc; ?>"><?php
                    if ($desc === 'desc')
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
                    }
                    ?></a></td>
            <td>Role<a href="/users.php?col=username&dir=<?php echo $desc; ?>"><?php
                    if ($desc === 'desc')
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_desc_1.png" . " />";
                    elseif ($desc === 'asc') {
                        echo "<img  style=" . "float:right;" . " width=" . "22" . " src=" . "images/arrow_asc_1.png" . " />";
                    }
                    ?></a></td>
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
//        $dt = DateTime::createFromFormat('Y-m-d',$row['last_login'] );
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
// Get page data
	$query1 = "SELECT * FROM $tableName LIMIT $start, $limit";
	
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	
	$paginate = '';
	if($lastpage > 1)
	{	
	$result = mysql_query($query1);
 while ($row = mysql_fetch_array($result)) {
                     $dt = DateTime::createFromFormat('Y-m-d',$row['last_login'] );
                    ?>
            <tr>                                     
                <td><?php echo $row['username']; ?></td>
                <td ><?php echo $row['email']; ?></td>
                <td ><?php echo $row['name']; ?></td>
                <td ><?php echo $row['role']; ?></td>
                <td ><a href="user_edit.php?userid=<?php echo($row['user_id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a>
                    <a href="user_delete.php?userid=<?php echo($row['user_id']); ?>" title="Delete" onclick ="return confirm('Delete user?');" > <img src="images/icon_delete.png" /> </a> </td>
            </tr>
	
 <?php }
        }
          }
        ?>
</tbody>
</table>
<table  align="center" >
            <tr>
            <td>
             <?php
		$paginate .= "<div class='paginate' align='center' style="." padding-right:610px;".">";
		// Previous
		if ($page > 1){
			$paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
		}else{
			$paginate.= "<span class='disabled'>previous</span>";	}
			

		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
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
 // pagination
 echo $paginate;
?>
            </td>
            </tr>

</table>
   
	
    
