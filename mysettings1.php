<?php $title = "Kraus Price Defender | mysettings1.php"; ?>
 
<h3 align="center">USER SETTINGS</h3>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Settings</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <table align="center"  width="1000px" >
        <tr>
            <td>
        </tr>
    </table>
 
    <form action="update_settings.php" method="post" name="frm" id="frm" >
        <table align="center">
            <tbody>
                <tr> 
                    <td>
                        Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>	
                    <td>
                        <input type="text" name="username"  size="40" id="username" value="<?php echo $us; ?>" autocomplete="off" style="padding:5px;"/>&nbsp; <label id="username_label" ></label>
                    </td>
                </tr>
                <tr> 
                    <td>&nbsp;</td>	
                    <td><input type="hidden" value="hidden"/>
                    </td>
                </tr>
                <?php
                if ($_SESSION['role'] === 'Admin') {
                    echo "<tr>
                    <td>New Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td ><input type=" . "password" . " name=" . "newpassword" . " id=" . "newpassword" . " size=" . "40" . " autocomplete=" . "off" . " style=" . "padding:5px;" . " />&nbsp; <label id=" . "newpassword_label" . " ></label></td>
                          </tr>";
                    echo "<tr><td>&nbsp;</td>
                    <td><input type=" . "hidden" . " name=" . " value=" . " /></td></tr>";
                    echo "<tr>
                    <td>Confirm Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <td ><input type=" . "password" . " name=" . "cpassword" . " id=" . "cpassword" . " size=" . "40" . " autocomplete=" . "off" . " style=" . "padding:5px;" . "/>&nbsp; <label id=" . "cpassword_label" . " ></label></td>
                         </tr>";
                }
                ?>
                <tr> 
                    <td>&nbsp;</td>	
                    <td>
                        <input type="hidden" value="hidden"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="submit"  name="Submit" value="Update" /></td>
                </tr>

            </tbody>
        </table>
      </form>
</html>
