<?php
include('db.php');
// Get values from form 
$uname =$_POST['username'];
$pass =$_POST['password'];
$em =$_POST['email'];
$nm =$_POST['name'];
$rl =$_POST['role'];
$encryppassword=md5($pass);
// Insert data into mysql 
$sql="INSERT INTO admin_users(username, password, email, name, role)VALUES('$uname', '$encryppassword', '$em', '$nm', '$rl')";
$result=mysql_query($sql);

// if successfully insert data into database, displays message "Successful". 
if (!$result) {
?>
        <script>
            window.alert('Error...')
            window.location.href = 'users.php';</script>
    <?php

} else {
    ?>
        <script>
            window.alert('User Created...')
            window.location.href = 'users.php';</script>
    <?php

}
    ?>