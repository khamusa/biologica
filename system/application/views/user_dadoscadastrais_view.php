        <div id="logged"></div>
        <div id="loginerror"></div>
        <div id="dadosform">
         <?=form_open('user/change',array('id' => 'userpassword'));?>
         <input 
         <?=form_close();?>
         </div>
        <div id="loginform">
        <p>Altera&ccedil;&atilde;o de Senha de Acesso</p>
   		  <?=form_open('user/change',array('id' => 'userpassword'));?>
                    
          <label for="password">Senha atual:</label><br />
                    <input class="logininput" type="password" name="atualpassword" id="atualpassword" /><br />
          <label for="password">Nova Senha:</label><br />
                    <input class="logininput" type="password" name="password" id="password" /><br />
          <label for="confpassword">Confirme a Nova Senha:</label><br />
                    <input class="logininput" type="password" name="confpassword" id="confpassword" />   <br />      
                    <input class="loginsubmit" type="submit" value="Alterar" />
          <?=form_close();?>
       </div>

<script language="javascript">
function updateLogin(data) {
$('#logged').html('');
$('#logged').hide();
$("#loginform").fadeOut("slow",
    function() {
         if (data.success == 'yes') {
            $('#logged').html(data.welcome).fadeIn("slow");    
        }
        if (data.success == 'no') {
            $('#loginerror').html(data.message).fadeIn("slow").fadeOut("slow",
                function() {
                    $("#loginform").fadeIn("slow");
                }
            );    
        }
    }
);

} 

$(document).ready(
function(){
    $("#userpassword").ajaxForm({
        type: "POST",
        url: BASE_URL+"index.php/user/changepassword",
        dataType: "json",
        data: "atualpassword="+$("#atualpassword").val()+"confpassword="+$("#confpassword").val()+"&password;="+$("#password").val(),
        success: updateLogin
    });

}
) 
</script>
