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
        <link href="css/TBLCSS.css" rel="stylesheet" type="text/css" /> <!-- Styles from recent.php -->
        <link href="css/div.css" rel="stylesheet" type="text/css" /> <!-- Styles from recent.php -->
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/paginator.css" />
        <script src="js/tabs_old.js"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {

                $('#login').click(function()
                {
                    document.getElementById('newpassword').value = '';
                    $('#login_block').show();

                });

                $.validator.addMethod("email", function(value, element)
                {
                    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value);
                }, "Please enter a valid email address.");


// Validate signup form
                $("#form").validate({
                    rules: {
                        email: "required email",
                    },
                });





            });
        </script>
        <style type="text/css">
            body
            {
                font-family:Arial, Helvetica, sans-serif;

                font-size:12px;
            }
            /* === List Styles === */
            .contact_form ul {
                width:750px;
                list-style-type:none;
                list-style-position:outside;
                margin:0px;
                padding:0px;
            }
            .contact_form li{
                padding:12px;
                border-bottom:1px solid #eee;
                position:relative;
            }
            .contact_form li:first-child, .contact_form li:last-child {
                border-bottom:1px solid #777;
            }
            label
            {
                color:#999;
                padding:4px;
                margin-top:4px;
                color: #000;
            }
            .input
            {
                padding:9px;
                border:solid 1px #999;
                margin:4px;
                width:220px;
            }
            .radio
            {
                margin:4px;

            }
            .button
            {
                font-size:15px;
                padding:6px;
                font-weight:bold;
            }
            label.error
            {
                font-size:11px;
                background-color: #dedede;
                color:#000;
                padding:3px;
                margin-left:5px;
                -moz-border-radius: 4px;
                -webkit-border-radius: 4px;
            }
            #signup_block
            {
                display:none;
            }
        </style>



    </head>

    <body id="home" >

        <div id="templatemo_header_wrapper" >
            <div><a href="/" target="_blank"><img src="images/Kraus-Logo-HQ.png" width="186" height="71" /> </a>
                <!-- <div style="float: left ">
     
                     <h2 style="overflow: auto; min-height: 10px">Price Defender</h2>
                 </div>-->
            </div>

        </div>

        <div id="templatemo_menu" class="ddsmoothmenu">
            <br style="clear: left" />
        </div>
        </div>	
        <div id="templatemo_footer_wrapper1">
            <div id="templatemo_footer">
                <div align="right">
                    <a href="" target="_blank" class="top-menu-item-4" style="padding-left: 200px;padding-right: 20px" > <img src="images/user.jpg" width="28" height="24" /><strong>User Name	</strong> </a>
                    <a href="" target="_blank" class="top-menu-item-5" style="padding-left: 10px;"> <strong>LogOut</strong> </a>

                </div>
            </div>
        </div>


        <div id="wrapper" align="center">
            <div style="margin:60px; padding:20px">
                <form name="form" action="run.php" method="post" class="contact_form" >
                    <ul>
                        <li>
                            <h2>Login</h2>
                        </li>
                        <li>
                            <label><b>Email</b></label> <br/>
                            <input type="email" name="email" class="input" placeholder="abd_d@example.com" required /><br />
                        </li>
                        <li>

                            <label><b>Password</b></label><br />
                           <input type='password' name='password' id='password' class="input" maxlength="50" required /><br/><br/>
                        <input type="checkbox" name="rememberme" value="rememnerme" />&nbsp;&nbsp;Remember Me
                        </li>
                        <li>
                            <input class="button" type="submit" name="login" value="log in"/><input class="button" type="reset"/>
                        </li>
                </form>

            </div>

            <div class="clear"></div>
            <div class="shadow"></div>
        </div>



        <div id="templatemo_footer_wrapper">
            <div id="templatemo_footer">
                Copyright © Kraus USA 2013
            </div>
        </div>

    </body>
</html>