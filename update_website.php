<?php
$title = "Kraus Price Defender | update_websites.php";
include('db.php');
session_start();
$_SESSION['role'] === 'Admin';
$id = $_POST['id'];
$name = $_POST['name'];
$dom = $_POST['domain'];
$dc = $_POST['date_created'];
$ex = $_POST['excluded'];
$_SESSION['a'] = '';
$result = mysql_query("UPDATE website SET name='$name', domain='$dom', date_created='$dc', excluded='$ex' WHERE id=$id") or die(mysql_error());
// if successfully update data, displays message "Successful".
if (!$result) {
    ?>
    <script>
    <?php
    $_SESSION['a'] = '0';
    ?>
        window.location.href = '/websites.php';</script>
    <?php
} else {
    ?>
    <script>
    <?php
    $_SESSION['a'] = '1';
    ?>
        window.location.href = '/websites.php';</script>
    <?php
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#logs').delay(2000).fadeOut();
    });
</script>