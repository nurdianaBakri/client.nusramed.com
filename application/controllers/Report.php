<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
 
	public function __construct()
	{
		parent::__construct();

		// $this->load->database();

		$this->load->database();
		$this->load->model(array('customer_model','login_model','user_model','transaksi_model','provider_model','fasilitas_model'));
		// die();
	}
	
	public function penyewa(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/report_customer_content';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Report Penyewa';

		//init data
		$data['customer'] = $this->customer_model->get()->result_array();

		$data['scripts'] = ['js/provider/general.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js',];
		$this->load->view('admin/tamplate_admin',$data);
	}
	public function transaksi_pembelian(){
		$user = $this->login_model->get();
        $data['userdata'] = $user;
        // $data['provider'] = $this->provider_model->get(array('user_login_id'=>$user['id']))->row_array();
        $data['navbar']='admin/navbar_admin';
        $data['content']='admin/report_transaksi_content';
        $data['slide']=null;
        $data['transaksi'] = $query = $this->transaksi_model->get(array("tipe "=>2))->result_array();
        $data['sidebar']='admin/sidebar_admin';
        $data['title']='Laporan Transaksi Pembelian';
        $data['scripts'] = array('js/provider/general.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js');
        $this->load->view('admin/tamplate_admin',$data);
	}
	public function transaksi_penjualan(){
		$user = $this->login_model->get();
        $data['userdata'] = $user;
        // $data['provider'] = $this->provider_model->get(array('user_login_id'=>$user['id']))->row_array();
        $data['navbar']='admin/navbar_admin';
        $data['content']='admin/report_transaksi_content';
        $data['slide']=null;
        $data['transaksi'] = $query = $this->transaksi_model->get(array("tipe "=>1))->result_array();
        $data['sidebar']='admin/sidebar_admin';
        $data['title']='Laporan Transaksi Penjualan';
        $data['scripts'] = array('js/provider/general.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js');
        $this->load->view('admin/tamplate_admin',$data);
	}
	public function provider(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/report_provider_content';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Report Provider';

		//init data
		$data['provider'] = $this->provider_model->get()->result_array();
		$data['fasilitas'] = $this->fasilitas_model->get()->result_array();
        $data['provinsi'] = $this->provider_model->get_provinsi()->result_array();
		$data['scripts'] = ['js/provider/general.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js',];
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
}
