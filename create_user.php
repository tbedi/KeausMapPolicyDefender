<script language="javascript" type="text/javascript">
    function validate()
    {

        var formName = document.frm;
        
        if (formName.newpassword.value == "")
        {
            document.getElementById("newpassword_label").innerHTML = 'Please Enter New Password';
            formName.newpassword.focus();
            return false;
        }
        else
        {
            document.getElementById("newpassword_label").innerHTML = '';
        }


        if (formName.cpassword.value == "")
        {
            document.getElementById("cpassword_label").innerHTML = 'Enter ConfirmPassword';
            formName.cpassword.focus();
            return false;
        }
        else
        {
            document.getElementById("cpassword_label").innerHTML = '';
        }


        if (formName.newpassword.value != formName.cpassword.value)
        {
            document.getElementById("cpassword_label").innerHTML = 'Passwords Missmatch';
            formName.cpassword.focus()
            return false;
        }
        else
        {
            document.getElementById("cpassword_label").innerHTML = '';
        }

    }
</script>
<form name="frm" method="post" action="insert.php" onSubmit="return validate();">
    <table align="center">
        <tbody align="center">
            <tr> 
                <td align="left">
                    Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>	
                <td>
                    <input type="text" class="input" name="username"  size="40"  style="padding:5px;"/>
                </td>
            </tr>
            <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
            <tr>

                <td align="left">
                    Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <input type="password" class="input"  name="newpassword" id="newpassword"  size="40"  style="padding:5px;"/><label id= "newpassword_label" ></label>
                </td>
            </tr>
            <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
             <tr>
                 <td align="left">
                    Confirm Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <input type="password" class="input"  name="cpassword" id="cpassword"  size="40"  style="padding:5px;"/><label id="cpassword_label" ></label>
                </td>
             </tr>
             <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
            
            <tr>
                <td align="left">
                    Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <input type="text" class="input"  name="email"  size="40"  style="padding:5px;" pattern="^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$" placeholder="abd_d@example.com" required />
                </td>
            </tr>
            <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
            <tr>

                <td align="left">
                    Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    <input type="text" class="input"  name="name"  size="40"  style="padding:5px;"/>
                </td>
            </tr>
            <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
            <tr>
                <td align="left">
                    Role:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>       
                <td>
                <input type="text" class="input"  name="role" value="Operations"  size="40"  style="padding:5px;"/>
                    </td>
            </tr>
            <tr> 
                <td>&nbsp;</td>	
                <td><input type="hidden" value="hidden"/>
                </td>
            </tr>
            <tr>
                <td rowspan="2" colspan="2" align="center"><input type="submit" class="butl" name="submit" value="Apply" /></td>
            </tr>

        </tbody></table>
</form>





