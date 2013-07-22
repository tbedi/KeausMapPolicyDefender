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

        $sql = mysql_query("UPDATE admin_users SET username='$user', password='$new_pass' WHERE email='$email'");
    } elseif ($_SESSION['role'] == '') {
        $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
    }
}
?>
<!--//echo mysql_query($sql);
//header("Location:mysettings.php?username=updated");
}

//print_r($_REQUEST);
?> -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Price Defender </title

        ><!-- hightcharts libraries -->
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

                if (formName.newpassword.value == "")
                {
                    document.getElementById("newpassword_label").innerHTML = 'Please Enter New Password';
                    formName.newpassword.focus();
                    return false;
                }
                else
                {
                    document.getElementById("newpassword_label").innerHTML = '';
                }


                if (formName.cpassword.value == "")
                {
                    document.getElementById("cpassword_label").innerHTML = 'Enter ConfirmPassword';
                    formName.cpassword.focus();
                    return false;
                }
                else
                {
                    document.getElementById("cpassword_label").innerHTML = '';
                }


                if (formName.newpassword.value != formName.cpassword.value)
                {
                    document.getElementById("cpassword_label").innerHTML = 'Passwords Missmatch';
                    formName.cpassword.focus()
                    return false;
                }
                else
                {
                    document.getElementById("cpassword_label").innerHTML = '';
                }
            }
        </script>
        <script src="js/exporting.js"></script>
        <script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 
        <script type="text/javascript" src="js/search.js"></script> 
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <script type="text/javascript">
            <?php echo (isset($_GET['tab']) ? "var selected_tab='" . $_GET['tab'] . "'; " : "var selected_tab='recent'; " ); ?></script>
        <script src="js/tabs_old.js"></script>


        <link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />

        <script type="text/javascript" src="calender/jsDatePick.min.1.3.js"></script>
        <script type="text/javascript">
            window.onpageshow = function() {
                new JsDatePick({
                    useMode: 2,
                    target: "inputFieldto",
                    dateFormat: "%Y-%m-%d"
                });

                new JsDatePick({
                    useMode: 2,
                    target: "inputFieldfrom",
                    dateFormat: "%Y-%m-%d"
                });
            };


        </script>




        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-1332079-8']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

            //highcharts colors
            $(function() {
                // Radialize the colors
                Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
                    return {
                        radialGradient: {cx: 0.2, cy: 0.5, r: 0.4},
                        stops: [
                            [0, color],
                            [1, Highcharts.Color(color).brighten(-0.1).get('rgb')] // darken
                        ]
                    };
                });
            });
            //highcharts colors

        </script>



    </head>

    <body id="home" >

        <div id="templatemo_header_wrapper" >


            <div class="container"  style="margin:auto; width:980px;height:80px;  min-height:2px;overflow:auto;">

                <div style="float:left; padding-right: 30px"> <a  href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
                </div>
                   <!-- <img align="top" src="images/head4.PNG" /> -->
                <div class="left-part" style="float:left;width:200px; ">
                    <h2>Price Defender</h2>
                </div>           

            </div>
        </div>


        <div id="templatemo_footer_wrapper1">
            <div id="templatemo_footer">
                <div align="center" style="min-height:5px;overflow:auto;">
                    <div  class="menu-item first" style="float:left; padding-top:3px;" > 

                        <a href="mysettings.php" class="top-menu-item-3" > <strong>SETTINGS</strong> </a>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div  class="menu-item first" style="float:left; padding-top:3px;" >  
                        <?php
                        if ($_SESSION['role'] === 'Admin') {
                            echo "<a href=" . "websites.php" . " class=" . "top-menu-item-3" . " > <strong>WEBSITES</strong> </a>";
                        }
                        ?>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div  class="menu-item third" style="float:left; padding-top:3px;" >  
                        <?php
                        if ($_SESSION['role'] === 'Admin') {
                            echo "<a href=" . "users.php" . " class=" . "top-menu-item-3" . " > <strong>USERS</strong> </a>";
                        }
                        ?>
                    </div>

                    <div style="float:right; padding-top:3px;width:176px;" >  
                        <img src="images/agent.png" width="28" height="24" style="padding-left:  10px; float:right;"/>
                        <a href="" target="_blank" class="top-menu-item-4" >  
<?php
if (isset($_SESSION['username'])) {
    echo "" . $_SESSION['username'] . ", <br><small><a href=\"login.php\">logout</a></small>";
} else {
    echo "Welcome Guest! <small><a href=\"login.php\">Login</a></small>";
}
?> 
                        </a>
                    </div>                        

                </div>
            </div> 
        </div> 

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
