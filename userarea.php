<?php
include_once 'db.php';
if(!loggedin())
{
    header("Location: index1.php");
    exit();
}

?>

you are logged in!<p/>
<a href="logout.php">log out</a>
        
