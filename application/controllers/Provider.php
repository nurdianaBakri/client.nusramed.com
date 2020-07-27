<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Provider extends CI_Controller {

 
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('provider_model','fasilitas_model','login_model','user_model','gallery_model','transaksi_model','customer_model'));
	}
	public function index(){
		//ngambil data user dari database
		$user = $this->login_model->get();
		$data['userdata'] = $user;

		//check role kalo bukan admin langsung di redirect ke halaman depan
		$this->check_role();

		//init layout
		$data['navbar']='admin/navbar_admin';
		$data['content']='admin/provider_content';
		$data['slide']=null;
		$data['sidebar']='admin/sidebar_admin';
		$data['title']='Penyedia Lapang';

		//init data
		$data['provider'] = $this->provider_model->get()->result_array();
		$data['fasilitas'] = $this->fasilitas_model->get()->result_array();
        $data['provinsi'] = $this->provider_model->get_provinsi()->result_array();
		$data['scripts'] = array('js/admin/provider.js','plugin/form-validation/jquery.validate.min.js','plugin/form-validation/extjquery.validate.min.js','plugin/bootbox/bootbox.js','js/bootstrap-datepicker.min.js');
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
    function delete_img(){
        $id = $_POST['id'];
        $foto = 'assets/img/'.$_POST['id_provider'].'/'.$_POST['foto'];
        // print_r($foto);
         if(file_exists($foto)){
                unlink($foto);
                if($this->gallery_model->delete_img($id)){
                    echo "1";
                }else{
                    echo "0";
                }
            }
        
    }
    function set_default(){
        $id=$_POST['id'];
        $id_provider = $_POST['id_provider'];
        $data['is_display_picture'] = 0;
        $this->gallery_model->edit_img(array('id_provider' => $id_provider,'is_display_picture'=> 1),$data);
        $data['is_display_picture'] = 1;
        $this->gallery_model->edit_img(array('id' => $id),$data);
        echo "1";
    }
    function upload_image_gallery(){
        $id=$_POST['id'];
        $attachment_file=$_FILES["image"];
        $output_dir = "assets/img/".$id."/";
        if(!is_dir($output_dir)) {
            mkdir($output_dir);
        }
        $fileName = $_FILES["image"]["name"];
        move_uploaded_file($_FILES["image"]["tmp_name"],$output_dir.$fileName);
        $data['foto'] = $fileName;
        $data['id_provider'] = $_POST['id'];
        if($this->gallery_model->add_img_gallery($data)){
            echo $id;
        }else{
            echo "0";
        }
    }
	function check_password_admin(){
        $pass = md5($_POST['password_admin']);
        $id = $_POST['id'];
        $data = $this->provider_model->is_password_admin($pass, $id);
        if($data){
            $result = true;
        }else{
            $result = false;
        }

        echo json_encode($result);
    }
    
    function change_pass(){
   	 	$data['password'] = md5($_POST['new_password']);
	 	$id = $_POST['id'];
	    if($this->provider_model->change_pass(array('id'=>$id),$data)){
	        echo "1";
	    }else{
	        echo "0";
	    }

    }
    function get_gallery(){
        $id = $_POST['id'];
        $result = $this->gallery_model->get(array("id_provider"=>$id))->result_array();
        echo json_encode($result);

    }
    function check_code(){
        $code = $_POST['email'];
        $id = $_POST['id'];
        $data = $this->provider_model->is_code_exist($code, $id);
        if($data){
            $result = false;
        }else{
            $result = true;
        }

        echo json_encode($result);
    }
	 public function post(){
        $dt = $_POST['data'];
        $data_provider['password'] = md5($dt[1]);
        $data_provider['username'] = $dt[0];
        $data_provider['email'] = $dt[3];
        $data_provider['role'] = 2;
        if($_POST['arr'] != 0){
            $trc = $_POST['arr'];
        }
        if($dt[6] == 0){
        $user_id = $this->user_model->add_user_login($data_provider);
        $data['lokasi'] = $dt[2];
        $data['nama'] = $dt[4];
        $data['longitude'] = $dt[8];
        $data['latitude'] = $dt[9];
        $data['provinsi_id'] = $dt[10];
        $data['status'] = $dt[5];
        $data['user_login_id'] = $user_id;
            if($provider_id = $this->provider_model->add($data)){
                if($_POST['arr'] != 0){
                    $last_id = $provider_id;
                    for ($i=0; $i < count($trc); $i++) {
                        $data1 = [];
                        $data1['id_provider'] = $last_id;
                        $data1['id_fasilitas'] = $trc[$i];
                        $this->provider_model->add_fasilitas($data1);
                    }
                }
                echo "1";
            }else{
                echo "0";
            }
        }else{
        	$data_user_edit['username'] = $dt[0];
        	$data_user_edit['email'] = $dt[3];
        	$data['lokasi'] = $dt[2];
	        $data['nama'] = $dt[4];
	        $data['status'] = $dt[5];
            $data['longitude'] = $dt[8];
            $data['latitude'] = $dt[9];
            $data['provinsi_id'] = $dt[10];
        	$this->user_model->update_user_login($dt[7],$data_user_edit);
            if($this->provider_model->update($dt[6],$data)){
                if($_POST['arr'] != 0){
                $query = $this->provider_model->get_provider_fasilitas(array("id_provider"=>$dt[6]))->result_array();
                $result = [];
                foreach ($query as $row) {
                    array_push($result, $row['id_fasilitas']);
                }
                // print_r($result);
                // print_r($trc);
                $added = array_values(array_diff($trc,$result));
                // echo"added";
                // print_r($added);
                    if(count($added)>0){
                        for ($i=0; $i < count($added); $i++) { 
                            $data1 = [];
                            $data1['id_provider'] = $dt[6];
                            $data1['id_fasilitas'] = $added[$i];
                            $this->provider_model->add_fasilitas($data1);
                        }
                    }
                    // echo"deleted";
                $deleted = array_values(array_diff($result,$trc));
                // print_r($deleted);
                    if(count($deleted)>0){
                        for ($i=0; $i < count($deleted); $i++) { 
                            $this->provider_model->delete_provider_fasilitas(array('id_provider'=>$dt[6],'id_fasilitas'=>$deleted[$i]));
                        }
                    }
                }
                echo "1";
            }else{
                echo "0";
            }
        }
        // echo $data['kode'];
        // print_r($_POST);
    }
    public function post_lapang(){
    	$id = $_POST['id'];
    	$kode = $_POST['kode_lapang'];
    	$harga = $_POST['harga'];
    	$ukuran = $_POST['ukuran'];
    	$id_lapang = $_POST['id_lapang'];
    	$jenis = $_POST['jenis'];
    	for ($i=0; $i <count($id_lapang) ; $i++) { 
    		if($id_lapang[$i]!=0){
    			$data['kode_lapang'] = $kode[$i];
    			$data['ukuran'] = $ukuran[$i];
    			$data['harga'] = $harga[$i];
    			$data['jenis'] = $jenis[$i];
    			$this->provider_model->update_lapang($id_lapang[$i],$data);
    		}else{
    			$data['kode_lapang'] = $kode[$i];
    			$data['ukuran'] = $ukuran[$i];
    			$data['harga'] = $harga[$i];
    			$data['jenis'] = $jenis[$i];
    			$data['id_provider'] = $id;
    			$this->provider_model->insert_lapang($data);
    		}
    	}
    	echo"1";
    }
    public function delete_lapang(){
        $id = $_POST['id'];
        if($this->provider_model->delete_lapang($id)){
            echo "1";
        }else{
            echo "0";
        }
    }
  	public function get_lapangan(){
  		$id = $_POST['idx'];
        $result = $this->provider_model->get_lapang(array("id_provider"=>$id))->result_array();
        echo json_encode($result);
  	}
    public function get_by_id(){
        $id = $_POST['idx'];
        $result = $this->provider_model->get(array("id_provider"=>$id))->row_array();
        echo json_encode($result);
    }
    public function delete(){
        $id = $_POST['id'];
        if($this->provider_model->delete($id)){
            echo "1";
        }else{
            echo "0";
        }
    }
    function get_fasilitas_by_id(){
        // $this->layout = false;
        $id = $_POST['idx'];
        $query = $this->provider_model->get_provider_fasilitas(array("id_provider"=>$id))->result_array();
        $result = "";
        foreach($query as $row){
            // $result = $row['TrcTypeID']+
        }
        // print_r($query);
        echo json_encode($query);
    }
    function get_trans_by_id(){
        $id = $_POST['idx'];
        $query = $this->provider_model->get_provider_trans(array("provider.id_provider"=>$id))->result_array();
        $result = [];
        foreach($query as $row => $value){
            // for ($i=0; $i < ; $i++) { 
                # code...
                $result[$row][0]=$value['kode_transaksi'];
                $result[$row][1]=$value['tgl_sewa'];
                $result[$row][2]=$value['tgl_main'];
                $result[$row][3]=$value['jam_mulai'];
                $result[$row][4]=$value['jam_selsai'];
                $result[$row][5]=$value['total_bayar'];
                $result[$row][6]=$value['nama'];
                $result[$row][7]=$value['kode_lapang'];
                if($value["status"] == 0){
                    $status = 'Waiting Transfer';
                }else if($value["status"] == 1){
                    $status = "Waiting Approval";
                }else{
                    $status = "Booking Complete";
                }
                $result[$row][8]='<span class="tag tag-danger">'.$status.'</span>';
            // }
        }
        // print_r($query);
        echo json_encode($result);
    }
    function updateStatus(){
        $id = $_POST['idx'];
        $transaksi = $this->transaksi_model->get(array('kode_transaksi' =>$id  ))->row_array();
        $user = $this->customer_model->get(array('id_customer' => $transaksi['id_customer']))->row_array();
        $trans = $this->provider_model->get_provider_trans(array('kode_transaksi'=>$id))->row_array();
        $lapang = $this->provider_model->get_lapang(array('id_lapang'=>$transaksi['id_lapang']))->row_array();
        $provider = $this->provider_model->get(array('id_provider'=>$trans['id_provider']))->row_array();
        $from = 'kfebrianto0@gmail.com';
        $data['total_bayar'] = (abs($transaksi['jam_selsai'] - $transaksi['jam_mulai']))*$lapang['harga']/2; 
        if($transaksi['status'] == 0){
            $data['status'] = 1;
            $to = $user['email'];
            $subject = 'Efutsal transaksi status [Waiting Approval]';
            $message = '
            <html>
            <head>
                <title>Selamat status transaksi anda telah berhasil diupdate.</title>
            </head>
            <body>
            <h3>Code Transaki : '.$transaksi['kode_transaksi'].'</h3>
            <p>Status transaksi saat ini adalah "Waiting Approval". Tunggu email kami selanjutnya.</p><br><br>
            <p>Best regard, efutsal team</p>
            <p>Krisna Febrianto</p>
            </body>
            </html>
        ';
        }else if($data['status'] = 1){
            $data['status'] = 2;
            $to = $user['email'];
            $subject = 'Efutsal transaksi status [Booking Completed]';
            $message = '
            <html>
            <head>
                <title>Selamat Booking Complete</title>
            </head>
            <body>
            <h3>Code Transaki : '.$transaksi['kode_transaksi'].'</h3>
            <p>Status : <b>Booking Completed</b>.</p><br>
            <p>Silahkan tunjukan email ini ke pihak penyedia lapang, sebagai bukti pembayaran yang sah.</p><br><br>
            <p>Best regard, efutsal team</p>
            <p>Krisna Febrianto</p>
            </body>
            </html>';
        }
        if($this->transaksi_model->update($id,$data)){
            // echo $this->transaksi_model->send_mail($message,$subject,$to,$from);
            $this->transaksi_model->send_mail($message,$subject,$to,$from);
                # code...
                echo"1";
            // } else {
            //     # code...
            //     echo"0";
            // }
            
        }else{
            echo "0";
        }
    }

}
