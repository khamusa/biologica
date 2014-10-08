<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Promocao extends Controller {
	
    function Promocao() {
        parent::Controller();
        
        $this->load->model("user_model");
          
        $this->load->helper("security");
        $this->load->helper("form");
		$this->load->helper('html');
		$this->load->helper('url');
    }
		
	function lista() {
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['title'] = "Floresta Mágica Hotel e Eventos";
			$this->db->orderby('id', 'desc');		
				$data['query'] = $this->db->get('promocao');
			
			
			$this->load->view('promocao_lista_view', $data);

		}
	}
	
	function remove() {
	$this->load->model('user_model');
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['title'] = "Remoção de imagem";
			$this->db->where('id',$this->uri->segment(3));
			
			// descobre qual o arquivo da imagem
			$query = $this->db->get('promocao');
			$row = $query->row();
			
			// apaga o arquivo do servidor:
			if(file_exists("./uploads/promocoes/".$row->arquivo))
					{
						if(!unlink("./uploads/promocoes/".$row->arquivo)) 
						{
							// O arquivo existe mas não foi apagado... não rola apagar do DB
							$output = '{ "success": "no", "message": "A Imagem não pode ser removida do servidor." }';
						} else {
							// o arquivo foi apagado, remova do BD
							if(file_exists("./uploads/promocoes/thumbs/".$row->arquivo))
								{
									if(unlink("./uploads/promocoes/thumbs/".$row->arquivo)) 
									{
										if($this->user_model->remove_promocao($this->uri->segment(3)))
											{
											$output = '{ "success": "yes", "message": "Imagem Removida" }';
											} else {
											$output = '{ "success": "no", "message": "Erro ao alterar o banco de dados." }';
											}
									} else {
										$output = '{ "success": "no", "message": "A Imagem não pode ser removida do servidor." }';
									}// unlink thumb
								} else {
									$output = '{ "success": "no", "message": "A Imagem não pode ser removida do servidor." }';
								}// file exists thumb
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
	
	function adiciona() {
		if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			// processa formulario enviado pelo add()
			//$this->output->set_header("charset:utf-8");
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
					
					//	
					
					// REDIMENSIONA O MAIS PRÓXIMO POSSÍVEL
					$newname = md5($upload_data['full_path'].time());
					
					// imagem da promoção em tamanho maior:
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_data['full_path'];
					$config['new_image'] = './uploads/promocoes/'.$newname.'.jpg';
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 670;
					$config['height'] = 450;
					
					$this->load->library('image_lib', $config);
					$this->image_lib->resize();
					// miniatura em tamanho menor
					$par['width'] = 400;
					$par['height'] = 210;
					$par['prop'] = $par['width']/$par['height'];
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_data['full_path'];
					$config['new_image'] = './uploads/promocoes/thumbs/'.$newname.'.jpg';
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
					
					$this->image_lib->clear();
					$this->image_lib->initialize($config);
					if($this->image_lib->resize())
					{
							// CORTA O EXCEDENTE
						chmod('./uploads/promocoes/thumbs/'.$newname.'.jpg', 0777);
						$config2['image_library'] = 'gd2';
						$config2['source_image'] = './uploads/promocoes/thumbs/'.$newname.'.jpg';
						$config2['x_axis'] = $newwidth-$par['width'];
						$config2['y_axis'] = $newheight-$par['height'];
						$config2['width'] = $par['width'];
						$config2['height'] = $par['height'];
						$config2['maintain_ratio'] = FALSE;
						
						$this->image_lib->clear();
						$this->image_lib->initialize($config2);
					
					if ($this->image_lib->crop())
					{

						chmod('./uploads/promocoes/thumbs/'.$newname.'.jpg', 0777);
						// remove arquivo temporário
						if(file_exists($upload_data['full_path']))
						{
							unlink($upload_data['full_path']);
						}
						
							//resize
						$inserir["arquivo"] = $newname.'.jpg';		
						$inserir["data"] = time();						
						if($this->db->insert('promocao', $inserir)) {
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
	
	function banner() {
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			$data['title'] = "Floresta Mágica Hotel e Eventos";
			$this->db->orderby('id', 'desc');		
				$data['query'] = $this->db->get('promocao');
			
			
			$this->load->view('promocao_banner_view', $data);

		}
	}
    
	function adicionabanner() {
	if(!$this->session->userdata('logged_in')) {
			redirect('user/index');
		} else {
			$this->output->set_header("Cache-Control: no-cache, must-revalidate");
			$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
			// processa formulario enviado pelo add()
			//$this->output->set_header("charset:utf-8");
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
					
					//	
					
					// REDIMENSIONA O MAIS PRÓXIMO POSSÍVEL
					$newname = md5($upload_data['full_path'].time());
					
					// imagem da promoção em tamanho maior:
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_data['full_path'];
					$config['new_image'] = './uploads/promocoes/banners/banner.png';
						$config['maintain_ratio'] = false;
					$config['width'] = 418;
					$config['height'] = 70;
					
					$this->load->library('image_lib', $config);
					if($this->image_lib->resize())
					{
						$output = '{ "success": "yes", "message": "Banner atualizado!" }';
					}
						// remove arquivo temporário
						if(file_exists($upload_data['full_path']))
						{
							unlink($upload_data['full_path']);
						}						
					
				} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; } //$upload_data['file_name']
			} else { $output = '{ "success": "no", "message": "Ocorreu um erro no envio!" }'; } //faz o upload
					
		$output = str_replace("\r", "", $output);
        $output = str_replace("\n", "", $output);
        
        echo $output;
		} // if estál ogado	
	}
    

}

?>