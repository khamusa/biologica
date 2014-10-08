<?

/*
    * Elaboração html/css + AJAX no FRONTEND.
    * Sistema dinâmico em php + mysql.
    * Área do Cliente:
          o Acesso e modificação dos próprios dados cadastrais. - OK
          o Acesso a arquivos e resultados postados pelos administradores.  - OK
          o Possibilidade de preenchimento dos relatórios de satisfação do cliente (modelo a ser elaborado pelo cliente ou pela Raiz 16).
          o Envio de E-mail / contato aos administradores - ordenar layout
    * Área do Administrador
          o Recursos:
                + Cadastro de Resultado de análise com envio de arquivo(s), por exemplo PDF ou DOC.
                + Consulta aos relatórios enviados pelos clientes.
                + Criação e edição de usuários (clientes e administradores)
                + Publicação de selos de qualidade (associado ao resultado ou independente)

*/
class Main extends Controller {

	function Main() {
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->model('user_model');
		
		
		//$this->load->scaffolding('clientes');
	}
	
	function index() {
		// página principal
		//$this->output->set_header("charset:utf-8");
		$data['title'] = "Biológica";
		$data['index'] = true;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('main_view');
		$this->load->view('footer_view', $data);
	}
	function contato() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Contato";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('faleconosco_view');
		$this->load->view('footer_view', $data);
	}
	
	function parceria_ecoar() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Parceria ECOAR";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('ecoar_view');
		$this->load->view('footer_view', $data);
	}
	
	function institucional() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Institucional";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('institucional_view');
		$this->load->view('footer_view', $data);
	}
	
	function servicos() {
		redirect('servicos');
	}
	
	function pesquisa() {
	//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Pesquisa e Desenvolvimento";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('pesquisa_view');
		$this->load->view('footer_view', $data);
	}
	
		function clientes() {
	//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Clientes e Parceiros";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('clientes_view');
		$this->load->view('footer_view', $data);
	}
	
	function verExames($userid) {
	$data['logged'] = $this->user_model->_logged();
	$data['admin'] = $this->user_model->isadmin();
	$data['uid'] = strip_tags(htmlentities($userid));
		// you can only see the exams section if it's your personal section or if you're an admin
		if(($this->_logged() == $userid)||($this->isadmin())) {
			if($data['query'] = $this->exames_model->pegaExames($userid)) {
				$this->load->view('exames_list_view', $data);
			} else {
				echo("erro ao obter exames");
			}
		} else {
			redirect('');
		}
    }
	
	
	function enviamail($interno = 0) {
		$this->load->library('email');
		 $this->load->library('validation');
		 
		//$this->load->model('user_model');
		
		$rules['nome'] = 'trim|required|xss_clean';
        $rules['email'] = 'trim|required|valid_email|xss_clean';
		$rules['telefone'] = 'trim|xss_clean';
		$rules['empresa'] = 'trim|xss_clean';
		$rules['setor'] = 'trim|xss_clean';
		$rules['assunto'] = 'trim|xss_clean';
		$rules['mensagem'] = 'trim|xss_clean';
        $this->validation->set_rules($rules);
        
        $fields['nome'] = 'Nome';
        $fields['email'] = 'E-mail de contato';
		$fields['telefone'] = 'Telefone';
		$fields['assunto'] = 'Assunto';
		$fields['empresa'] = 'Empresa';
		$fields['setor'] = 'Setor';
		$fields['mensagem'] = 'Mensagem';
        $this->validation->set_fields($fields);
		
		if($this->validation->run())
		{
			$config['useragent'] = 'Biológicaform';
			$config['mailtype'] = 'html';
			$config['wordwrap'] = TRUE;
			
			$this->email->initialize($config);
	
			$this->email->from($this->validation->email, $this->validation->nome);
			$this->email->reply_to($this->validation->email, $this->validation->nome);
			if($interno == 1) {
				$this->email->to('biologica.bh@biologicalab.com.br');
			} else {
				$this->email->to('comercial@biologicalab.com.br');
			}
			$this->email->cc('gb.samuel@gmail.com');	
			$this->email->subject('[Contato Biológica]: '.$this->validation->assunto);
			$mensagem = "Segue abaixo dados de contato enviados pelo site <br><br>";
			$mensagem .= "Assunto: ".$this->validation->assunto;
			$mensagem .= "<br> Enviado Por: ".$this->validation->nome;
			$mensagem .= "<br> Empresa: ".$this->validation->empresa;
			$mensagem .= "<br> Setor: ".$this->validation->setor;
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