<script src="<?=base_url()?>jquery/ajax_upload.js"></script>
<script src="<?=base_url()?>js/json_parse.js"></script>
<div id="admincontentwrapper">	
    <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/admin/lista')">Imagens Logo</a></div>
    <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/promocao/lista')">Promoções</a></div>
    <div class="admin_aba_on"><a href="javascript:loadPage('<?=site_url()?>/promocao/banner')">Banner</a></div>
   <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/admin/password_edit')">Configurar</a></div>
    <div class="admin_aba_config_off"><a href="<?=site_url()?>/user/logout">X</a></div>
  <div id="admincontent">
    <div align="right" style="margin-bottom:5px; height:95px">
    <span id="errormessage" style="width: 250px; display:none; margin-bottom: 10px; font-weight:bold; text-align:center; border: 1px solid #fff; padding:5px;"></span>
    <img id="upload_button" src="<?=base_url()?>images/content/upload_img.png" border="0" style="display:block"/><br />
    <span style="font-size:80%"><i>Atenção: As dimensões do banner são 418x70 pixels, qualquer imagem enviada será redimensionada para este tamanho, podendo ser deformada se tiver em proporções diferentes. </i></span></div>
 
 <div id="imgswrapper">
     <div style="width:418px; height:70px; float:left; margin-right: 10px;"><img src="http://www.florestamagica.com.br/uploads/promocoes/banners/banner.png" width="418" height="70"></div>
         </div>
         </div>
</div>

<script>
$(document).ready(function(){
new Ajax_upload('#upload_button', {
  action: 'promocao/adicionabanner',
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
									   var html = "<div style='width:418px; height:70px; float:left; margin-right: 10px;'><img src='http://www.florestamagica.com.br/uploads/promocoes/banners/banner.png?rand="+Math.random()+"' width='418' height='70'></div>";
									  $("#imgswrapper").fadeOut('slow', 
									  					function() {$("#imgswrapper").html(html).fadeIn('slow');
														});
									  $('#upload_button').fadeIn('slow');
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