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
                <div class="top-panel">Edit User</div>
                <div id="tabscontent" align="center">
                    <div class="tabpage recent" id="tabpage_1">
                        <div class="formlog1" >
                            <form id="test" action="update_user.php" method="POST"> 
                                <?php
                                $uid = $_GET['userid'];
                                $sql2 = "select * from admin_users where user_id = '$uid'";
                                $row = $db_resource->GetResultObj($sql2);

                                if (count($row) > 0) {
                                    $us = $row[0]->username;
                                    $email = $row[0]->email;
                                    $name = $row[0]->name;
                                    $user_id = $row[0]->user_id;
                                }
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
                                                    <input type="text" class="input" name="username" Value="<?php echo $us; ?>"  size="40"  style="padding:5px;"/>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td>&nbsp;</td>	
                                                <td><input type="hidden" value="hidden"/>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td align="left">
                                                    Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>	
                                                <td>
                                                    <input type="password" class="input" name="password" Value=""  size="40"  style="padding:5px;"/>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td>&nbsp;</td>	
                                                <td><input type="hidden" value="hidden"/>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td align="left">
                                                    Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td>
                                                    <input type="text" class="input"  name="email" value="<?php echo $email; ?>"  size="40"  style="padding:5px;"/>
                                                </td></tr>
                                            <tr> 
                                                <td>&nbsp;</td>	
                                                <td><input type="hidden" value="hidden"/>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td align="left">
                                                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td>
                                                    <input type="text" class="input"  name="name" value="<?php echo $name; ?>"  size="40"  style="padding:5px;"/>
                                                </td>
                                            </tr>
                                            <tr> 
                                                <td>&nbsp;</td>	
                                                <td><input type="hidden" value="hidden"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left">
                                                    Role:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </td>
                                                <td align="left">
                                                    <select name="role" class="input">
                                                        <option value="Admin" >Admin</option>
                                                        <option value="Executive">Executive</option>
                                                        <option value="Customer Service">Customer Service</option>
                                                        <option value="Operations">Operations</option>
                                                    </select>
                                                </td>

                                            </tr>

                                        <input type="hidden" name="user_id" value=" <?php echo $user_id; ?> " style="padding:5px;"/>      
                                        <tr><td>&nbsp;</td>
                                            <td><input type="hidden" name="" value="" /></td></tr> 
                                        <tr> 
                                            <td>&nbsp;</td>	
                                            <td><input type="hidden" value="hidden"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="5" colspan="5" align="center">
                                                <div><button  href="javascript:()" onclick="document.getElementById('test').submit();" class="butl" title="Saving" rel="1" >Update</button> &nbsp;<input type="button" class="butl" value="Cancel" onclick="window.location.href = '/users.php'"></div>
                                            </td>
                                        </tr>         

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cleaner"></div>
        </div> 
    </div> 

    <?php include_once 'template/footer.phtml'; ?> 
</body>
