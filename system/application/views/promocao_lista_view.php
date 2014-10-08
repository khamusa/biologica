<script src="<?=base_url()?>jquery/ajax_upload.js"></script>
<script src="<?=base_url()?>js/json_parse.js"></script>
<div id="admincontentwrapper">	
    <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/admin/lista')">Imagens Logo</a></div>
    <div class="admin_aba_on"><a href="javascript:loadPage('<?=site_url()?>/promocao/lista')">Promoções</a></div>
    <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/promocao/banner')">Banner</a></div>
   <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/admin/password_edit')">Configurar</a></div>
    <div class="admin_aba_config_off"><a href="<?=site_url()?>/user/logout">X</a></div>
    <div id="admincontent">
    <div align="right" style="margin-bottom:5px; height:25px">
    <span id="errormessage" style="width: 250px; display:none; margin-bottom: 10px; font-weight:bold; text-align:center; border: 1px solid #fff; padding:5px;"></span>

    <img id="upload_button" src="<?=base_url()?>images/content/upload_img.png" border="0" style="display:block"/>
    </div>
 
 <div id="imgswrapper">
<? if($query->num_rows() > 0)
		{ ?>
            <? foreach($query->result() as $row): ?>
                <div id="img_<?=$row->id?>" style="width:150px; height:110px; float:left; margin-right: 10px;">
                <a href="<?=base_url()."uploads/promocoes/".$row->arquivo?>" rel="imagebox" title="Promoção"><img src="<?=base_url()."uploads/promocoes/thumbs/".$row->arquivo?>" width="150" height="110"></a><br><a href="javascript:remove_promocao('<?=$row->id?>')">Remover</a><br /><?=date('d/m/Y',$row->data)?></div>		
            <? endforeach; ?>
     <? } ?>
	 </div>
     </div>
</div>

<script>
$(document).ready(function(){
new Ajax_upload('#upload_button', {
  action: 'promocao/adiciona',
  name: 'img',
  // Additional data to send
  data: {
    // example_key1 : 'example_value',
    //example_key2 : 'example_value2'
  },
  // Submit file after selection
  autoSubmit: true,
  // @param file basename of uploaded file
  // @param extension of that file
  onSubmit: function(file, extension) {
 	$('#upload_button').fadeOut('slow', function() {
  		$('#errormessage').html('Enviando a imagem, aguarde.').fadeIn('slow');
			});
  },
  // @param file basename of uploaded file
  // @param response server response
  onComplete: function(file, response) {
  data = json_parse(response);
  if(data.success == 'yes') {
  // animate errorbox
  $('#errormessage').fadeOut('slow', 
 					 function() { 
  								$('#errormessage').html(data.message).fadeIn(2000, 
							  function() { 
 								 $('#errormessage').fadeOut('slow', 
									 function() {
									   var html = "<div id='img_"+data["id"]+"' style='display:none; width:150px; height:110px; float:left; margin-right: 10px;'><a title='Promoção' href='<?=base_url()."uploads/promocoes/"?>"+data["arquivo"]+"' rel='imagebox'><img src='http://www.florestamagica.com.br/uploads/promocoes/thumbs/"+data["arquivo"]+"' width='150' height='110'></a><br><a href=\"javascript:remove_promocao('"+data["id"]+"')\">Remover</a></div>";
									  $("#imgswrapper").prepend(html);
									  $('#img_'+data["id"]).fadeIn(2500);
									  $('#upload_button').fadeIn('slow');
									  $.ImageBox.init(
												{
												loaderSRC: 'http://www.florestamagica.com.br/images/imagebox/loading.gif',
												closeHTML: '<img src="http://www.florestamagica.com.br/images/imagebox/close.jpg" />'
												});
									 }
								 );
								 });
							});
  // insert the loaded image into the list


 
  } else {
	 $('#errormessage').fadeOut('slow', 
 					 function() { 
  								$('#errormessage').html("ERRO: "+data.message).fadeIn(3000, 
							  function() { 
 								 $('#errormessage').fadeOut(3000);
								 });
								});
  }
  }
});	//newajax
}); //document.ready
</script>