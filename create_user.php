<script language="javascript" type="text/javascript">
    function validate()
    {

        var formName = document.frm;

        if (formName.newpassword.value == "")
        {
            document.getElementById("newpassword_label").innerHTML = '*Please Enter New Password';
            formName.newpassword.focus();
            return false;
        }
        if (formName.cpassword.value == "")
        {
            document.getElementById("cpassword_label").innerHTML = '*Enter ConfirmPassword';
            formName.cpassword.focus();
            return false;
        }
        else
        {
            document.getElementById("cpassword_label").innerHTML = '';
        }


        if (formName.newpassword.value != formName.cpassword.value)
        {
            document.getElementById("cpassword_label").innerHTML = '*Passwords Missmatch';
            formName.cpassword.focus()
            return false;
        }
        else
        {
            document.getElementById("cpassword_label").innerHTML = '';
        }
        
        re = /^\w+$/;
    if(!re.test(formName.newpassword.value)) {
      alert("Error: Username must contain only letters, numbers and underscores!");
      form.username.focus();
      return false;
    }

    if(formName.newpassword.value != "" && formName.newpassword.value == formName.newpassword.value) {
      if(formName.newpassword.value < 6) {
        alert("Error: Password must contain at least six characters!");
        formName.newpassword.focus();
        return false;
      }
      if(formName.newpassword.value == formName.username.value) {
          document.getElementById("newpassword_label").innerHTML = '*Password must be different from Username!';
        formName.newpassword.focus();
        return false;
      }
      re = /[0-9]/;
      if(!re.test(formName.newpassword.value)) {
          document.getElementById("newpassword_label").innerHTML = '*password must contain at least one number (0-9)!';
       formName.newpassword.focus();
        return false;
      }
      re = /[a-z]/;
      if(!re.test(formName.newpassword.value)) {
          document.getElementById("newpassword_label").innerHTML = '*password must contain at least one lowercase letter (a-z)!';
        formName.newpassword.focus();
        return false;
      }
      re = /[A-Z]/;
      if(!re.test(formName.newpassword.value)) {
          document.getElementById("newpassword_label").innerHTML = '*password must contain at least one uppercase letter (A-Z)!';
        formName.newpassword.focus();
        return false;
      }
    }  
        else
        {
            document.getElementById("newpassword_label").innerHTML = '';
        }
    }
</script>
<div class="formlog1" >
    <form name="frm" method="post" action="insert.php" onSubmit="return validate();">
        <table>
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
                          <td align="left">
                            <select name="role" class="input">
                             <option value="Admin" >Admin</option>
                             <option value="Executive">Executive</option>
                             <option value="Customer Service">Customer Service</option>
                             <option value="Operations">Operations</option>
                             </select>
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

            </table>
    </form>
</div>





