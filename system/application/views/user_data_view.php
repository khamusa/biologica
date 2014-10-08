<script language="javascript">
<? if($userpriv > 50) { ?>
	function removeuser(id) {
		if(confirm("Tem certeza que deseja apagar completamente este usuário? Esta operação não poderá ser desfeita! ")) {
			window.location = "<?=site_url();?>/user/removeUser/"+id;
		}
	}
	<? } ?>

$(document).ready(
		function() { 
		$.editable.addInputType('masked', {
													element : function(settings, original) {
													/* Create an input. Mask it using masked input plugin. Settings */
													/* for mask can be passed with Jeditable settings hash. */
													var input = $('<input alt=\''+settings.masktype+'\' />').setMask({attr: 'alt'});
													$(this).append(input);
													return(input);
													}
												});			
			$('.editable').editable(BASE_URL+'index.php/user/savefield', { 
									 onblur     : 'submit',
									 submit     : 'OK',
									 indicator  : 'Salvando...',
									 tooltip    : 'Clique para editar ...',
									 style      : 'display: inline',
									 submitdata : {"uid": "<?=$row->uid;?>"}
						 			});
			$('.end_editable').editable(BASE_URL+'index.php/user/saveendfield', { 
												 onblur     : 'submit',
												 submit     : 'OK',
												 indicator  : 'Salvando...',
												 tooltip    : 'Clique para editar ...',
												 style      : 'display: inline',
												 submitdata : {"uid": "<?=$row->uid;?>"},
												 width      : 100
												});
			$('.fone_editable').editable(BASE_URL+'index.php/user/savefonefield', { 
												 onblur     : 'submit',
												 submit     : 'OK',
												 indicator  : 'Salvando...',
												 tooltip    : 'Clique para editar ...',
												 style      : 'display: inline',
												 submitdata : {"uid": "<?=$row->uid;?>"},
												 width      : 100
												});		
			$('.phone_editable').editable(BASE_URL+'index.php/user/savefonefield', { 
												 onblur     : 'submit',
												 type       : 'masked',
												 masktype   : 'phone',
												 submit     : 'OK',
												 indicator  : 'Salvando...',
												 tooltip    : 'Clique para editar ...',
												 style      : 'display: inline',
												 submitdata : {"uid": "<?=$row->uid;?>"},
												 width      : 100
												});		
			$('.cnpj_editable').editable(BASE_URL+'index.php/user/savefield', { 
									 onblur     : 'submit',
									 type       : 'masked',
									 masktype   : 'cnpj',
									 submit     : 'OK',
									 indicator  : 'Salvando...',
									 tooltip    : 'Clique para editar ...',
									 style      : 'display: inline',
									 submitdata : {"uid": "<?=$row->uid;?>"},
									 width      : 100
									});		
									
			$('.userdivs_header').each(function(e) {
													$(this).bind('click', function(e) { 
																			$(this).next('div').toggle('slow');
																				$(this).children('a:first').toggleClass('userdivs_header_on');
																					});
													});									
													
		}
);


function removecontato(id) {
	$.getJSON(BASE_URL+"index.php/user/removecontato/<?=$row->uid;?>/"+id,
        function(data){
         	if(data.success == 'yes') {
				mensagem('Dados de contato removidos!', 1);
				$('#contato_'+id).fadeOut('slow');
				} else {
				
				}
        });

}

var contatos = 0;
function adicionacontato() {
	var html = new Array();

	html.push('<div id="contato_'+contatos+'">');
	html.push('<div class="userdatadiv_divisoria"> Contato #'+(contatos*1+1*1));
	html.push(' - <a href="javascript:void(0)" onclick="removecontato('+contatos+')">Remover</a>');
	html.push('</div>');
	
	html.push('<div class="userdatadiv_each_small">Cargo: ');
	html.push('<b class="fone_editable" title="Clique para Editar" id="'+contatos+'_cargo"></b></div>');
	
	html.push('<div class="userdatadiv_each_small">Nome: ');
	html.push('<b class="fone_editable" title="Clique para Editar" id="'+contatos+'_responsavel"></b></div>');
	
	html.push('<div class="userdatadiv_each">Telefone: ');
	html.push('<b class="phone_editable" alt="phone" title="Clique para Editar" id="'+contatos+'_telefone"></b></div>');
	
	html.push('<div class="userdatadiv_each">E-mail: ');
	html.push('<b class="fone_editable" title="Clique para Editar" id="'+contatos+'_email"></b></div></div>');
	
	var htmlfinal = html.join('');
	contatos++;
	
	$('#contatos').append(htmlfinal);
	$('.fone_editable').editable(BASE_URL+'index.php/user/savefonefield', { 
												 onblur     : 'submit',
												 submit     : 'OK',
												 indicator  : 'Salvando...',
												 tooltip    : 'Clique para editar ...',
												 style      : 'display: inline',
												 submitdata : {"uid": "<?=$row->uid;?>"},
												 width      : 100
												});		
			$('.phone_editable').editable(BASE_URL+'index.php/user/savefonefield', { 
												 onblur     : 'submit',
												 type       : 'masked',
												 masktype   : 'phone',
												 submit     : 'OK',
												 indicator  : 'Salvando...',
												 tooltip    : 'Clique para editar ...',
												 style      : 'display: inline',
												 submitdata : {"uid": "<?=$row->uid;?>"},
												 width      : 100
												});			
}
</script>
<? 
function showOption($privOption, $userpriv, $privnames, $selected) {
	if($userpriv > $privOption) {
		
		$title = $privnames[$privOption];
		if($selected == $privOption)
		echo('<option value='.$privOption.' selected="selected">'.$title.'</option>');
		else
		echo('<option value='.$privOption.'>'.$title.'</option>');
		
	}
}

$end = json_decode($row->dados_endereco);
$contatos = json_decode($row->dados_contatos, true);
$emailprincipal = $row->email;
?>

<div class="userdivs">
  <div class="userdivs_header"><a href="javascript:void(0)" class="userdivs_header_off <? if($admin == 1) { echo("userdivs_header_on"); } ?>"><? if($row->uid == $logged) { ?>Meus Dados<? } else { ?>Dados do usuário<? } ?></a></div>
  <div class="userdivs_accordeon" style=" <? if($admin == 1) { echo("display:block;"); } else { echo("display:none");} ?>">
    <div class="userdatadiv_divisoria">Informações Básicas</div>
    <div>
    <? if($admin) { ?>
    <div class="userdatadiv_each_small">Nome da Empresa: <b class='editable' title='Clique para Editar' id="dados_nome"><?=$row->dados_nome;?></b></div>
    <div class="userdatadiv_each_small">CNPJ: <b class='cnpj_editable' title='Clique para Editar' id="dados_cnpj"><?=trim($row->dados_cnpj);?></b></div>
    <div class="userdatadiv_each_small">Id do usu&aacute;rio: <b><?=$row->uid;?></b></div>
    <div class="userdatadiv_each_small">Nome de Usu&aacute;rio: <b><?=$row->username;?></b></div>
    <div class="userdatadiv_each">Privil&eacute;gios do Usu&aacute;rio: <b><?=$privnames[$row->priv];?></b></div>
    <div class="userdatadiv_each">Email Principal: <b class='editable' title='Clique para Editar' id="email"><?=$row->email;?></b></div>
      <? if($userpriv > 50) { ?>
       <div class="userdatadiv_each">&nbsp;</div>
       <div class="userdatadiv_each"><a href="javascript:removeuser('<?=$row->uid;?>')">Apagar Este Usuário</a></div>
       <? } ?>
    <? } else { ?>
    <div class="userdatadiv_each">Email Principal: 
      <b class='editable' title='Clique para Editar' id="email"><?=$row->email;?></b>
    </div>
    <div class="userdatadiv_each">Nome da Empresa: <b>
      <?=$row->dados_nome;?>
      </b></div>
    <div class="userdatadiv_each_small">&nbsp;</div>
    <div class="userdatadiv_each_small">CNPJ: <b><?=trim($row->dados_cnpj);?></b></div>
    <? } ?>
    </div>
    <div class="userdatadiv_divisoria">Endere&ccedil;o</div>
    <div>
    <div class="userdatadiv_each">N&uacute;mero: <b class='end_editable' title='Clique para Editar' id='numero'><? if(isset($end->{'numero'})) { echo($end->{'numero'});}?></b></div>
    <div class="userdatadiv_each">Logradouro: <b class='end_editable' title='Clique para Editar' id='logradouro'><? if(isset($end->{'logradouro'})) { echo($end->{'logradouro'});}?></b> </div>
    <div class="userdatadiv_each_small">Bairro: <b class='end_editable' title='Clique para Editar' id='bairro'><? if(isset($end->{'bairro'})) { echo($end->{'bairro'});}?></b> </div>
    <div class="userdatadiv_each_small">Complemento: <b class='end_editable' title='Clique para Editar' id='complemento'><? if(isset($end->{'complemento'})) { echo($end->{'complemento'});}?></b> </div>
    <div class="userdatadiv_each_small">UF: <b class='end_editable' title='Clique para Editar' id='uf'><? if(isset($end->{'uf'})) { echo($end->{'uf'});}?></b> </div>
    <div class="userdatadiv_each_small">Cidade: <b class='end_editable' title='Clique para Editar' id='cidade'><? if(isset($end->{'cidade'})) { echo($end->{'cidade'});}?></b></div>
    <div class="userdatadiv_each_small">&nbsp; </div>
    <div class="userdatadiv_each_small">CEP: <b class='end_editable' title='Clique para Editar' id='cep'><? if(isset($end->{'cep'})) { echo($end->{'cep'});}?></b> </div>
      </div>
    <div id="contatos">
      <? $i = 0;
		
		if(is_array($contatos)): ?>
      <? foreach($contatos as $contato):?>
      <div id="contato_<?=$i;?>">
        <div class="userdatadiv_divisoria">Contato #
          <?=($i+1);?>
          - <a href="javascript:void(0)" onclick="removecontato(<?=($i);?>)">Remover</a></div>
          
		 <div class="userdatadiv_each_small">Cargo: <b class='fone_editable' title='Clique para Editar' id='<?=$i;?>_cargo'><? if(isset($contato['cargo'])) echo($contato['cargo']);?></b> </div>
        <div class="userdatadiv_each_small">Nome: <b class='fone_editable' title='Clique para Editar' id='<?=$i;?>_responsavel'><?  if(isset($contato['responsavel'])) echo($contato['responsavel']);?></b> </div>
        <div class="userdatadiv_each">Telefone: <b class='phone_editable' alt="phone" title='Clique para Editar' id='<?=$i;?>_telefone'><? if(isset($contato['telefone'])) echo($contato['telefone']);?></b> </div>
        <div class="userdatadiv_each">E-mail: <b class='fone_editable' title='Clique para Editar' id='<?=$i;?>_email'><? if(isset($contato['email'])) echo($contato['email']);?></b> </div>
      </div>
      <? $i++; ?>
      <? endforeach; ?>
      <script language="javascript"><? echo("contatos = ".$i.";"); ?></script>
      <? endif;?>
    </div>
    <div class="userdatadiv_divisoria"> <a href="javascript:void(0)" onclick="adicionacontato()">Adicionar novo Contato [+]</a> </div>
  </div>
</div>
