<?php
session_start();
session_destroy(); // put it into if logged out condition

include_once 'db_login.php';
include_once 'db_class.php'; //we included database class

$db_resource = new DB (); // we created database resourse object which contains methods and connection

$e = '';
$a = 0;
$_SESSION['role'] = '';

/* Cookie check */
if (isset($_COOKIE['email'])) { //optimize code
    $email = $_COOKIE['email'];
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
/* Cookie check */

if (isset($_POST['login'])) {
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email && $password) {
        $login = mysql_query("select * from admin_users where email='$email'"); //optimize code
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
                $_SESSION['role'] = '';
                $loginok = FALSE;
            }
            if ($loginok == TRUE) { 
                $_SESSION['username'] = $us;
                $_SESSION['role'] = $role;
                if ($_POST['rememberme'] == "on")
                setcookie("email", $email, time() + 7200);
                $_SESSION['email'] = $email;
                header("Location: index.php");
                exit();
            }
            else {

                $a = 1;
            }
        }
        $e = $email;
    }
}

$title = "Kraus Price Defender | Login";
?>


<?php include_once 'template/head.phtml'; ?>

<body id="login-page">

    <?php include_once 'template/header.phtml'; ?>  
    <div id="wrapper" align="center">

        <?php
        $sql1 = "select * from admin_users where email='$e'"; //optimize errors. you can use session for informational messages
        $products1 = $db_resource->GetResultObj($sql1);
        if (count($products1) === 0 && isset($_POST['email']) && $a === 0) {
            echo " <div id=" . "log" . " align=" . "center" . ">" . "<b>Incorrect email and password</b>" . "</div>";
        } elseif ($a === 1) {
            echo " <div id=" . "log" . " align=" . "center" . ">" . "<b>wrong password!!,please login again with correct password</b>" . "</div>";
        }
        ?>
        <div  class="main-content" align="center" >
            <div style="margin:0px;   padding:0px" align="center"> 
                <h2 align="center">Login</h2>

                <form name="form" action="login.php" method="post"  >

                    <ul>

                        <li>
                            Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            <input type="text" name="email" class="input" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$" placeholder="abd_d@example.com" required /><br />
                        </li>
                        <li>
                            Password:&nbsp;&nbsp;&nbsp;
                            <input type='password' name='password' id='password' class="input" maxlength="50" required /><br/><br/>
                            <input type="checkbox" name="rememberme" />&nbsp;&nbsp;Remember Me
                        </li>
                        <li>
                            <input class="btn-login" type="submit" name="login" value="Login"  />
                        </li>
                    </ul>
                </form>

            </div>
        </div>
    </div>

    <?php include_once 'template/footer.phtml'; ?>        

</body>
</html>