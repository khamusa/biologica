<div id="contentextended">
  <h1>Lista de Usuários</h1>
  <script language="javascript">

$(document).ready(
    function(){  
	$('.maskme').setMask({attr: 'alt'}); 
	$('#filtroadmin').bind('click', function(){ filtrar(); });
	// começa accordions
    }
) 

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

function mostrauser(id) {
	$('#viewmore_'+id+' .exam_column_selo').hide();
	$('#viewmore_'+id).toggle('slow', function() { $('#viewmore_'+id+' .exam_column_selo').fadeIn('fast');});
	
	
	
	
	$('#user_header_'+id).toggleClass('user_list_div_header_on');

}

function do_upload_selo(id) {
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

function maisexame(id) {
var html = new Array();
html.push('<form id="formexame_'+id+'" class="form_add_exame" method="post" action="<?=base_url();?>index.php/exames/registraexame/" enctype="multipart/form-data">');
html.push('<table border="0" cellpadding="0" cellspacing="6" class="user_add_exam"><tbody>');
html.push('<tr>');
html.push('<td><small>Data</small></td>');
html.push('<td><small>Título</small></td>');
html.push('<td><small>Arquivo</small></td>');
html.push('<td><small>Selo <small>(opcional)</small></small></td>');
html.push('</tr>');

html.push('<tr>');
html.push('<td><div><input name="data" type="text" class="maskme" id="data" size="20" value="<?=date('d/m/Y');?>" onFocus="if(this.value == \'<?=date('d/m/Y');?>\') this.value=\'\'; return true;" alt="date"/></div></td>');
html.push('<td><div><input name="titulo" type="text" class="logininput" id="titulo" size="20" value="Título" onFocus="if(this.value == \'T&iacute;tulo\') this.value=\'\'; return true;"/></div></td>');
html.push('<td><div>');
html.push('<input name="arquivo" type="file" class="logininput" id="arquivo'+id+'" size="10"/></div></td>');
html.push('<td><div><input name="selo" type="file" class="logininput" id="selo'+id+'>" size="10"/></div>');
html.push('</td>');
html.push('</tr>');
html.push('</tbody></table><input type="hidden" name="userid" value="'+id+'" /></form>');

	var htmlfinal = html.join('\r');
	$('#cadastraexame_'+id).before(htmlfinal);
	$('.maskme').setMask({attr: 'alt'});
		
}

function enviaforms(id) {
	$('#formsdiv_'+id+' form:first').ajaxSubmit({
						success: function(data) { 
								if(data.success == 'yes') {
									$('#formsdiv_'+id+' form:first').remove();
									mensagem(data.message, 1);
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
									html.push('<td width="105">');
									if (data.selo) { 
									html.push('<a href="'+BASE_URL+'index.php/exames/downloadexame/'+data.eid+'/1" target="downloadexames"><img src="'+BASE_URL+'/images/template/confirm.gif" border="0" /></a>');
									} else {
									<? if($admin) { ?>
									html.push('<div class="exam_column_selo"><form id="upload_selo_'+data.eid+'" name="upload_selo" enctype="multipart/form-data" method="post" action="'+BASE_URL+'index.php/exames/upload_selo/">');
									html.push('<div class="fake_file_input"><input type="file" name="selo" class="file_hidden" size="1" onchange="do_upload_selo('+data.eid+')"><input type="hidden" name="eid" value="'+data.eid+'"></div>');
									html.push('<span class="selo_upload_txt">Enviar</span>');
									html.push('</form></div>');
									<? } else {?>
									html.push('<img src="'+BASE_URL+'/images/template/deny.gif" border="0" />');
									<? } ?>
									}
									html.push('</td>');
									<? if($priv > 50) { ?>
									html.push('<td class="spacer">&nbsp;</td> ');                    
									html.push('<td width="105"><a href="javascript:void(0)" onclick="removeExame('+data.eid+')"><img src="'+BASE_URL+'/images/template/deny.gif" border="0"/></a></td>');
									<? } ?>
									html.push('</tr>');
									html.push('</tbody>');
									html.push('</table>');
									html.push('</div>');
									$('#exames_header_'+id).after(html.join(''));
									$('#linha_exame_'+data.eid).slideDown('slow');
									enviaforms(id);	
								} else {
									mensagem('Erro:'+data.message+' <br> Envio cancelado!', 3);
								}				
													
												},
									iframe:true,
									type: "POST",
									dataType: 'json'
												}).append(loader);
}


function filtrar() {
	var filtro = $('#filtro').val();
	if(!$('#filtroadmin').attr("checked")) {
		$('.admindiv').hide();
		$('.userlistdiv').each(function(e) {
										 parametro = new RegExp(filtro, "gi");
										 
										 if($(this).text().match(parametro)) {
											 $(this).show();
										 } else {
											 $(this).hide();
										 }
										 });
									 
	} else {
		$('.userlistdiv').hide();
		$('.admindiv').each(function(e) {
										 parametro = new RegExp(filtro, "gi");
										 
										 if($(this).text().match(parametro)) {
											 $(this).show();
										 } else {
											 $(this).hide();
										 }
										 });
	}
	//parametro = new RegExp(filtro_amigos["nome"], "gi");
		//if(!(amigos[userid]["display_name"].match(parametro)))

}
</script>
<iframe name="downloadexames" id="iframe_download" style="display:none"></iframe>
  Busca
  :
  <input type='text' name='filtro' id='filtro' onkeyup='filtrar()'/>
  <input type="checkbox" name="filtroadmin" value="admins" onchange="" id="filtroadmin" />
  Administradores Apenas<br />
  <br />
  <div id="usersresultdiv">
    <div id="usersresult_header"></div>
    <? if($query->num_rows() > 0)
                { 
        ///////////////////////////////////////////////////////////////////
        foreach($query->result() as $row): ?>
    <div class="<? if(($row->priv) >= 50) {echo("admindiv");} else { echo("userlistdiv");} ?>" id="userlistdiv_<?=$row->uid;?>">
      <!-- Title heading -->
      <a href="javascript:void(0)" id="user_header_<?=$row->uid;?>" onclick="mostrauser(<?=$row->uid;?>)" class="user_list_div_header_off">
      <? if($row->dados_nome != "") echo($row->dados_nome); else echo($row->username);?>
      </a>&nbsp;&nbsp;
      <!-- View More Div, containing more information -->
      <div id="viewmore_<?=$row->uid;?>" class="userlist_viewmore" style="display:none">
        <? if($row->priv > 1) { echo("N&iacute;vel de acesso: ".$privnames[$row->priv]."<br><br>"); } ?>
        <? if(($row->priv) < 50) { ?>
        <!-- adding exam results -->
        Cadastro de Novo Exame <a href="javascript:void(0)" onclick="maisexame('<?=$row->uid;?>')">[+]</a>:
       <div id="formsdiv_<?=$row->uid;?>">
        <form id='formexame_<?=$row->uid;?>' class="form_add_exame" method="post" action="<?=base_url();?>index.php/exames/registraexame/" enctype="multipart/form-data">
          <table border="0" cellpadding="0" cellspacing="6" class="user_add_exam">
            <tbody>
              <tr>
                <td><small>Data</small></td>
                <td><small>Título</small></td>
                <td><small>Arquivo</small></td>
                <td><small>Selo <small>(opcional)</small></small></td>
              </tr>
              <tr>
                <td><div><input name="data" type="text" class="maskme" id="data" size="20" value="<?=date('d/m/Y');?>" onFocus="if(this.value == '<?=date('d/m/Y');?>') this.value=''; return true;" alt="date"/></div></td>
                <td><div><input name="titulo" type="text" class="logininput" id="titulo" size="20" value="Título" onFocus="if(this.value == 'T&iacute;tulo') this.value=''; return true;"/></div></td>
                <td><div>
                      <input name="arquivo" type="file" class="logininput" id="arquivo<?=$row->uid;?>" size="10"/></div></td>
                <td><div><input name="selo" type="file" class="logininput" id="selo<?=$row->uid;?>" size="10"/></div>
                 </td>
              </tr>
            </tbody>
          </table>
          <input type="hidden" name="userid" value="<?=$row->uid;?>" />
        </form>
        <div id="cadastraexame_<?=$row->uid;?>" style="text-align:center; color:#fff; font-weight:bold; background-color: #adadad; padding: 6px; width: 90px;"><a href="javascript:void(0)" style="color:#fff" onclick="enviaforms(<?=$row->uid;?>)">Enviar</a></div>
        </div>
        <div class="exames_lista_curta">
        <div class="users_list_exams_list_div" id="exames_header_<?=$row->uid;?>">
        <table border="0" cellpadding="0" cellspacing="3">
          <tbody>
            <tr>
              <th width="20%"><small>Data</small></th>
              <th width="50%"><small>Título</small></th>
              <th width="15%"><small>Selo <small>(opcional)</small></small></th>
               <? if ($priv > 50): ?><th width="15%"><small>Apagar</small></th><? endif;?>
            </tr>
           </tbody>
           </table>
           </div>
            <? if($exameslist[$row->uid]) { ?>
            <? foreach($exameslist[$row->uid] as $row_exame):?>
             <div class="users_list_exams_list_div" id="linha_exame_<?=$row_exame["id"]?>">
            <table border="0" cellpadding="0" cellspacing="0">
          	<tbody>
            	<tr>
              <td width="140"><small><a href='<?=base_url();?>index.php/exames/downloadexame/<?=$row_exame["id"];?>' target="downloadexames"><?=date("d/m/Y",$row_exame["data_exame"]);?></a></small></td>
              <td class="spacer">&nbsp;</td>
              <td width="347" style="text-align:left; padding-left: 5px;"><small><a href='<?=base_url();?>index.php/exames/downloadexame/<?=$row_exame["id"];?>' target="downloadexames"><?=$row_exame["titulo"];?></a></small></td>
              <td class="spacer">&nbsp;</td>
              <td width="105">
              <div style="overflow:hidden; width:90px; height: 22px;">
              
			  		<? if ($row_exame["selo"]) { 
							echo("<a href='".base_url()."index.php/exames/downloadexame/".$row_exame["id"]."/1' target='downloadexames'><img src='".base_url()."/images/template/confirm.gif' border='0' /></a>");
					} else {
							echo("<div class='exam_column_selo'>");
							echo("<form id='upload_selo_".$row_exame["id"]."' name='upload_selo' enctype='multipart/form-data' method='post' action='".base_url()."index.php/exames/upload_selo/'>");
						echo("<div class='fake_file_input'><input type='file' name='selo' class='file_hidden' size='1' onchange='do_upload_selo(".$row_exame["id"].")'><input type='hidden' name='eid' value='".$row_exame["id"]."'></div>");
							echo("<div class='selo_upload_txt'>Enviar</div>");
						echo('</form></div>');
						}
							 ?>

</div></td>
                     <? if ($priv > 50): ?> <td class="spacer">&nbsp;</td>
               <td width="105"><a href="javascript:void(0)" onclick="removeExame(<?=$row_exame["id"]?>)"><img src="<?=base_url();?>/images/template/deny.gif" border="0"/></a></td><? endif; ?>
               </tr>
               </tbody>
               </table>
               </div>
            <? endforeach; 
			} else { ?>
             <div class="users_list_exams_list_div">
			Nenhum registro.
        	</div>
            <? } ?>
            </div>
            
     
        <? } // Adição/lista de exames?>
       <div align="right"><a href="<?=site_url();?>/clientes/minhaarea/<?=$row->uid;?>">Ver Todos</a></div>
       </div>
    </div>
    <? endforeach; 
	//////////////////////////////////////////////////////////////////////////////////////////// ?>
    <? } else {?>
    <script language="javascript">
            $(document).ready(function()
            {		
				mensagem('N&atilde;o h&aacute; outros usu&aacute;rios cadastrados ou voc&ecirc; n&atilde;o tem permiss&atilde;o para visualiz&aacute;-los.');	
            }); 
    </script>
    <? } ?>
  </div>
</div>
