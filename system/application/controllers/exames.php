<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
* This login system requires Codeigniter's Database to Work properly and safely. Please refer to 
http://codeigniter.com/user_guide/libraries/sessions.html for more details.
To configure:
1 - Create the table: 

	CREATE TABLE IF NOT EXISTS  `ci_sessions` (
	session_id varchar(40) DEFAULT '0' NOT NULL,
	ip_address varchar(16) DEFAULT '0' NOT NULL,
	user_agent varchar(50) NOT NULL,
	last_activity int(10) unsigned DEFAULT 0 NOT NULL,
	user_data text NOT NULL,
	PRIMARY KEY (session_id)
	);
	
2 - open your config.php file and put or change these lines:
$config['sess_use_database'] = TRUE;
$config['sess_table_name'] = 'ci_sessions";

3- Always load sessions when starting the application. You may also autoload it in your autoload.php file
*
*

PRIV CONTROL:
0 - 50 - FRONTEND PRIVS
> 50 - BACKEND PRIVS
99 - SUPER ADMINISTRATOR
*/
class Exames extends Controller {

    function Exames() {
        parent::Controller();
        
        $this->load->model("exames_model");    
		$this->load->model("user_model");          
		
        $this->load->helper("security");
        $this->load->helper("form");
		$this->load->helper('html');
		$this->load->helper('url');
		
		// check if tables exists, if not, makes!
		if(!$this->db->table_exists($this->exames_model->tablename)) {
			$this->exames_model->_mkTables();
			} 
			//$this->load->scaffolding('exames');
    }
    
   	function index () {
	}
	
    function verExames($userid = 0) {
	if($userid == 0) {
		$userid = $this->user_model->logged();
	}
	
	$data['logged'] = $this->user_model->logged();
	$data['admin'] = $this->user_model->isadmin();
	$data['uid'] = strip_tags(htmlentities($userid));
		// you can only see the exams section if it's your personal section or if you're an admin
		if(($this->user_model->logged() == $userid)||($this->user_model->isadmin())) {
			if($data['query'] = $this->exames_model->pegaExames($userid)) {
				$this->load->view('exames_list_view', $data);
			} else {
				echo("erro ao obter exames");
			}
		} else {
			redirect('');
		}
    }
	
	function downloadExame($examid, $tipo = 0) {
	
	// Allow direct file download (hotlinking)?
		// Empty - allow hotlinking
		// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
		define('ALLOWED_REFERRER', '');
		
		// Download folder, i.e. folder where you keep all files for download.
		// MUST end with slash (i.e. "/" )
		if($tipo == 0)
			define('BASE_DIR','C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/exames/');
		else 
			define('BASE_DIR','C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/');
		
		
		// log downloads?  true/false
		define('LOG_DOWNLOADS',false);
		
		// log file name
		define('LOG_FILE','downloads.log');
		
	// If hotlinking not allowed then make hackers think there are some server problems
	if (ALLOWED_REFERRER !== ''
	&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
	) {
	  die("Internal server error. Please contact system administrator.");
	}
		
	if(!isset($examid)) {
		die('Voc&ecirc; n&atilde;o especificou um exame para baixar.');
	}
	
	$exame = $this->exames_model->pegaExame($examid);
	
	if(($exame->userid != $this->user_model->logged())&&(!$this->user_model->isadmin()))
	{
		die('Voc&ecirc; n&atilde;o possui privil&eacute;gios para baixar este arquivo.');
	}		
	
	
	$allowed_ext = array (
	
	  // archives
	  //'zip' => 'application/zip',
	
	  // documents
	  'pdf' => 'application/pdf',
	  'doc' => 'application/msword',
	  //'xls' => 'application/vnd.ms-excel',
	  //'ppt' => 'application/vnd.ms-powerpoint',
	  
	  // executables
	  //'exe' => 'application/octet-stream',
	
	  // images
	  'gif' => 'image/gif',
	  'png' => 'image/png',
	  'jpg' => 'image/jpeg',
	  'jpeg' => 'image/jpeg',
	
	  // audio
	  'mp3' => 'audio/mpeg',
	  'wav' => 'audio/x-wav',
	
	  // video
	  //'mpeg' => 'video/mpeg',
	  //'mpg' => 'video/mpeg',
	  //'mpe' => 'video/mpeg',
	  //'mov' => 'video/quicktime',
	  //'avi' => 'video/x-msvideo'
	);
		
	// Make sure program execution doesn't time out
	// Set maximum script execution time in seconds (0 means no limit)
	set_time_limit(0);
	
	if($tipo == 0) {
		$fname = $exame->arquivo;
		if($exame->userid == $this->user_model->logged())
		$this->exames_model->exameVelho($examid);
		}
	else {
		$fname = $exame->selo;
		}
		
	
	
	$file_path = BASE_DIR.'/'.$fname;
	
	if (!is_file($file_path)) {
	  die("O Arquivo n&atilde;o existe. "); 
	}
	
	// file size in bytes
	$fsize = filesize($file_path); 
	
	// file extension
	$fext = strtolower(substr(strrchr($fname,"."),1));
		
	// check if allowed extension
	if (!array_key_exists($fext, $allowed_ext)) {
	  die("Not allowed file type."); 
	}
	
	// get mime type
	if ($allowed_ext[$fext] == '') {
	  $mtype = '';
	  // mime type is not set, get from server settings
	  if (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file_path);
	  }
	  else if (function_exists('finfo_file')) {
		$finfo = finfo_open(FILEINFO_MIME); // return mime type
		$mtype = finfo_file($finfo, $file_path);
		finfo_close($finfo);  
	  }
	  if ($mtype == '') {
		$mtype = "application/force-download";
	  }
	}
	else {
	  // get mime type defined by admin
	  $mtype = $allowed_ext[$fext];
	}
	
	// Browser will try to save file with this filename, regardless original filename.
	// You can override it if needed.
	
	
	/*if (!isset($_GET['fc']) || empty($_GET['fc'])) {
	  $asfname = 'resultado_'.$exame->userid.date('Ymd',$exame->data_exame);
	}
	else {
	  // remove some bad chars
	  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
	  if ($asfname === '') $asfname = 'NoName';
	}*/
	
	$asfname = 'resultado_'.$exame->userid.date('Ymd',$exame->data_exame).'.'.$fext;
	
	// set headers
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Type: $mtype");
	header("Content-Disposition: attachment; filename=\"$asfname\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: " . $fsize);
	
	// download
	// @readfile($file_path);
	$file = @fopen($file_path,"rb");
	if ($file) {
	  while(!feof($file)) {
		print(fread($file, 1024*8));
		flush();
		if (connection_status()!=0) {
		  @fclose($file);
		  die();
		}
	  }
	  @fclose($file);
	}
	
	// log downloads
	if (!LOG_DOWNLOADS) die();
	
	$f = @fopen(LOG_FILE, 'a+');
	if ($f) {
	  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
	  @fclose($f);
	}
	
}
	
	function addExame($userid) { 
		$data['title'] = 'Adição de exame';
		$data['uid'] = strip_tags(htmlentities($userid));
		// checks if the user is logged in and has a BACKEND priv
		if(($this->user_model->logged())&&($this->user_model->isadmin())) {
			$this->load->view("exame_add_view", $data);
		}
	}
	
	function removeExame() {
		$id = strip_tags(htmlentities($this->uri->segment(3)));
		// verifica privilegios
		// adds 1 because we want the user to have a HIGHER priv, not the same
		if($this->user_model->hasPriv(50)){
			if($this->exames_model->removeExame($id)) {
				$output = '{ "success": "yes", "message": "Removido" }';
			} else {
				$output = '{ "success": "no", "message": "Erro ao remover do banco de dados." }';
			}
		} else {
			$output = '{ "success": "no", "message": "Voc&ecirc; n&atilde;o possui os privil&eacute;gios necess&aacute;rios." }';
        }
        
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
	}
	
	    
	/* */
	
	function getPost($name) {
		if(isset($_POST[$name])){
			return htmlentities($_POST[$name], ENT_COMPAT, 'UTF-8');
		} else {
			return "";
			}
	}
	
	function upload_selo() {
		if($this->session->userdata('logged_in')&&($this->user_model->isadmin())) {
			$config['upload_path'] = 'uploads/selos/';
			$config['allowed_types'] = 'pdf|doc|txt|gif|jpg|png';
			$config['max_size'] = '10000';
			$config['encrypt_name'] = true;
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('selo'))
			{
				if($selo = $this->upload->data()) {
						$selo = $selo['file_name'];
						} else {
						$selo = "";
						}
						
				// atualiza o BD
						$data['selo'] = $selo;
						$data['id'] = $_POST["eid"];
						if($this->exames_model->updateExame($data)) {
							$output = '{ "success": "yes", "message": "Selo inserido" }';
						} else {
							$output = '{ "success": "no", "message": "Erro ao atualizar o selo.}';
							if(file_exists("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$data['selo'])) {
								unlink("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$data['selo']);
							}
						}
			} else {
				$output = '{ "success": "no", "message": "Erro no envio do arquivo." }';
			}			
		} else {
			$output = '{ "success": "no", "message": "Sem pemiss&atilde;o para efetuar a altera&ccedil;&atilde;o." }';
		}
		$output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
		echo("<textarea>");
		echo $output;
		echo("</textarea>");
	}
	function registraexame() {
		if($this->session->userdata('logged_in')&&($this->user_model->isadmin())) {
		// efetua o upload da imagem
			$config['upload_path'] = 'uploads/exames/';
			$config['allowed_types'] = 'pdf|doc|txt|gif|jpg|png';
			$config['max_size'] = '10000';
			$config['encrypt_name'] = true;
	
			$this->load->library('upload', $config);
			
			//$this->upload->do_upload('img');
			
			if ($this->upload->do_upload('arquivo'))
			{
				
				$upload_data = $this->upload->data();
				
				// redimensiona e salva com novo nome
				if($arquivo = $upload_data['file_name']) {
				
					$config['upload_path'] = 'uploads/selos/';
					$this->upload->initialize($config);
					
					$this->upload->do_upload('selo');
					
						if($selo = $this->upload->data()) {
						$selo = $selo['file_name'];
						} else {
						$selo = "";
						}

						
					$this->load->library('validation');
					$rules['titulo'] = 'trim|required|xss_clean';
					$rules['data'] = 'trim|required';
					$this->validation->set_rules($rules);
					
					$fields['titulo'] = 'Titulo';
					$fields['data'] = 'Data';
					$this->validation->set_fields($fields);
					
					if($this->validation->run())
					{
						$data['titulo']     = $this->validation->titulo;
						$tmp_data = explode("/", $this->validation->data);
						$dia = $tmp_data[0];
						$mes = $tmp_data[1];
						$ano = $tmp_data[2];
						if($ano)
						{
							$data['data_exame'] = mktime(1,1,1,$mes,$dia,$ano);
							$data['data_insercao'] = time();
							$data['userid'] = $this->getPost('userid');
							$data['arquivo']     = $upload_data['file_name'];	
							$data['selo'] = $selo;		
							$data['adminid'] = $this->user_model->logged();	
													
								
								if($this->exames_model->registerExame($data)) {
									$data['eid'] = mysql_insert_id();
									$data['success'] = 'yes';
									$data['message'] = "Exame adicionado!";
									$data['data_exame_formatada'] = date("d/m/Y",$data['data_exame']);
									 $output = json_encode($data);
								} else {
									 $output = '{ "success": "no", "message": "Ocorreu um erro no registro." }';
								}
						} else {
							 $output = '{ "success": "no", "message": "O Formato de data informado &eacute; inv&aacute;lido!" }';
						}
					} else {
						 $output = '{ "success": "no", "message": "'.$this->validation->error_string.'" }';
					}
					
				} else {// upload_data
					$output = '{ "success": "no", "message": "Erro ao enviar o arquivo (cod #1) - '.$this->upload->display_errors().'" }';
					
				}
			} else { 
			$output = '{ "success": "no", "message": "Erro ao enviar o arquivo (cod #2). - '.$this->upload->display_errors().'" }';
				
			}
		} else {
			
			$output = '{ "success": "no", "message": "Sem pemiss&atilde;o para efetuar a altera&ccedil;&atilde;o." }';
		} //do_upload
			
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
		echo ("<textarea>");
		echo $output;
		echo ("</textarea>");
	}
	
	function isviewed() {
		$userid = $this->uri->segment(3);
		$trueorfalse = $this->uri->segment(4);
		$targetpriv = $this->user_model->getUserPriv($userid);
		if(($this->user_model->logged())
			&&($this->user_model->isadmin())
			&&($this->user_model->hasPriv($targetpriv+1))
			) {
			if($this->user_model->blockUser($userid, $trueorfalse)) {
				$output = '{ "success": "yes", "message": "OK" }';
			} else {
				$output = '{ "success": "no", "message": "Erro: Banco de Dados!" }';
			}
		} else {
			$output = '{ "success": "no", "message": "Erro: Sem permiss&atilde;o!" }';
		}
		
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        echo $output;
	}	
	
	function _hasPriv($minpriv) {
		if($this->user_model->getUserPriv() >= $minpriv)
		return true;
		else
		return false;
	}
}

?>