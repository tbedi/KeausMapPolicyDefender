<?php include_once 'db.php'; ?>
<?php include_once 'db_login.php'; ?>
<?php include_once 'template/head.phtml'; ?>
<body id="home" >
    <?php include_once 'template/header.phtml'; ?>    
    <div id="wrapper" align="center" >

        <div id="tabContainer" align="center" > 

            <div id="tabscontent" align="center">

                <div class="tabpage recent" id="tabpage_1">
                    <?php include_once 'websites1.php'; ?>
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
