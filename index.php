<?php include_once 'db.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Price MAP Violation </title

        ><!-- hightcharts libraries -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script src="js/highcharts.js"></script>

        <script src="js/exporting.js"></script>  
        <!-- hightcharts libraries -->
        <!--<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> -->

<!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
<!--<script type="text/javascript" src="js/showhide.js"></script> -->
        <script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 

<!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
<!--<script type="text/javascript" src="js/ddsmoothmenu.js"></script>-->
        <script type="text/javascript" src="js/search.js"></script> <!-- Js from recent.php -->

        <!--<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" /> -->
        <link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
        <link href="css/TBLCSS.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
		<script type="text/javascript"> <?php echo (isset($_GET['tab']) ? "var selected_tab='".$_GET['tab']."'; " : "var selected_tab='recent'; " );    ?> </script>
        <script src="js/tabs_old.js"></script>
 
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
            <div><a  href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
           <!-- <div style="float: left ">

                <h2 style="overflow: auto; min-height: 10px">Price Defender</h2>
            </div>-->
            </div>
            
        </div>

        <div id="templatemo_menu" class="ddsmoothmenu">
            <br style="clear: left" />
        </div> 
        <!-- http://192.168.5.66/Forms/Web%20Forms/frmLogin.aspx-->

        </div>	
        <div id="templatemo_footer_wrapper1">
            <div id="templatemo_footer">
                <div align="center">
                    <a href="http://192.168.5.26:8080/KeausMapPolicyDefender/" target="_blank" class="top-menu-item-1" style="padding-right: 10px"> <strong>Price Defender	</strong> </a>
                    <a href="http://192.168.5.66/Forms/Web%20Forms/frmLogin.aspx" target="_blank" class="top-menu-item-2" style="padding-left:  10px;padding-right: 10px"> <strong>Shipment Controller	 </strong> </a>
                    <a href="" target="_blank" class="top-menu-item-3" style="padding-left:  10px;padding-right: 200px;"> <strong>Other App </strong> </a>
                    <a href="" target="_blank" class="top-menu-item-4" style="padding-left:  200px;padding-right: 20px"> <img src="images/user.jpg" width="28" height="24" /><strong>User Name	 </strong> </a>
                    <a href="" target="_blank" class="top-menu-item-5" style="padding-left:  10px;"> <strong>LogOut</strong> </a>

                </div>
            </div> 
        </div> 


        <div id="wrapper" align="center">

            <div id="tabContainer" align="center" onclick="tableSearch.init()" onmousemove="tableSearch.init()">
                <div id="tabs" align="center">
                    <ul>
                        <li id="tabHeader_1" class="recent">Recent violations</li>
                        <li id="tabHeader_2" class="violation-by-product" >Violation by product</li>
                        <li id="tabHeader_3" class="violation-by-seller" >Violation by seller</li>
                        <li id="tabHeader_4" class="violations-history">Violation history</li>
                    </ul>
                </div>
                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        <?php include_once 'recent1.php'; ?>


                    </div>

                    <div class="tabpage violation-by-product" id="tabpage_2">
                        <?php include_once 'product1.php'; ?>

                    </div>

                    <div class="tabpage violation-by-seller" id="tabpage_3">
                        <?php include_once 'vendor1.php'; ?>
                    </div>
                    <div class="tabpage violations-history" id="tabpage_4">
                        <?php include_once 'history1.php'; ?>

                    </div>    


                </div>


                <div class="cleaner"></div>

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
