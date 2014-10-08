var BASE_URL = 'http://www.biologicalab.com.br/';
var SITE_URL = 'http://www.biologicalab.com.br/index.php';

var loader = new Image();
	loader.src = BASE_URL+'images/ajax-loader.gif';
 $.fn.wait = function(time, type) {
        time = time || 1000;
        type = type || "fx";
        return this.queue(type, function() {
            var self = this;
            setTimeout(function() {
                $(self).dequeue();
            }, time);
        });
    };


function showdados() {
	$('#dados_detalhes').toggle('slow');	
}

function blockUser(userid, truefalse) {
	$.getJSON(BASE_URL+"index.php/user/blockuser/"+userid+"/"+truefalse,
        function(data){
			if(data.success == 'yes') 
			{
				if(truefalse == 1) {
				$('#blocklink_'+userid).html("<a href='javascript:void(0)' onClick='blockUser("+userid+",0)'>N&atilde;o</a>");
				} else {
				$('#blocklink_'+userid).html("<a href='javascript:void(0)' onClick='blockUser("+userid+",1)'>Sim</a>");
				
				}
			} else if(data.success == 'no')
			{  
				mensagem('Ocorreu um erro ao efetuar seu pedido');
			}
        });
	return false;
}

function removeUser(userid) {
	if(confirm('Tem certeza? Esta a&ccedil;&atilde;o n&atilde;o poder&aacute; ser desfeita!')) {
	$.getJSON(BASE_URL+"index.php/user/removeuser/"+userid,
        function(data){
			if(data.success == 'yes') 
			{
				$('#tr_'+userid).remove();
				mensagem('O Usu&aacute;rio foi removido.', 1);
				
			} else if(data.success == 'no')
			{  
				mensagem('Erro: Usu&aacute;rio n&atilde; foi removido');
			}
        });
	}
	return false;
}

function removeExam(exameid) {
	if(confirm('Tem certeza? Esta a&ccedil;&atilde;o n&atilde;o poder&aacute; ser desfeita!')) {
	$.getJSON(BASE_URL+"index.php/exames/removeexame/"+exameid,
        function(data){
			if(data.success == 'yes') 
			{
				$('#tr_'+exameid).remove();
				mensagem('O Exame foi removido.', 1);
				
			} else if(data.success == 'no')
			{  
				mensagem('Erro: Usu&aacute;rio n&atilde; foi removido');
			}
        });
	}
	return false;
}
// JavaScript Document
var imagens = {};
var img_atual = 0;
var max_imgs = 0;
var last_img = 0;
var debugar = 0;
 $.fn.wait = function(time, type) {
        time = time || 1000;
        type = type || "fx";
        return this.queue(type, function() {
            var self = this;
            setTimeout(function() {
                $(self).dequeue();
            }, time);
        });
    };

function criaElem(elem, id, classe) {
	if(!elem)
	 {
		 elem = 'div';
	 }
	 if(!classe)
	 {
		 classe = id;
	 }
	 if(!id)
	 {
		return false; 
	 }
	 var newelem = document.createElement(elem);
	 newelem.setAttribute('id', id);
	 newelem.setAttribute('class', classe);
	 newelem.setAttribute('className', classe);
	 return newelem;
 }
 
function mensagem(msg, tipo, persist) {
	var boxid = 'erro_'+Math.random();
	tipo = tipo || 3;
	persist = persist || '2000';
	if(tipo == 2)
		var box = criaElem('div', boxid, 'messagebox');
	else if (tipo == 1)
		var box = criaElem('div', boxid, 'greenbox');
	else
		var box = criaElem('div', boxid, 'errorbox');
		
	box.innerHTML = msg;
	//$('#error').append(box);
	$(box).prependTo('#error');
	if(persist)
	{
		$(box).fadeIn(2000).wait(persist).fadeOut(2000);
	} else {
		$(box).fadeIn(2000).fadeOut(2000);
	}
	//$('#'+boxid).fadeIn('slow').fadeOut('slow');
}

function loadDefaultUserSection() {
	$('#content').fadeOut('slow',
												function() {
												$('#content').html('<div id="user_admin_leftbox"></div><div id="user_admin_rightbox"></div>').show();
												
												loadContentPage(SITE_URL+'/user/listuser');
												loadAdminModule(SITE_URL+'/user/adduser');

																			  }); // ('#content').fadeOut
	
}
function loadContentPage(page, mode) {
	$.get(page, function(data){
													$('#user_admin_leftbox').fadeOut('slow', function() 
														{ 
															var modulocontent = criaElem('div', 'modulecontent', 'modulecontent');
															modulocontent.innerHTML = data;
															if(mode == 1) {
																$(modulocontent).prependTo('#user_admin_leftbox');
															} else {
																$('#user_admin_leftbox').html(modulocontent);
															}
															$('#user_admin_leftbox').fadeIn('slow');
														});
											

									 });
}
function loadPage(page) {
	$('#upload_button').hide();
	$('#valums97hhu0').remove();
				$.get(page, function(data){
					//$('#a3_boxcontent').hide();
					$('#content').fadeOut('slow',
												function() {
												$('#content').html(data);	
												$('#content').fadeIn('slow', 
																			  function(){
																				$.ImageBox.init(
																					{
																						loaderSRC: 'http://www.florestamagica.com.br/images/imagebox/loading.gif',
																						closeHTML: '<img src="http://www.florestamagica.com.br/images/imagebox/close.jpg" />'
																					}
																				);

																			  });
												}
												);
									 });
	
}

function loadExamsPage(page) {
				$.get(page, function(data){
					//$('#a3_boxcontent').hide();
					$('#examscontent').fadeOut('slow',
												function() {
												$('#examscontent').html(data);	
												$('#examscontent').fadeIn('slow');

												}
												);
									 });	
}
function loadAdminModule(page, mode, id) {
		if(!id) id = 'modulediv';
				$.get(page, function(data){
									 if(mode == 1) {
										 $('#'+id).slideUp('fast').remove();
										 $('#user_admin_rightbox .moduledivcontent').toggle('slow');
										 var modulodiv = criaElem('div', id, 'modulediv');
										modulodiv.innerHTML = data;
										 $(modulodiv).hide().prependTo('#user_admin_rightbox').slideDown('slow');
									 } else {
														$('#user_admin_rightbox').fadeOut('slow', function() 
														{ 
															var modulodiv = criaElem('div', 'modulediv', 'modulediv');
															modulodiv.innerHTML = data;
															$('#user_admin_rightbox').html(modulodiv);
															$('#user_admin_rightbox').fadeIn('slow');
									
														});
									}		

									 });
	
}

function debuga(msg, alerta) {
	if(debugar == 1)
	$('#debuginfo').append("<br>"+msg);
}
function dalert(msg) {
	if(debugar >= 1)
	alert(msg);
}


function gera_senha() {
	var senha = randomPassword();
	$('#senhagerada').text('Senha gerada:'+senha);
	$('#password').val(senha);
	$('#confpassword').val(senha);
}

function randomPassword() {
		var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$_+";
		var size = 10;
		var i = 1;
		var ret = ""
		while ( i <= size ) {
			$max = chars.length-1;
			$num = Math.floor(Math.random()*$max);
			$temp = chars.substr($num, 1);
			ret += $temp;
			i++;
		}
		return ret;
	}
