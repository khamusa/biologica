<script language="javascript">
function addExame(data) {
    $("#examform").slideUp("slow",
        function() {
             if (data.success == 'yes') {
			 mensagem('Exame Adicionado!', 3);             
			
					$("#examform").slideDown("slow"); 	                   
            }
            if (data.success == 'no') {
				 mensagem(data.message);
                 $("#examform").slideDown("slow"); 
            }
        }
    );
    
} 

$(document).ready(
    function(){    
        $("#examsending").ajaxForm({
            type: "POST",
            url: "<?=base_url();?>index.php/exames/registraexame/<?=$uid;?>",
			resetForm: true,
			dataType: 'json',
			iframe: true,
            success: addExame
        });
    
    }
) 
</script>
<div id="logged"></div>

<div id="examform">
<?=form_open('user/login',array('id' => 'examsending'));?>
	
                                    Titulo:<br />
                                    <input name="titulo" type="text" class="logininput" id="titulo" size="30"  />
                                     <br />
                                     <label for="data">Data do Exame:</label>
                                     <br />
                                    <input name="data" type="text" class="logininput" id="data" size="30" />       
                                     <br />
                                     <label for="arquivo">Arquivo:</label><br />
                                    <input name="arquivo" type="file" class="logininput" id="arquivo" size="30" />      
                                     <input type="hidden" name="userid" value="<?=$uid;?>" />
									<input class="loginsubmit" type="submit" value="Cadastrar" />
                                <?=form_close();?>

</div>