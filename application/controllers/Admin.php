<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	 
	public function __construct()
	{
		parent::__construct();

		// $this->load->database();

		$this->load->database();
		$this->load->model(array('login_model','transaksi_model','provider_model'));
		// die();
	}
	public function index()
	{
		$user = $this->login_model->get();
		$this->check_role();
		$data['userdata'] = $user;
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/dashboard';
		$data['sidebar']='admin/sidebar_admin';
		$data['title']="Dashboard";
		$data['scripts'] = array('js/admin/dashboard.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js');
		$this->load->view('admin/tamplate_admin',$data);
	}
	public function data_line_chart(){
        // $this->load->model()
        // $data = $this-;
        $user = $this->login_model->get();

        $category = array();
        $category['name'] = 'Category';
        $provider = $this->provider_model->get(array('user_login_id'=>$user['id']))->row_array();
        $series1 = array();
        $series1['name'] = 'Transaction';
        $category['data'] = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        for ($i=0; $i < count($category['data']) ; $i++) { 
            # code...
            if($user['role'] == 1){
            	$tot = $this->transaksi_model->get(array('month(tgl_transaksi)'=>($i+1),'year(tgl_transaksi)'=> date('Y')))->num_rows();
			}else{
				$tot = $this->provider_model->get_provider_trans(array('month(tgl_transaksi)'=>($i+1),"provider.id_provider"=>$provider['id_provider'],'year(tgl_transaksi)'=> date('Y')))->num_rows();
			}
            
            
            $series1['data'][] = ($tot!=null)?$tot:0;
        }
        $result = array();
        array_push($result,$category);
        array_push($result,$series1);
        print json_encode($result, JSON_NUMERIC_CHECK);
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
}
