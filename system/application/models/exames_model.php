<?php
class Exames_model extends Model
{
    function __construct()
    {
        parent::__construct();
		$this->tablename = 'exames';
		
    }
	
	function _mkTables() {
		$this->load->dbforge();	
		if(!$this->db->table_exists($this->tablename)) {
			$fields = array(
                        'id' => array(
                                                 'type' => 'INT',	
												 'constraint' => '9',
												 'null' => FALSE,
												 'auto_increment' => TRUE,
                                           ),
                        'data_exame' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '60',
												 'null' => FALSE,
                                          ),
                        'data_insercao' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '50',
												 'null' => FALSE,
                                          ),
                        'arquivo' => array(
                                                 'type' => 'VARCHAR',
												 'constraint' => '250',
                                                 'null' => TRUE,
                                          ),
						'selo' => array(
                                                 'type' => 'VARCHAR',
												 'constraint' => '250',
                                                 'null' => TRUE,
                                          ),				
						'titulo' => array (
												 'type' => 'VARCHAR',
												 'constraint' => '250',
												 'null' => TRUE,
										  ),
						'descricao' => array (
												 'type' => 'TEXT',
												 'null' => TRUE,
										  ),
						'userid' => array (
												 'type' => 'INT',	
												 'constraint' => '9',
												 'null' => FALSE,									
										  ),
						'adminid' => array (
												 'type' => 'INT',	
												 'constraint' => '9',
												 'null' => FALSE,									
										  ),
						'new' => array (
												 'type' => 'BOOL',	
												 'default' => TRUE,
												 'null' => FALSE,									
										  ),
                );
		    $this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', TRUE);
			$this->dbforge->create_table($this->tablename, TRUE);
		}

	}
	
	function pegaExames($uid, $limit = 0) {
	
		$this->db->where("userid",$uid);
		$this->db->orderby('data_exame','DESC');
		$this->db->orderby('id','DESC');
		if($limit != 0) {				
			$this->db->limit($limit);
		} 
        $query = $this->db->get($this->tablename);

			return $query;
	}
	
	function pegaExame($exameid) {
	
		$query = $this->db->where("id",$exameid);
        $query = $this->db->get($this->tablename);
		if($query->num_rows() > 0) {
			return $query->row();
			} else {
			return false;
			}
	}
	
	function updateExame($data) {
		$this->db->where('id', $data['id']);
		if($this->db->update($this->tablename, $data))
			return true;
		else
			return false;
	}
	
	function exameVelho($exameid) {
		
			$data = array(
               'new' => false,
            );

		$this->db->where('id', $exameid);
		if($this->db->update($this->tablename, $data))
			return true;
		else
			return false;
	}
	
	function removeExame($exameid) {
		$this->db->where('id', $exameid);
		if($query = $this->db->get($this->tablename)) {
			if($query->num_rows() > 0) {
				foreach($query->result() as $exame) {
					// remove arquivos
					if($exame->arquivo) {
						if(file_exists("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/exames/".$exame->arquivo)) {						
							unlink("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/exames/".$exame->arquivo);
						}
					}
					if($exame->selo) {
						if(file_exists("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$exame->selo)) {
							unlink("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$exame->selo);
						}
					}
					$this->db->where('id', $exame->id);
					$this->db->delete($this->tablename);
					
				}
				return true;
			}
		} else {
			return false;
		}
	}
	
	function removeExamesPorUser($userid) {
		$this->db->where('userid', $userid);
		if($query = $this->db->get($this->tablename)) {
			if($query->num_rows() > 0) {
				foreach($query->result() as $exame) {
					// remove arquivos
					if(file_exists("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/exames/".$exame->arquivo)) {
						unlink("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/exames/".$exame->arquivo);
					}
					if(file_exists("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$exame->selo)) {
						unlink("C:/inetpub/vhosts/biologicalab.com.br/httpdocs/uploads/selos/".$exame->selo);
					}
					$this->db->where('id', $exame->id);
					$this->db->delete($this->tablename);
					
				}
				return true;
			}
		} else {
			return false;
		}
	}
	
	function registerExame($data) 
	{

		if($this->db->insert($this->tablename, $data))
		{
			return true;
		}
		return false;

	}
	}
?>