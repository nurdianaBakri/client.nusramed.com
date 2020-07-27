<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_detail extends CI_Controller {

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


    public function index($id_trx)
    {  
        $data['title'] = "Transaksi Penjulan";
        $data['url'] = "transaksi/Penjualan_detail/"; 

        $where = array('id_trx' => $id_trx );
        $data['data']= $this->M_trxPenjualan_detail->detail($where)->result_array(); 
  
        $this->load->view('trxPenjualan/detail/data',$data); 
    } 
  
    public function get_form()
    {     
        $data['jenis_aksi'] ="add"; 
        $data['data']['nama'] = null;
        $data['data']['deskripsi'] = null;
        $data['data']['id_transaksi'] = null; 

        $data['url_inquery']="trxPenjualan/inquery"; 
        $this->load->view('trxPenjualan/form_tambah_trx',$data);
    }
   

     public function reload_data()
    { 
        $data_balikan['data']= array();
        $data['data']= $this->M_trxPenjualan->getAll();  
        $this->load->view('trxPenjualan/data', $data); 
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
            'id_transaksi' => $this->input->post('id_transaksi'),
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
            $hasil['status'] = $this->M_trxPenjualan->save($data);   
        }   
        else{
            $where = array('id_transaksi' => $this->input->post('id_transaksi') );
            $hasil['status'] = $this->M_trxPenjualan->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah obat berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah obat gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah obat berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah obat gagal";  
        }  
 
        $hasil['jenis_aksi']=$jenis_aksi;  
        $this->session->set_flashdata('pesan', $data['pesan']);  
        redirect('Penjulan');
    }

     public function edit($id_transaksi)
    {  
         $data['title'] = "Penjulan";
        $data['url'] = "transaksi/Penjualan_detail/"; 
        $where = array('id_transaksi' => $id_transaksi );
        $data['data']=$this->M_trxPenjualan->getBy($where);   
        $data['id_transaksi']=$id_transaksi;  
        if ($id_transaksi==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        } 
 
        $data['url_inquery']="trxPenjualan/inquery"; 
        $data['industri'] = $this->Industri_model->getAll();   
        $data['suplier'] = $this->Suplier_model->getAll();   
        $data['kategori_obat'] = $this->Kategori_obat->getAll();   
        $data['satuan'] = $this->Satuan_obat->getAll();    
        $data['kategori'] = $this->Kategori->getAll();    
 

        $this->load->view('include/header'); 
        $this->load->view('trxPenjualan/form',$data);
        $this->load->view('include/footer');   
    }

     public function hapus($id)
    {
        $hasil = array();  
        $where = array('id_transaksi' => $id );
 
        $hasil['status']=$this->M_trxPenjualan->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data obat berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data obat berhasil";  
        }
        echo json_encode($hasil);
    }

}