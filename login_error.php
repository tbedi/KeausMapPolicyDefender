<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login Form</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>
<body>


<?php
include_once 'template/head.phtml';
include_once 'template/header.phtml';

if ( !isset($_POST['email'])) {
                echo " <div id=" . "log1" . " align=" . "center" . ">" . "<b>Wrong Password, please login</b>" . "</div>";
            }
         
?>
</body>