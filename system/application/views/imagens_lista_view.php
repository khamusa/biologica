<script src="<?=base_url()?>/jquery/ajax_upload.js"></script>
<script src="<?=base_url()?>/js/json_parse.js"></script>
<div id="admincontentwrapper">	
    <div class="admin_aba_on"><a href="javascript:loadPage('<?=site_url()?>/admin/lista')">Imagens Logo</a></div>
    <div class="admin_aba_off"><a href="javascript:loadPage('<?=site_url()?>/promocao/lista')">Promoções</a></div>
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
                <div id="img_<?=$row->id?>" style="width:88px; height:87px; float:left; margin-right: 10px;"><img src="<?=base_url()."uploads/".$row->arquivo?>" width="88" height="57"><br><a href="javascript:remove_img('<?=$row->id?>')">Remover</a></div>		
            <? endforeach; ?>
     <? } ?>
	 </div>
     </div>
</div>

<script>
$(document).ready(function(){
new Ajax_upload('#upload_button', {
  // Location of the server-side upload script
  action: 'admin/imagens_inserir',
  // File upload name
  name: 'img',
  // Additional data to send
  data: {
    // example_key1 : 'example_value',
    //example_key2 : 'example_value2'
  },
  // Submit file after selection
  autoSubmit: true,
  // Fired when user selects file
  // You can return false to cancel upload
  // @param file basename of uploaded file
  // @param extension of that file
  onSubmit: function(file, extension) {
 	$('#upload_button').fadeOut('slow', function() {
  		$('#errormessage').html('Enviando a imagem, aguarde.').fadeIn('slow');
			});
  },
  // Fired when file upload is completed
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
									   var html = "<div id='img_"+data["id"]+"' style='display:none; width:88px; height:87px; float:left; margin-right: 10px;'><img src='http://www.florestamagica.com.br/uploads/"+data["arquivo"]+"' width='88' height='57'><br><a href=\"javascript:remove_img('"+data["id"]+"')\">Remover</a></div>";
									  $("#imgswrapper").prepend(html);
									  $('#img_'+data["id"]).fadeIn(2500);
									  $('#upload_button').fadeIn('slow');
									 }
								 );
								 });
							});
  // insert the loaded image into the list


 
  } else {

  }
  }
});
});
</script>