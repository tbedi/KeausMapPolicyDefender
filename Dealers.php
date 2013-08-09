<?php include_once 'db.php'; ?>
<?php include_once 'db_login.php';
$title = "Kraus Price Defender | Dealers.php";
if(!isset($_SESSION['username'])){
    header('Location: login.php');
}
else {
    if(isset($_REQUEST['col']) & isset($_REQUEST['dir']))
    {
    $_SESSION['col'] = $_REQUEST['col'];
    $_SESSION['dir'] = $_REQUEST['dir'] === 'asc' ? 'desc' : 'asc';
    }
    elseif(!isset($_REQUEST['col']) && !isset($_REQUEST['dir']))
    {
        $_SESSION['col'] = 'name';
     $_SESSION['dir'] = 'asc';
    }
     ?>
    <?php include_once 'template/head.phtml'; ?>
<body id="Dealers-page" >
<?php include_once 'template/header.phtml'; ?>    
    <div id="wrapper" align="center" >
        <?php
        if(isset($_SESSION['a']) && $_SESSION['a'] === '1'){
        echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">Dealer is updated successfully</div>";}
                 elseif(isset($_SESSION['a']) && $_SESSION['a'] === '0') {
                 echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">Dealer failed</div>";
                 }
                 unset ($_SESSION['a']);
                 ?>
        <div  class="main-content" align="center" >
            <div class="top-panel">Dealers</div>
        <div style="margin:0px;   padding:0px" align="center">
             <div id="tabscontent" align="center">  
             <?php include_once 'Dealers1.php'; } ?>
            </div>
            <div class="cleaner"></div>
        </div>
        </div>
    </div>
    <?php include_once 'template/footer.phtml'; ?> 
</body>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $('.logs').delay(2000).fadeOut();
        });
</script>

