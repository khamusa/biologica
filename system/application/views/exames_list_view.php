<script language="javascript">	

	$(document).ready(
		function(){    
		$('.maskme').setMask({attr: 'alt'});
		}
	) 
	
	function maisexame(id) {
var html = new Array();
	html.push('<form id="formexame" class="form_add_exame" method="post" action="<?=base_url();?>index.php/exames/registraexame/" enctype="multipart/form-data">');
	html.push('<input name="titulo" type="text" class="logininput" id="titulo" size="10" value="T&iacute;tulo" onFocus="if(this.value == \'T&iacute;tulo\') this.value=\'\'; return true;"/>');
	html.push('<input name="data" type="text" class="maskme" id="data" size="10" value="<?=date('d/m/Y');?>" onFocus="if(this.value == \'<?=date('d/m/Y');?>\') this.value=\'\'; return true;" alt="date"/>');
	html.push('<label for="arquivo"> Arquivo:</label>');
	html.push('<input name="arquivo" type="file" class="logininput" id="arquivo" size="10" />');
	html.push('<label> Selo: </label>');
	html.push('<input name="selo" type="file" class="logininput" id="arquivo" size="10" /><br>');
	html.push('<input type="hidden" name="userid" value="'+id+'" />');
	html.push('</form>');
	var htmlfinal = html.join('\r');
	$('#cadastraexame').before(htmlfinal);
	$('.maskme').setMask({attr: 'alt'});
		
		
}
<? //hide JS functions from non-admin
	if ($admin) { ?>
function removeExame(id) {
	if(confirm("Tem certeza que deseja apagar este exame?")) {
		$.getJSON("<?=site_url();?>/exames/removeExame/"+id,
        function(data){
          if(data.success=='yes') {
		  	$('#linha_exame_'+id).fadeOut().remove();
			mensagem(data.message, 1);
		  } else {
		  	mensagem('Erro: '+data.message, 3);
		  }
        });

	}
}

function do_upload_selo(id) {
// prevents chrome from uploading if there's no file
	if(!$('#upload_selo_'+id+' .file_hidden').val()){
		return false;
	}
	$('#upload_selo_'+id+' .selo_upload_txt').html($(loader).clone());
	$('#upload_selo_'+id).ajaxSubmit({
						success: function(data) { 
								if(data.success == 'yes') {
									var html = '<a href="'+BASE_URL+'index.php/exames/downloadexame/'+id+'/1" target="downloadexames"><img src="'+BASE_URL+'/images/template/confirm.gif" border="0" /></a>';
									$('#upload_selo_'+id).parent().fadeOut('slow').html(html).fadeIn('slow');
									mensagem(data.message, 1);									
								} else {
									mensagem('Erro:'+data.message, 3);
								}				
													
												},
									iframe:true,
									type: "POST",
									dataType: 'json'
												});
}
function enviaforms() {
	
	$('#formsdiv form:first').ajaxSubmit({
						success: function(data) { 
								if(data.success == 'yes') {
									$('#formsdiv form:first').fadeOut('fast').remove();
									mensagem(data.message, 1);
									//adiciona à lista
									var html = new Array();
									
									html.push('<div class="users_list_exams_list_div" id="linha_exame_'+data.eid+'" style="display:none;">');
									html.push('<table border="0" cellpadding="0" cellspacing="0">');
									html.push('<tbody>');
									html.push('<tr>');
									html.push('<td width="140"><small><a href="'+BASE_URL+'index.php/exames/downloadexame/'+data.eid+'" target="downloadexames">'+data.data_exame_formatada+'</a></small></td>');
									html.push('<td class="spacer">&nbsp;</td>');
									html.push('<td width="*" style="text-align:left; padding-left: 5px;"><small><a href="'+BASE_URL+'index.php/exames/downloadexame/'+data.eid+'" target="downloadexames">');
									html.push('<sup>Novo!&nbsp;</sup>');
									html.push(data.titulo+'</a></small></td>');
									html.push('<td class="spacer">&nbsp;</td>');
									html.push('<td width="105"><div class="exam_column_selo">');
									if (data.selo) { 
									html.push('<a href="'+BASE_URL+'index.php/exames/downloadexame/'+data.eid+'/1" target="downloadexames"><img src="'+BASE_URL+'/images/template/confirm.gif" border="0" /></a>');
									} else {
									<? if($admin) { ?>
									html.push('<form id="upload_selo_'+data.eid+'" name="upload_selo" enctype="multipart/form-data" method="post" action="'+BASE_URL+'index.php/exames/upload_selo/">');
									html.push('<div class="fake_file_input"><input type="file" name="selo" class="file_hidden" size="1" onchange="do_upload_selo('+data.eid+')"><input type="hidden" name="eid" value="'+data.eid+'"></div>');
									html.push('<span class="selo_upload_txt">Enviar</span>');
									html.push('</form>');
									<? } else {?>
									html.push('<img src="'+BASE_URL+'/images/template/deny.gif" border="0" />');
									<? } ?>
									}
									html.push('</div></td>');
									<? if($userpriv > 50) { ?>
									html.push('<td class="spacer">&nbsp;</td> ');                    
									html.push('<td width="105"><a href="javascript:void(0)" onclick="removeExame('+data.eid+')"><img src="'+BASE_URL+'/images/template/deny.gif" border="0"/></a></td>');
									<? } ?>
									html.push('</tr>');
									html.push('</tbody>');
									html.push('</table>');
									html.push('</div>');
									$('#exames_header').after(html.join(''));
            						$('#linha_exame_'+data.eid).slideDown('slow');
            
									enviaforms();	
								} else {
									mensagem('Erro:'+data.message+' <br> Envio cancelado!', 3);
								}				
													
												},
									iframe:true,
									type: "POST",
									dataType: 'json'
												}).append(loader);
}
<? } ?>
function downloadexame(id, n) {
	if(!n)
	n = 0;
	$("#iframe_download").src = BASE_URL+"index.php/exames/downloadexame/"+id+"/"+n;
}

function filtra_exame() {
	var filtro = $('#filtro_exame').val();
	$('.users_list_exams_list_div').not('#exames_header').each(function(e) {
									 parametro = new RegExp(filtro, "gi");
									 
									 if($(this).text().match(parametro)) {
										 $(this).show();
									 } else {
										 $(this).hide();
									 }
									 });
	//parametro = new RegExp(filtro_amigos["nome"], "gi");
		//if(!(amigos[userid]["display_name"].match(parametro)))

}
</script>
 <? if($admin == 1) { ?>
<div style="font-size:0.8em; float:right;"><a href="javascript:history.go(-1)"><< Voltar para Lista de Usuários </a></div>
<? } ?><? 
// você tem que ser admin e ao mesmo tempo não estar SE editando e, alem disso o usuário em questão não deve ser admin mas cliente
if(($admin)&&($uid != $logged)&&($row->priv < 50)) { 

?>

<div id="content" style="overflow:auto;">
<h3>Adicionar Resultado de Exames</h3>
<div id="formsdiv">
Adicionar Exame: <a href="javascript:void(0)" onclick="maisexame(<?=$uid;?>)">[+]</a><br />
                        
                            <form id='formexame' class="form_add_exame" method="post" action="<?=base_url();?>index.php/exames/registraexame/" enctype="multipart/form-data">
                            <input name="titulo" type="text" class="logininput" id="titulo" size="10" value="T&iacute;tulo" onFocus="if(this.value == 'T&iacute;tulo') this.value=''; return true;"/>
                            <input name="data" type="text" class="maskme" id="data" size="10" value="<?=date('d/m/Y');?>" onFocus="if(this.value == '<?=date('d/m/Y');?>') this.value=''; return true;" alt="date"/>
                            <label for="arquivo"> Arquivo:</label>
                            <input name="arquivo" type="file" class="logininput" id="arquivo" size="10" />
                            <label> Selo: </label>
                            <input name="selo" type="file" class="logininput" id="selo" size="10" /><input type="hidden" name="userid" value="<?=$uid;?>" />
                            <br />
                            </form>
                            <a href="javascript:void(0)" id="cadastraexame" onclick="enviaforms()"/>Enviar </a>
</div>
</div>
<?  } ?>
<div id="contentextended" style="padding-left: 0px; padding-right:0px; width:949px;"><div class="userdivs">
 <iframe name="downloadexames" id="iframe_download" style="display:none"></iframe>
 
<? 
// current exams should be displayed if:
//  you're an admin and are not editing yourself
//									 | you'r not an admin and are seeing yourself
//and anyway the user 
if(($row->priv < 50)) { 

?>

<div class="userdivs_header"><a href="javascript:void(0)" class="userdivs_header_off userdivs_header_on">Resultados de Exames</a></div>
<div class="userdivs_accordeon" id="listaexames">
<div style="text-align:center">Busca:
       <input type='text' name='filtro' id='filtro_exame' onkeyup='filtra_exame()'/></div>
<br />
        
    <? if($query->num_rows() > 0)
            { ?>   
            <div class="exames_lista_curta">
        <div class="users_list_exams_list_div" id="exames_header">
        <table border="0" cellpadding="0" cellspacing="3">
          <tbody>
            <tr>
              <th width="20%"><small>Data</small></th>
              <? if($admin) { ?>
              <th width="50%"><small>Título</small></th>
              <th width="15%"><small>Selo <small>(opcional)</small></small></th>
               <th width="15%"><small>Apagar</small></th>
               <? } else { ?>
               <th width="60%"><small>Título</small></th>
              <th width="20%"><small>Selo <small>(opcional)</small></small></th>
               <? } ?>
            </tr>
           </tbody>
           </table>
           </div>            
                <? foreach($query->result() as $row): ?>
                	<div class="users_list_exams_list_div" id="linha_exame_<?=$row->id?>">
            <table border="0" cellpadding="0" cellspacing="0">
          	<tbody>
            	<tr>
              <td width="140"><small><a href='<?=base_url();?>index.php/exames/downloadexame/<?=$row->id;?>' target="downloadexames"><?=date("d/m/Y",$row->data_exame);?></a></small></td>
              <td class="spacer">&nbsp;</td>
              <td width="*" style="text-align:left; padding-left: 5px;"><small><a href='<?=base_url();?>index.php/exames/downloadexame/<?=$row->id;?>' target="downloadexames"><? if($row->new) { echo("<sup>Novo!&nbsp;</sup>");} ?><?=$row->titulo?></a></small></td>
              <td class="spacer">&nbsp;</td>
              <td width="105"><div class="exam_column_selo">
			  		<? if ($row->selo) { 
							echo("<a href='".base_url()."index.php/exames/downloadexame/".$row->id."/1' target='downloadexames'><img src='".base_url()."/images/template/confirm.gif' border='0' /></a>");
					} else if($admin) {
							echo("<form id='upload_selo_".$row->id."' name='upload_selo' enctype='multipart/form-data' method='post' action='".base_url()."index.php/exames/upload_selo/'>");
						echo("<div class='fake_file_input'><input type='file' name='selo' class='file_hidden' size='1' onchange='do_upload_selo(".$row->id.")'><input type='hidden' name='eid' value='".$row->id."'></div>");
							echo("<span class='selo_upload_txt'>Enviar</span>");
						echo('</form>');
							} else {
							echo("<img src='".base_url()."/images/template/deny.gif' border='0' />");
							}; ?></div></td>
                      <? if($userpriv > 50) { ?>
                      <td class="spacer">&nbsp;</td>                     
               <td width="105"><a href="javascript:void(0)" onclick="removeExame(<?=$row->id?>)"><img src="<?=base_url();?>/images/template/deny.gif" border="0"/></a></td>
               <? } ?>
               </tr>
               </tbody>
               </table>
               </div>
   
                <? endforeach; ?>
                
              </div>  
         <? } else {?>
       <div class="userdivs_accordeon"> N&atilde;o h&aacute; resultados de exames dispon&iacute;veis!</div>
         <? } ?>
	<? } ?>
         </div>
