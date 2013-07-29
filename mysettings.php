<?php
include_once 'db_login.php';
include_once 'db_class.php'; //we included database class

$db_resource = new DB (); // we created database resourse object which contains methods and connection
$title = "Kraus Price Defender | User Settings";
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
} else {
    ?>
    <?php include_once 'template/head.phtml'; ?>
    <body id="login-page" >

        <?php include_once 'template/header.phtml'; ?>  
        <div id="wrapper" align="center" >
            <div  class="main-content" align="center" >
                <div class="top-panel">
                    <span style="font-size: 1.4em;">User Settings</span>
                </div>
                <div  align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  --> 
                    <div id="tabscontent" align="center">  
                    <?php include_once 'mysettings1.php';
                }
                ?>
                    </div>
            </div>
            <div class="cleaner"></div>
        </div>
    </div> 
    <?php include_once 'template/footer.phtml'; ?> 

</body>