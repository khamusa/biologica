
<div id="logged"></div>
<div id="loginform">
<?=form_open('user/checkLogin',array('id' => 'userlogin'));?>
				
                                    <label for="username">Login:</label><br />
                                    <input name="username" type="text" class="logininput" id="username" size="25"  />
<Br />
                                     <label for="password">Senha:</label><br />
                                    <input name="password" type="password" class="logininput" id="password" size="25" />       
                                    <br /><input class="loginsubmit" type="submit" value="Login" />
                                <?=form_close();?></div>
<script language="javascript">
function updateLogin(data) {
    $('#logged').html('');
    $('#logged').hide();
    $("#loginform").fadeOut("slow",
        function() {
             if (data.success == 'yes') {
                $('#logged').html(data.message).fadeIn("slow").fadeOut("slow",
					function() {
						loadDefaultUserSection();

					}
				);    
            }
            if (data.success == 'no') {
                mensagem(data.message);
				$("#loginform").fadeIn('slow');
            }
        }
    );
    
} 

$(document).ready(
    function(){    
        $("#userlogin").ajaxForm({
            type: "POST",
            url: "<?=base_url();?>index.php/user/checkLogin",
            dataType: "json",
            data: "username="+$("#username").val()+"&password="+$("#password").val(),
            success: updateLogin
        });
    
    }
) 
</script>