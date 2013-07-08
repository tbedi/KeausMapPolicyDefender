<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Top Products And Vendors</title>

<script type="text/javascript" src="js/jquery-1-4-2.min.js"></script> 
 
<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
<script type="text/javascript" src="js/showhide.js"></script> 
<script type="text/JavaScript" src="js/jquery.mousewheel.js"></script> 

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/ddsmoothmenu.js"></script>
<script type="text/javascript" src="js/search.js"></script> <!-- Js from recent.php -->

<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
<link href="css/templatemo_style.css" rel="stylesheet" type="text/css" />
<link href="css/TBLCSS.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
<link href="css/div.css" rel="stylesheet" type="text/css" />  <!-- Styles from recent.php -->
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/paginator.css" />








<script type="text/javascript">

ddsmoothmenu.init 
({
	mainmenuid: "templatemo_menu", 
	orientation: 'h',
	classname: 'ddsmoothmenu',
	
	contentsource: "markup" 
})

</script> 

<script src="js/tabs_old.js"></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1332079-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
 


</head>

<body id="home" >

<div id="templatemo_header_wrapper" >
	<div ><a href="http://www.kraususa.com/"><img src="images/images.jpg" width="186" height="84" /> </a>
    <img align="top" src="images/head1.PNG" /></div>
     <div id="templatemo_menu" class="ddsmoothmenu">
             <br style="clear: left" />
    </div> 
    <div class="cleaner" align="center"></div>
</div>	

<div id="wrapper" align="center">
  
  <div id="tabContainer" align="center" onclick="tableSearch.init()" onmousemove="tableSearch.init()">
    <div id="tabs" align="center">
      <ul>
        <li id="tabHeader_1">Recent Violations</li>
        <li id="tabHeader_2">Violation By Product</li>
        <li id="tabHeader_3">Violation By Seller</li>
        <li id="tabHeader_4">Violation History</li>
      </ul>
    </div>
    <div id="tabscontent" align="center">
    
      <div class="tabpage" id="tabpage_1">
    <?php include_once 'recent1.php';?>
  
     
      </div>
      
      <div class="tabpage" id="tabpage_2">
         <?php include_once 'product1.php';?>
  
      </div>
      
      <div class="tabpage" id="tabpage_3">
       <?php include_once 'vendor1.php';?>
            </div>
          <div class="tabpage" id="tabpage_4">
           <?php include_once 'history1.php';?>
    
            </div>    
        
  </div>

<div class="cleaner"></div>

    	<div class="cleaner"></div>
    </div> 
</div> 

<div id="templatemo_footer_wrapper">
    <div id="templatemo_footer">
    	Copyright © Kuaus USA 2013
    </div> 
</div> 
 
</body>
</html>
