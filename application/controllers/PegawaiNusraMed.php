<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PegawaiNusraMed extends CI_Controller {

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
        $data['title'] = "Pegawai Nusra Medika";
        $data['url'] = "PegawaiNusraMed/";  
        $data['data']="Module sedang dibuat";    

        $this->load->view('include/header'); 
        $this->load->view('pegawaiNusraMed/index',$data); 
        $this->load->view('include/footer');   
    } 

    public function get_form()
    {     
        $data['jenis_aksi'] ="add"; 
        $data['data']['nama'] = null;
        $data['data']['deskripsi'] = null;
        $data['data']['id_pegawai'] = null;
        $data['data']['kd_industri'] = null;
        $data['data']['kd_suplier'] = null;
        $data['data']['kd_satuan'] = null;
        $data['data']['kd_kategori'] = null;
        $data['data']['kd_kategori_obat'] = null;
        $data['data']['stok'] = null;
        $data['data']['kandungan'] = null;
        $data['data']['produsen'] = null; 
        $data['data']['harga_beli'] = null;
        $data['data']['diskon_beli'] = null;
        $data['data']['harga_jual'] = null;
        $data['data']['diskon_jual'] = null;
        $data['data']['lokasi_rak'] = null;
        $data['data']['jenis_terapi'] = null; 
        $data['id_pegawai'] = null; 

        $data['industri'] = $this->Industri_model->getAll();   
        $data['suplier'] = $this->Suplier_model->getAll();   
        $data['kategori_obat'] = $this->Kategori_obat->getAll();   
        $data['kategori'] = $this->Kategori->getAll();   
        $data['satuan'] = $this->Satuan_obat->getAll();    

        $data['url_inquery']="pegawaiNusraMed/inquery"; 
        $this->load->view('pegawaiNusraMed/form',$data);
    }  

    public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'nama' => $this->input->post('nama'),
            'kd_satuan' => $this->input->post('kd_satuan'),
            'kd_industri' => $this->input->post('kd_industri'),
            'kd_suplier' => $this->input->post('kd_suplier'),
            'deskripsi' => $this->input->post('deskripsi'),
            'lokasi_rak' => $this->input->post('lokasi_rak'),
            'id_pegawai' => $this->input->post('id_pegawai'),
            'kd_kategori_obat' => $this->input->post('kd_kategori_obat'),
            'kategori' => $this->input->post('kategori'),
            'kandungan' => $this->input->post('kandungan'),
            'diskon_jual' => $this->input->post('diskon_jual'),
            'harga_beli' => $this->input->post('harga_beli'),
            'harga_jual' => $this->input->post('harga_jual'),
            'diskon_beli' => $this->input->post('diskon_beli'),
            'jenis_terapi' => $this->input->post('jenis_terapi'), 
        );   

        // aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->M_pegawai->save($data);   
        }   
        else{
            $where = array('id_pegawai' => $this->input->post('id_pegawai') );
            $hasil['status'] = $this->M_pegawai->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah pegawai berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah pegawai gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah pegawai berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah pegawai gagal";  
        }  
 
        $hasil['jenis_aksi']=$jenis_aksi;  
        $this->session->set_flashdata('pesan', $data['pesan']);  
        redirect('Obat');
    }

     public function edit($id_pegawai)
    {  
         $data['title'] = "Obat";
        $data['url'] = "PegawaiNusraMed/"; 
        $where = array('id_pegawai' => $id_pegawai );
        $data['data']=$this->M_pegawai->getBy($where);   
        $data['id_pegawai']=$id_pegawai;  
        if ($id_pegawai==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        } 
 
        $data['url_inquery']="pegawaiNusraMed/inquery"; 
        $data['industri'] = $this->Industri_model->getAll();   
        $data['suplier'] = $this->Suplier_model->getAll();   
        $data['kategori_obat'] = $this->Kategori_obat->getAll();   
        $data['satuan'] = $this->Satuan_obat->getAll();    
        $data['kategori'] = $this->Kategori->getAll();    
 

        $this->load->view('include/header'); 
        $this->load->view('pegawaiNusraMed/form',$data);
        $this->load->view('include/footer');   
    }

     public function hapus($id)
    {
        $hasil = array();  
        $where = array('id_pegawai' => $id );
 
        $hasil['status']=$this->M_pegawai->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data pegawai berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data pegawai berhasil";  
        }
        echo json_encode($hasil);
    }

}