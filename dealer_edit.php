<?php
include_once 'db_login.php';
$title = "Kraus Price Defender | Dealer_edit.php";
?>
    <?php include_once 'template/head.phtml'; ?>
<body id="dealers_page" >
<?php include_once 'template/header.phtml'; ?>
    <div id="wrapper" align="center" >  
        <div class="main-content" align="center" >
            <div style="margin:0px;   padding:0px" align="center"> 
                <div class="top-panel">Edit Dealers</div>
                <div id="tabscontent" align="center">

                    <div class="tabpage recent" id="tabpage_1">
                        <div class="formlog1" >
                            <form id="test" action="update_dealers.php" method="POST"> 
                                <?php
                                $name = $_GET['name'];
                                $sqld = "select * from website where name = '$name'"; //retrive data from database
                                $row = $db_resource->GetResultObj($sqld);
                                if (count($row) > 0) {
                                    $id = $row[0]->id;
                                    $name = $row[0]->name;
                                    $domain = $row[0]->domain;
                                    $date_created = $row[0]->date_created;
                                    $excluded = $row[0]->excluded;
                                }
                                ?> 
                                <table align="center">
                                    <tbody>
                                        <tr> 
                                            <td>
                                                Dealers Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>	
                                            <td>
                                                <input type="text" name="name" class="input"  size="40" Value="<?php echo $name; ?>" style="padding:5px;"/>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td>&nbsp;</td>	
                                            <td><input type="hidden" value="hidden"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="domain" class="input"  size="40" value="<?php echo $domain; ?>" style="padding:5px;"/>
                                            </td></tr>

                                        <tr>

                                            <td>
                                                <input type="hidden" name="date_created" class="input" size="40" value="<?php echo $date_created; ?>" style="padding:5px;"/>
                                            </td>
                                        </tr>
                                        <tr>

                                            <td>
                                                Excluded:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                            <td>
                                                <input type="checkbox" name="excluded" size="40" value="<?php echo $excluded; ?>" <?php if ($excluded != 0) echo "checked"; ?> style="padding:5px;"/>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td>&nbsp;</td>	
                                            <td><input type="hidden" value="hidden"/>
                                            </td>
                                        </tr>
                                        <tr> 
                                            <td>&nbsp;</td>	
                                            <td><input type="hidden" name="id" value="<?php echo $id; ?>" />
                                            </td>
                                        </tr>
                                        <tr align="center">
                                            <td rowspan="5" colspan="5" align="center">
                                                <button  href="javascript:()" onclick="document.getElementById('test').submit();" class="butl" title="Saving" rel="1" > submit</button> &nbsp;<input type="button" class="butl" value="Cancel" onclick="window.location.href = '/dealers.php'">
                                            </td>
                                        </tr>

                                    </tbody></table>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
<?php include_once 'template/footer.phtml'; ?> 
</body>


