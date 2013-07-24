<?php
$title="Kraus Price Defender | user_delete.php";
include('db.php');
$uid = $_GET['user_id'];
// delete the entry
$result = mysql_query("DELETE  FROM admin_users WHERE user_id=$uid") or die(mysql_error());

if (!$result) {
    ?>
    <script>
        window.alert('Error...')
        window.location.href = 'users.php';</script>
    <?php

} else {
    ?>
    <script>
        window.alert('user Deleted successfully...')
        window.location.href = 'users.php';</script>
    <?php

}
?>