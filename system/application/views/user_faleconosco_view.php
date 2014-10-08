<? if(!$admin) { ?>
<script type="text/javascript">
$(document).ready(function() {
	$('body').append('<div id="temp" style="display:none"></div>');
});


dados_contatos = new Array();
function atualizacontatos() {
	if($('#quickcontato').val() == -1) {
		var text = "Cliente: <?=$row->uid;?> - <?=$row->dados_nome;?> (<?=$row->username;?>)";
		$('#temp').html(text);
		$('#nomecontato').val($('#temp').html());
		$('#emailcontato').val('<?=$row->email;?>');
		$('#telefonecontato').val('');
	} else if($('#quickcontato').val() == -2) {
		$('#nomecontato').val('');
		$('#emailcontato').val('');
		$('#telefonecontato').val('');
	} else {
		var text = dados_contatos[$('#quickcontato').val()]['responsavel'];
		$('#temp').html(text);
		$('#nomecontato').val($('#temp').html());
		var text = dados_contatos[$('#quickcontato').val()]['email'];
		$('#temp').html(text);
		$('#emailcontato').val($('#temp').html());
		$('#telefonecontato').val(dados_contatos[$('#quickcontato').val()]['telefone']);
	}
}

</script>
<div class="userdivs">
  <div class="userdivs_header"><a href="javascript:void(0)" class="userdivs_header_off">Central de Relacionamento com o Cliente</a></div>
  <div class="userdivs_accordeon" style="display:none">
  <p>Por favor, entre em contato através do nosso Centro de Relacionamentos com o Cliente. Escreva diretamente para comercial@biologicalab.com.br ou utilize o formulário abaixo.</p>
    <div id="mensagem" style="display:block"></div>
    <div id="formulario">
      <?=form_open('user/sendmail',array('id' => 'sendmail'));?>
      <input type="hidden" name="empresa" value="#<?=$row->uid;?> - <?=$row->dados_nome;?> (<?=$row->username;?>)" />
      <input type="hidden" name="empresa_id" value="<?=$row->uid;?>" />
      <table cellpadding="0" cellspacing="0" border="0">
        <tbody>
        <? 
		$contatos = json_decode($row->dados_contatos, true);
		if(is_array($contatos)): ?>
        <tr>
            <td><p>Contato:&nbsp;&nbsp;</p></td>
            <td><select name="quickcontato" id="quickcontato" onchange="atualizacontatos()">
			<option value="-2" selected="selected">Nenhum</option>
            <option value="-1"><?=$row->email;?></option>
            
             <? $i = 0;
			 $script = "";
			 foreach($contatos as $contato):
				 $script .= "dados_contatos[".$i."] = new Array();";
				 $script .= "dados_contatos[".$i."]['responsavel'] = '".$contato['responsavel']."';";
				 $script .= "dados_contatos[".$i."]['email'] = '".$contato['email']."';";
				 $script .= "dados_contatos[".$i."]['telefone'] = '".$contato['telefone']."';";
			 ?>
            	
      <option value="<?=$i?>">
    <? if(isset($contato['responsavel'])) echo($contato['responsavel']);?>
	<? if(isset($contato['telefone'])) echo(" - ".$contato['telefone']);?>
   <? if(isset($contato['email'])) echo(" - ".$contato['email']);?>
   </option>
      <? $i++; ?>
      <? endforeach; ?>
      </select>
      <? echo('<script type="text/javascript">'.$script.'</script>');?>
            </td>
          </tr>
          <? endif; ?>
          <tr>
            <td><p>Seu Nome:&nbsp;&nbsp;</p></td>
            <td><input class="texto" type="text" name="nome" id="nomecontato" style="width:250px"/>
            </td>
          </tr>
          <tr>
            <td><p>E-mail de contato:&nbsp;&nbsp;</p></td>
            <td><input class="texto" type="text" name="email" id="emailcontato" style="width:250px"/>
            </td>
          </tr>
          <tr>
            <td><p>Telefone:&nbsp;&nbsp;</p></td>
            <td><input class="texto" type="text" name="telefone" id="telefonecontato" style="width:250px" />
            </td>
          </tr>
          <tr>
            <td><p>Assunto:&nbsp;&nbsp;</p></td>
            <td><select name="assunto"><option value="Reclamações">Reclamações</option><option value="Sugestões">Sugestões</option><option value="Dúvidas">Dúvidas</option><option value="Informações">Informações</option></select>
            </td>
          </tr>
          <tr>
            <td><p>Mensagem:&nbsp;&nbsp;</p></td>
            <td><textarea name="mensagem" id="mensagem" style="width:250px" rows="2"></textarea>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="submit" value="Enviar" />
            </td>
          </tr>
        </tbody>
      </table>
      </form>
    </div>
    <script language="javascript">

function updateEnvio(data) {
$('#mensagem').html('');
$('#mensagem').hide();
$("#formulario").fadeOut("slow",
    function() {
         if (data.success == 'yes') {
            $('#mensagem').html(data.message).fadeIn("slow").fadeOut("slow", function() { $("#formulario").fadeIn("slow"); });    
        }
        if (data.success == 'no') {
            $('#mensagem').html(data.message).fadeIn("slow").fadeOut("slow",
                function() {
                   $("#mensagem").fadeIn("slow");
                }
            );    
        }
    }
);
} 



$(document).ready(
function(){
    $("#sendmail").ajaxForm({
        type: "POST",
        url: "<?=base_url()?>/index.php/clientes/enviamail/1",
        dataType: "json",
        data: $("#sendmail").serialize(),
        success: updateEnvio
    });
}
) 

</script>
    
  </div>
</div>
<? } ?>
</div>