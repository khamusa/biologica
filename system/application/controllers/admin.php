<?

/*
sistema de logo: envio de logotipo e opção de apagar os q já existem. único dado a ser colocado é o nome do cliente e link pro site dele.
então tabela:
	id
	nome
	site
	imagem
*/
class Admin extends Controller {

	function Admin() {
		parent::Controller();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->model('user_model');

	}
	
	function index() {
	if(!$this->session->userdata('logged_in')) {
			$data['title'] = "Floresta Mágica Hotel e Eventos";
			$this->load->view('header_view', $data);
        	$this->load->view("user_login_view");
			$this->load->view('footer_view', $data);
		} else if($this->user_model->getUserPriv() < 50) {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['title'] = "Floresta Mágica Hotel e Eventos";
			$this->load->view('header_view', $data);
        	$this->load->view("main_admin_view");
			$this->load->view('footer_view', $data);
		}
	}
	
	function adiciona() {
		if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
		$this->output->set_header("charset:utf-8");
		$data['title'] = "Adicionar Imagem";
		$this->load->view('imagens_add_view', $data);
		}
	}
	
	function lista() {
		if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['title'] = "Floresta Mágica - Lista de Imagens";
			$this->db->orderby('id', 'desc');		
				$data['query'] = $this->db->get('imagens');
			
			
			$this->load->view('imagens_lista_view', $data);

		}
	}
	
	function lista_json() {
		$data['title'] = "Floresta Mágica - Lista de Imagens";
		$this->output->set_header("Cache-Control: no-cache, must-revalidate");
		$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
		$this->db->orderby('id', 'desc');		
			$data['query'] = $this->db->get('imagens');
		
		$json = $this->load->view('imagens_json_view', $data);
		
	}
	function remove() {
	$this->load->model('user_model');
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$data['title'] = "Remoção de imagem";
			$this->db->where('id',$this->uri->segment(3));
			
			// descobre qual o arquivo da imagem
			$query = $this->db->get('imagens');
			$row = $query->row();
			
			// apaga o arquivo do servidor:
			if(file_exists("./uploads/".$row->arquivo))
					{
						if(!unlink("./uploads/".$row->arquivo)) 
						{
							// O arquivo existe mas não foi apagado... não rola apagar do DB
							$output = '{ "success": "no", "message": "A Imagem não pode ser removida do servidor." }';
						} else {
							// o arquivo foi apagado, remova do BD
							if($this->user_model->remove_img($this->uri->segment(3)))
							{
							$output = '{ "success": "yes", "message": "Imagem Removida" }';
							} else {
							$output = '{ "success": "no", "message": "Erro ao alterar o banco de dados." }';
							}
						}
					} else {
						// o arquivo não existe, não há motivo para manter o DB então
						if($this->user_model->remove_img($this->uri->segment(3)))
						{
						$output = '{ "success": "yes", "message": "Imagem Removida" }';
						} else {
						$output = '{ "success": "no", "message": "Erro ao alterar o banco de dados." }';
						}
					}
					
		$output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
			
		}
	}
	
	function view_image () {
	//admin/view_image/"+i+"'
		$this->output->set_header("Cache-Control: no-cache, must-revalidate");
		$this->output->set_header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");
		$imgid = $this->uri->segment(3);
		$leftright = $this->uri->segment(4);
		$this->db->where('id', $imgid);	
		$query = $this->db->get('imagens');
		
		 	if ($query->num_rows() > 0 ) {
            	$row = $query->row(); 
				
				$config['source_image'] = './uploads/'.$row->arquivo;
				$config['wm_type'] = 'overlay';
				$config['wm_vrt_alignment'] = 'top';
				$config['wm_hor_alignment'] = 'left';
				if($leftright == 1)
				$config['wm_overlay_path'] = './uploads/maskleft.gif';
				else
				$config['wm_overlay_path'] = './uploads/maskright.gif';
				$config['wm_opacity'] = 100;
				$config['wm_x_transp'] = 80;
				$config['wm_y_transp'] = 80;
				$config['dynamic_output'] = true;
				

				$this->load->library('image_lib', $config);

				$this->image_lib->watermark();
			
				
			} else {
				return false;
			}		
	
	}
	function imagens_inserir() {
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			// processa formulario enviado pelo add()
			
			// efetua o upload da imagem
			$config['upload_path'] = 'uploads/temp/';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '10000';
	
			$this->load->library('upload', $config);
			
			//$this->upload->do_upload('img');
			
			if ($this->upload->do_upload('img'))
			{
				
				$upload_data = $this->upload->data();
				
				// redimensiona e salva com novo nome
				if($upload_data['file_name'])
				{
					$size = getimagesize($upload_data['full_path']);
					$imgprop = $size[0] / $size[1];
					// dimensões desejadas
					$par['width'] = 176;
					$par['height'] = 115;
					$par['prop'] = $par['width']/$par['height'];
					//	
					
					// REDIMENSIONA O MAIS PRÓXIMO POSSÍVEL
					$newname = md5($upload_data['full_path'].time());
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_data['full_path'];
					$config['new_image'] = './uploads/'.$newname.'.jpg';
					$config['maintain_ratio'] = TRUE;
					if($imgprop >= $par['prop']) {
							$newwidth = $imgprop*$par['height'];
							$newheight = $par['height'];
						} else {
							$newwidth = $par['width'];
							$newheight = $par['width']/$imgprop;
						}
					$config['width'] = $newwidth;
					$config['height'] = $newheight;
					
					$this->load->library('image_lib', $config);
					if($this->image_lib->resize())
					{
							// CORTA O EXCEDENTE
						chmod('./uploads/'.$newname.'.jpg', 0777);
						$config2['image_library'] = 'gd2';
						$config2['source_image'] = './uploads/'.$newname.'.jpg';
						$config2['x_axis'] = $newwidth-$par['width'];
						$config2['y_axis'] = $newheight-$par['height'];
						$config2['width'] = $par['width'];
						$config2['height'] = $par['height'];
						$config2['maintain_ratio'] = FALSE;
						
						$this->image_lib->clear();
						$this->image_lib->initialize($config2);
					
					if ($this->image_lib->crop())
					{

						chmod('./uploads/'.$newname.'.jpg', 0777);
						// remove arquivo temporário
						if(file_exists($upload_data['full_path']))
						{
							unlink($upload_data['full_path']);
						}
						
							//resize
						$inserir["arquivo"] = $newname.'.jpg';							
						if($this->db->insert('imagens', $inserir)) {
							$output = '{ "success": "yes", "message": "A imagem foi inserida!", "arquivo": "'.$inserir["arquivo"].'", "id":"'.mysql_insert_id().'" }';
						} else {
							$output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }';
						} //insert db
						
				} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; }// !$this->image_lib->crop()
					} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; } // $this->image_lib->resize()
				} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; } //$upload_data['file_name']
			} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; } //faz o upload
					
		$output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
		} // if estál ogado
	}
	function password_edit() {		
		if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['uid'] = $this->session->userdata('logged_in');
			$data['title'] = "Mudança de Senha";
			$data['title'] = "Floresta Mágica - Alteração de Senha";
		//	$this->load->view('header_view', $data);
			$this->load->view('user_passchange_view', $data);
			//$this->load->view('footer_view', $data);
		}
	}
	function user_editar() {
	}	
}
?>