<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
</head>

<body>
<h3><?=$title?></h3>


<?=form_open_multipart('admin/imagens_inserir');?>
<!-- <p>Nome: <input type="text" name="nome" /></p> -->
<!--<p>Site: http://<input type="text" name="site" value=""/></p>-->
<p>Imagem: <input type="file" name="img" />
<br />
<span>Tamanho máximo da imagem: 1000 kb</span></p>
<p><input type="submit" value="Cadastrar"/></p>
</form>
</body> 
</html>
