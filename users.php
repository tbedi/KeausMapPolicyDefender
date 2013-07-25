<?php include_once 'db.php'; ?>
<?php
include_once 'db_login.php';
$title = "Kraus Price Defender | users.php";
?>
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

    </head>
        <?php include_once 'template/head.phtml'; ?>
    <body id="users-page" >
<?php include_once 'template/header.phtml'; ?> 

        <div id="wrapper" align="center" >
            <div  class="main-content" align="center" >

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
                                        <td ><a href="user_edit.php?user_id=<?php echo($row['user_id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a>
                                            <a href="user_delete.php?user_id=<?php echo($row['user_id']) ?>" title="Delete" onclick ="return confirm('are you sure you want to delete');" > <img src="images/icon_delete.png" /> </a> </td>
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
        </div> 
        <div id="templatemo_footer_wrapper">
            <div id="templatemo_footer">
                Copyright © Kraus USA 2013
            </div> 
        </div> 

    </body>
</html>
