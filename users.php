<?php include_once 'db.php'; ?>
<?php
include_once 'db_login.php';
$title = "Kraus Price Defender | users.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
} else {
    if(isset($_REQUEST['col']) & isset($_REQUEST['dir']))
    {
    $_SESSION['col'] = $_REQUEST['col'];
    $_SESSION['dir'] = $_REQUEST['dir'] === 'asc' ? 'desc' : 'asc';
    }
    elseif(!isset($_REQUEST['col']) && !isset($_REQUEST['dir']))
    {
        $_SESSION['col'] = 'username';
     $_SESSION['dir'] = 'asc';
    }
    ?>
        <?php include_once 'template/head.phtml'; ?>
    <body id="login-page" >
    <?php include_once 'template/header.phtml'; ?> 

        <div id="wrapper" align="center" >
            <?php
// print_r($_SESSION);
            if (isset($_SESSION['a']) && $_SESSION['a'] === '1') {
                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User is updated successfully</div>";
            } elseif (isset($_SESSION['a']) && $_SESSION['a'] === '0') {
                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User failed</div>";
            }
            unset($_SESSION['a']);
            if (isset($_SESSION['b']) && $_SESSION['b'] === '1') {
                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">New user is Created</div>";
            } elseif (isset($_SESSION['b']) && $_SESSION['b'] === '0') {
                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User failed</div>";
            }
            unset($_SESSION['b']);
            if (isset($_SESSION['userdata']) && $_SESSION['userdata'] === '1') {
                echo " <div class=" . "log" . "  align=" . "left" . ">Email is already registered..</div>";
            }
            unset($_SESSION['userdata']);
            ?>
            <div id="tabContainer" class="main-content" align="center" >
                <div id="tabs" align="center">
                    <ul>
                        <li id="tabHeader_1" class="dashboard">Edit/Delete user</li>
                        <li id="tabHeader_2" class="recent" >Create New User</li>
                    </ul>
                </div>
                <div id="tabscontent" align="center">

                    <div class="tabpage dashboard" id="tabpage_1">
                        <?php include_once 'new_user.php'; ?>
                    </div>
<!--                    <div id="tabscontent1" align="center">-->
                        <div class="tabpage recent" id="tabpage_2">
                            <?php include_once 'create_user.php';
                        } ?>
                    </div></div>
            </div>
            <div class="cleaner"></div>
        </div>
    </div>

<?php include_once 'template/footer.phtml'; ?>
</body>







