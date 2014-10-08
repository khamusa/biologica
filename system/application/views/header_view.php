<? $rand = rand(1,4);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache">
<title>
<?=$title?>
</title>
<?=link_tag('css/estilos.css')?>
<?=link_tag('css/imagebox.css')?>
<script src="<?=base_url()?>jquery/jquery13.js"></script>
<script src="<?=base_url()?>jquery/form_plugin.js"></script>
<script src="<?=base_url()?>js/admin.js"></script>
<script src="<?=base_url()?>js/mask.js"></script>
<script src="<?=base_url()?>js/jeditable.js"></script>
<script src="<?=base_url()?>js/slidemenu.js"></script>
<script src="<?=base_url()?>interface/compressed/imagebox.js"></script>
<script>
var clientesopen = 0;
function clientes() {
	if(clientesopen == 0) {
		clientesopen = 1;
		$("#a1_cliente_container").stop();
		$("#a1_cliente_container").animate({"width": "245px"}, "slow");
	} else {
		clientesopen = 0;
		$("#a1_cliente_container").stop();
		$("#a1_cliente_container").animate({"width": "27px"}, "slow");
	}

}

$(document).ready( function() {
<? if($index == true) { ?>
	slideMenu.build('sm',325,5,7);
	<? } ?>


$('#link_institucional').bind("mouseenter", function (e) {$('#link_institucional ul').stop().show().animate({opacity:1}, 500);});

$('#link_institucional').bind("mouseleave", function (e) {$('#link_institucional ul').hide().animate({opacity:0}, 500);});

});

</script>
</head>
<body>
<div id="box00">
<!-- AREA 1> logo -->
<div id="a1_boxlogos">
  <div id="a1_logo">
  	<div id="a1_logo_img">
   <a href="<?=site_url()?>">
    <img src="<?=base_url()?>/images/template/logo.jpg" border="0" />
    </a>
    </div>
    <div id="a1_menu">
	<div style="float:left; height:60px; width:167px" id="link_institucional">
    <a href="<?=site_url()?>/main/institucional">
    <img src="<?=base_url()?>/images/template/menu_institucional_<?=$rand?>.jpg" border="0" />
    </a>
    <ul>
    	<li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/servicos">Serviços/Atividades</a></div>
    	</li>
    	<li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#missao">Missão</a></div></li>
    	<li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#historico">Histórico</a></div></li>
        <li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#politica">Política de Qualidade</a></div></li>
        <li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#organizacao">Descrição da Organização</a></div></li>
        <li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#registro">Registros</a></div></li>
         <li><div class="submenu_bground"></div><div class="submenu_content"><a href="<?=site_url()?>/main/institucional#links">Links</a></div></li>
    </ul>
    </div>
    <div style="float:left; height:60px; width:197px; overflow:hidden;">
    <a href="<?=site_url()?>/main/pesquisa">
    <img src="<?=base_url()?>/images/template/menu_pesquisa_<?=$rand?>.jpg" border="0" />
    </a>
    </div>
    <div style="float:left; height:60px; width:133; overflow:hidden;">
    <a href="<?=site_url()?>/main/clientes">
    <img src="<?=base_url()?>/images/template/menu_clientes_<?=$rand?>.jpg" border="0" />
    </a>
    </div>
    <div style="float:left; height:60px; width:179px; overflow:hidden;">
    <a href="<?=site_url()?>/main/contato">
    <img src="<?=base_url()?>/images/template/menu_contato_<?=$rand?>.jpg" border="0" />
    </a>
    </div>
    </div>
  </div>
  <div id="a1_imgdireita_container" style="background-image:url(<?=base_url()?>/images/template/t_imagemdireita_<?=$rand?>.jpg);">
  <div id="a1_cliente_container">
  	<div style="width:245px; overflow:hidden;">
      <div id="a1_cliente" onclick="clientes()" style="background-image:url(<?=base_url()?>/images/template/areadocliente.jpg);"></div>
      <div id="a1_loginbox">
      <div style="padding-left: 15px; margin-top: 30px; text-align:left;">
      <? if(!$logged) { ?>
      <script language="javascript">
		function updateLogin(data) {
			
					 if (data.success == 'yes') {
						 	mensagem(data.message);
					 			$('#envia_login').val('Redirecionando...');								
								window.location.href = "<?=base_url();?>index.php/user";
							}
					if (data.success == 'no') {
						mensagem(data.message);
						$('#userlogin input').attr('disabled', false);
						$('#envia_login').val('Login');
					}			
		} 
		
		function disableLoginForm () {			
			$('#userlogin input').attr('disabled', true);
			$('#envia_login').val('Enviando...');
		}
		
		$(document).ready(
			function(){    
				$("#userlogin").ajaxForm({
					type: "POST",
					url: "<?=base_url();?>index.php/user/checkLogin",
					dataType: "json",
					data: "username="+$("#username_login").val()+"&password="+$("#password_login").val(),
					success: updateLogin,
					beforeSubmit: disableLoginForm
				});
			
			}
		) 
		</script>

      	<?=form_open('user/mklogin',array('id' => 'userlogin'));?>
				
                                    <label for="username">Login:</label><br />
                                    <input name="username" type="text" class="logininput" id="username_login" size="25"  />
<Br />
                                     <label for="password">Senha:</label><br />
                                    <input name="password" type="password" class="logininput" id="password_login" size="25" />       
                                    <br /><input class="loginsubmit" type="submit" id="envia_login" value="Login" />
                                <?=form_close();?>
         <? } else { ?>
         <script>clientes();</script>
         <p>Bem-vindo(a)</p>
         <ul>
        	 <? if($admin) { ?>
            <li><a href="<?=site_url()?>/user/listuser">Lista de Usuários</a></li>
            <li><a href="<?=site_url()?>/user/adduser">Adicionar Usuário</a></li>
            <li><a href="<?=site_url()?>/clientes/minhaarea">Meus Dados</a></li>
            <? } else { ?>
            <li><a href="<?=site_url()?>/clientes/minhaarea">Meus Exames</a></li>
            <? } ?>
         	
         	<li><a href="<?=site_url()?>/user/logout">Sair do Sistema</a></li>
         </ul>
         <? } ?></div>
         </div>
     </div>
  </div>
  </div>
</div>
<? if($index == true) { ?>
<div id="a2_boxboxes" style="background-image:url(<?=base_url()?>/images/template/t_imagemcentro_<?=$rand?>.jpg);">
  <ul id="sm">
    <li id="a2_box01" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 20px; text-align:center;"><img src="<?=base_url()?>/images/template/box_residuos.png" /></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">
        <a href="<?=site_url()?>/servicos/residuos">Descrição</a>
          <a href="<?=site_url()?>/servicos/residuos#analise"><br />
          Análise</a>
          <a href="<?=site_url()?>/servicos/residuos#normas"><br />
          Normas</a>        </div>
      </div>
    </li>
    <li id="a2_box02" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 18px; text-align:center;"><img src="<?=base_url()?>/images/template/box_agua.png" /></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">

<a href="<?=site_url()?>/servicos/agua">Descrição</a><br />
<a href="<?=site_url()?>/servicos/agua#analise">Análise</a>
<br />
<a href="<?=site_url()?>/servicos/agua#normas">Normas</a>        </div>
      </div>
    </li>
    <li id="a2_box03" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 30px; text-align:center;"><img src="<?=base_url()?>/images/template/box_eco.png" /></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">
<a href="<?=site_url()?>/servicos/ecotoxicidade">Descrição</a><br />
          <a href="<?=site_url()?>/servicos/ecotoxicidade#analise">Análise</a><br />
            <a href="<?=site_url()?>/servicos/ecotoxicidade#normas">Normas</a>        </div>
      </div>
    </li>
    <li id="a2_box04" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 30px; text-align:center;"><img src="<?=base_url()?>/images/template/box_solo.png" /></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">
<a href="<?=site_url()?>/servicos/solo">Descrição</a><br />
          <a href="<?=site_url()?>/servicos/solo#analise">Análise</a><br />
            <a href="<?=site_url()?>/servicos/solo#normas">Normas</a>        </div>
      </div>
    </li>
    <li id="a2_box05" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 30px; text-align:center;"><img src="<?=base_url()?>/images/template/box_ar.png" /></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">
<a href="<?=site_url()?>/servicos/ar">Descrição</a><br />
            <a href="<?=site_url()?>/servicos/ar_analise">Análise</a><br />
            <a href="<?=site_url()?>/servicos/ar_normas">Normas</a>        </div>
      </div>
    </li>
    <li id="a2_box06" class="movebox">
      <div class="boxes_conteudo_00">
        <div class="boxes_conteudo" style="margin-top: 30px; text-align:center;"><img src="<?=base_url()?>/images/template/box_consultoria.png"/></div>
        <div class="boxes_conteudo" style="margin-top: 20px; margin-left: 38px; width: 120px;">
  <a href="<?=site_url()?>/servicos/coleta">Descrição</a><br />
            <a href="<?=site_url()?>/servicos/coleta#normas">Normas</a>        </div>
      </div>
    </li>
  </ul>
</div>
<? } ?>
<div id="a3_content">
<div id="error"></div>