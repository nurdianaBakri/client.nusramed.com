<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogActivityUser_new extends CI_Controller {

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
        $data['title'] = "Laporan Log Activity User";
        $data['url'] = "laporan/LogActivityUser_new/"; 
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ),
            array(
             'fitur' => "Laporan Log Activity User", 
             'link' => base_url()."laporan/LogActivityUser_new", 
             'status' => "active", 
            ), 
        );
 
        $data['user'] = $this->db->get('user_login')->result_array(); 

        $this->load->view('include2/sidebar',$data); 
        $this->load->view('lapLogActivityUser_new/index',$data);
        $this->load->view('include2/footer');  
    }  

    public function get_laporan()
    {
        $id_user = $this->input->post('id');
        $tanggal_mulai = $this->input->post('tanggal_mulai');
        $tanggal_sampai = $this->input->post('tanggal_sampai');
        $data = array(); 

        if ($id_user=="all")
        {
            $data['laporan'] = $this->db->query("SELECT log_activity.*, user_login.nama as nama_user from log_activity, user_login WHERE user_login.id=log_activity.id_user and `tgl_log` between '$tanggal_mulai 00:00:01' and '$tanggal_sampai 23:59:59' order by tgl_log DESC");
        }
        else
        {
            $data['laporan'] = $this->db->query("SELECT log_activity.*, user_login.nama as nama_user from log_activity, user_login  WHERE log_activity.id_user='$id_user' and log_activity.id_user=user_login.id and  (`tgl_log` >= '$tanggal_mulai 00:00:01' and `tgl_log` <= '$tanggal_sampai 23:59:59') order by tgl_log DESC");
        }
        // echo $this->db->last_query();
        $this->load->view('lapLogActivityUser_new/data',$data);
    }
    
}