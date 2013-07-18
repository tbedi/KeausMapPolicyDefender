<?php include_once 'db_login.php'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Price Defender </title

        ><!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/highcharts.js"></script>

        <script src="js/exporting.js"></script>  
        <!-- hightcharts libraries -->
        <!--script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> -->

<!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
<!--<script type="text/javascript" src="js/showhide.js"></script> -->
        <script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 

<!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->    
<!--<script type="text/javascript" src="js/ddsmoothmenu.js"></script>-->
        <script type="text/javascript" src="js/search.js"></script> 

        <!--<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" /> -->
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <script type="text/javascript">
            <?php echo (isset($_GET['tab']) ? "var selected_tab='" . $_GET['tab'] . "'; " : "var selected_tab='recent'; " ); ?></script>
        <script src="js/tabs_old.js"></script>


        <link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />

        <script type="text/javascript" src="calender/jsDatePick.min.1.3.js"></script>
        <script type="text/javascript">
            window.onpageshow = function() {
                new JsDatePick({
                    useMode: 2,
                    target: "inputFieldto",
                    dateFormat: "%Y-%m-%d"
                });

                new JsDatePick({
                    useMode: 2,
                    target: "inputFieldfrom",
                    dateFormat: "%Y-%m-%d"
                });
            };


        </script>




        <script type="text/javascript">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-1332079-8']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();

            //highcharts colors
            $(function() {
                // Radialize the colors
                Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
                    return {
                        radialGradient: {cx: 0.2, cy: 0.5, r: 0.4},
                        stops: [
                            [0, color],
                            [1, Highcharts.Color(color).brighten(-0.1).get('rgb')] // darken
                        ]
                    };
                });
            });
            //highcharts colors

        </script>


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
                    <div  class="menu-item first" style="float:left; padding-top:3px;" >  
                        <a href=websites.php class=top-menu-item-3 > <strong>WEBSITES</strong> </a> 
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
                <!--<div id="tabs" align="center">
                    <ul>
                        <li id="tabHeader_1" class="recent">Recent violations</li>
                        <li id="tabHeader_2" class="violation-by-product" >Violation by product</li>
                        <li id="tabHeader_3" class="violation-by-seller" >Violation by seller</li>
                        <li id="tabHeader_4" class="violations-history">Violation history</li>
                    </ul>
                </div> -->

                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        <?php
                        ////pagination
                        include_once 'db_login.php';
                        $tableName = "website";
                        $targetpage = "websites.php";
                        $limit = 10;


                        $query = "SELECT COUNT(*) as num FROM $tableName";
                        $total_pages = mysql_fetch_array(mysql_query($query));
                        $total_pages = $total_pages['num'];

                        $stages = 3;
                        $page = 1;
                        if (isset($_GET['page'])) {
                            $page = mysql_escape_string($_GET['page']);
                            $start = ($page - 1) * $limit;
                        } else {
                            $start = 0;
                            $page = 1;
                        }
                        ?>
                        <html>

                            <head>
                                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                                <script type="text/javascript" src="js/search.js"></script> 
                                <link href="css/tblcss.css" rel="stylesheet" type="text/css" />  
                                <link rel="stylesheet" type="text/css" href="css/paginator.css" />
                                <link href="css/style.css" rel="stylesheet" type="text/css" />
                            </head>
                            <body><br>
                                    <table align="center" width="1000px" >
                                        <tr>
                                            <td >



                                                <input  class="recent_search" 	placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action']) == 'recent') echo $_GET['value']; ?>" id="textBoxSearch"   
                                                        style="padding:5px;
                                                        padding-right: 40px;
                                                        background-image:url(images/sr.png); 
                                                        background-position: 100% -5px; 
                                                        background-repeat: no-repeat;
                                                        border:2px solid #456879;
                                                        border-radius:10px;float:left;
                                                        height: 15px;
                                                        outline:none; 
                                                        width: 200px; "/> 

                                                <a href="javascript:void(0);" class="myButton"  onclick="recent_search();">Search</a>





                                            </td>

                                            <td>
                                                </table>





                                                <table class="GrayBlack" align="center">
                                                    <tbody id="data">
                                                        <tr>
                                                            <td>S.no</td>
                                                            <td>Website Name</td>
                                                            <td>Website Link</td>
                                                            <td>Data Created</td>
                                                            <td>Excluded</td>
                                                            <td>Edit</td>
                                                        </tr>


                                                        <?php
// Get page data
                                                        $query1 = "SELECT * from $tableName LIMIT $start, $limit";
                                                        $result = mysql_query($query1);

// Initial page num setup
                                                        if ($page == 0) {
                                                            $page = 1;
                                                        }
                                                        $prev = $page - 1;
                                                        $next = $page + 1;
                                                        $lastpage = ceil($total_pages / $limit);
                                                        $LastPagem1 = $lastpage - 1;


                                                        $paginate = '';
                                                        if ($lastpage > 1) {




                                                            $paginate .= "<div class='paginate'>";
// Previous
                                                            if ($page > 1) {
                                                                $paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
                                                            } else {
                                                                $paginate.= "<span class='disabled'>previous</span>";
                                                            }
// Pages
                                                            if ($lastpage < 7 + ($stages * 2)) { // Not enough pages to breaking it up
                                                                for ($counter = 1; $counter <= $lastpage; $counter++) {
                                                                    if ($counter == $page) {
                                                                        $paginate.= "<span class='current'>$counter</span>";
                                                                    } else {
                                                                        $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                                                                    }
                                                                }
                                                            } elseif ($lastpage > 5 + ($stages * 2)) { // Enough pages to hide a few?
// Beginning only hide later pages
                                                                if ($page < 1 + ($stages * 2)) {
                                                                    for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                                                                        if ($counter == $page) {
                                                                            $paginate.= "<span class='current'>$counter</span>";
                                                                        } else {
                                                                            $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                                                                        }
                                                                    }
                                                                    $paginate.= "...";
                                                                    $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                                                                    $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
                                                                }
// Middle hide some front and some back
                                                                elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                                                                    $paginate.= "<a href='$targetpage?page=1'>1</a>";
                                                                    $paginate.= "<a href='$targetpage?page=2'>2</a>";
                                                                    $paginate.= "...";
                                                                    for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                                                                        if ($counter == $page) {
                                                                            $paginate.= "<span class='current'>$counter</span>";
                                                                        } else {
                                                                            $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                                                                        }
                                                                    }
                                                                    $paginate.= "...";
                                                                    $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                                                                    $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
                                                                }
// End only hide early pages
                                                                else {
                                                                    $paginate.= "<a href='$targetpage?page=1'>1</a>";
                                                                    $paginate.= "<a href='$targetpage?page=2'>2</a>";
                                                                    $paginate.= "...";
                                                                    for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                                                                        if ($counter == $page) {
                                                                            $paginate.= "<span class='current'>$counter</span>";
                                                                        } else {
                                                                            $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                                                                        }
                                                                    }
                                                                }
                                                            }

// Next
                                                            if ($page < $counter - 1) {
                                                                $paginate.= "<a href='$targetpage?page=$next'>next</a>";
                                                            } else {
                                                                $paginate.= "<span class='disabled'>next</span>";
                                                            }

                                                            $paginate.= "</div>";



                                                            echo $total_pages . ' Results';
// pagination
                                                            echo $paginate;
                                                            while ($row = mysql_fetch_array($result)) {
                                                                ?>

                                                                <tr>
                                                                    <td ><?php echo $row['id']; ?></td>
                                                                    <td ><?php echo $row['name']; ?></td> 
                                                                    <td ><?php echo $row['domain']; ?></td>
                                                                    <td ><?php echo $row['date_created']; ?></td>
                                                                    <td ><?php echo $row['excluded']; ?></td>
                                                                    <td ><a href="website_edit.php?id=<?php echo($row['id']); ?> " title="Edit" > <img src="images/icon_edit.png" /> </a> </td>

                                                                </tr>
                                                            <?php }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>

                                                </body>

                                                </html>

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
