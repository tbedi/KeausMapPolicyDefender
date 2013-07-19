<?php

include('db.php');
session_start();
$_SESSION['role'] === 'Admin';
$id = $_POST['id'];
$name = $_POST['name'];
$dom = $_POST['domain'];
$dc = $_POST['date_created'];
$ex = $_POST['excluded'];



$result = mysql_query("UPDATE website SET name='$name', domain='$dom', date_created='$dc', excluded='$ex' WHERE id=$id") or die(mysql_error());
//echo "deleted";
if (!$result) {
    ?>
    <script>
        window.alert('Error...')
        window.location.href = 'website_test.php';</script>
    <?php

} else {
    ?>
    <script>
        window.alert('website Updated...')
        window.location.href = 'websites.php';</script>
    <?php

}
?>