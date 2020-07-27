<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemeliharaanBidang extends CI_Controller {

    public function index()
    { 
        $this->load->view('include/header');
        $this->load->view('pemeliharaan_bidang/index');
        $this->load->view('include/footer'); 
    }

    public function reload_data()
    { 
        $data['data'] = $this->MPemeliharaanBidang->getAll();   
        $this->load->view('pemeliharaan_bidang/data', $data); 
    }

    public function detail($KD_DATA_BIDANG=null )
    { 
        $where = array('KD_DATA_BIDANG' => $KD_DATA_BIDANG );
        $data['data']=$this->MPemeliharaanBidang->getBy($where); 
        if ($KD_DATA_BIDANG==null)
        { 
            //get last kd_bidang + 1
            $data['data']['KD_DATA_BIDANG'] = $this->MPemeliharaanBidang->get_last();
            $data['data']['NM_BIDANG'] = "";
            $data['data']['KD_BIDANG'] = "";
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";  
        } 

        $data['urusan'] = $this->MPemeliharaanUrusan->getAll();
        $this->load->view('pemeliharaan_bidang/form', $data); 
    }

    public function get_kode_bidang_by_kode_urusan($KD_URUSAN)
    { 
        $where = array('KD_URUSAN' => $KD_URUSAN);
        $data['data']=$this->MPemeliharaanBidang->get_kode_bidang_by_kode_urusan($where);    
        echo json_encode($data['data']);
    }

     public function hapus($KD_DATA_BIDANG)
    {
        $hasil = array(); 
        $where = array('KD_DATA_BIDANG' => $KD_DATA_BIDANG );
        $hasil['status']=$this->MPemeliharaanBidang->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data Bidang berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data Bidang berhasil";  
        }
        echo json_encode($hasil);
    }

	public function  cari_kesda($kode)
	{ 
        $where = array('KD_BIDANG' => $kode );
        $data=$this->MPemeliharaanBidang->getBy($where);   
        echo json_encode($data); 
    }
 
    
    public function save()
	{   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_BIDANG' => $this->input->post('KD_BIDANG'),
            'NM_BIDANG' => $this->input->post('NM_BIDANG'),
            'KD_URUSAN' => $this->input->post('KD_URUSAN'), 
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
        );

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MPemeliharaanBidang->save($data);  
        }

        //aksi edit
        else{
            $where = array('KD_DATA_BIDANG' => $this->input->post('KD_DATA_BIDANG') );
            $hasil['status'] = $this->MPemeliharaanBidang->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah data Bidang berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah data Bidang gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah data Bidang berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah data Bidang gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);
	}
	 
}
