<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_skpd extends CI_Controller {

	public function  index()
	{  
        $data['title'] = "Pemeliharaan Rekening SKPD";
        $data['url'] = "parameterrekeningbank/Rekening_skpd/";
        $this->load->view('include/header'); 
		$this->load->view('parameter/index',$data);
        $this->load->view('include/footer');  
	} 

	public function get_form()
	{   
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $skpd = $this->MPemeliharaanSubUnit->getAll();  
        foreach ($skpd as $key)
        {
            $data['skpd'][] = array(
                'kd_gabungan' => $key->KD_URUSAN.".".$key->KD_BIDANG.".".$key->KD_UNIT.".".$key->KD_SUB_UNIT, 
                'nama_skpd'=>$key->NM_SUB_UNIT
            );
        }
        $data['jenis_aksi'] ="add"; 
        $data['NO_REK'] =null; 
        $data['KD_DATA'] =null; 
        $data['NM_PEMILIK'] =null; 
        $data['url_inquery']="parameterrekeningbank/Rekening_skpd/inquery"; 
		$this->load->view('parameter/rekeningSkpd/formPemeliharaaanRekSkpd',$data);
	}

	 public function reload_data()
    { 
        $data_balikan['data']= array();
        $data = $this->MRekeningSkpd->getAll(); 
        if ($data->num_rows()>0)
        {
            foreach ($data->result_array() as $key)
            {
                $key['KD_KASDA'] = $key['KD_KASDA']." - ".$this->MPemeliharaanKasda->getBy( array('KD_KASDA' => $key['KD_KASDA'] ))['NM_KASDA'];  
                $key['KD_CAB'] = $key['KD_CAB']." - ".$this->MPemeliharaanCabang->getBy( array('KD_CAB' => $key['KD_CAB'] ))['URAIAN']; 
                $data_balikan['data'][]= $key;
            } 
        } 
              
        $this->load->view('parameter/data', $data_balikan); 
    } 

    public function get_kd_sumber_dana()
    {
        $KD_KASDA= $this->input->post('KD_KASDA'); 
        $where = array(
            'KD_KASDA' => $KD_KASDA, 
        );
        $data['jenis_aksi']=$this->input->post('jenis_aksi');
        $data['sumber_dana'] = $this->MRekeningSumber->getByResult($where); 
        $this->load->view('parameter/form_kd_data_sumber_dana',$data);
    }

      public function inquery()
    {
        $KD_KASDA = $this->input->post('KD_KASDA'); 
        $where = array('KD_KASDA' => $KD_KASDA );   
        $data['data'] = $this->MRekeningSkpd->getByObject($where);  

        $this->load->view('parameter/data', $data);  
    }

      public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_KASDA' => $this->input->post('KD_KASDA'), 
            'KD_SKPD' => $this->input->post('KD_SKPD'), 
            'KD_CAB' => $this->input->post('KD_CAB'), 
            'NO_REK' => $this->input->post('NO_REK'), 
            'NM_PEMILIK' => $this->input->post('NM_PEMILIK'), 
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
            'STATUS' => $this->input->post('STATUS'), 
        );   

        // aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MRekeningSkpd->save($data);   
        }   
        else{
            $where = array('KD_DATA' => $this->input->post('KD_DATA') );
            $hasil['status'] = $this->MRekeningSkpd->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah Rekening SKPD berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah Rekening SKPD gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah Rekening SKPD berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah Rekening SKPD gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['where'] = $where;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);  
    }

     public function detail()
    { 
        $KD_DATA = $this->input->post('KD_DATA'); 
        $where = array('KD_DATA' => $KD_DATA );
        $data['data']=$this->MRekeningSkpd->getBy($where);   
        $data['KD_DATA']=$KD_DATA; 
        $data['KD_SKPD']=$data['data']['KD_SKPD'];   
        $data['NO_REK']=$data['data']['NO_REK'];  
        $data['KD_KASDA']=$data['data']['KD_KASDA'];  
        $data['KD_CAB']=$data['data']['KD_CAB'];  
        $data['NM_PEMILIK']=$data['data']['NM_PEMILIK'];  
        if ($KD_DATA==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        } 

        $data['skpd'] = $this->M_SKPD->getAll();   
        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['url_inquery']="parameterrekeningbank/Rekening_sumber/inquery"; 
        $this->load->view('parameter/rekeningSkpd/formPemeliharaaanRekSkpd',$data);
    }

     public function hapus()
    {
        $hasil = array(); 
        $KD_DATA = $this->input->post('KD_DATA');
        $where = array('KD_DATA' => $KD_DATA );
        $hasil['status']=$this->MRekeningSkpd->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus rekening SKPD berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus rekening SKPD berhasil";  
        }
        echo json_encode($hasil);
    }

}