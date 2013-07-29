<?php
/*What is it????*/

$title = "Kraus Price Defender | update_settings.php";
include_once 'db_login.php';
include_once 'db_class.php';
include('db.php');
$db_resource = new DB ();
$em = $_SESSION['email'];
$fetch = "select username from admin_users where email='$em'";
$products = $db_resource->GetResultObj($fetch);
$us = $products[0]->username;
if (count($products) > 0) {
    $us = $products[0]->username;
}

if (isset($_REQUEST["Submit"])) {
    $email = $_SESSION['email'];
    $user = $_REQUEST['username'];
    if (isset($_REQUEST['cpassword']))
        $new_pass = md5($_REQUEST['cpassword']);
    if ($_SESSION['role'] == 'Admin') {
        if (isset($user) && trim($_REQUEST['cpassword']) === '' && trim($_REQUEST['newpassword']) === '') {
            $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");

// if successfully update data, displays message "Successful".
            if (!$sql) {
                ?>
                <script>
                    window.alert('Error!!!');
                    window.location.href = 'mysettings.php';</script>
                <?php

            } else {
                ?>
                <script>
                    window.alert('Username is successfully entered...');
                    window.location.href = 'mysettings.php';</script>
                <?php

            }
        } elseif (isset($user) && trim($_REQUEST['cpassword']) != '' && trim($_REQUEST['newpassword']) != '') {
            $sql = mysql_query("UPDATE admin_users SET username='$user', password='$new_pass' WHERE email='$email'");
            if (!$sql) {
                ?>
                <script>
                    window.alert('Error!!!');
                    window.location.href = 'mysettings.php';
                </script>
                <?php

            } else {
                ?>
                <script>
                    window.alert('Username and password successfully entered...');
                    window.location.href = 'mysettings.php';
                </script>
                <?php

            }
        }
    } elseif ($_SESSION['role'] == '') {
        $sql = mysql_query("UPDATE admin_users SET username='$user' WHERE email='$email'");
             if (!$sql) {
                ?>
                <script>
                    window.alert('Error!!!');
                    window.location.href = 'mysettings.php';
                </script>
                <?php

            } else {
                ?>
                <script>
                    window.alert('Username and password successfully entered...');
                    window.location.href = 'mysettings.php';
                </script>
                <?php

            }
    }
}
include_once 'template/mysettings1.phtml';
?>