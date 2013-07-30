<?php
session_start();
    //login check function    
function loggedin(){
    if(isset($_SESSION['email'])|| isset($_COOKIE['email']))
    {
        $loggedin = TRUE;
       if ( isset($_COOKIE['email']) && !isset($_SESSION['email']) ) { //save value from cookie to session
       	$_SESSION['email']=$_COOKIE['email'];
       }
        return $loggedin;
    }
}


//function display_error()
//{
//    if($_SESSION['err-code']!== '')
//    {$_error = $_SESSION[err-code];
//    echo " <div id=" . "log" . " style=" . "color:#f40f0f" . " align=" . "center" . ">" . "<p font color=" . "red" . ">ABCDE</p>" . "</div>";
//    echo $$error;
//    }
//}

?>
 


