<?php
include_once 'db.php';

/*if(loggedin())
{
    header("Location:userarea.php");
    exit();
}*/
$e='';
if ( isset($_POST['login']))
{
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if($email && $password)
    {
        $login = mysql_query("select * from admin_users where email='$email'");
     
        while($row = mysql_fetch_assoc($login))
        {
            
            $db_password = $row['password'];
            
            if(md5($password) === $db_password)
           $loginok = TRUE;          
             else
           $loginok = FALSE;
     if($loginok == TRUE)
             {
         $_SESSION['username'] = $row['username'];
         //*$_SESSION['password']= $password;*//
         if($_POST['rememberme']=="on")
             setcookie("email", $email, time()+7200);
         $_SESSION['email'] = $email;
         header("Location: index.php");
         exit();
            }
     else 
    header("Location: index.php");
    }
    
   //*die("Incorrect email/password combination");*/
    }
    $e=$email;
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login Form</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <script src="js/tabs_old.js"></script>
    </head>

    <body id="home" >
        
        <div id="templatemo_header_wrapper" >
            <div><a  href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
                <!-- <div style="float: left ">
     
                     <h2 style="overflow: auto; min-height: 10px">Price Defender</h2>
                 </div>-->
            </div>

        </div>
       	
        <div id="templatemo_footer_wrapper1">
            <div id="templatemo_footer">
                <div align="right">
                    <a href="" target="_blank" style="padding-left:  200px;padding-right: 20px; padding-top: 30px" ></a>
                </div>
            </div> 
        </div> 


        <div id="wrapper" align="center">
            
    <?php
    $login = mysql_query("select * from admin_users where email='$e'");
    if (mysql_num_rows($login)===0 && isset($_POST['email']))
    {
        echo " <div id="."log"." align="."center".">"."<b>Incorrect email and password</b>"."</div>";
    } ?>
            
            <div style="margin:60px;   padding:20px" align="center"> 
                <div id="login" align="center" >
                    <form name="form" action="login.php" method="post"  >
                        <ul>
                            <li>
                                <h2>Login</h2>
                            </li>
                            <li>
                                <label><b>Email</b></label> <br/>
                                <input type="text" name="email" class="input" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$" placeholder="abd_d@example.com" required /><br />
                            </li>
                            <li>

                                <label><b>Password</b></label><br />
                                <input type='password' name='password' id='password' class="input" maxlength="50" required /><br/><br/>
                                <input type="checkbox" name="rememberme" value="rememnerme" />&nbsp;&nbsp;Remember Me
                            </li>
                            <li>
                                <input class="button"  type="submit" name="login" value="Login"  />
                            </li>
                        </ul>
                    </form>
                </div>
            </div>    
        </div>


        <div id="templatemo_footer_wrapper">
            <div id="templatemo_footer">
                Copyright © Kraus USA 2013
            </div> 
        </div> 

    </body>
</html>
