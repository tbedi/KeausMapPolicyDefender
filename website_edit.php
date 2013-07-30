<?php include_once 'db_login.php'; ?>
<?php include_once 'template/head.phtml'; ?>
<body id="website_page" >
    <?php include_once 'template/header.phtml'; ?>

    <div id="wrapper" align="center" >
        <div class="main-content" align="center" >
            <div style="margin:0px;   padding:0px" align="center"> 
                <div class="top-panel">
                    <span style="font-size: 1.8em;">Edit Websites</span>
                </div>
                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        <form id="test" action="update_website.php" method="POST"> 

                            <?php
                            $id = $_GET['id'];
                            include "db.php";

                            $sql2 = "select * from website where id = '$id'";
                            $qry = mysql_query($sql2);
                            $row = mysql_fetch_array($qry);
                            ?>
                            <table align="center">
                                <tbody>
                                    <tr> 
                                        <td>
                                            Website Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>	
                                        <td>
                                            <input type="text" name="name" class="input"  size="40" Value="<?php echo $row['name']; ?>" style="padding:5px;"/>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td>
                                            Website Link:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <input type="text" name="domain" class="input"  size="40" value="<?php echo $row['domain']; ?>" style="padding:5px;"/>
                                        </td></tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Date Created:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <input type="text" name="date_created" class="input" size="40" value="<?php echo $row['date_created']; ?>" style="padding:5px;"/>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr>

                                        <td>
                                            Excluded:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        <td>
                                            <input type="text" name="excluded" class="input" size="40" value="<?php echo $row['excluded']; ?>" style="padding:5px;"/>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" value="hidden"/>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>	
                                        <td><input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td rowspan="5" colspan="5" align="center">
                                            <div ><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="btn-login" title="Saving" rel="1" > submit</a> &nbsp;<a  href="websites.php" class="btn-login" type="reset" >Cancel</a> </div>
                                        </td>
                                    </tr>

                                </tbody></table>  
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div> 

    <div id="templatemo_footer_wrapper">
        <div id="templatemo_footer">
            Copyright © Kraus USA 2013
        </div> 
    </div> 

</body>

