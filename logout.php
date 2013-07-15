<?php
session_start();

//destroy session
session_destroy();

//unset cookies
setcookie("email", "", time()-7200);

header("Location: login.php");
exit();


?>

