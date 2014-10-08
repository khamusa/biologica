 <div id="content">
  <h1>Contato<br />
  </h1>
  <p><br />
    Biol&oacute;gica<br />
Rua Pouso Alegre, 458 - Floresta - CEP 31015-30<br />
tel.: (31) 3423-0188<br />
  <br />
</p>
</div>
<div id="contentextended">
  <h2>Formul&aacute;rio</h2>

<div id="mensagem" style="display:block"></div>

<div id="formulario" style="margin-bottom:0px; padding-left:50px">

 <?=form_open('user/sendmail',array('id' => 'sendmail'));?>

<table cellpadding="0" cellspacing="0" border="0">

	<tbody>

    <tr> <td>

  <p>Seu Nome:&nbsp;&nbsp;</p>

  </td><td><input class="texto" type="text" name="nome" id="nome" style="width:250px"/>

  </td></tr>

  <tr> <td>

  <p>E-mail de contato:&nbsp;&nbsp;</p>

  </td><td><input class="texto" type="text" name="email" id="contato" style="width:250px"/>

  </td></tr>

  <tr> <td>

  <p>Telefone:&nbsp;&nbsp;</p>

  </td><td><input class="texto" type="text" name="telefone" id="telefone" style="width:250px" />

  </td></tr>

  <tr>
    <td><p>Empresa:</p></td>
    <td><input class="texto" type="text" name="empresa" id="empresa" style="width:250px"/></td>
  </tr>
  <tr>
    <td><p>&Aacute;rea / Setor:</p></td>
    <td><input class="texto" type="text" name="setor" id="setor" style="width:250px"/></td>
  </tr>
  <tr> <td>

  <p>Assunto:&nbsp;&nbsp;</p>

  </td><td><input class="texto" type="text" name="assunto" id="assunto" style="width:250px"/>

  </td></tr>

  <tr> <td>

  <p>Mensagem:&nbsp;&nbsp;</p>

  </td><td><textarea name="mensagem" id="mensagem" style="width:250px" rows="2"></textarea>

  </td></tr>

  <tr> <td>&nbsp; </td><td>

  <tr><td><input type="submit" value="Enviar" /> </td></td>
  </tbody>
</table>
</form></div>


<script language="javascript">

function updateEnvio(data) {

$('#mensagem').html('');
$('#mensagem').hide();

$("#formulario").fadeOut("slow",

    function() {

         if (data.success == 'yes') {

            $('#mensagem').html(data.message).fadeIn("slow");    

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

        url: "<?=base_url()?>/index.php/main/enviamail",

        dataType: "json",

        data: $("#sendmail").serialize(),

        success: updateEnvio

    });

}

) 

</script>
</div>