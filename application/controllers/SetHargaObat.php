<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SetHargaObat extends CI_Controller
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
        // $this->cart->destroy();
        $data['title'] = "Set Harga Obat";
        $data['url'] = "transaksi/SetHargaObat/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Set Harga Obat",
             'link' => base_url()."transaksi/SetHargaObat",
             'status' => "active",
            ),
        ); 
        
        $where = array(
            // 'user_verified' => "", 
            'harga_jual' => 0, 
        );
        $data['detail_obat'] = $this->M_detail_obat->cek_detail_obat($where);

        $this->load->view('include2/sidebar', $data);
        $this->load->view('setHargaObat/index', $data);
        $this->load->view('include2/footer');
    } 

    public function reload_data()
    {
        $data_balikan['data']= array();
        $data['data']= $this->M_trxPenjualan->getAll();
        $this->load->view('trxPenjualan/data', $data);
    }

    public function setHarga()
    { 
        $pesan="";
        $return=""; 
        $hasil_query = array();

        // var_dump ($_POST);
        if (isset($_POST['barcode']))
        { 
            $barcode = $this->input->post('barcode');
            $no_reg = $this->input->post('no_reg');
            $no_batch = $this->input->post('no_batch'); 
            $harga_jual = $this->input->post('harga_jual'); 
            $diskon_maximal = $this->input->post('diskon_maximal'); 

            // $size = sizeof($barcode); 
            $data = array();
            foreach($barcode as $key=>$value) {  

                $where = array(
                    'barcode' => $value, 
                    'no_reg' => $no_reg[$key], 
                    'no_batch' => $no_batch[$key], 
                );
                 
                $data = array( 
                    'harga_jual' => $harga_jual[$key],  
                    'diskon_maximal' => $diskon_maximal[$key],  
                );  

                $this->db->where($where);
                $hasil_query[] =$this->db->update('detail_obat', $data);  
            } 

            $return=1;
            if ($return>0)
            {
                $return=1;
                $pesan= "Proses set harga obat berhasil ";
            }
            else{
                $return=0; 
                $pesan= "Proses set harga obat gagal ";
            }  
        }
        else
        {
            $return=0;  
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        } 

        $data2 = array(
            'return' => $return, 
            'pesan' => $pesan,  
            'hasil_query' => $hasil_query,  
        ); 
        echo json_encode($data2); 
    } 
     
}
