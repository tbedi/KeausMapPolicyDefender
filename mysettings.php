<?php
include_once 'db_login.php';
include_once 'db_class.php'; //we included database class

$db_resource = new DB (); // we created database resourse object which contains methods and connection
$title = "Kraus Price Defender | User Settings";
?>
<?php include_once 'template/head.phtml'; ?>
<body id="my-settings" >
    <?php include_once 'template/header.phtml'; ?>  
    <div id="wrapper" align="center" >
        <div id="tabContainer" align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  
            <div id="tabscontent" align="center">
                <div class="tabpage recent" id="tabpage_1">
                    <?php include_once 'mysettings1.php'; ?>
                </div>
            </div> 
        </div>
        <div class="cleaner"></div>
    </div> 
    <div id="templatemo_footer_wrapper">
        <div id="templatemo_footer">
            Copyright © Kraus USA 2013
        </div> 
    </div> 

</body>