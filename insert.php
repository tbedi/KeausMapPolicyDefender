<?php

include('db.php');
// Get values from form 
$uname = $_POST['username'];
$pass = $_POST['password'];
$em = $_POST['email'];
$nm = $_POST['name'];
$rl = $_POST['role'];
$_SESSION['b'] = '';
$encryppassword = md5($pass);
// Insert data into mysql 
$sql = "INSERT INTO admin_users(username, password, email, name, role)VALUES('$uname', '$encryppassword', '$em', '$nm', '$rl')";
$result = mysql_query($sql);

// if successfully insert data into database, displays message "Successful". 
if (!$result) {
    ?>
    <script>
        <?php
            $_SESSION['b'] = '0';
            ?>
        window.location.href = '/users.php';</script>
    <?php

} else {
    ?>
    <script>
         <?php
            $_SESSION['b'] = '1';
            ?>
        window.location.href = '/users.php';</script>
    <?php

}
?>