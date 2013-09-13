<?php
include_once 'db_class.php'; //we included database class
$db_resource = new DB (); // we created database resourse object which contains methods and connection
session_start();
// Get values from form 
$_SESSION['userdata'] = '0';
$uname = $_POST['username'];
$pass = $_POST['newpassword'];
$em = $_POST['email'];
$nm = $_POST['name'];
$rl = $_POST['role'];
$_SESSION['b'] = '';
$encryppassword = md5($pass);

$sql1 = "SELECT * FROM admin_users where email like '$em'"; // Retrieve data from database 
$userdata = $db_resource->GetResultObj($sql1);

if(count($userdata) === 0)
{
// Insert data into mysql 
$sql = "INSERT INTO admin_users(username, password, email, name, role)VALUES('$uname', '$encryppassword', '$em', '$nm', '$rl')";
$db_resource->ExecSql($sql);

// if successfully insert data into database, displays message "Successful" else error 
if (!$sql) {
    $_SESSION['b'] = '0'; //session used on user.php page
    ?>
    <script>window.location.href = '/users.php?tab=create_user';</script>
    <?php

} else {$_SESSION['b'] = '1'; //session used on user.php page
    ?>
    <script>window.location.href = '/users.php';</script>
    <?php

}
}
elseif(count($userdata) != 0)
{
     $_SESSION['userdata'] = '1'; //session used on user.php page
     ?>
    <script>window.location.href = '/users.php';</script>
    <?php
}
?>