<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Angsuran extends CI_Controller
{
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

    public function index()
    { 
        $data['title'] = "Return barang dari Outlet";
        $data['url'] = "laporan/Angsuran";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Return barang dari Outlet",
             'link' => base_url()."return/ReturnOutlet",
             'status' => "active",
            ),
        ); 
        
        $data['outlet'] = $this->db->query('SELECT DISTINCT(outlet1.id_outlet) as id_outlet,  outlet1.nama from trx_penjualan_tmp,outlet1 WHERE trx_penjualan_tmp.kd_outlet = outlet1.id_outlet')->result_array(); 
        // $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('returnDariOutlet/index', $data);
        $this->load->view('include2/footer');
    } 
     

     function save()
     {
        $data = array( );
        $data_log = array( );
        $pesan = "";
        $keterangan = "";
        $validation_errors="";
        $insert = false;

        $no_faktur = $this->input->post('no_faktur');
        $angsuran = $this->input->post('angsuran');
        $lunas = $this->input->post('lunas');  

        $this->form_validation->set_rules("angsuran", "harus di masukkan ", "trim|required");   

        if ($this->form_validation->run() == FALSE) {
            $validation_errors = validation_errors();

            $pesan =  "Gagal menambah angsuran faktur : '".$no_faktur.". Karena jumlah angsuuran tidak di masukkan"; 

            // $pesan="Proses return obat dari outlet gagal .";  
            $this->M_log->tambah($this->session->userdata['id'], $pesan);
        } 
        else {   
               $data= array(
                   "no_faktur" => $no_faktur,
                   "angsuran" => $angsuran, 
                   "lunas" => $lunas, 
                   "id_user_input" => $this->session->userdata('id'), 
                   "tgl_angsuran" => date('Y-m-d H:m:s'), 
                );  
                $insert = $this->db->insert('riwayat_angsuran', $data);  
                if ($insert)
                {
                     $pesan =  "Menambah angsuran faktur : '".$no_faktur."' sebesar ".$angsuran; 
                }
                else{
                    $pesan =  "gagal Menambah angsuran faktur : '".$no_faktur."' sebesar ".$angsuran;  
                } 
               
                  // $pesan="Proses return obat dari outlet gagal .";  
                $this->M_log->tambah($this->session->userdata['id'], $pesan); 
            }  


        $data = array( 
            'pesan' => $pesan,   
            'return' => $insert,   
            'validation_errors' => $validation_errors,  
        ); 
        echo json_encode($data);
     } 

    public function get_riwayat_angsuran()
    {
       $no_faktur = $this->input->post('no_faktur');
       $data['data'] = $this->db->query("SELECT riwayat_angsuran.*, user_login.nama FROM riwayat_angsuran, user_login WHERE no_faktur='".$no_faktur."' and riwayat_angsuran.id_user_input=user_login.id")->result_array(); 

        $this->load->view('laporan_riwayat_return/riwayat', $data);  
    }

    public function disableEnableAddButton()
    {
       $no_faktur = $this->input->post('no_faktur'); 
         $data['show_button_angsuran']= $this->db->query("SELECT * from riwayat_angsuran WHERE no_faktur='".$no_faktur."' and lunas=1");
         if ($data['show_button_angsuran']->row_array()['lunas']==1)
         {
             echo true;
         }
         else
         {
             echo false;
         }

    }
  
}
