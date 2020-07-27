<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemeliharaanUrusan extends CI_Controller {

    public function index()
    {
        //get data kasda 
        $data['data'] = $this->MPemeliharaanUrusan->getAll();
        $this->load->view('include/header');
        $this->load->view('pemeliharaan_urusan/index', $data);
        $this->load->view('include/footer');  
    }

    public function reload_data()
    { 
        $data['data'] = $this->MPemeliharaanUrusan->getAll(); 
          
        $this->load->view('pemeliharaan_urusan/data', $data); 
    }

    public function detail($kd_urusan=null)
    {
        $where = array('KD_URUSAN' => $kd_urusan );
        $data['data']=$this->MPemeliharaanUrusan->getBy($where);   
        $data['kd_urusan']=$kd_urusan; 
        if ($kd_urusan==null)
        { 
            //get last kd_urusan + 1
            // $data['data']['KD_URUSAN'] = $this->MPemeliharaanUrusan->get_last_kdurusan();
            // $data['data']['NM_URUSAN'] = "";
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        } 
        $this->load->view('pemeliharaan_urusan/form', $data); 
    }

     public function hapus($kode_kasda)
    {
        $hasil = array(); 
        $where = array('KD_URUSAN' => $kode_kasda );
        $hasil['status']=$this->MPemeliharaanUrusan->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data Urusan berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data Urusan berhasil";  
        }
        echo json_encode($hasil);
    }

	public function  cari_kesda($kode)
	{ 
        $where = array('KD_URUSAN' => $kode );
        $data=$this->MPemeliharaanUrusan->getBy($where);   
        echo json_encode($data); 
    }
    
    public function  save()
	{   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_URUSAN' => $this->input->post('KD_URUSAN'),
            'NM_URUSAN' => $this->input->post('NM_URUSAN'), 
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
        );

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $data['KD_URUSAN'] = $this->input->post('KD_URUSAN');
            $hasil['status'] = $this->MPemeliharaanUrusan->save($data);  
        }

        //aksi edit
        else{
            $where = array('KD_URUSAN' => $this->input->post('KD_URUSAN') );
            $hasil['status'] = $this->MPemeliharaanUrusan->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah data Urusan berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah data Urusan gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah data Urusan berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah data Urusan gagal";  
        }  

        $hasil['data'] = $data;
        // $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);
	} 
	 
}
