<?php include_once 'db.php'; ?>
<?php include_once 'db_login.php';?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>users </title><!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <script type="text/javascript">
            <?php echo (isset($_GET['tab']) ? "var selected_tab='" . $_GET['tab'] . "'; " : "var selected_tab='recent'; " ); ?></script>
        <script src="js/tabs_old.js"></script>
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

<div id="tabs" align="center">
                        <ul>
                            <li id="tabHeader_1" class="recent">Edit/Delete user</li>
                            <li id="tabHeader_2" class="violation-by-product" >Create New User</li>
                        </ul>
                    </div>

                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        <table class="GrayBlack" align="center">
                            <tbody id="data">
                                <tr>
                                    <td>S.no</td>
                                    <td>USER NAME</td>
                                    <td>EMAIL</td>
                                    <td>NAME</td>
                                    <td>ROLE</td>
                                    <td>Edit</td>
                                </tr>


                                <?php
                                $query1 = "SELECT * from admin_users";
                                $result = mysql_query($query1);
                                while ($row = mysql_fetch_array($result)) {
                                    ?>

                                    <tr>
                                        <td ><?php echo $row['user_id']; ?></td>
                                        <td ><?php echo $row['username']; ?></td>
                                        <td ><?php echo $row['email']; ?></td>
                                        <td ><?php echo $row['name']; ?></td>
                                        <td ><?php echo $row['role']; ?></td>
                                        <td ><a href="user_edit.php?user_id=<?php echo($row['user_id']); ?> " title="Edit" > <img src="images/icon_edit.png" /> </a>
                                            <a href="user_delete.php?user_id=<?php echo($row['user_id']) ?>" title="Delete"  > <img src="images/icon_delete.png" /> </a> </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>


                    </div>
                    <div class="tabpage violation-by-product" id="tabpage_2">
                            <?php include_once 'create_user.php'; ?>

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
