<?php
include_once 'db_login.php';
include_once 'db_class.php'; //we included database class

$db_resource = new DB (); // we created database resourse object which contains methods and connection

$e = '';
$_SESSION['role'] = '';

if (isset($_POST['login'])) {
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email && $password) {
        $login = mysql_query("select * from admin_users where email='$email'");
        $sql = "select * from admin_users where email='$email'";
        $products = $db_resource->GetResultObj($sql);
        //print_r($products);
        if (count($products) > 0) {

            $us = $products[0]->username;
            $pass = $products[0]->password;
            $role = $products[0]->role;
        }
        if (count($products) > 0) {
            $db_password = $pass;
            if (md5($password) === $db_password)
                $loginok = TRUE;
            else {
                session_destroy();
                session_start();
                $_SESSION['role'] = '';
                $loginok = FALSE;
            }
            if ($loginok == TRUE) {
                session_destroy();
                session_start();
                $_SESSION['username'] = $us;
                $_SESSION['role'] = $role;
                if ($_POST['rememberme'] == "on")
                setcookie("email", $email, time() + 7200);
                $_SESSION['email'] = $email;
                header("Location: index.php");
                exit();
            }
            else {
                
              
            }
        }
        $e = $email;
    }
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login Form</title>
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>

<?php include_once 'template/head.phtml'; ?>

    <body id="home">

    <?php include_once 'template/login_header.phtml'; ?> 

        <div id="wrapper" align="center">

        <?php
        $sql1 = "select * from admin_users where email='$e'";
        $products1 = $db_resource->GetResultObj($sql1);
        if (count($products1) === 0 && isset($_POST['email'])) {
            echo " <div id=" . "log" . " align=" . "center" . ">" . "<b>Incorrect email and password</b>" . "</div>";
        }
//        else{
//          echo " <div id=" . "log" . " align=" . "center" . ">" . "<b>gjhgkj</b>" . "</div>";  
//        }
        ?>

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
                                <input type="checkbox" name="rememberme"  />&nbsp;&nbsp;Remember Me
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