<?php

include('db.php');
session_start();
$_SESSION['role'] === 'Admin';

$id = $_POST['user_id'];
$un = $_POST['username'];
$em = $_POST['email'];
$nm = $_POST['name'];
$rl = $_POST['role'];

$result = mysql_query("UPDATE admin_users SET username='$un', email='$em', name='$nm', role='$rl' WHERE user_id='$id'") or die(mysql_error());
if (!$result) {
    ?>
    <script>
        window.alert('Error...')
        window.location.href = 'user_test.php';</script>
    <?php

} else {
    ?>
    <script>
        window.alert('User Updated Successfully...')
        window.location.href = 'users.php';</script>
    <?php

}
?>