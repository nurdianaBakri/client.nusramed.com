<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
 
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

	public function penjualan(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/content_transaksi_penjualan';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Transaksi Penjualan';

		//init data

		$data['pembeli'] = $this->customer_model->get()->result_array();
		$data['transaksi'] = $this->transaksi_model->get(array('tipe'=>1))->result_array();
		$data['kode_transaksi'] = 'TRX'.date('his');
		$data['kasir'] = $this->pegawai_model->get(array('user_login_id'=>$user['id']))->row_array();
		$data['kasir'] = $data['kasir']['id_pegawai'];

		$data['scripts'] = array('js/admin/transaksi.js','plugin/form-validation/jquery.validate.min.js','plugin/bootbox/bootbox.js','js/bootstrap-datepicker.min.js');
		$this->load->view('admin/tamplate_admin',$data);
	}
	public function get_obat(){
		$obat = $this->obat_model->get()->result_array();
		echo json_encode($obat);
	}
	public function get_obat_details(){
		$id = $_POST['id'];
		$obat = $this->obat_model->get(array('id_obat'=>$id))->row_array();
		echo json_encode($obat);
	}
	public function pembelian(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/content_transaksi_pembelian';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Transaksi Pembelian';

		//init data
		$data['transaksi'] = $this->transaksi_model->get(array('tipe'=>2))->result_array();
		$data['kode_transaksi'] = 'TRX'.date('his');
		$data['kasir'] = $this->pegawai_model->get(array('user_login_id'=>$user['id']))->row_array();
		$data['kasir'] = $data['kasir']['id_pegawai'];
		$data['scripts'] = array('js/admin/transaksi.js','plugin/form-validation/jquery.validate.min.js','plugin/bootbox/bootbox.js','js/bootstrap-datepicker.min.js');
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
        $data['id'] = $_POST['kd_transaksi'];
        $data['id_pegawai'] = $_POST['pegawai'];
        $data['tgl_transaksi'] = date('Y-m-d');
        $data['tipe'] = 1;
        if(isset($_POST['pembeli'])){
        	$data['id_pembeli'] = $_POST['pembeli'];
    	}
        $data['jumlah'] = $_POST['jml_bayar'];
            
        if($this->transaksi_model->add($data)){
        	for ($i=0; $i <count($_POST['obat']) ; $i++) { 
        		$data_detail['id_transaksi'] = $_POST['kd_transaksi'];
        		$data_detail['id_obat'] = $_POST['obat'][$i];
        		$data_detail['total'] = $_POST['jumlah'][$i];
        		$data_detail['jumlah_bayar'] = $_POST['subTotal'][$i];
        		// $obat['stok'] = 'stok - '.$_POST['jumlah'][$i];
        		$obat = $this->obat_model->get(array('id_obat'=>$data_detail['id_obat']))->row_array();
        		$obat_data['stok'] = $obat['stok'] - $_POST['jumlah'][$i];
        		$this->obat_model->update($data_detail['id_obat'],$obat_data);
        		$this->transaksi_model->add_detail($data_detail);

        	}
        	
            echo "1";
        }else{
            echo "0";
        }
    }
    public function post_beli(){
        $data['id'] = $_POST['kd_transaksi'];
        $data['id_pegawai'] = $_POST['pegawai'];
        $data['tgl_transaksi'] = date('Y-m-d');
        $data['tipe'] = 2;
        if(isset($_POST['pengirim'])){
        	$data['pengirim'] = $_POST['pengirim'];
    	}
        $data['jumlah'] = $_POST['jml_bayar'];
            
        if($this->transaksi_model->add($data)){
        	for ($i=0; $i <count($_POST['obat']) ; $i++) { 
        		$data_detail['id_transaksi'] = $_POST['kd_transaksi'];
        		$data_detail['id_obat'] = $_POST['obat'][$i];
        		$data_detail['total'] = $_POST['jumlah'][$i];
        		$data_detail['jumlah_bayar'] = $_POST['subTotal'][$i];
        		// $obat['stok'] = 'stok - '.$_POST['jumlah'][$i];
        		$obat = $this->obat_model->get(array('id_obat'=>$data_detail['id_obat']))->row_array();
        		$obat_data['stok'] = $obat['stok'] + $_POST['jumlah'][$i];
        		$this->obat_model->update($data_detail['id_obat'],$obat_data);
        		$this->transaksi_model->add_detail($data_detail);

        	}
        	
            echo "1";
        }else{
            echo "0";
        }
    }
    public function get_transaksi(){
    	$id = $_POST['idx'];
    	$data = [];
    	$data['master'] = $this->transaksi_model->get(array('transaksi_obat.id'=>$id))->row_array();
    	$data['detail'] = $this->transaksi_model->get_detail(array('id_transaksi'=>$id))->result_array();
    	echo json_encode($data);
    }
    public function get_by_id(){
        $id = $_POST['idx'];
        $result = $this->fasilitas_model->get(array("id_fasilitas"=>$id))->row_array();
        echo json_encode($result);
    }
    public function delete(){
        $id = $_POST['id'];
        if($this->fasilitas_model->delete($id)){
            echo "1";
        }else{
            echo "0";
        }
    }

}
