<div id="contentextended"><h1>Adicionar Usuário</h1>
  <? 
function showOption($privOption, $userpriv, $privnames) {
	if($userpriv > $privOption) {
		
		$title = $privnames[$privOption];
		echo('<option value='.$privOption.'>'.$title.'</option>');
		
	}
}
?>
  <script language="javascript">
var contatos = 1;
function adicionacontato() {
	contatos++;
	var html = new Array();
	html.push('<div class="contato"> <p>Contato #'+contatos);
	html.push('</p><p>');
	html.push('<label>Respons&aacute;vel: </label>');
	html.push('<br />');
	html.push('<input name="dados_c_responsavel[]" type="text" class="logininput" size="30" />');
	html.push('</p>');
	html.push('<p>');
	html.push('<label>Cargo </label>');
	html.push('<br />');
	html.push('<input name="dados_c_cargo[]" type="text" class="logininput" size="30" />');
	html.push('</p>');
	html.push('<p>');
	html.push('<label>Email </label>');
	html.push('<br />');
	html.push('<input name="dados_c_email[]" type="text" class="logininput" size="30" />');
	html.push('</p>');
	html.push('<p>');
	html.push('<label>Telefone </label>');
	html.push('<br />');
	html.push('<input name="dados_c_telefone[]" type="text" size="30" class="maskme" alt="phone"/>');
	html.push('</p>');
	html.push('</div>');
	var htmlfinal = html.join('');
	
	$('#edit_contatos').append(htmlfinal);
	$('.maskme').setMask({attr: 'alt'});
}

function addUser(data) {
    $('#logged').html('');
    $('#logged').hide();
    $("#loginform").fadeOut("slow",
        function() {
		
             if (data.success == 'yes') {
			 mensagem('Usu&aacute;rio Adicionado!', 1);
             
										$("#loginform").fadeIn("slow");
										$('#priv').val(1);
										$('#contatosdaempresa div.contato').remove();
										$('#useravailable').val('').fadeOut('slow');
										contatos = 1;
										$('#userlogin *').attr("disabled", false);                  
            }
            if (data.success == 'no') {
				 mensagem(data.message);
				 $('#userlogin *').attr("disabled", false);      
                 $("#loginform").fadeIn("slow"); 
            }
        }
    );
    
} 

function disableForm() {
	$('#userlogin *').attr("disabled", true); 
}
$(document).ready(
    function(){    
	// masks for input fields
	$('.maskme').setMask({attr: 'alt'});
	
        $("#userlogin").ajaxForm({
            type: "POST",
            url: "<?=base_url();?>index.php/user/register",
            dataType: "json",
            success: addUser,
			resetForm: true,
			beforeSubmit: disableForm
        });
    
    }
) 


function validateUsername() {
	valor = $("#username").val();
	$.getJSON(BASE_URL+"index.php/user/checkusernameavailable/"+valor,
        function(data){
         if(data.available == "yes") {
		 	if(data.username == valor) {
				$("#useravailable").html(' Dispon&iacute;vel');
				$("#useravailable").css('color', '#00BB00');
			}
		 } else if(data.available == "no") {
		 	if(data.username == valor) {
			 $("#useravailable").html(' Não Disponível!');
			 $("#useravailable").css('color', '#BB0000');
			 }
		 
		 }
        });

}
</script>
  <?=form_open('user/register',array('id' => 'userlogin'));?>
  <div id="useraddmodulediv" class="moduledivcontent">
    <div id="loginform">
      <div id="camposnecessarios" class="useraddbox" style="border: 1px solid #999999;">
        <p>
          <label for="username"><strong>Nome de usu&aacute;rio *:</strong></label>
          <br />
          <input name="username" type="text" class="logininput" id="username" size="30" onkeyUp='validateUsername()'/>
          <span id="useravailable"></span> </p>
        <p>
          <label for="email"><strong>Email *:</strong></label>
          <br />
          <input name="email" type="text" class="logininput" id="email" size="30" />
        </p>
        <p>
        	 <a href='javascript:gera_senha()'>Gerar senha</a><br />
          <label for="password"><strong>Senha *:</strong></label>
          <br />
          <input name="password" type="password" class="logininput" id="password" size="30" />
         
        </p>
        <p>
          <label for="confpassword"><strong>Confirme a Senha *:</strong></label>
          <br />
          <input name="confpassword" type="password" class="logininput" id="confpassword" size="30" />
          <br /><span id="senhagerada"></span>
        </p>
        <p>
          <label for="priv"><strong>Tipo de Usuário *:</strong> </label>
          <select name="priv" class="logininput" id="priv">
            <? //showOption(1, $priv, 'Usu&aacute;rios Abertos'); ?>
            <? showOption(1, $priv, $privnames); ?>
            <? //showOption(2, $priv, '&nbsp;&nbsp;|- Redator'); ?>
            <? //showOption(3, $priv, '&nbsp;&nbsp;|- Editor'); ?>
            <? //showOption(50, $priv, 'Administra&ccedil;&atilde;o'); ?>
            <? showOption(50, $priv, $privnames); ?>
            <? showOption(60, $priv, $privnames); ?>
            <? //showOption(90, $priv, '&nbsp;&nbsp;|- Super Administrador'); ?>
          </select>
        </p><br />
        <p>
          <input class="loginsubmit" type="submit" value="Cadastrar" />
        </p>
      </div>
      <div id="dadosdaempresa" class="useraddbox">
        <p>
          <label for="dados_nome">Nome da Empresa:</label>
          <br />
          <input name="dados_nome" type="text" id="dados_cnpj" size="30"/>
        </p>
        <p>
          <label for="dados_cnpj">CNPJ:</label>
          <br />
          <input name="dados_cnpj" type="text" id="dados_cnpj" size="30" alt="cnpj" class="maskme"/>
        </p>
        <br />
        <p id="edit_endereco">-- Endere&ccedil;o -- </p>
        <p>
          <label for="UF">UF: </label>
          &nbsp;
          <select name="uf" class="logininput" id="uf">
            <option value="AL">AL</option>
            <option value="BA">BA</option>
            <option value="CE">CE</option>
            <option value="DF">DF</option>
            <option value="ES">ES</option>
            <option value="GO">GO</option>
            <option value="MG" selected="selected">MG</option>
            <option value="MS">MS</option>
            <option value="MT">MT</option>
            <option value="PB">PB</option>
            <option value="PE">PE</option>
            <option value="PR">PR</option>
            <option value="RJ">RJ</option>
            <option value="RN">RN</option>
            <option value="SC">SC</option>
            <option value="SE">SE</option>
            <option value="SP">SP</option>
            <option value="TO">TO</option>
          </select>
        </p>
        <p>
          <label for="cidade">Cidade:</label>
          <br />
          <input name="cidade" type="text" class="logininput" id="cidade" size="30" />
        </p>
        <p>
          <label for="logradouro">Logradouro:</label>
          <br />
          <input name="logradouro" type="text" class="logininput" id="logradouro" size="30" />
        </p>
        <p>
          <label for="numero">N&uacute;mero:</label>
          <br />
          <input name="numero" type="text" class="logininput" id="numero" size="30" />
        </p>
        <p>
          <label for="complemento">Complemento:</label>
          <br />
          <input name="complemento" type="text" class="logininput" id="complemento" size="30" />
        </p>
        <p>
          <label for="bairro">Bairro:</label>
          <br />
          <input name="bairro" type="text" class="logininput" id="bairro" size="30" />
        </p>
        <p>
          <label for="CEP">CEP:</label>
          <br />
          <input name="cep" type="text" id="cep" size="30" class="maskme" alt="cep"/>
        </p>
      </div>
      <div id="contatosdaempresa" class="useraddbox">
        <div id="edit_contatos">
          <p>Contato Principal <a href="javascript:void(0)" onclick="adicionacontato()">[+]</a></p>
          <p>
          <p>
            <label>Respons&aacute;vel: </label>
            <br />
            <input name="dados_c_responsavel[]" type="text" class="logininput" size="30" />
          </p>
          <p>
            <label>Cargo </label>
            <br />
            <input name="dados_c_cargo[]" type="text" class="logininput" size="30" />
          </p>
          <p>
            <label>Email </label>
            <br />
            <input name="dados_c_email[]" type="text" class="logininput" size="30" />
          </p>
          <p>
            <label>Telefone </label>
            <br />
            <input name="dados_c_telefone[]" type="text" size="30" class="maskme" alt="phone"/>
          </p>
          </p>
        </div>
      </div>
      <?=form_close();?>
    </div>
  </div>
</div>
