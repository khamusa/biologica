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
class User extends Controller {

    function User() {
        parent::Controller();
        
        $this->load->model("user_model");
		$this->load->model("exames_model");     
		
        $this->load->helper("security");
        $this->load->helper("form");
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->config('usersconfig');
		
		// check if tables exists, if not, makes!
		if((!$this->db->table_exists('ci_sessions'))
			||(!$this->db->table_exists('users'))) {
			$this->user_model->_mkTables();
			} 
		if($this->user_model->checkUsernameAvailable('admin'))
				{
					// no admin user, create it
					$data['username']     = 'admin';
					$data['password']     = md5('admin');
					$data['priv'] = 99;
					$data['email'] = 'no@email.com';
					$this->user_model->registerUser($data);
				}
		
    }
    
   
	
    function index() {
		$this->output->set_header("Cache-Control: no-cache, must-revalidate");
		$this->output->set_header("Expires: Sat, 26 Jul 2010 05:00:00 GMT");
		$data['index'] = false;
		if(!$this->user_model->logged()) {
				$data['title'] = "Biológica";
				$data['logged'] = $this->user_model->logged();
				$data['admin'] = $this->user_model->isadmin();
				
			$this->load->view('header_view', $data);
			$this->load->view("user_login_view");
			$this->load->view("footer_view");
		} else {
			if($this->user_model->isadmin()) {
			// redirect to administration area
			redirect("user/listuser");
			} else {
			// redirect to user's personal area
			redirect("clientes/minhaarea");
			}
		}
    }

	function addUser() { 
	$data['priv'] = $this->user_model->getUserPriv();
	$data['privnames'] = $this->config->item('privnames');
	$data['title'] = 'Adição de clientes';
	$data['index'] = false;
	$data['logged'] = $this->user_model->logged($data);
	$data['admin'] = $this->user_model->isadmin();
	// checks if the user is logged in and has a BACKEND priv
		if(($this->user_model->logged())&&($this->user_model->isadmin())) {
			$this->load->view("header_view", $data);		
			//$this->load->view("user_admn_menu_view", $data);
			$this->load->view("user_add_view", $data);
			$this->load->view("footer_view", $data);
			
		} else {
			redirect("user/edituser/".$this->user_model->logged());
		}
	}
	
	function editUser() {
		$data['uid'] = $this->uri->segment(3);
		$data['title'] = 'Lista de usuários';
		$data['privnames'] = $this->config->item('privnames');
		$data['priv'] = $this->user_model->getUserPriv();
		$data['index'] = false;
		if(($this->user_model->logged())&&($this->user_model->isadmin())) {
			if($data['row'] = $this->user_model->findUser($data['uid']))
			{		
				$this->load->view("user_edit_view", $data);
			}
		} else {
		}
	}
	
	function listUser() 
	{
	$data['title'] = 'Sistema de Administra&ccedil;&atilde;o';
	$data['privnames'] = $this->config->item('privnames');
	$data['priv'] = $this->user_model->getUserPriv();
	$data['logged'] = $this->user_model->logged($data);
	$data['admin'] = $this->user_model->isadmin();
	$data['index'] = false;
		if(($this->user_model->logged())&&($this->user_model->isadmin())) {
			$data['query'] = $this->user_model->findUsers($this->user_model->getUserPriv());	
			foreach($data['query']->result() as $row) {
				$exames = $this->exames_model->pegaExames($row->uid, 3);
				$c = 0;
				if($exames->num_rows() > 0) {
				foreach($exames->result() as $exam_row) {
					$data['exameslist'][$row->uid][$c]["id"] = $exam_row->id;
					$data['exameslist'][$row->uid][$c]["titulo"] = $exam_row->titulo;
					$data['exameslist'][$row->uid][$c]["arquivo"] = $exam_row->arquivo;
					$data['exameslist'][$row->uid][$c]["selo"] = $exam_row->selo;
					$data['exameslist'][$row->uid][$c]["data_exame"] = $exam_row->data_exame;
					$c++;
				}
				} else {
					$data['exameslist'][$row->uid] = false;
				}
			}
			
			$this->load->view("header_view", $data);		
			//$this->load->view("user_admn_menu_view", $data);
			$this->load->view("user_list_view", $data);
			$this->load->view("footer_view", $data);
		} else if(($this->user_model->logged())){
			redirect("clientes/minhaarea");
		} else {
			redirect("");
		}
	}
	
		
    function logout()
    {
        $this->session->sess_destroy();
        redirect("");
    }
	
	function removeUser() {
		$uid = strip_tags(htmlentities($this->uri->segment(3)));
		// verifica privilegios
		// adds 1 because we want the user to have a HIGHER priv, not the same
		$targetpriv = $this->user_model->getUserPriv($uid)+1;
		if($this->user_model->haspriv($targetpriv)&&($this->user_model->isadmin())){
			if($this->user_model->removeUser($uid)) {
				$this->exames_model->removeExamesPorUser($uid);
				redirect("user/listuser");
			} else {
				redirect("minhaarea/".$uid);
			}
		} else {
			redirect("minhaarea/".$uid);
        }
        
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
	}
	
	/*
	* This function is called when the user submits the form, and returns a JSON string
	*
	*
	*/
    function checkLogin()
    {
        $this->load->library('validation');
        $rules['username'] = 'trim|required|xss_clean|callback__check_login';
        $rules['password'] = 'trim|required';
        $this->validation->set_rules($rules);
        
        $fields['username'] = 'username';
        $fields['password'] = 'password';
        $this->validation->set_fields($fields);
        
		if ($this->validation->run()) {
            $username     = $this->validation->username;
            $uid         = $this->user_model->getUserId($username,"users");     
			if($this->user_model->userIsActive($uid))
			{       
            	$this->session->set_userdata("logged_in",$uid);	         
   				$output = '{ "success": "yes", "message": "Login efetuado." }';
			} else {
				$output = '{ "success": "no", "message": "Este usu&aacute;rio foi desativado, contate os administradores." }';
			}
			
        } else {
			$output = '{ "success": "no", "message": "Erro no login, tente novamente." }';
		}
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
		echo($output);
    }
    
	/* */
	
	function getPost($name) {
		if(isset($_POST[$name])){
			return htmlentities($_POST[$name], ENT_COMPAT, 'UTF-8');
		} else {
			return "";
			}
	}
	
	function update() {
		$data['logged'] = $this->user_model->logged($data);
		$data['admin'] = $this->user_model->isadmin();
		if($this->session->userdata('logged_in')) {
			$this->load->library('validation');
			$rules['priv'] = 'integer|callback__noSuperAdministrator|callback__hasPriv';
			$this->validation->set_rules($rules);
			$fields['priv'] = 'Privil&eacute;gios';
			$this->validation->set_fields($fields);
			$this->validation->set_message('_hasPriv','Você não tem privilégios para editar este usuário');
			$this->validation->set_message('_noSuperAdministrator','Não é possível adicionar superadministradores');
			
			if($this->validation->run())
			{
				$data['priv']     = $this->validation->priv;
					$data['dado_cnpj'] = $this->getPost('dado_cnpj');
					$data['dado_telefone'] = $this->getPost('dado_telefone');
					
					$end['uf'] = $this->getPost('uf');
					$end['cidade'] = $this->getPost('cidade');
					$end['logradouro'] = $this->getPost('logradouro');
					$end['numero'] = $this->getPost('numero');
					$end['complemento'] = $this->getPost('complemento');
					$end['bairro'] = $this->getPost('bairro');
					$end['cep'] = $this->getPost('cep');
					$data['dado_endereco'] = json_encode($end);
					
					$uid2 = $this->getPost('uid'); 
					$uid = $this->uri->segment(3);
				
				if(($uid == $uid2)&&($this->user_model->updateuser($data, $uid))) {
					$data['uid'] = mysql_insert_id();
					$data['success'] = 'yes';
					$data['message'] = "Dados alterados!";
					$data['privname'] = $this->config->item('privnames');
					$data['privname'] = $data['privname'][$data['priv']];				
					
					 $output = json_encode($data);
				} else {
					 $output = '{ "success": "no", "message": "Ocorreu um erro ao inserir o registro." }';
				}
			} else {
				 $output = '{ "success": "no", "message": "'.$this->validation->error_string.'" }';
			}
			
			
		} else {
			$output = '{ "success": "no", "message": "Sem pemiss&atilde;o para efetuar a altera&ccedil;&atilde;o." }';
		}
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
		echo $output;
	}
	
	
	function register() {
		if($this->session->userdata('logged_in')) {
			$this->load->library('validation');
			$rules['username'] = 'trim|required|xss_clean|alpha_dash|callback__username_exists|max_length[16]';
			$rules['password'] = 'trim|required|matches[confpassword]|md5';
        	$rules['confpassword'] = 'trim|required|md5';
			$rules['email'] = 'trim|required|valid_email';
			$rules['priv'] = 'integer|callback__noSuperAdministrator|callback__hasPriv';
			$this->validation->set_rules($rules);
			
			$fields['username'] = 'Nome de usu&aacute;rio';
			$fields['password'] = 'Senha';
			$fields['confpassword'] = 'Confirma&ccedil;&atilde;o de Senha';
			$fields['priv'] = 'Privil&eacute;gios';
			$fields['email'] = 'Email';
			$this->validation->set_fields($fields);
			
			$this->validation->set_message('_username_exists','Nome de usuário já existe');
			$this->validation->set_message('_hasPriv','Você não tem privilégios para adicionar este usuário');
			$this->validation->set_message('_noSuperAdministrator','Não é possível adicionar superadministradores');
			
			if($this->validation->run())
			{
				$data['username']     = $this->validation->username;
				$data['password']     = $this->validation->password;
				$data['priv']     = $this->validation->priv;
				$data['email']     = $this->validation->email;
				$data['dados_cnpj'] = trim($this->getPost('dados_cnpj'));
				$data['dados_nome'] = trim($this->getPost('dados_nome'));
				
				$dados_responsavel = $_POST['dados_c_responsavel'];
				$dados_telefone = $_POST['dados_c_telefone'];
				$dados_cargo = $_POST['dados_c_cargo'];
				$dados_email = $_POST['dados_c_email'];
				
				foreach ($dados_responsavel as $id => $valor) {
				
					$dados_contatos->{$id}->{'responsavel'} = trim(htmlentities($valor, ENT_COMPAT, "UTF-8"));
					$dados_contatos->{$id}->{'telefone'} = trim(htmlentities($dados_telefone[$id], ENT_COMPAT, "UTF-8"));
					$dados_contatos->{$id}->{'cargo'} = trim(htmlentities($dados_cargo[$id], ENT_COMPAT, "UTF-8"));
					$dados_contatos->{$id}->{'email'} = trim(htmlentities($dados_email[$id], ENT_COMPAT, "UTF-8"));
				}
					$end['uf'] = trim($this->getPost('uf'));
					$end['cidade'] = trim($this->getPost('cidade'));
					$end['logradouro'] = trim($this->getPost('logradouro'));
					$end['numero'] = trim($this->getPost('numero'));
					$end['complemento'] = trim($this->getPost('complemento'));
					$end['bairro'] = trim($this->getPost('bairro'));
					$end['cep'] = trim($this->getPost('cep'));
					
				$data['dados_endereco'] = json_encode($end);
				$data['dados_contatos'] = json_encode($dados_contatos);
				
				if($this->user_model->registerUser($data,"users")) {
					$data['uid'] = mysql_insert_id();
					$data['success'] = 'yes';
					$data['message'] = "Usu&aacute;rio adicionado!";
					$data['privname'] = $this->config->item('privnames');
					$data['privname'] = $data['privname'][$data['priv']];				
					
					 $output = json_encode($data);
				} else {
					 $output = '{ "success": "no", "message": "Ocorreu um erro no registro." }';
				}
			} else {
				 $output = '{ "success": "no", "message": "'.$this->validation->error_string.'" }';
			}
			
			
		} else {
			$output = '{ "success": "no", "message": "Sem pemiss&atilde;o para efetuar a altera&ccedil;&atilde;o." }';
		}
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
		echo $output;
	}
	
	function _matchpassword ($pw) {
		$pw = md5($pw);
		if($this->user_model->matchuserpassword($pw)) {
			return true;
		} else {
			return false;
		}
	}
	
	function blockuser() {
		$userid = $this->uri->segment(3);
		$trueorfalse = $this->uri->segment(4);
		$targetpriv = $this->user_model->getUserPriv($userid);
		if(($this->user_model->logged())
			&&($this->user_model->isadmin())
			&&($this->user_model->haspriv($targetpriv+1))
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
	
	function removecontato($uid, $cid) {
		if($uid == 0)
		return false;
		
		if(($this->user_model->logged() == $uid) // you're editing yourself
			||(
				($this->user_model->isadmin())
				&&($this->user_model->hasPriv($this->user_model->getUserPriv($uid))) // or you're an admin and your privs are higher or the same
			   )
			) {
			if ($userdata = $this->user_model->findUser($uid)) {
					$contatos = json_decode($userdata->dados_contatos);
				
					$i = 0;
					if($contatos) {
					foreach ($contatos as $cnr => $contato):
						if($cnr != $cid)
						{
						$novocontato->{$i} = $contato;
						$i++;
						}
					
					endforeach;
					}
					if($i == 0) {
						$novocontato = "";
					}
					$data = array(
					   'dados_contatos' => json_encode($novocontato),
					);
					if($this->user_model->updatedata($data, $uid)) 
					{
						echo("{'success':'yes', 'message':'Contato removido!'}");
						return true;
					}					
				}
			}
		return false;		
			
			
	}
	
	function saveendfield() 
	{
	
		$campo = $this->getPost("id");
		$valor = $this->getPost("value");
		$uid = $this->getPost("uid");
		
		// preliminary checks
		if($uid == 0)
		return false;
		if($campo == "")
		return false;

		//id=elements_id&value=user_edited_content
		// you need to be loggedin
		if(($this->user_model->logged() == $uid) // you're editing yourself
			||(
				($this->user_model->isadmin())
				&&($this->user_model->hasPriv($this->user_model->getUserPriv($uid))) // or you're an admin and your privs are higher or the same
			   )
			) {
			// then o.k, start playing around! :D
				if ($userdata = $this->user_model->findUser($uid)) {
					$end = json_decode($userdata->dados_endereco);
					$end->{$campo} = $valor;
					
					$data = array(
					   'dados_endereco' => json_encode($end),
					);
					if($this->user_model->updatedata($data, $uid)) 
					{
						echo($valor);
						return true;
					}					
				}
			}
		return false;		
		

	}
	
	function savefield() 
	{
		$campo = $this->getPost("id");
		$valor = $this->getPost("value");
		$uid = $this->getPost("uid");
		
		// preliminary checks
		if($uid == 0)
		return false;
		if($campo == "")
		return false;

		//id=elements_id&value=user_edited_content
		// you need to be loggedin
		if(($this->user_model->logged() == $uid) // you're editing yourself
			||(
				($this->user_model->isadmin())
				&&($this->user_model->hasPriv($this->user_model->getUserPriv($uid))) // or you're an admin and your privs are higher or the same
			   )
			) {
			// then o.k, start playing around! :D
			
					if(($campo != 'uid')&&($campo != 'password')&&($campo != 'username'))
					{
						$data = array(
						   $campo => $valor,
						);
						if($this->user_model->updatedata($data, $uid)) 
						{
							echo($valor);
							return true;
						}	
					}				
			}
		return false;		
		

	}
	
	function savefonefield() 
	{
		$campo = $this->getPost("id");
		$valor = $this->getPost("value");
		$uid = $this->getPost("uid");
		
		// preliminary checks
		if($uid == 0)
		return false;
		if($campo == "")
		return false;

		//id=elements_id&value=user_edited_content
		// you need to be loggedin
		if(($this->user_model->logged() == $uid) // you're editing yourself
			||(
				($this->user_model->isadmin())
				&&($this->user_model->hasPriv($this->user_model->getUserPriv($uid))) // or you're an admin and your privs are higher or the same
			   )
			) {
			// then o.k, start playing around! :D
				if ($userdata = $this->user_model->findUser($uid)) {
					$contatos = json_decode($userdata->dados_contatos);
					$exp = explode('_',$campo);
					$contatonr = $exp[0];
					$contatocampo = $exp[1];
				
					$contatos->{$contatonr}->{$contatocampo} = $valor;
					
					$data = array(
					   'dados_contatos' => json_encode($contatos),
					);
					if($this->user_model->updatedata($data, $uid)) 
					{
						echo($valor);
						return true;
					}					
				}
			}
		return false;		
		

	}
	
	function changepassword()
    {
		if($this->session->userdata('logged_in')) {
        $this->load->library('validation');
			
		$uid = $this->getPost('uid');
		if(($this->user_model->logged() == $uid)||($uid == "")) // you're editing yourself
		{	
			$uid = $this->user_model->logged();
			$rules['atualpassword'] = 'trim|required|callback__matchpassword';
			$fields['atualpassword'] = 'Senha Atual';
			$uid = $this->user_model->logged();
		} else if ( // you're not editing yourself, then you MUST match the following conditions:
				($this->user_model->isadmin()) // must be a priv higher then 50
				&& ($this->user_model->hasPriv($this->user_model->getUserPriv($uid))) // your priv must be higher then your target's
				) 
				{
				// you're not editing yourself, then you MUST have privileges
		//it's ok, just let it continue! D
			$rules['adminpassword'] = 'trim|required|callback__matchpassword';
			$fields['adminpassword'] = 'Senha do Administrador';
		} else {// any other case shouldn't be allowed
			$output = '{ "success": "no", "message": "Voc&ecirc; n&atilde;o possui privil&eacute;gios!" }';
			$output = str_replace("\r", "", $output);
			$output = str_replace("\n", "", $output);
        
			echo $output;
			return false;
		}

        $rules['password'] = 'trim|required|matches[confpassword]|md5';
        $rules['confpassword'] = 'trim|required|md5';
        $this->validation->set_rules($rules);
        
       
        $fields['password'] = 'Nova Senha';
		$fields['confpassword'] = 'Confirmar Senha';
        $this->validation->set_fields($fields);
        
        if ($this->validation->run()) {
			if($this->user_model->changepassword($this->validation->password, $uid))
			{     
           		$output = '{ "success": "yes", "welcome": "A senha foi alterada." }';
			} else {
				$output = '{ "success": "no", "message": "Erro, tente novamente. '.$this->validation->error_string.'" }';
			}
        } else {
            $output = '{ "success": "no", "message": "Erro, tente novamente. '.$this->validation->error_string.'" }';
        }
        } else {
			 $output = '{ "success": "no", "message": "&Eacute; necess&aacute;rio entrar no sistema. (Tentativa registrada)" }';
		}
        $output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
    }

	function checkusernameavailable($username) {
		$username = htmlentities(strip_tags($username), ENT_COMPAT, 'UTF-8');
		if($this->user_model->checkUsernameAvailable($username,"users"))
            echo("{\"available\" : \"yes\", \"username\": \"".$username."\"}");
		else
			 echo("{\"available\" : \"no\", \"username\": \"".$username."\"}");
	}

	/* AUXILIARY FUNCTIONS
	*/
	/* Checks if the user has the minimum privileges... only a wrapper to the model -*/
	
	
	
	/* wont let any priv higher then 99 to be registred */
	function _noSuperAdministrator($priv){
		if($priv >= 99) {
			return false;
		} else {
			return true;
		}
	}
	
		 /* Checks if the password that the user gives is equal at the DB ones */
    function _check_login($username) {
        $password = md5($this->validation->password);
        
        if(!$this->user_model->checkUserLogin($username,$password,"users"))
            return FALSE;
            
        return TRUE;
    }
    
	/* Checks if the username exists */
	function _username_exists($username) {
		if($this->user_model->checkUsernameAvailable($username,"users"))
            return TRUE;
            
        return FALSE;
	}
	
	function _hasPriv($minpriv) {
		if($this->user_model->getUserPriv() >= $minpriv)
		return true;
		else
		return false;
	}
}

?>