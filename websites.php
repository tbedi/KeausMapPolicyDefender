<?php include_once 'db.php'; ?>
<?php include_once 'db_login.php';
$title = "Kraus Price Defender | websites.php";
if(!isset($_SESSION['username'])){
    header('Location: login.php');
}
else {
?>
    <?php include_once 'template/head.phtml'; ?>
<body id="websites-page" >
<?php include_once 'template/header.phtml'; ?>    
    <div id="wrapper" align="center" >
        <div  class="main-content" align="center" >
            <div class="top-panel">
            <span style="font-size: 1.3em";>Websites</span>
        </div>
        <div style="margin:0px;   padding:0px" align="center">
        <div id="tabContainer" align="center" > 

<!--            <div id="tabscontent" align="center">  -->
             <?php include_once 'websites1.php';} ?>
<!--            </div>-->


            <div class="cleaner"></div>

        </div> 
        </div>
        </div>
    </div>
        
    <?php include_once 'template/footer.phtml'; ?> 

</body>
</html>
