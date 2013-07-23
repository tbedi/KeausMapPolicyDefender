<?php
////print_r($_REQUEST);
//print_r($_POST);
if (isset($_REQUEST["Submit"])) {
    $email = $_SESSION['email'];
    $user = $_REQUEST['username'];
    if (isset($_REQUEST['cpassword']))
        $new_pass = md5($_REQUEST['cpassword']);
    if ($_SESSION['role'] == 'Admin') {
        if(isset($user) && trim($_REQUEST['cpassword'])==='' && trim($_REQUEST['newpassword'])===''){
            $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
        }
        elseif (isset($user) && trim($_REQUEST['cpassword'])!='' && trim($_REQUEST['newpassword'])!=''){
         $sql = mysql_query("UPDATE admin_users SET username='$user', password='$new_pass' WHERE email='$email'");   
        }
    } elseif ($_SESSION['role'] == '') {
        $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
    }
}
?>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Settings</title>
        <script language="javascript" type="text/javascript">
            function validate()
            {

                var formName = document.frm;

                if (formName.username.value == "")
                {
                    document.getElementById("username_label").innerHTML = 'Please Enter username';
                    formName.username.focus();
                    return false;
                }
                else
                {
                    document.getElementById("username_label").innerHTML = '';
                }

//                if (formName.newpassword.value == "")
//                {
//                    document.getElementById("newpassword_label").innerHTML = 'Please Enter New Password';
//                    formName.newpassword.focus();
//                    return false;
//                }
//                else
//                {
//                    document.getElementById("newpassword_label").innerHTML = '';
//                }
//
//
//                if (formName.cpassword.value == "")
//                {
//                    document.getElementById("cpassword_label").innerHTML = 'Enter ConfirmPassword';
//                    formName.cpassword.focus();
//                    return false;
//                }
//                else
//                {
//                    document.getElementById("cpassword_label").innerHTML = '';
//                }
//
//
//                if (formName.newpassword.value != formName.cpassword.value)
//                {
//                    document.getElementById("cpassword_label").innerHTML = 'Passwords Missmatch';
//                    formName.cpassword.focus()
//                    return false;
//                }
//                else
//                {
//                    document.getElementById("cpassword_label").innerHTML = '';
//                }
            }
        </script>
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>

                        <form action="mysettings.php" method="post" name="frm" id="frm" onSubmit="return validate();">
                            <div align="center" style="font-size: 150%;">
                                <table>
                                    <tr>
                                        <td colspan="2" align="center"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="background-color:white;font-size:22px" align="center"><b>USER SETTINGS</b></td>
                                    </tr><tr></tr><tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:18px">Username:</td>
                                        <td ><input type="username" name="username" id="username" size="25" autocomplete="off"/>&nbsp; <label id="username_label" ></label></td>
                                    </tr>
                                    <?php
                                    if ($_SESSION['role'] === 'Admin') {
                                        echo "<tr>
                                    <td  style=" . "background-color:white;font-size:18px" . ">New Password:</td><td ><input type=" . "password" . " name=" . "newpassword" . " id=" . "newpassword" . " size=" . "25" . " autocomplete=" . "off" . " />&nbsp; <label id=" . "newpassword_label" . " ></label></td>
                                    </tr>";
                                    echo "<tr>
                                    <td style=" . "background-color:white;font-size:18px" . ">Confirm Password:</td>
                                    <td ><input type=" . "password" . " name=" . "cpassword" . " id=" . "cpassword" . " size=" . "25" . " autocomplete=" . "off" . " />&nbsp; <label id=" . "cpassword_label" . " ></label></td>
                                    </tr>";
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="2" align="center"><input type="submit" name="Submit" value="Update" onSubmit="return validate();"/></td>
                                    </tr>

                                </table>
                            </div>
                        </form>

            