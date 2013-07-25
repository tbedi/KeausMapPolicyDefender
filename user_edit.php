<?php
include_once 'db_login.php';
$title = "Kraus Price Defender | user_edit.php";
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>User Edit</title>
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
    </head>
        <?php include_once 'template/head.phtml'; ?>
    <body id="home" >
<?php include_once 'template/header.phtml'; ?>  
   <div id="wrapper" align="center" >
        <div id="tabContainlogin" align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  
            <div id="tabscontent" align="center">
                 <div class="tabpage recent" id="tabpage_1">
                    <form id="test" action="update_user.php" method="POST"> 
                      <?php
                      $uid = $_GET['user_id'];
                      include "db.php";
                      $sql2 = "select * from admin_users where user_id = '$uid'";
                      $qry = mysql_query($sql2);
                      $row = mysql_fetch_array($qry);
                       ?>
<div align="center" style="font-size: 150%;">
    <table>
      <tbody id="data">
          <tr>
             <td colspan="2" align="center"></td>
          </tr>
          <tr>
             <td colspan="2" style="background-color:white;font-size:22px" align="center"><b>Edit User</b></td>
          </tr><tr>
           <td>&nbsp;</td>
           <td><input type="hidden" name="" value="" /></td></tr>
            <tr>
            <td style="background-color:white;font-size:12px">Username:</td>
            <td ><input type="text" name="username" Value="<?php echo $row['username']; ?>" style="padding:5px;"/></td>
            </tr>
            <tr></tr>
            <tr>
            <td style="background-color:white;font-size:12px">Email:</td>
            <td ><input type="text" name="email" value="<?php echo $row['email']; ?>" style="padding:5px;"/></td>
             </tr>
             <tr></tr>
             <tr>
              <td style="background-color:white;font-size:12px">Name:</td>
              <td ><input type="text" name="name" value="<?php echo $row['name']; ?>" style="padding:5px;"/></td>
              </tr>
             <tr></tr>
            <tr>
         <td style="background-color:white;font-size:12px">Role:</td>
            <td ><input type="text" name="role" value="<?php echo $row['role']; ?>" style="padding:5px;"/></td>
              </tr>
            <input type="hidden" name="user_id" value=" <?php echo $row['user_id']; ?> " style="padding:5px;"/>
           <tr><td>&nbsp;</td>
            <td><input type="hidden" name="" value="" /></td></tr>
            <tr>
             <td align="center">
              <div><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="butlog" title="Saving" rel="1" > submit</a><a  href="users.php" class="butlog" type="reset" >Cancel</a></div>
             </td>
          </tr>
        </tbody>
      </table>
    </div>
   </form>
 </div>
</div>

<div class="cleaner"></div>
</div> 
</div> 

<div id="templatemo_footer_wrapper">
   <div id="templatemo_footer">
        Copyright © Kraus USA 2013
   </div> 
</div> 
</body>
</html>

