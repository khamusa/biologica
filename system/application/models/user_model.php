<?php
class User_model extends Model
{
    function __construct()
    {
        parent::__construct();
		// prefixo utilizado pelo campo dados do usuario
		$this->prefixodados = "dados_";
    }
	
	function findUsers($maxpriv, $table='users') {
		$query = $this->db->where("priv <",$maxpriv);
        $query = $this->db->get($table);

			return $query;
	}
		
	function findUser($uid = 0, $table='users') {
		if($uid == 0)
			$uid = $this->session->userdata('logged_in');
			
		$query = $this->db->where("uid",$uid);
        $query = $this->db->get($table);
		if($query->num_rows() > 0) {
			return $query->row();
			} else {
			return false;
			}
	}
	
	function userIsActive($uid, $table='users') {
		$query = $this->db->where("uid",$uid);
        $query = $this->db->get($table);
        
        if ($query->num_rows() > 0 ) {
			$resultado = $query->row();
			if($resultado->ativo == 1)
				return true;
			else
				return false;
		} else {
			return true;
		}
	
	}	
	
	function isadmin() {
		if($this->hasPriv(50)) {
			return true;
		}
	}
	
	function logged() {
		if($this->session->userdata('logged_in'))
				return $this->session->userdata('logged_in');
			else
				return false;
	}
	
	function hasPriv($minpriv, $uid = '0', $table='users') {
			if($uid == '0') {
				$uid = $this->logged();
			}
			
			$query = $this->db->where("uid",$uid);
			$query = $this->db->where("priv >=",$minpriv);
			$query = $this->db->get($table);
			
			if ($query->num_rows() == 0) {
				return FALSE;
			} else {
				return TRUE;
			}
	}
	
	function getUserPriv($uid = "0", $table='users') {
		if($uid == 0) {
			$uid = $this->session->userdata('logged_in');
		} 
		$query = $this->db->where("uid",$uid);
        $query = $this->db->get($table);
        
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
			  $row = $query->row();
			  return $row->priv;
		}
	}
	function _mkTables() {
		$this->load->dbforge();	
		if(!$this->db->table_exists('ci_sessions')) {
			$fields = array(
                        'session_id' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '40',
												 'default' => '0',
												 'null' => FALSE,
                                          ),
                        'ip_address' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '16',
												 'default' => '0',
												 'null' => FALSE,
                                          ),
                        'user_agent' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '50',
												 'null' => FALSE,
                                          ),
                        'last_activity' => array(
                                                 'type' => 'INT',
												 'constraint' => '10',
												 'unsigned' => TRUE,
												 'default' => '0',
                                                 'null' => FALSE,
                                          ),
						'user_data' => array (
												 'type' => 'TEXT',
												 'null' => FALSE,
										  ),
                );
		    $this->dbforge->add_field($fields);
			$this->dbforge->add_key('session_id', TRUE);
			$this->dbforge->create_table('ci_sessions', TRUE);
		}
		if(!$this->db->table_exists('users')) {
			
			$fields = array(
                        'uid' => array(
                                                 'type' => 'INT',
                                                 'constraint' => '9',
												 'null' => FALSE,
												 'auto_increment' => TRUE,										
                                          ),
                        'username' => array(
                                                 'type' => 'VARCHAR',
                                                 'constraint' => '16',
												 'null' => FALSE,
                                          ),
                        'password' => array(
                                                 'type' =>'VARCHAR',
                                                 'constraint' => '128',
												 'null' => FALSE,
                                          ),
                        'priv' => array(
                                                 'type' => 'VARCHAR',
												 'constraint' => '10',
												 'default' => '0',
                                                 'null' => FALSE,
                                          ),
						'email' => array (
												 'type' => 'VARCHAR',
												 'constraint' => '250',
												 'null' => FALSE,
										  ),
						'ativo' => array (
												 'type' => 'BOOL',
												 'default' => '1',
												 'null' => FALSE,
										  ),
						'dados_contatos' => array (
												 'type' => 'TEXT',
												 'null' => TRUE,
										  ),			
						'dados_nome' => array (
												 'type' => 'VARCHAR',
												 'constraint' => '250',
												 'null' => TRUE,
										  ),					  
						'dados_cnpj' => array (
												 'type' => 'VARCHAR',
												 'constraint' => '20',
												 'null' => TRUE,
										  ),					  
						'dados_endereco' => array (
												 'type' => 'VARCHAR',
												 'constraint' => '250',
												 'null' => TRUE,
										  ),					  
										  	  				  
                );
		    $this->dbforge->add_field($fields);
			$this->dbforge->add_key('uid', TRUE);
			if($this->dbforge->create_table('users', TRUE)) {
				if($this->checkUsernameAvailable('admin'))
				{
					// no admin user, create it
					$data['username']     = 'admin';
					$data['password']     = md5('admin');
					$data['priv'] = 99;
					$data['email'] = 'no@email.com';
					$this->registerUser($data);
				}
			}
			
			
		}
	}
    
	function getUserId($username, $table='users') {
		$query = $this->db->where("username",$username);       
        $query = $this->db->limit(1,0);
        $query = $this->db->get($table);	
		 if ($query->num_rows() > 0 ) {
            $row = $query->row(); 
				return $row->uid;
        	}
		}
	
	function getAdminMail($table='users') {
		$query = $this->db->where("username","admin");       
        $query = $this->db->limit(1,0);
        $query = $this->db->get($table);	
		 if ($query->num_rows() > 0 ) {
            $row = $query->row(); 
				return $row->email;
        	}
		}
		
    function checkUserLogin($username,$password,$table='users')
    {
        $query = $this->db->where("username",$username);
        $query = $this->db->where("password",$password);
        $query = $this->db->limit(1,0);
        $query = $this->db->get($table);
        
        if ($query->num_rows() == 0) {
            return NULL;
        }
        
        return TRUE;
    }
	
	function checkUsernameAvailable($username, $table='users') {
		$query = $this->db->where("username",$username);
        $query = $this->db->get($table);
        
        if ($query->num_rows() == 0) {
            return TRUE;
        } else {
			return FALSE;
		}
	}
	
	function updatedata($data, $uid) {
			$this->db->where('uid', $uid);
		if($this->db->update('users', $data))
			return true;
		else
			return false;
	}
	function changepassword($pw, $uid = false) {
		if(!$uid) {
			$uid = $this->session->userdata('logged_in');
		}
			$data = array(
               'password' => $pw,
            );

		$this->db->where('uid', $uid);
		if($this->db->update('users', $data))
			return true;
		else
			return false;
	}
	
	function updateuser($data, $uid, $table = 'users') {
		$this->db->where('uid', $uid);
		if($this->db->update($table, $data))
			return true;
		else
			return false;
	}
	
	function blockuser($uid, $trueorfalse) {
		
			$data = array(
               'ativo' => $trueorfalse,
            );

		$this->db->where('uid', $uid);
		if($this->db->update('users', $data))
			return true;
		else
			return false;
	}
	
	function changemail($email, $uid = false) {
		if(!$uid) {
			$uid = $this->session->userdata('logged_in');
		}
			$data = array(
               'email' => $email,
            );

		$this->db->where('uid', $uid);
		if($this->db->update('users', $data))
			return true;
		else
			return false;
	}
	
	function changepriv($priv, $uid = false) {
		if(!$uid) {
			$uid = $this->session->userdata('logged_in');
		}
			$data = array(
               'priv' => $priv,
            );

		$this->db->where('uid', $uid);
		if($this->db->update('users', $data))
			return true;
		else
			return false;
	}
	
	function matchuserpassword($pw) {
		$query = $this->db->where("password",$pw);
		$query = $this->db->where("uid",$this->session->userdata('logged_in'));
        $query = $this->db->get('users');
        
        if ($query->num_rows() > 0) {
            return TRUE;
        }
		return false;
	}
	
	function removeUser($uid, $table='users') {
		$this->db->where('uid', $uid);
		if($this->db->delete($table)) {
			return true;
		} else {
			return false;
		}
	}
	
	function registerUser($data, $table='users') 
	{

		if($this->db->insert($table, $data))
		{
			return true;
		}
		return false;

	}
	}
?>