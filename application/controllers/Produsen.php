<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produsen extends CI_Controller {  
	
	public function __construct()
    {
        parent ::__construct(); 
        $this->logged_in();  
    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    } 

	public function index(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/produsen_content';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Produsen';

		//init data
		$data['produsen'] = $this->produsen_model->get()->result_array();

		$data['scripts'] = array('js/admin/produsen.js','plugin/form-validation/jquery.validate.min.js','plugin/bootbox/bootbox.js','js/bootstrap-datepicker.min.js');
		$this->load->view('admin/tamplate_admin',$data);
	}
	function check_role(){
		$user = $this->login_model->get();
		if(isset($user)){
			if($user['role'] == 1){
			// $this->session->set_flashdata('form_msg', array('success' =>true, 'fail'=> false, 'msg' => 'Login Success'));
				// redirect('welcome');
			
			}else if($user['role'] == 2){
					redirect('admin_provider');
			}else{
				redirect('welcome');
			}
		}else{
			redirect('login');
		}
	}	
	public function post(){
        $data['nama'] = $_POST['nama'];
        $data['alamat'] = $_POST['alamat'];
        if($_POST['id_produsen'] == 0){
            
            if($this->produsen_model->add($data)){
                echo "1";
            }else{
                echo "0";
            }
        }else{
           
            if($this->produsen_model->update($_POST['id_produsen'],$data)){
                echo "1";
            }else{
                echo "0";
            }
        }
    }
    public function get_by_id(){
        $id = $_POST['idx'];
        $result = $this->produsen_model->get(array("id_produsen"=>$id))->row_array();
        echo json_encode($result);
    }
    public function delete(){
        $id = $_POST['id'];
        if($this->produsen_model->delete($id)){
            echo "1";
        }else{
            echo "0";
        }
    }

}
