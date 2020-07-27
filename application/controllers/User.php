<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
        $data['title'] = "Manajemen User";
        $data['url'] = "User/"; 
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ),
            array(
             'fitur' => "user", 
             'link' => base_url(), 
             'status' => "active", 
            ), 
        );  

       
        $this->load->view('include2/sidebar',$data); 
        $this->load->view('user/index',$data); 
        $this->load->view('include2/footer');   
    }  

    public function reload_data()
    { 
        $data['data']= $this->MUserSystem->getAll(); 
        $this->load->view('user/data', $data); 
    }   

    public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'nama' => $this->input->post('nama'),
            'alamat' => $this->input->post('alamat'),
            'no_hp' => $this->input->post('no_hp'),
            'username' => $this->input->post('username'),
            'jabatan' => $this->input->post('jabatan'),
            'email' => $this->input->post('email'),
            'deleted' => $this->input->post('deleted'),
            'password' => md5($this->input->post('password')), 
        );   

        // aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MUserSystem->save($data);   
        }   
        else{
            $where = array('id' => $this->input->post('id') );
            $hasil['status'] = $this->MUserSystem->update($where, $data);   
        }  
        
        // var_dump($where); 
        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah user berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah user gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah user berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah user gagal";  
        }      
        redirect('User?return='.$hasil['status']);
    }

     public function detail($id)
    {  
         $data['title'] = "Detail User";
        $data['url'] = "user/"; 
        $where = array('id' => $id );
        $data['data']=$this->MUserSystem->getBy($where);   
        $data['id']=$id;  
        if ($id==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }       
        $this->load->view('user/form',$data); 
    }

     public function hapus($id)
    {
        $hasil = array();  
        $where = array('id' => $id );
 
        $hasil['status']=$this->MUserSystem->hapus($where);  
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus user berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus user gagal";  
        }

        $data = array(
            'pesan' => $hasil['pesan'], 
            'return' => $hasil['status'], 
        );   
        echo json_encode($data);  
    }

    public function get_form()
    {      
        $data['url']="outlet/"; 
        $data['title']="Tambah User"; 

         $data['data'] = array( 
            'nama' => "",
            'alamat' => "",
            'no_hp' => "",
            'username' => "",
            'password' =>  "",  
            'email' =>  "",  
            'id' =>  "",  
            'deleted' =>  "",  
            'jabatan' =>  "",  
        );     
        $data['jenis_aksi'] ="add";   
        $this->load->view('user/form',$data);
    } 

}