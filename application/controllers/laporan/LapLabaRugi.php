<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LapLabaRugi extends CI_Controller {

    public function __construct()
    {
        parent ::__construct();
        $cek = $this->M_login->cek_userIsLogedIn(); 
        // var_dump($cek);
        if ($cek==FALSE)
        {
            $this->session->set_flashdata('pesan',"Anda harus login jika ingin mengakses halaman lain");
            redirect('Home');
        } 
    } 

    public function  index()
    {  
        $data['title'] = "Laporan Laba Ruugi";
        $data['url'] = "laporan/LapLabaRugi/"; 
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ),
            array(
             'fitur' => "Laporan Laba Ruugi", 
             'link' => base_url()."laporan/LapLabaRugi", 
             'status' => "active", 
            ), 
        );

        $data['outlet'] = $this->db->get('outlet1')->result_array(); 

        $this->load->view('include2/sidebar',$data); 
        $this->load->view('lapLabaRugi/index',$data);
        $this->load->view('include2/footer');  
    }  

    public function get_laporan()
    {
        $kd_suplier = $this->input->post('kd_suplier');
        $tanggal_mulai = $this->input->post('tanggal_mulai');
        $tanggal_sampai = $this->input->post('tanggal_sampai');
        $data = array();

        if ($kd_suplier=="all")
        {
            $data['laporan'] = $this->db->query("SELECT * from detail_obat WHERE `time` between '$tanggal_mulai 00:00:01' and '$tanggal_sampai 00:00:00'");
        }
        else
        {
            $data['laporan'] = $this->db->query("SELECT * from detail_obat where kd_suplier='$kd_suplier' and (`time` >= '$tanggal_mulai 00:00:01' and `time` <= '$tanggal_sampai 00:00:00')");
        }
        // echo $this->db->last_query();
        $this->load->view('lapLabaRugi/data',$data);
    }
    
}