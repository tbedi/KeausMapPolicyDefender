<?php
include_once 'db_login.php';
$title = "Kraus Price Defender | user_edit.php";
?>
<?php include_once 'template/head.phtml'; ?>
<body id="login-page" >
    <?php include_once 'template/header.phtml'; ?>  
    <div id="wrapper" align="center" >
        <div class="main-content" align="center" >
            <div style="margin:0px;   padding:0px" align="center"> 
                <div class="top-panel">
                    <span style="font-size: 1.8em;">Edit User</span>
                </div>
                <div id="tabscontent" align="center">
                    <div class="tabpage recent" id="tabpage_1">
                        <form id="test" action="update_user.php" method="POST"> 
                            <?php
                            $uid = $_GET['user_id'];
                            include "db.php";
                            $sql2 = "select * from admin_users where user_id = '$uid'";
                            $qry = mysql_query($sql2);
                            $row = mysql_fetch_array($qry);
                            ?>
                            <div align="center" style="font-size: 150%;">
                                <table>
                                    <tbody id="data">
                                        <tr>
                                            <td colspan="2" align="center"></td>
                                        </tr>
                                        <tr> 
                                            <td align="left">
                                                Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>	
                                            <td>
                                                <input type="text" class="input" name="username" Value="<?php echo $row['username']; ?>"  size="40"  style="padding:5px;"/>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td align="left">
                                                Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <input type="text" class="input"  name="email" value="<?php echo $row['email']; ?>"  size="40"  style="padding:5px;"/>
                                            </td></tr>
                                        <tr>

                                            <td align="left">
                                                Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <input type="text" class="input"  name="name" value="<?php echo $row['name']; ?>"  size="40"  style="padding:5px;"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="left">
                                                Role:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <input type="text" class="input"  name="role" value="<?php echo $row['role']; ?>" size="40"  style="padding:5px;"/>
                                            </td>
                                        </tr>
                                    <input type="hidden" name="user_id" value=" <?php echo $row['user_id']; ?> " style="padding:5px;"/>      
                                    <tr><td>&nbsp;</td>
                                        <td><input type="hidden" name="" value="" /></td></tr>             
                                    <tr>
                                        <td rowspan="5" colspan="5" align="center">
                                            <div><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="btn-login" title="Saving" rel="1" > submit</a> &nbsp;<a  href="users.php" class="btn-login" type="reset" >Cancel</a></div>
                                        </td>
                                    </tr>         

                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="cleaner"></div>
        </div> 
    </div> 

    <?php include_once 'template/footer.phtml'; ?> 
</body>
