<?php
include_once 'db_login.php';
include_once 'db_class.php'; //we included database class

$db_resource = new DB (); // we created database resourse object which contains methods and connection
$title = "Kraus Price Defender | User Settings";
if(!isset($_SESSION['username'])){
    header('Location: login.php');
}
else {
?>
<?php include_once 'template/head.phtml'; ?>
<body id="my-settings" >
    
    <?php include_once 'template/header.phtml'; ?>  
    <div id="wrapper" align="center" >
        <div  class="main-content" align="center" >
        <div class="top-panel">
            <span>User Settings</span>
        </div>
        <div  align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  
                <?php include_once 'update_settings.php';} ?>
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