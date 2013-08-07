<?php    
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
     session_start();
    session_destroy();
    if (isset($_COOKIE['email']))    		 
    	setcookie("email","",time()-60000);
    header('Location: login.php');
    die();
}
include_once 'db_login.php';
include_once 'db_class.php'; //we included database class
$db_resource = new DB (); // we created database resourse object which contains methods and connection

$_session['a'] = '0';
$_SESSION['role'] = '';

/* Cookie check */
if (isset($_COOKIE['email']) || isset($_SESSION['email'])) { //optimize code
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

/* Cookie check */
if (isset($_POST['login'])) {
    //getdata
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email && $password) {
        $sql = "select * from admin_users where email='$email'";
        $products = $db_resource->GetResultObj($sql);
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

        if (count($products) === 0 && isset($_POST['email']) && $_session['a']=== '0' ) {
        echo " <div class=" . "log" . "  align=" . "left" . ">Please register..</div>";
       } elseif ($_session['a']=== '1') {
       echo " <div class=" . "log" . "  align=" . "left" . " >Please enter correct password</div>";
       }
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
                                    <tr><td>&nbsp;</td><td><input type="checkbox" name="rememberme" align="center"/>&nbsp;&nbsp;Remember Me</td></tr>
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