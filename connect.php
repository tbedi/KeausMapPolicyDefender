<?php
$dbhost							= "192.168.1.74";
$dbuser							= "india";
$dbpass							= "indiaICG2013";
$dbname							= "prices";

$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ("Error connecting to database");
mysql_select_db($dbname);
?>