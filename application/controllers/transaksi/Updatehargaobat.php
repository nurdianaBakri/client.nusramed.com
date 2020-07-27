<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updatehargaobat extends CI_Controller {

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
        // var_dump($this->db->get('detail_obat')->result_array()) ; 
        echo $this->db->query("UPDATE detail_obat set tgl_exp='2023-05-25 00:00:00' where tgl_exp=NULL");
    }

    

}