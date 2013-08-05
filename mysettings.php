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

        <?php include_once 'template/header.phtml';
        ?>

        <div id="wrapper" align="center" >
            <?php
            if (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '1' && (isset($_SESSION['a'])) && $_SESSION['a'] === '1') {

                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User is updated successfully</div>";
            } elseif (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '2' && isset($_SESSION['a']) && $_SESSION['a'] === '1') {

                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User and password is updated successfully</div>";
            }elseif (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '3' && isset($_SESSION['a']) && $_SESSION['a'] === '1') {

            echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">password updated</div>";}
            elseif (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '1' && (isset($_SESSION['a'])) && $_SESSION['a'] === '0') {

                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User failed</div>";
            } elseif (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '2' && isset($_SESSION['a']) && $_SESSION['a'] === '0') {

                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User failed</div>";
            }
            elseif (isset($_SESSION['adminupd']) && $_SESSION['adminupd'] === '3' && isset($_SESSION['a']) && $_SESSION['a'] === '0') {

                echo " <div class=" . "logs" . " style=" . "color:#9AC847" . " align=" . "left" . ">User failed</div>";
            }
            
            ?>
            <div  class="main-content" align="center" >
                <div class="top-panel">User Settings</div>
                <div  align="center" >
                    <div id="tabscontent" align="center">  

                        <?php
                        include_once 'mysettings1.php';
                    }
                    ?>
                </div>
            </div>
            <div class="cleaner"></div>
        </div>
    </div> 
    <?php include_once 'template/footer.phtml'; ?> 

</body>