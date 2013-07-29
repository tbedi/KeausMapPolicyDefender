<?php include_once 'db.php'; ?>
<?php include_once 'db_login.php';
$title = "Kraus Price Defender | users.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
} else {
    ?>
    <?php include_once 'template/head.phtml'; ?>
    <body id="users-page" >
        <?php include_once 'template/header.phtml'; ?> 

        <div id="wrapper" align="center" >
                <div id="tabContainer" class="main-content" align="center" >

                    <div id="tabs" align="center">
                        <ul>
                            <li id="tabHeader_1" class="recent">Edit/Delete user</li>
                            <li id="tabHeader_2" class="violation-by-product" >Create New User</li>
                        </ul>
                    </div>

                    <div id="tabscontent" align="center">

                        <div class="tabpage recent" id="tabpage_1">
                            <?php include_once 'new_user.php'; ?>
                         </div>
                        <div class="tabpage violation-by-product" id="tabpage_2">
                            <?php include_once 'create_user.php';}?>
                    </div>
                </div>

                <div class="cleaner"></div>
            </div>
        </div>
        
<?php include_once 'template/footer.phtml'; ?>
</body>
</html>
