<?php
session_start();

$con=mysql_connect("192.168.1.74","india","indiaICG2013","prices");
	
 mysql_select_db("prices",$con);
        
    //login check function    
  function loggedin(){
    if(isset($_SESSION['email'])|| isset($_COOKIE['email']))
    {
        $loggedin = TRUE;
        return $loggedin;
    }
}
?>

