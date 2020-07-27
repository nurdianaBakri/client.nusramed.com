<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_kasda extends CI_Controller {

	public function  index()
	{  
        $this->load->view('include/header'); 
		$this->load->view('pemeliharaan_rekening_bank/rek_kasda/index');
        $this->load->view('include/footer');  
	} 

	public function get_form()
	{   
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $data['sumber_dana'] = $this->MRekeningSumber->getAll();  
        $data['jenis_aksi'] ="add"; 
        $data['NO_REK'] =null; 
        $data['KD_DATA'] =null; 
        $data['NM_PEMILIK'] =null; 
        $data['url_inquery']="parameterrekeningbank/Rekening_kasda/inquery";  
		$this->load->view('pemeliharaan_rekening_bank/rek_kasda/form',$data);
	}

	 public function reload_data()
    { 
        $data_balikan = array();
        $data = $this->MRekeningKasda->getAll();   
        foreach ($data as $key)
        {
            $key['KD_KASDA'] = $key['KD_KASDA']." - ".$this->MPemeliharaanKasda->getBy( array('KD_KASDA' => $key['KD_KASDA'] ))['NM_KASDA'];  
            $key['KD_CAB'] = $key['KD_CAB']." - ".$this->MPemeliharaanCabang->getBy( array('KD_CAB' => $key['KD_CAB'] ))['URAIAN']; 
            $data_balikan['data'][]= $key;
        }  

        $this->load->view('pemeliharaan_rekening_bank/rek_kasda/data', $data_balikan); 
    }

    public function get_kd_sumber_dana()
    {
        $KD_KASDA= $this->input->post('KD_KASDA'); 
        $where = array(
            'KD_KASDA' => $KD_KASDA, 
        );
        $data['jenis_aksi']=$this->input->post('jenis_aksi');
        $data['sumber_dana'] = $this->MRekeningSumber->getByResult($where); 
        $this->load->view('pemeliharaan_rekening_bank/rek_kasda/form_kd_data_sumber_dana',$data);
    }

      public function inquery()
    {
        $KD_KASDA = $this->input->post('KD_KASDA'); 
        $where = array('KD_KASDA' => $KD_KASDA );   
        $data['data'] = $this->MRekeningKasda->getByObject($where);  

        $this->load->view('pemeliharaan_rekening_bank/rek_kasda/data', $data);  
    }

      public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_KASDA' => $this->input->post('KD_KASDA'),
            'KD_SUMBER_DANA' => $this->input->post('KD_SUMBER_DANA'),  
            'KD_CAB' => $this->input->post('KD_CAB'), 
            'NO_REK' => $this->input->post('NO_REK'), 
            'NM_PEMILIK' => $this->input->post('NM_PEMILIK'), 
            'KD_SUMBER_DANA' => $this->input->post('KD_SUMBER_DANA'),  
            'NM_PEMILIK' => $this->input->post('NM_PEMILIK'), 
            'STATUS' => 1, 
        );    

        // aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $data['USER_INPUT'] = $this->input->post('USER_INPUT');
            $hasil['status'] = $this->MRekeningKasda->save($data);   
        }   
        else{
            $data['USER_UPDATE'] = $this->input->post('USER_INPUT'); 
            $where = array('KD_DATA' => $this->input->post('KD_DATA') );
            $hasil['status'] = $this->MRekeningKasda->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah Rekening Kasda berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah Rekening Kasda gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah Rekening Kasda berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah Rekening Kasda gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);
        // var_dump($data); 
    }

     public function detail()
    { 
        $KD_DATA = $this->input->post('KD_DATA'); 
        $where = array('KD_DATA' => $KD_DATA );
        $data['data']=$this->MRekeningKasda->getBy($where);   
        $data['KD_DATA']=$KD_DATA; 
        $data['KD_SUMBER_DANA']=$data['data']['KD_SUMBER_DANA'];  
        $data['KD_CAB']=$data['data']['KD_CAB'];  
        $data['NO_REK']=$data['data']['NO_REK'];  
        $data['KD_CAB']=$data['data']['KD_CAB'];  
        $data['KD_SUMBER_DANA']=$data['data']['KD_SUMBER_DANA'];  
        $data['NM_SUMBER_DANA']=$data['data']['NM_SUMBER_DANA'];  
        $data['NM_PEMILIK']=$data['data']['NM_PEMILIK'];  
        if ($KD_DATA==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }


        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['url_inquery']="parameterrekeningbank/Rekening_sumber/inquery"; 
        $this->load->view('pemeliharaan_rekening_bank/rek_kasda/form',$data);
    }

     public function hapus()
    {
        $hasil = array(); 
        $KD_DATA = $this->input->post('KD_DATA');
        $where = array('KD_DATA' => $KD_DATA );
        $hasil['status']=$this->MRekeningKasda->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus rekening kasda berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus rekening kasda berhasil";  
        }
        echo json_encode($hasil);
    }

}