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
                <?php echo (isset($_GET['tab']) ? "var selected_tab='".$_GET['tab']."'; " : "var selected_tab='recent'; " );    ?> </script>
        <script src="js/tabs_old.js"></script>
        
        
 <link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />

<script type="text/javascript" src="calender/jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
	window.onpageshow = function(){
 new JsDatePick({
    useMode:2,
    target:"inputFieldto",
    dateFormat:"%Y-%m-%d"
});

 new JsDatePick({
    useMode:2,
    target:"inputFieldfrom",
    dateFormat:"%Y-%m-%d"
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
            <div><a  href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
                <img align="top" src="images/head4.PNG" />
           <!-- <div style="float: left ">

                <h2 style="overflow: auto; min-height: 10px">Price Defender</h2>
            </div>-->
            </div>
            
        </div>
 
        <!-- http://192.168.5.66/Forms/Web%20Forms/frmLogin.aspx-->
 
       


        <div id="wrapper" align="center" >

            <div id="tabContainer" align="center" <!-- onclick="tableSearch.init()" onmousemove="tableSearch.init()" --> >
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
                        <?php include_once 'recent.php'; ?>


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
