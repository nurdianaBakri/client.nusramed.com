<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_sumber extends CI_Controller {

	public function  index()
	{   
        $this->load->view('include/header'); 
		$this->load->view('pemeliharaan_rekening_bank/rek_sumber/index');
        $this->load->view('include/footer');  
	} 

	public function get_form()
	{  
		$data['jenis_aksi'] ="add";
        $data['KD_DATA'] =NULL; 
        $data['KD_SUMBER_DANA']=NULL; $data['NM_SUMBER_DANA']=NULL;

		$data['url_inquery']="parameterrekeningbank/Rekening_sumber/inquery"; 
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();   
        $data['rek_sumber'] = $this->MPemeliharaanKasda->getAll();   
		$this->load->view('pemeliharaan_rekening_bank/rek_sumber/form',$data);
	}

	 public function reload_data()
    { 
        $data_balikan = array();
        $data = $this->MRekeningSumber->getAll(); 
        foreach ($data as $key)
        {
            $key['KD_KASDA'] = $key['KD_KASDA']." - ".$this->MPemeliharaanKasda->getBy( array('KD_KASDA' => $key['KD_KASDA'] ))['NM_KASDA']; 
            $data_balikan['data'][]= $key;
        }  
        $this->load->view('pemeliharaan_rekening_bank/rek_sumber/data', $data_balikan); 
    }

     public function inquery()
    {
        $KD_KASDA = $this->input->post('KD_KASDA'); 
        $data = array('KD_KASDA' => $KD_KASDA );   
        $data['data'] = $this->MRekeningSumber->getByObject($data);  
        $this->load->view('pemeliharaan_rekening_bank/rek_sumber/data', $data);  
    }

     public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_KASDA' => $this->input->post('KD_KASDA'),
            'NM_SUMBER_DANA' => $this->input->post('NM_SUMBER_DANA'), 
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
            'KD_SUMBER_DANA' => $this->input->post('KD_SUMBER_DANA'), 
        );

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MRekeningSumber->save($data);   
        }   
        else{
            $where = array('KD_DATA' => $this->input->post('KD_DATA') );
            $hasil['status'] = $this->MRekeningSumber->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah Rekening Sumber berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah Rekening Sumber gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah Rekening Sumber berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah Rekening Sumber gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);  
    }

     public function detail($KD_DATA=null)
    { 
        $where = array('KD_DATA' => $KD_DATA );
        $data['data']=$this->MRekeningSumber->getBy($where);   
        $data['KD_DATA']=$KD_DATA;
        $data['KD_SUMBER_DANA']=$data['data']['KD_SUMBER_DANA']; 
        $data['NM_SUMBER_DANA']=$data['data']['NM_SUMBER_DANA']; 
        if ($KD_DATA==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }

        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['url_inquery']="parameterrekeningbank/Rekening_sumber/inquery"; 
        $this->load->view('pemeliharaan_rekening_bank/rek_sumber/form',$data);
    }

     public function hapus()
    {
        $hasil = array(); 
        $KD_DATA = $this->input->post('KD_DATA');
        $where = array('KD_DATA' => $KD_DATA );
        $hasil['status']=$this->MRekeningSumber->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus rekening sumber berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus rekening sumber berhasil";  
        }
        echo json_encode($hasil);
    }

}