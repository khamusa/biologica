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
class Servicos extends Controller {

	function Servicos() {
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
		$data['title'] = "Biológica - Servicos";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('footer_view', $data);
	}

	function coleta() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Coleta e Amostragem";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_coleta_view');
		$this->load->view('footer_view', $data);
	}
		function agua() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Água e Efluentes";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_agua_view');
		$this->load->view('footer_view', $data);
	}
		function ecotoxicidade() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Ecotoxicidade";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_ecotoxicidade_view');
		$this->load->view('footer_view', $data);
	}
		function ar() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Ar";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_ar_view');
		$this->load->view('footer_view', $data);
	}
	
		function residuos() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Resíduos Sólidos Industriais e Biosólidos";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_residuos_view');
		$this->load->view('footer_view', $data);
	}
		function solo() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Solo";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_solo_view');
		$this->load->view('footer_view', $data);
	}
	
		function outros() {
		//$this->db->orderby('nome', 'asc');
		//$data['query'] = $this->db->get('clientes');
		$data['title'] = "Biológica - Serviços: Outros";
		$data['index'] = false;
		$data['logged'] = $this->user_model->logged();
		$data['admin'] = $this->user_model->isadmin();
		$this->load->view('header_view', $data);
		$this->load->view('servicos_menu_view');
		$this->load->view('servicos_outros_view');
		$this->load->view('footer_view', $data);
	}
	
	}
?>