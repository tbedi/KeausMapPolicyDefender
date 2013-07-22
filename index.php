 <?php 
/*configuration*/
 
setlocale(LC_MONETARY, 'en_US');
include_once 'db.php';
include_once 'db_login.php';
/*configuration*/
include_once 'db_class.php'; 
$db_resource = new DB ();
/* Example of usage*/
/*
$sql="SELECT p.sku as sku FROM catalog_product_flat_1 p LIMIT 2";
$products=$db_resource->GetResultObj($sql);
foreach ($products as $product) {
	echo $product->sku;
}
*/
/* Example of usage*/
?>
 <?php include_once 'template/head.phtml'; ?>
    <body id="home" >
       <?php include_once 'template/header.phtml'; ?>       
            <div id="wrapper" align="center" >
                <div id="tabContainer" align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  
                    <div id="tabs" align="center">
                        <ul>
                            <li id="tabHeader_1" class="recent">Recent violations</li>
                            <li id="tabHeader_2" class="violation-by-product" >Violation by product</li>
                            <li id="tabHeader_3" class="violation-by-seller" >Violation by seller</li>
                            <li id="tabHeader_4" class="violations-history">Violation history</li>
                        </ul>
                    </div>
                    <div id="tabscontent" align="center">
                        <div class="tabpage recent" id="tabpage_1">
                            <?php include_once 'recent.php'; ?>
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
			<?php include_once 'template/footer.phtml'; ?>        
    </body>
</html>
