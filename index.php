 <?php 
 
/*configuration*/
setlocale(LC_MONETARY, 'en_US');
include_once 'db.php';
include_once 'db_login.php';

/*Login check*/
if (!isset($_SESSION['username']))
	header('Location: login.php');

/*configuration*/
include_once 'db_class.php'; 
include_once 'pagination_class.php';

$db_resource = new DB ();
$title="Price Defender";
$pagination= new Pagination();

?>
 <?php include_once 'template/head.phtml'; ?>
    <body id="home">
       <?php include_once 'template/header.phtml'; ?>       
            <div id="wrapper" align="center" >
                <div id="tabContainer" class="main-content" align="center" >
                    <div id="tabs" align="center">
                        <ul>
                        	<li id="tabHeader_1" class="dashboard">Dashboard</li>
                            <li id="tabHeader_2" class="recent">Recent violations</li>
                            <li id="tabHeader_5" class="violations-history">Violation history</li>
                        </ul>
                    </div>
                    <div id="tabscontent" align="center">
                        <div class="tabpage dashboard" id="tabpage_1">
                            <?php include_once 'dashboard.php'; ?>
                        </div>
                        <div class="tabpage recent" id="tabpage_2">
                            <?php include_once 'recent.php'; ?>
                        </div>
<!--                        <div class="tabpage violation-by-product" id="tabpage_3">
                            <?php // include_once 'product.php'; ?>
                        </div>
                    	<div class="tabpage violation-by-seller" id="tabpage_4">
                            <?php // include_once 'vendor.php'; ?>
                        </div>-->
                        <div class="tabpage violations-history" id="tabpage_5">
                            <?php include_once 'history.php'; ?>
                        </div>    
                    </div>
                    <div class="cleaner"></div>
                </div> 
            </div> 
			<?php include_once 'template/footer.phtml'; ?> 
			<?php include_once 'template/js.phtml'; ?>        
    </body>
</html>
