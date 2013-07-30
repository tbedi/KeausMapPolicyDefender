<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript"> 
      $(document).ready( function() {
        $('#logs').delay(2000).fadeOut();
      });
    </script>
<form action="mysettings.php" method="post" name="frm" id="frm">
    <div align="center" style="font-size: 150%;">
        <table>
            <tr>
                <td colspan="2" align="center"></td>
            </tr>
            <tr><td>&nbsp;</td>
                <td><input type="hidden" name="" value="" /></td></tr>
            <tr> 
                        <td align="left">
                            Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>	
                        <td>
                            <input type="text" class="input" name="username" id="username"  size="40" value="<?php echo $us; ?>" autocomplete="off" style="padding:5px;"/>&nbsp; <label id="username_label" ></label>
                        </td>
                    </tr>
            <?php
            if ($_SESSION['role'] === 'Admin') {
                echo "<tr>
                    <td>New Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td ><input type=" . "password" . " name=" . "newpassword" . " class=" . "input" . " id=" . "newpassword" . " size=" . "40" . " autocomplete=" . "off" . " style=" . "padding:5px;" . " />&nbsp; <label id=" . "newpassword_label" . " ></label></td>
                          </tr>";

                echo "<tr>
                    <td>Confirm Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <td ><input type=" . "password" . " name=" . "cpassword" . " class=" . "input" . " id=" . "cpassword" . " size=" . "40" . " autocomplete=" . "off" . " style=" . "padding:5px;" . "/>&nbsp; <label id=" . "cpassword_label" . " ></label></td>
                         </tr>";
            }
            ?>
            <tr><td>&nbsp;</td>
                <td><input type="hidden" name="" value="" /></td></tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" class="btn-login"  name="Submit" value="Update" /></td>

            </tr>

        </table>

    </div>
</form>


<?php
$title = "Kraus Price Defender | mysettings1.php";
include_once 'db_class.php';
include('db.php');
$db_resource = new DB ();
$em = $_SESSION['email'];
$fetch = "select username from admin_users where email='$em'";
$products = $db_resource->GetResultObj($fetch);
$us = $products[0]->username;
if (count($products) > 0) {
    $us = $products[0]->username;
}

if (isset($_REQUEST["Submit"])) {
    $email = $_SESSION['email'];
    $user = $_REQUEST['username'];
    if (isset($_REQUEST['cpassword']))
        $new_pass = md5($_REQUEST['cpassword']);
    if ($_SESSION['role'] == 'Admin') {
        if (isset($user) && trim($_REQUEST['cpassword']) === '' && trim($_REQUEST['newpassword']) === '') {
            $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");

            if (!$sql) {
                echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">error</p>" . "</div>";
            } else {
                echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">username is added successfully</p>" . "</div>";
            }
        } elseif (isset($user) && trim($_REQUEST['cpassword']) != '' && trim($_REQUEST['newpassword']) != '') {
            $sql = mysql_query("UPDATE admin_users SET username='$user', password='$new_pass' WHERE email='$email'");
            if (!$sql) {
                echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">error</p>" . "</div>";
            } else {
                echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">username and password successfully entered..</p>" . "</div>";
            }
        }
    } elseif ($_SESSION['role'] == '') {
        $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
        if (!$sql) {
            echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">error</p>" . "</div>";
        } else {
            echo " <div id=" . "logs" . " style=" . "color:#9AC847" . " align=" . "center" . ">" . "<p font color=" . "red" . ">user is added successfully</p>" . "</div>";
        }
    }
}
?>

