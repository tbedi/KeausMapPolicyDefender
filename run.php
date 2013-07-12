<?php
include_once 'db.php';

 if(loggedin())
{
    header("Location:userarea.php");
    exit();
} 
if(isset($_POST['login']) && $_POST['login'])
{
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if($email && $password)
    {
        $login = mysql_query("select * from admin_users where email='$email'");
        if(mysql_num_rows($login)==0)
        {
            die("Incorrect email/password combination");
            //header("Location:index1.php");
        }
 else {
        while($row = mysql_fetch_assoc($login))
        {
            $db_password = $row['password'];
            if(md5($password) === $db_password)
           $loginok = TRUE;          
             else {
     $loginok = FALSE;
 }
 
 
     if($loginok==TRUE){
         if($_POST['rememberme']=="on")
             setcookie("email", $email, time()+7200);
         elseif($_POST['rememberme']=="")
             $_SESSION['email'] = $email;
         
         header("Location: userarea.php");
         exit();
     }
     else 
     die("Incorrect email/password combination");  
         
 
        }
    }
    }
}
?>