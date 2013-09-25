<?php
date_default_timezone_set('America/New_York');
include_once 'db_login.php';
include_once 'db.php';
include_once './classes/db_class.php'; //we included database class
$db_resource = new DB (); // we created database resourse object which contains methods and connection
/* cookie start and destroy */
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_start();
    session_destroy();
    if (isset($_COOKIE['email']))
        setcookie("email", "", time() - 60000);
    header('Location: login.php');
    die();
}
$_session['a'] = '0';
$_SESSION['role'] = '';

/* Cookie check */
if (isset($_COOKIE['email']) || isset($_SESSION['email'])) {
    $email = (isset($_COOKIE['email']) ? $_COOKIE['email'] : $_SESSION['email'] );
    $sql = "select * from admin_users where email='$email'";
    $products = $db_resource->GetResultObj($sql);
    if (count($products) > 0) {

        $us = $products[0]->username;
        $role = $products[0]->role;
    }
    $_SESSION['username'] = $us;
    $_SESSION['role'] = $role;
    $_SESSION['email'] = $email;

    header("Location: index.php");
    exit();
}
/* cookie start and destroy */

/* Login check */
if (isset($_POST['login'])) {
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email && $password) {
        // Retrieve data from database 
        $sql = "select * from admin_users where email='$email'";
        $products = $db_resource->GetResultObj($sql);
        if (count($products) > 0) {
            $us = $products[0]->username;
            $pass = $products[0]->password;
            $role = $products[0]->role;
            $user_id = $products[0]->user_id;
            $last_log = $products[0]->last_login;
            $_SESSION['us_id'] = $user_id;
        }
        if (count($products) > 0) {
            $db_password = $pass;
            if (md5($password) === $db_password) //encrypt password
                $loginok = TRUE;
            else {
                $_SESSION['role'] = '';
                $loginok = FALSE;
            }
            if ($loginok == TRUE) {
                $_SESSION['username'] = $us;
                $_SESSION['role'] = $role;
                $_SESSION['last_login'] = $last_log;  //last date time stored in this session
                $_SESSION['curent_login'] = date('Y-m-d H:i:s');  //current date time stored in this session
                $user_id = $_SESSION['us_id'];
                $current_date = $_SESSION['curent_login']; //current date stored in $current_date variable
                $sql1 = mysql_query("UPDATE admin_users SET last_login = '$current_date' WHERE user_id = $user_id");
                /* query checking if false */
                if (!$sql1) {
                    $_SESSION['errerr'] = 'err';
                    echo 'error';
                }
                /* query checking if false */

                /* Remember me on */
                if ($_POST['rememberme'] == "on")
                    setcookie("email", $email, time() + 7200);
                $_SESSION['email'] = $email;
                header("Location: index.php");
                exit();
            }
            else {
                $_session['a'] = '1';
            }
        }
    }
}
$title = "Kraus Price Defender | Login";
?>
<?php include_once 'template/head.phtml'; ?>
<body id="login-page">
<?php include_once 'template/header.phtml'; ?>  
    <div id="wrapper" align="center">
<?php
/* Error message if login fails */
if (count($products) === 0 && isset($_POST['email']) && $_session['a'] === '0') {
    echo " <div class=" . "log" . "  align=" . "left" . ">Please register..</div>";
} elseif ($_session['a'] === '1') {
    echo " <div class=" . "log" . "  align=" . "left" . " >Please enter correct password</div>";
}
/* Error message if login fails */
?>
        <div  class="main-content" align="center" >
            <div style="margin:0px;   padding:0px" align="center"> 
                <div class="top-panel">
                    Login
                </div>
                <div id="tabscontent" align="center"> 
                    <div class="formlog1" >
                        <form name="form" action="login.php" method="post"  >
                            <table align="center">
                                <tbody>
                                    <tr> 
                                        <td>
                                            Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>	
                                        <td>
                                            <input type="text" name="email" class="input" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$" placeholder="abd_d@example.com" required /> 
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <input type='password' name='password' id='password'  class="input" maxlength="50" required />
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td><input type="checkbox" name="rememberme" align="center" />&nbsp;&nbsp;Remember Me</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td rowspan="5" colspan="5" align="center">
                                            <input class="btn-login" type="submit" name="login" value="Login"  />
                                        </td>
                                    </tr>
                                </tbody></table>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once 'template/footer.phtml'; ?>        
</body>
</html>