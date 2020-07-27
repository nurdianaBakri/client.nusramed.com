<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekening_potongan extends CI_Controller {

	public function  index()
	{  
        $this->load->view('include/header'); 
		$this->load->view('pemeliharaan_rekening_bank/rek_potongan/index');
        $this->load->view('include/footer');  
	} 

	public function get_form()
	{  
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $data['map'] = $this->MPemeliharaanMap->getAll();  
        $data['jenis_aksi'] ="add";
        $data['KD_DATA'] =NULL; $data['NO_REK']=NULL; $data['NM_PEMILIK']=NULL;
        $data['url_inquery']="parameterrekeningbank/Rekening_potongan/inquery"; 
		$this->load->view('pemeliharaan_rekening_bank/rek_potongan/form',$data);
	}
 

	public function reload_data()
    { 
        $data['data'] = $this->MRekeningPotongan->getAll();  
        $this->load->view('pemeliharaan_rekening_bank/rek_potongan/data', $data); 
    } 

    public function get_pemilik_rekening($bo_rek)
    { 
        $data = array(  
            'accnbr'=>$bo_rek, 
        );

        $data_new = json_encode($data);
        
        $curl_bearer = curl_init();
        curl_setopt($curl_bearer, CURLOPT_URL, 'http://10.2.2.3/ntb-rest-server/index.php/inquiry');
        curl_setopt($curl_bearer, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_bearer, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl_bearer, CURLOPT_POST, true);
        curl_setopt($curl_bearer, CURLOPT_POSTFIELDS, $data_new);
        curl_setopt($curl_bearer, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($curl_bearer);
        curl_close($curl_bearer); 
        $hasil = json_decode($result, true); 
        echo json_encode($hasil);
    }

    public function inquery()
    {
        $KD_KASDA = $this->input->post('KD_KASDA'); 
        $data = array('KD_KASDA' => $KD_KASDA );   
        $data['data'] = $this->MRekeningPotongan->getByObject($data);  
        $this->load->view('pemeliharaan_rekening_bank/rek_potongan/data', $data);  
    }

    public function save()
    {   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_CAB' => $this->input->post('KD_CAB'),
            'NM_PEMILIK' => $this->input->post('NM_PEMILIK'), 
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
            'NO_REK' => $this->input->post('NO_REK'), 
            'STATUS' => 1, 
            'KD_KASDA' => $this->input->post('KD_KASDA'),
            'KD_MAP' => $this->input->post('KD_MAP'),
        );

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MRekeningPotongan->save($data);   
        }   
        else{
            $where = array('KD_DATA' => $this->input->post('KD_DATA') );
            $hasil['status'] = $this->MRekeningPotongan->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah Rekening Potongan berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah Rekening Potongan gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah Rekening Potongan berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah Rekening Potongan gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);  
    }

     public function detail($KD_DATA=null)
    { 
        $where = array('KD_DATA' => $KD_DATA );
        $data['data']=$this->MRekeningPotongan->getBy($where);   
        $data['KD_DATA']=$KD_DATA;
        $data['NO_REK']=$data['data']['NO_REK']; 
        $data['NM_PEMILIK']=$data['data']['NM_PEMILIK']; 
        if ($KD_DATA==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }
        
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();  
        $data['cabang'] = $this->MPemeliharaanCabang->getAll();  
        $data['map'] = $this->MPemeliharaanMap->getAll();   
        $data['url_inquery']="parameterrekeningbank/Rekening_potongan/inquery"; 
        $this->load->view('pemeliharaan_rekening_bank/rek_potongan/form',$data);
    }

     public function hapus()
    {
        $hasil = array(); 
        $KD_DATA = $this->input->post('KD_DATA');
        $where = array('KD_DATA' => $KD_DATA );
        $hasil['status']=$this->MRekeningPotongan->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus rekening potongan berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus rekening potongan berhasil";  
        }
        echo json_encode($hasil);
    }

}