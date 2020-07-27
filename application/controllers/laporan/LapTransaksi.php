<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LapTransaksi extends CI_Controller {

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

    public function  index()
    {  
        $data['title'] = "Laporan Penjualan";
        $data['url'] = "laporan/LapTransaksi/"; 
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ),
            array(
             'fitur' => "Laporan Penjualan", 
             'link' => base_url()."laporan/LapTransaksi", 
             'status' => "active", 
            ), 
        );
        
        $this->db->select('id_outlet, nama');
        $data['outlet'] = $this->db->get('outlet1')->result_array(); 

        $this->load->view('include2/sidebar',$data); 
        $this->load->view('lapTransaksi/index',$data);
        $this->load->view('include2/footer');  
    }  
    
    public function get_laporan()
    {
        $kd_outlet = $this->input->post('id_outlet');
        $tanggal_mulai = $this->input->post('tanggal_mulai');
        $tanggal_sampai = $this->input->post('tanggal_sampai');
        $data = array();

        if ($kd_outlet=="all")
        {
            $data['laporan'] = $this->db->query("SELECT  B.nama, A.* from trx_penjualan_tmp A, outlet1 B WHERE B.id_outlet=A.kd_outlet and `time` between '$tanggal_mulai 00:00:00' and '$tanggal_sampai 23:59:59'");
        }
        else
        {
            $data['laporan'] = $this->db->query("SELECT  B.nama, A.* from trx_penjualan_tmp A, outlet1 B WHERE B.id_outlet=A.kd_outlet and kd_outlet='$kd_outlet' and (`time` >= '$tanggal_mulai 00:00:01' and `time` <= '$tanggal_sampai 23:59:59')");
        } 

        // echo $this->db->last_query();
        $this->load->view('lapTransaksi/data',$data);
    } 

}