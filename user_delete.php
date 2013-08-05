<?php
$title = "Kraus Price Defender | user_delete.php";
include 'db.php';
include_once 'db_login.php';
$uid = $_GET['userid'];
// delete the entry
$result = mysql_query("DELETE  FROM admin_users WHERE user_id = '$uid'") or die(mysql_error());

if (!$result) {
    ?>
    <script>
        window.alert('Error...')
        window.location.href = '/users.php';</script>
    <?php

} else {
    ?>
    <script>
        window.location.href = '/users.php';</script>
    <?php

}
?>