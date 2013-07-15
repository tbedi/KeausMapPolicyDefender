$(document).ready(function()
            {

                $('#login').click(function()
                {
                    document.getElementById('newpassword').value = '';
                    $('#login_block').show();

                });

               

                // Validate signup form
                $("#form").validate({
                    rules: {
                        email: "required email"
                    }
                });
            });