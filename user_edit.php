<?php include_once 'db_login.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Price Defender </title

        ><!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/highcharts.js"></script>

        <script src="js/exporting.js"></script>  
        <!-- hightcharts libraries -->
        <!--script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> -->

<!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
<!--<script type="text/javascript" src="js/showhide.js"></script> -->
        <script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 

<!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->    
<!--<script type="text/javascript" src="js/ddsmoothmenu.js"></script>-->
        <script type="text/javascript" src="js/search.js"></script> 

        <!--<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" /> -->
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
                    <div  class="menu-item second" style="float:left; padding-top:3px;" >  
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
                                    <tr>
                                        <td colspan="2" align="center"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="background-color:white;font-size:22px" align="center"><b>Edit User</b></td>
                                    </tr><tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:18px">Username:</td>
                                        <td ><input type="text" name="username" Value="<?php echo $row['username']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:18px">Email:</td>
                                        <td ><input type="text" name="email" value="<?php echo $row['email']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:18px">Name:</td>
                                        <td ><input type="text" name="name" value="<?php echo $row['name']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:18px">Role:</td>
                                        <td ><input type="text" name="role" value="<?php echo $row['role']; ?>" /></td>
                                    </tr>
                                    <input type="hidden" name="user_id" value=" <?php echo $row['user_id']; ?> " />
                                    <tr></tr>
                                    <tr>
                                        <td align="center">
                                          <div><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="button" title="Saving" rel="1" > submit</a><a  href="users.php" class="button" type="reset" >Cancel</a></div>
                                     </td>
                                    </tr>

                                </table>
                            </div>
                        </form>
                            </div>

                                <div class="tabpage violation-by-product" id="tabpage_2">
<?php include_once 'product.php'; ?>

                                </div>

                                <div class="tabpage violation-by-seller" id="tabpage_3">
<?php include_once 'vendor.php'; ?>
                                </div>
                                <div class="tabpage violations-history" id="tabpage_4">
<?php include_once 'history.php'; ?>

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

