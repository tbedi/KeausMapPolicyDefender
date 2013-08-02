<?php
include('db.php');
include_once 'db_class.php'; //we included database class
$db_resource = new DB (); // we created database resourse object which contains methods and connection
session_start();
// Get values from form 
$uname = $_POST['username'];
$pass = $_POST['newpassword'];
$em = $_POST['email'];
$nm = $_POST['name'];
$rl = $_POST['role'];
$_SESSION['b'] = '';
$encryppassword = md5($pass);
// Insert data into mysql 
$sql = "INSERT INTO admin_users(username, password, email, name, role)VALUES('$uname', '$encryppassword', '$em', '$nm', '$rl')";
$db_resource->ExecSql($sql);
$result = mysql_query($sql);// if successfully insert data into database, displays message "Successful". 
if (!$result) {$_SESSION['b'] = '0';
    ?>
    <script>window.location.href = '/users.php';</script>
    <?php

} else {$_SESSION['b'] = '1';
    ?>
    <script>window.location.href = '/users.php';</script>
    <?php

}
?>