<?php
$title = "Kraus Price Defender | update_user.php";
include('db.php');
session_start();
$_SESSION['role'] === 'Admin';

$id = $_POST['user_id'];
$un = $_POST['username'];
$pas = $_POST['password'];
$em = $_POST['email'];
$nm = $_POST['name'];
$rl = $_POST['role'];
$_SESSION['a'] = '';
$encryppassword = md5($pas);
 if (trim($pas) === '') {
 $result = mysql_query("UPDATE admin_users SET username='$un', email='$em', name='$nm', role='$rl' WHERE user_id='$id'");
 }
 elseif (trim($pas) != '') {
 $result = mysql_query("UPDATE admin_users SET username='$un',password='$encryppassword', email='$em', name='$nm', role='$rl' WHERE user_id='$id'") or die(mysql_error());
 }

if (!$result) {
    ?>
    <script>
        <?php
            $_SESSION['a'] = '0';
            ?>
        window.location.href = '/user_test.php';</script>
    <?php

} else {
    ?>
    <script>
        <?php
            $_SESSION['a'] = '1';
            ?>
        window.location.href = '/users.php';</script>
    <?php

}
?>