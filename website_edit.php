<?php include_once 'db_login.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
       <!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            <?php echo (isset($_GET['tab']) ? "var selected_tab='" . $_GET['tab'] . "'; " : "var selected_tab='recent'; " ); ?></script>
        <script src="js/tabs_old.js"></script>

    </head>

    <body id="home" >

        <div id="templatemo_header_wrapper" >


            <div class="container"  style="margin:auto; width:980px;height:80px;  min-height:2px;overflow:auto;">

                <div style="float:left; padding-right: 30px"> <a  href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
                </div>
                   <!-- <img align="top" src="images/head4.PNG" /> -->
                <div class="left-part" style="float:left;width:200px; ">
                    <h2>Price Defender</h2>
                </div>           

            </div>
        </div>


        <div id="templatemo_footer_wrapper1">
            <div id="templatemo_footer">
                <div align="center" style="min-height:5px;overflow:auto;">
                    <div  class="menu-item first" style="float:left; padding-top:3px;" >  
                        <a href="" target="_blank" class="top-menu-item-3" > <strong>SETTINGS</strong> </a>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div  class="menu-item second" style="float:left; padding-top:3px;" >  
                        <?php
                        if ($_SESSION['role'] === 'Admin') {
                            echo "<a href=" . "websites.php" . " class=" . "top-menu-item-3" . " > <strong>WEBSITES</strong> </a>";
                        }
                        ?>&nbsp;&nbsp;&nbsp;
                    </div>
                    <div  class="menu-item third" style="float:left; padding-top:3px;" >  
                        <?php
                        if ($_SESSION['role'] === 'Admin') {
                            echo "<a href=" . "users.php" . " class=" . "top-menu-item-3" . " > <strong>USERS</strong> </a>";
                        }
                        ?>
                    </div>
                    <div style="float:right; padding-top:3px;width:176px;" >  
                        <img src="images/agent.png" width="28" height="24" style="padding-left:  10px; float:right;"/>
                        <a href="" target="_blank" class="top-menu-item-4" >  
                            <?php
                            if (isset($_SESSION['username'])) {
                                echo "" . $_SESSION['username'] . ", <br><small><a href=\"login.php\">logout</a></small>";
                            } else {
                                echo "Welcome Guest! <small><a href=\"login.php\">Login</a></small>";
                            }
                            ?> 
                        </a>
                    </div>                        

                </div>
            </div> 
        </div> 

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
							<div class="section" >
                                <label>website name</label>   
                                <div> 
                                  <p>
                                  <textarea class="full" name="name" ><?php echo $row['name']; ?></textarea>
                                  </p>
                                 
                                </div>
                            </div>
							<div class="section" >
                                <label>Website link </label>   
                                <div> 
                                  <p>
                                   <input type="text" name="domain" value=" <?php echo $row['domain']; ?> " />
                                  </p>
                                 
                                </div>
                                <div class="section" >
                                <label>Date Created </label>   
                                <div> 
                                  <p>
                                   <input type="text" name="date_created" value=" <?php echo $row['date_created']; ?> " />
                                  </p>
                                 
                                </div>
                            </div>
                                <div class="section" >
                                <label>Excluded</label>   
                                <div> 
                                  <p>
                                   <input type="text" name="excluded" value=" <?php echo $row['excluded']; ?> " />
                                  </p>
                                 
                                </div>
                             <input type="hidden" name="id" value=" <?php echo $row['id']; ?> " />
                            <div class="section">
                              <label></label>
                            </div>
                                <div class="section last">
                                  <div><a  href="javascript:()" onclick="document.getElementById('test').submit();" class="button" title="Saving" rel="1" > submit</a> </div>
                            </div>
                          </form>
                        
                        
                </div>

                    <div class="tabpage violation-by-product" id="tabpage_2">
                        <?php include_once 'product.php'; ?>

                    </div>

                    <div class="tabpage violation-by-seller" id="tabpage_3">
                        <?php include_once 'vendor.php'; ?>
                    </div>
                    <div class="tabpage violations-history" id="tabpage_4">
                        <?php include_once 'history.php'; ?>

                    </div>    


                </div>


                <div class="cleaner"></div>

            </div> 

        </div> 

        <div id="templatemo_footer_wrapper">
            <div id="templatemo_footer">
                Copyright © Kraus USA 2013
            </div> 
        </div> 

    </body>
</html>

