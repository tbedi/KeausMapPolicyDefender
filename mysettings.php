<?php
include 'db.php';
include_once 'db_login.php';

//print_r($_SESSION);
//print_r($_POST);
if (isset($_REQUEST["Submit"])) {
    $email = $_SESSION['email'];
    $user = $_REQUEST['username'];
    if (isset($_REQUEST['cpassword']))
        $new_pass = md5($_REQUEST['cpassword']);
    if ($_SESSION['role'] == 'Admin') {
        if(isset($user) && !isset($_REQUEST['cpassword']) && !isset($_REQUEST['newpassword'])){
            $Sqll = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
        }
        elseif (isset($user) && isset($_REQUEST['cpassword']) && isset($_REQUEST['newpassword'])){
         $sql = mysql_query("UPDATE admin_users SET username='$user', password='$new_pass' WHERE email='$email'");   
        }
    } elseif ($_SESSION['role'] == '') {
        $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Settings</title><!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/highcharts.js"></script>
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
        <script src="js/exporting.js"></script>
        <script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 
        <script type="text/javascript" src="js/search.js"></script> 
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>
<?php include_once 'template/head.phtml'; ?>
    <body id="home" >
<?php include_once 'template/header.phtml'; ?>  

        <div id="wrapper" align="center" >

            <div id="tabContainer" align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  


                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">

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
                                    <td  style=" . "background-color:white;font-size:18px" . ">New Password:</td>
                                       
                                    <td ><input type=" . "password" . " name=" . "newpassword" . " id=" . "newpassword" . " size=" . "25" . " autocomplete=" . "off" . " />&nbsp; <label id=" . "newpassword_label" . " ></label></td>
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

                    </div>



                </div>    


            </div>


            <div class="cleaner"></div>

        </div> 

        <!-- </div> -->

        <div id="templatemo_footer_wrapper">
            <div id="templatemo_footer">
                Copyright © Kraus USA 2013
            </div> 
        </div> 

    </body>
</html>
