<?

/*
sistema de logo: envio de logotipo e opção de apagar os q já existem. único dado a ser colocado é o nome do cliente e link pro site dele.
então tabela:
	id
	nome
	site
	imagem
*/
class Clientes extends Controller {

	function Clientes() {
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->model('user_model');
		$this->load->model('exames_model');
		$this->load->config('usersconfig');
		
		
	}
	
	function index() {
			$userid = $this->user_model->logged();
		if(($userid == "")) {
			redirect("user");
		} else {
			if($this->user_model->getUserPriv($userid) >= 50) {
				redirect("admin");
			} else {
				redirect("clientes/minhaarea");
			}
		}
	}
	
	function minhaarea() {
		$userid = $this->uri->segment(3);
		// wich users page
		if(($userid == "")) {
			$userid = $this->user_model->logged();
		}
		if(($userid == "")) {
			redirect("user");
		}
		 // this function will load user's personal page, containing his own data, exam results and contact forms.
		 // you can only see the exams section if it's your personal section or if you're an admin
		 if(!
		 (
		 ($this->user_model->logged() == $userid)
		 ||(($this->user_model->isadmin())&&($this->user_model->hasPriv($this->user_model->getUserPriv($userid))))
		 )) {
			redirect("user");
			}
		 // preliminary attributes
		 $data['index'] = false;
		 $data['title'] = "&Aacute;rea do Cliente";
		 $data['admin'] = $this->user_model->isadmin();
		 $data['logged'] = $this->user_model->logged();
		 $data['uid'] = $userid;
		 $data['userpriv'] = $this->user_model->getUserPriv();
		 $data['privnames'] = $this->config->item('privnames');
		
		// load headers
		$this->load->view('header_view', $data);
		// load admin menu if is an admin
		//if($data['admin'])
		//$this->load->view('admin_menu_view', $data);
		
		 // load exam's portion of the code	
		if($data['query'] = $this->exames_model->pegaExames($userid)) {
		} else {
			echo("Erro ao obter exames!");
		}
		
		//load user data portion of the code
		if($data['row'] = $this->user_model->findUser($data['uid']))
		{		
			// exams 
			$this->load->view('exames_list_view', $data);
			// data
			$this->load->view("user_data_view", $data);
		} else {
			echo("Erro ao obter as informa&ccedil;&otilde;es do usu&aacute;rio!");
		}
		// load passchange part
		$this->load->view("user_passchange_view", $data);
		
		// load quality reports forms
		
		// load common contact form
		$this->load->view("user_faleconosco_view", $data);
		$this->load->view('footer_view', $data);

	}
	function enviamail() {
		$this->load->library('email');
		 $this->load->library('validation');
		 
		//$this->load->model('user_model');
		
		$rules['nome'] = 'trim|required|xss_clean';
        $rules['email'] = 'trim|required|valid_email|xss_clean';
		$rules['telefone'] = 'trim|xss_clean';
		$rules['assunto'] = 'trim|xss_clean';
		$rules['mensagem'] = 'trim|xss_clean';
		$rules['empresa'] = 'trim|xss_clean';
		$rules['empresa_id'] = 'trim|xss_clean';
        $this->validation->set_rules($rules);
        
        $fields['nome'] = 'Nome';
        $fields['email'] = 'E-mail de contato';
		$fields['telefone'] = 'Telefone';
		$fields['assunto'] = 'Assunto';
		$fields['mensagem'] = 'Mensagem';
		$fields['empresa'] = 'Empresa';
		$fields['empresa_id'] = 'Id do usuário';
        $this->validation->set_fields($fields);
		
		if($this->validation->run())
		{
			$config['useragent'] = 'Biológicaform';
			$config['mailtype'] = 'html';
			$config['wordwrap'] = TRUE;
			
			$this->email->initialize($config);
	
			$this->email->from($this->validation->email, $this->validation->nome);
			$this->email->reply_to($this->validation->email, $this->validation->nome);
			$this->email->cc('gb.samuel@gmail.com');	
			$this->email->to('biologica.bh@biologicalab.com.br');	
			$this->email->subject('[Contato Biológica]: '.$this->validation->assunto);
			$mensagem = "Esta mensagem foi enviada por um cliente registrado através do site da Biológica. <br><br>";
			$mensagem .= "Enviado pelo usuário: ".$this->validation->empresa;
			$mensagem .= "<br> Página do usuário: ".site_url()."/clientes/minhaarea/".$this->validation->empresa_id;
			$mensagem .= "<br>Assunto: ".$this->validation->assunto;
			$mensagem .= "<br> Enviado Por: ".$this->validation->nome;
			$mensagem .= "<br> E-mail de contato: ".$this->validation->email;
			$mensagem .= "<br> Telefone de contato: ".$this->validation->telefone;
			$mensagem .= "<br> Mensagem enviada: <br>".$this->validation->mensagem;
			$this->email->message($mensagem);
			
			if($this->email->send())
			{
				$output = '{ "success": "yes", "message": "E-mail Enviado!" }';
			} else {
				$output = '{ "success": "no", "message": "Ocorreu um erro no envio do e-mail", "erro: Problema no envio do e-mail, contactar o administrador do sistema." }';
			}
		} else {
			$output = '{ "success": "no", "message": "Ocorreu um erro no envio do e-mail", "erro:'.validation_errors().'" }';
		}
		$output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
}
}
?>