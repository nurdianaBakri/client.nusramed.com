<?php
class User_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function auth($email, $password, $where = NULL) {
		$this->db->select('*');
		$this->db->from('user_login');
		$this->db->where(array(
			'email' => $email,
			'password' => md5($password)
		));
		if($where != NULL){
			$this->db->where($where);
		}
		return $this->db->get()->row_array();
	}
	function add_user_login($data){
		$query = $this->db->insert('user_login', $data);
		// $this->db->insert();
		return $this->db->insert_id();
	}
	function update_user_login($id, $data) { 
        $this->db->where('id',$id);
        return $this->db->update('user_login', $data);
    }

    function getByRow_array($where){  
        $this->db->where($where);
        $hsl= $this->db->get('user_login')->row_array(); 
        return $hsl;
    }
}