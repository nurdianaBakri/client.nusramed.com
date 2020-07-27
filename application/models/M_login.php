<?php 

class M_login extends CI_Model{	

	
	function __construct() {
		parent::__construct();
	}

	 public function cek_userIsLogedIn()
	{
    	$this->secure_header();
		if($this->session->userdata('status')=="login")
	    {
	      return true;
	    }  
	    else
	    {
	      return false;
	    }
	}	

	function cek_login($table,$where){		
		return $this->db->get_where($table,$where);
	}	

	public function update($where, $data)
	{
		$this->db->where($where);
		$hasil = $this->db->update('user_login',$data);
		return $hasil;
	}


	function get() { 
		if (isset($_SESSION['id']))
		{
			$data = array(
				'id' => $_SESSION['id'], 
				'username' => $_SESSION['username'], 
				'email' => $_SESSION['email'], 
				'jabatan' => $_SESSION['jabatan'], 
				'role' => $_SESSION['role'],  
			);
		}
		else
		{
			$data = array( 
				'role' => null,  
			);
		}
		return $data;  
	}

	public function check_role(){ 
		$user = $this->get(); 
		if($user['role'] == 1){ 
			redirect('admin');
		}else if($user['role'] == 2){
			redirect('admin_provider');
		}else{
			redirect('login');
		}   
	} 
	 
	function create($email, $password) {
		$this->load->model('user_model');
		if($data = $this->user_model->auth($email, $password)){
			unset($data['password']);

			$this->set($data);
			return true;
		}else{
			return false;
		}
	}
	
	function clear() {
		session_destroy ();
	}

	 public function secure_header()
    {
     	// Prevent some security threats, per Kevin
		// Turn on IE8-IE9 XSS prevention tools
		$this->output->set_header('X-XSS-Protection: 1; mode=block');
		// Don't allow any pages to be framed - Defends against CSRF
		$this->output->set_header('X-Frame-Options: DENY');
		// prevent mime based attacks
    	$this->output->set_header('X-Content-Type-Options: nosniff');
    }
}