<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PemeliharaanSubUnit extends CI_Controller {

    public function index()
    {
        //get data kasda 
        $data['data'] = $this->MPemeliharaanSubUnit->getAll(); 
        $this->load->view('include/header');
        $this->load->view('pemeliharaan_sub_unit/index', $data);
        $this->load->view('include/footer'); 
    }

    public function reload_data()
    {   
        $data['data'] = $this->MPemeliharaanSubUnit->getAll();  
        $this->load->view('pemeliharaan_sub_unit/data', $data); 
    }

    public function detail($KD_DATA_SUB_UNIT=null)
    {
        $where = array('KD_DATA_SUB_UNIT' => $KD_DATA_SUB_UNIT );
        $data['data']=$this->MPemeliharaanSubUnit->getBy($where);   
        $data['KD_DATA_SUB_UNIT']=$KD_DATA_SUB_UNIT;  
        if ($KD_DATA_SUB_UNIT==null)
        { 
            //get last KD_DATA_SUB_UNIT + 1
            $data['data']['KD_SUB_UNIT'] =""; 
            $data['data']['NM_SUB_UNIT'] = ""; 
            $data['data']['KD_UNIT'] = ""; 
            $data['data']['KD_BIDANG'] = "";  
            $data['data']['KD_DATA_SUB_UNIT'] = "";  
            $data['jenis_aksi']="add";
            $data['urusan'] = $this->MPemeliharaanUrusan->getAll();
            $data['bidang'] = $this->MPemeliharaanBidang->getAll(); 
            $data['unit'] = $this->MPemeliharaanUnit->getAll();
        }
        else
        {
            $data['jenis_aksi']="edit";  

            //GET DATA URUSAN 
            $this->db->where(array(
                'KD_URUSAN' => $data['data']['KD_URUSAN'], 
            ));
            $data_urusan= $this->db->get("ref_urusan")->row_array();  
 
            //GET DATA BIDAGN 
           $this->db->where(array(
                'KD_BIDANG' => $data['data']['KD_BIDANG'],
                'KD_URUSAN' => $data['data']['KD_URUSAN'],
            ));
            $data_bidang= $this->db->get("ref_bidang")->row_array();  

              //GET DATA BIDAGN 
           $this->db->where(array(
                'KD_BIDANG' => $data['data']['KD_BIDANG'],
                'KD_URUSAN' => $data['data']['KD_URUSAN'],
                'KD_KASDA' => $data['data']['KD_KASDA'],
            ));
            $data_unit= $this->db->get("ref_unit")->row_array();   

            //GET DATA KASDA 
            $this->db->where(array('KD_KASDA' => $data['data']['KD_KASDA'] ));
            $data_kasda= $this->db->get("ref_kasda")->row_array();     

            $data['data']['data_urusan']= $data_urusan;   
            $data['data']['data_bidang']= $data_bidang;  
            $data['data']['data_unit']= $data_unit;   
            // var_dump($data['data']);

            $data['urusan'] = $this->MPemeliharaanUrusan->getAll();
            $data['unit'] = $this->MPemeliharaanUnit->getAll();
        } 
        
        $data['kasda'] = $this->MPemeliharaanKasda->getAll();    
        $data['bidang'] = $this->MPemeliharaanBidang->getAll(); 

        $this->load->view('pemeliharaan_sub_unit/form', $data); 
    }

     public function hapus($KD_DATA_SUB_UNIT)
    {
        $hasil = array(); 
        $where = array('KD_DATA_SUB_UNIT' => $KD_DATA_SUB_UNIT );
        $hasil['status']=$this->MPemeliharaanSubUnit->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data Unit berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data Unit berhasil";  
        }
        echo json_encode($hasil);
    }

	public function  cari_kesda($kode)
	{ 
        $where = array('KD_UNIT' => $kode );
        $data=$this->MPemeliharaanSubUnit->getBy($where);   
        echo json_encode($data); 
    } 

    
    public function  save()
	{   
        $jenis_aksi = $this->input->post('jenis_aksi');  

        $data = array( 
            'KD_KASDA'=>$this->input->post('KD_KASDA'), 
            'KD_URUSAN'=>$this->input->post('KD_URUSAN'), 
            'KD_BIDANG'=>$this->input->post('KD_BIDANG'), 
            'KD_UNIT'=>$this->input->post('KD_UNIT'),  
            'KD_SUB_UNIT'=>$this->input->post('KD_SUB_UNIT'), 
            'NM_SUB_UNIT'=>$this->input->post('NM_SUB_UNIT'),  
            'USER_UPDATE' => $this->input->post('USER_UPDATE'), 
            'USER_INPUT' => $this->input->post('USER_INPUT'), 
        ); 

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MPemeliharaanSubUnit->save($data);  
        }

        //aksi edit
        else{
            $where = array('KD_DATA_SUB_UNIT' => $this->input->post('KD_DATA_SUB_UNIT') );
            $hasil['status'] = $this->MPemeliharaanSubUnit->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah data Unit berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah data Unit gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah data Unit berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah data Unit gagal";  
        }  

        $hasil['data'] = $data;  
        echo json_encode($hasil);
	}

    public function get_bidang()
    {  
        $KD_URUSAN= $this->input->post('KD_URUSAN'); 
        $where = array(
            'KD_URUSAN' => $KD_URUSAN, 
        );
        $data['jenis_aksi']=$this->input->post('jenis_aksi');
        $data['data']['KD_BIDANG']=$this->input->post('KD_BIDANG');
        $data['bidang'] = $this->MPemeliharaanBidang->getByResult($where);
        // var_dump($data['bidang']);
        $this->load->view('pemeliharaan_sub_unit/form_data_bidang',$data);
    } 

    public function get_unit()
    {
        $KD_BIDANG= $this->input->post('KD_BIDANG');  
        $KD_URUSAN= $this->input->post('KD_URUSAN');  
        $KD_KASDA= $this->input->post('KD_KASDA');  
       
        $data['jenis_aksi']=$this->input->post('jenis_aksi');
        $data['data']['KD_UNIT']=$this->input->post('KD_UNIT');
        $data['unit'] = $this->MPemeliharaanUnit->getByResult($KD_URUSAN, $KD_BIDANG, $KD_KASDA);
        $this->load->view('pemeliharaan_sub_unit/form_data_unit',$data);
        // var_dump($data['data']['KD_UNIT']);
    } 
	 
}
