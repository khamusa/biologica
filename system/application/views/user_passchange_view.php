<script>
function passChange(data) {
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
        success: passChange
    });

}
) 
</script>
<div class="userdivs">
<div class="userdivs_header"><a href="javascript:void(0)" class="userdivs_header_off">Altera&ccedil;&atilde;o de Senha de Acesso</a></div>
	<div class="userdivs_accordeon" style="display:none">
    <div id="loginerror"></div>
    <div id="logged"></div>
        <div id="loginform">
             <?=form_open('user/change',array('id' => 'userpassword'));?>
               <? if($logged == $uid) { ?>         
              <label for="password">Senha atual:</label><br />
                        <input class="logininput" type="password" name="atualpassword" id="atualpassword" /><br />
               <? } else if($admin == 1) { ?>
              <label for="password">Sua senha de Administrador:</label><br />
                        <input class="logininput" type="password" name="adminpassword" id="adminpassword" /><br /> 
               <? } ?>
              <label for="password">Nova Senha:</label><br />
                        <input class="logininput" type="password" name="password" id="password" /><br />
              <label for="confpassword">Confirme a senha:</label><br />
                        <input class="logininput" type="password" name="confpassword" id="confpassword" />   <br />  
                        <input type="hidden" name="uid" value="<?=$row->uid;?>" />    
                        <input class="loginsubmit" type="submit" value="Alterar" />
              <?=form_close();?>
        </div>
	</div>
    <br />
 <? if($admin == 1) { ?>
<div style="font-size:0.8em; float:right;"><a href="javascript:history.go(-1)"><< Voltar para Lista de Usuários </a></div>
<? } ?>
</div>