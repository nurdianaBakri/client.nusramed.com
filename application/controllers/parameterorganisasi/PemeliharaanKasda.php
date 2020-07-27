<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemeliharaanKasda extends CI_Controller {

    public function index()
    {
        //get data kasda 
        $data['data_kasda'] = $this->MPemeliharaanKasda->getAll(); 
        $this->load->view('include/header');
        $this->load->view('pemeliharaan_organisasi/index', $data);
        $this->load->view('include/footer'); 
    }

    public function reload_data()
    { 
        $data['data_kasda'] = $this->MPemeliharaanKasda->getAll();  
        $this->load->view('pemeliharaan_organisasi/data_pm_kasda', $data); 
    }

    public function detail($kode_kasda=null)
    { 
        $where = array('KD_KASDA' => $kode_kasda );
        $data['data_kasda']=$this->MPemeliharaanKasda->getBy($where);   
        $data['kode_kasda']=$kode_kasda; 
        if ($kode_kasda==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }
        $this->load->view('pemeliharaan_organisasi/form', $data); 
    }

     public function hapus($kode_kasda)
    {
        $hasil = array(); 
        $where = array('KD_KASDA' => $kode_kasda );
        $hasil['status']=$this->MPemeliharaanKasda->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data kasda berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data kasda berhasil";  
        }
        echo json_encode($hasil);
    }


	public function  cari_kesda($kode)
	{ 
        $where = array('KD_KASDA' => $kode );
        $data=$this->MPemeliharaanKasda->getBy($where);   
        echo json_encode($data); 
    }
    
    public function  save()
	{   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KOTA' => $this->input->post('KOTA'),
            'NO_FAX' => $this->input->post('NO_FAX'),
            'USER_INPUT' => $this->input->post('USER_INPUT'),
            'NPWP' => $this->input->post('NPWP'),
            'IMAGE' => $this->input->post('IMAGE'),
            'USER_UPDATE' => $this->input->post('USER_UPDATE'),
            'ALAMAT_KASDA' => $this->input->post('ALAMAT_KASDA'),
            'NM_KASDA' => $this->input->post('NM_KASDA'),
            'NO_TELP_1' => $this->input->post('NO_TELP_1'),
            'NO_TELP_2' => $this->input->post('NO_TELP_2'),
            'KEPALA_DAERAH' => $this->input->post('KEPALA_DAERAH'),
            'JABATAN' => $this->input->post('JABATAN'),
            'LOGO_KASDA' => $this->input->post('LOGO_KASDA'),
            'KBUD' => $this->input->post('KBUD'),
            'NIP_KBUD' => $this->input->post('NIP_KBUD'),
            'SEKDA' => $this->input->post('SEKDA'),
            'NIP_SEKDA' => $this->input->post('NIP_SEKDA'),
            'BUD' => $this->input->post('BUD'),
            'NIP_BUD' => $this->input->post('NIP_BUD'),
            'PPKD' => $this->input->post('PPKD'),
            'NIP_PPKD' => $this->input->post('NIP_PPKD'),
            'TGL_IMPLEMENTASI' => date("Y/m/d h:m:s"),
        );

        //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $data['KD_KASDA'] = $this->input->post('KD_KASDA');
            $hasil['status'] = $this->MPemeliharaanKasda->save($data);  
        }

        //aksi edit
        else{
            $where = array('KD_KASDA' => $this->input->post('KD_KASDA') );
            $hasil['status'] = $this->MPemeliharaanKasda->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah data Kasda berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah data Kasda gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah data Kasda berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah data Kasda gagal";  
        }  
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);
	}
	 
}
