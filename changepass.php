<?php
include_once 'db.php';
session_start();
$email = $_POST['email'];
$user = $_SESSION['email'];

if($user)
{
    echo "
        <form action='changepass.php' method='post' >
        old password: <input type='text' name='oldpassword'><p>
        New password: <input type='password' name='password'><p>
        repeat password: <input type='text' name='repeatpassword'><p>
        <input type ='submit' name='submit' valure=submit>
        ";
        
}
?>