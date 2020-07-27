<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_restore extends CI_Controller {

	 
	public function __construct()
	{
		parent::__construct();

		// $this->load->database();

		$this->load->database();
		$this->load->model(array('customer_model','login_model','user_model','provider_model','fasilitas_model'));
		// die();
	}
	
	public function index(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/backup_restore_content';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Backup & restore database';

		//init data
		// $data['customer'] = $this->customer_model->get()->result_array();

		$data['scripts'] = array('js/provider/general.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/dataTables.buttons.min.js','plugin/datatables-plugins/buttons.flash.min.js','plugin/datatables-plugins/jszip.min.js','plugin/datatables-plugins/pdfmake.min.js','plugin/datatables-plugins/vfs_fonts.js','js/bootstrap-datepicker.min.js','plugin/datatables-plugins/buttons.html5.min.js','plugin/datatables-plugins/buttons.colVis.min.js','plugin/bootbox/bootbox.js','plugin/datatables-plugins/buttons.print.min.js');
		$this->load->view('admin/tamplate_admin',$data);
	}
	function backup(){
		$this->load->dbutil();

		// Backup database dan dijadikan variable
		$prefs = array(
        'tables'     => array('fasilitas','provinsi', 'user_login','provider','provider_fasilitas','provider_gallery','lapang','customer','transaksi'),
        // Array table yang akan dibackup
        'ignore'     => array(),
        // Daftar table yang tidak akan dibackup
        'format'     => 'sql',
        // gzip, zip, txt format filenya
        'filename'   => 'efutsal',
        // Nama file
        'add_drop'   => TRUE, 
        // Untuk menambahkan drop table di backup
        'add_insert' => TRUE,
        // Untuk menambahkan data insert di file backup
        'newline'    => "\n"
        // Baris baru yang digunakan dalam file backup
		);
		$backup = $this->dbutil->backup($prefs);
		// print_r($backup);
		// // Load file helper dan menulis ke server untuk keperluan restore
		$this->load->helper('file');
		write_file('/backup/database/efutsal.sql', $backup);

		// // Load the download helper dan melalukan download ke komputer
		$this->load->helper('download');
		force_download('efutsal.sql', $backup);
		// echo"1";
	}
	function restoredb()
	{
	   	$id=$_POST['id'];
	    $attachment_file=$_FILES["file"];
	    $output_dir = "assets/backup/database/";
	    // if(!is_dir($output_dir)) {
	    //     mkdir($output_dir);
	    // }
	    $fileName = $_FILES["file"]["name"];
	    move_uploaded_file($_FILES["file"]["tmp_name"],$output_dir.$fileName);
	  	$isi_file = file_get_contents('assets/backup/database/'.$fileName);
	  	$this->db->query('drop database efutsal;');
	  	$this->db->query('create database efutsal;');
	  	$this->db->query('use efutsal;');
	  	
	  	$string_query = rtrim( $isi_file, "\n;" );
	  	$array_query = explode(";", $string_query);
	  	foreach($array_query as $query)
	  	{
	    	$this->db->query($query);
	  	}
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
