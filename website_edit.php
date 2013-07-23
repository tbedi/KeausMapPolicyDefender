<?php include_once 'db_login.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
    </head>
<?php include_once 'template/head.phtml'; ?>
    <body id="home" >
<?php include_once 'template/header.phtml'; ?>

        <div id="wrapper" align="center" >

            <div id="tabContainer" align="center" ><!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()"  -->  
               
                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        
                                                  <form id="test" action="update_website.php" method="POST"> 
                                                      
                                                       <?php
					
					$id = $_GET['id'];
					include  "db.php";							
						
						$sql2="select * from website where id = '$id'";
						$qry = mysql_query($sql2);
						 $row = mysql_fetch_array($qry);
						 
						?>
                                                      
                                <div align="center" style="font-size: 150%;">
                                <table>
                                    <tr>
                                        <td colspan="2" align="center"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="background-color:white;font-size:22px" align="center"><b>Edit Websites</b></td>
                                    </tr><tr><td>&nbsp;</td>
                                        <td><input type="hidden" name="" value="" /></td></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:12px">Website Name:</td>
                                        <td ><input type="text" name="name" Value="<?php echo $row['name']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:12px">Website Link:</td>
                                        <td ><input type="text" name="domain" value="<?php echo $row['domain']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:12px">Date Created:</td>
                                        <td ><input type="text" name="date_created" value="<?php echo $row['date_created']; ?>" /></td>
                                    </tr>
                                    <tr></tr>
                                    <tr>
                                        <td style="background-color:white;font-size:12px">Excluded:</td>
                                        <td ><input type="text" name="excluded" value="<?php echo $row['excluded']; ?>" /></td>
                                    </tr>
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                                    <tr><td>&nbsp;</td>
                                        <td><input type="hidden" name="" value="" /></td></tr>
                                    <tr>
                                        <td align="center">
                                          <div><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="button" title="Saving" rel="1" > submit</a><a  href="websites.php" class="button" type="reset" >Cancel</a> </div>
                                     </td>
                                    </tr>

                                </table>
                            </div> 
 
                          </form>
                        
                        
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
</html>

