<?php 

class Login extends CI_Controller{

    private $jenis_log=" login ";

	public function __construct()
    {
        parent ::__construct();  
        $this->load->library('form_validation');
    }  

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=NULL) {
            redirect('Home');
        }
    } 

	function index(){    
        $this->logged_in();    
		$this->load->view('login/login');
	}

	 public function aksi_login()
    {
        $data['title'] = "Login";
        
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'required');
        
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        if($this->form_validation->run() == false){
           $this->load->view('login/login');
        } 
        else {
            $email = $this->security->xss_clean($this->input->post('email'));
            $password = $this->security->xss_clean($this->input->post('password'));
    		$where = array(
				'email' => $email,
				'password' => md5($password)
			);

            $cek = $this->M_login->cek_login("user_login",$where);  
			if($cek->num_rows()  > 0){   
				  $cek = $cek->row_array(); 
				  $userdata = array(
                    'username' => $cek['username'], 
					'jabatan' => $cek['jabatan'], 
					'role' => $cek['role'], 
					'id' => $cek['id'], 
					'status' => "login",
                    'authenticated' => TRUE
                );
                
                $this->session->set_userdata($userdata); 

                //masukkan data log

                $ket_log="Proses login berhasil";
                $this->M_log->tambah($userdata['id'], $ket_log);

                redirect('Home');
			}
			else
			{ 
				$this->session->set_flashdata('pesan', "username dan password salah !"); 

                $ket_log="Proses login pengguna meggunakan username ".$email." gagal, ".$this->session->flashdata('pesan');
                $this->M_log->tambah($email, $ket_log);

                redirect('Login');
			}  
        }
    } 

	public function logout()
    {
        $hdah = $this->session->sess_destroy(); 
        $this->clear_cache();
        redirect('Login');
    }

	function clear_cache()
    {
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }
}