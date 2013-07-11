<?php
include_once 'db.php';

if(loggedin())
{
    header("Location:userarea.php");
    exit();
}
if($_POST['login'])
{
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];
    //$rememberme = $_POST['rememberme'];
    
    if($email && $password)
    {
        $login = mysql_query("select * from admin_users where email='$email'");
        while($row = mysql_fetch_assoc($login))
        {
            $db_password = $row['password'];
            if(md5($password==$db_password))
                    $loginok = TRUE;
 else {
     $loginok = FALSE;
 include 'index1.php';}
     if($loginok==TRUE){
         if(rememberme=="on")
             setcookie("email", $email, time()+7200);
         elseif($rememberme=="")
             $_SESSION['email'] = $email;
         header("Location: userarea.php");
         exit();
     }
     else 
     die("Incorrect email/password combination");  
     
     
     
 
        }
    }
    
}
?>