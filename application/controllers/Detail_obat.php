<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_obat extends CI_Controller {

    public function index($id=null)
    {   
        if ($id==null)
        {
           $this->invalid_page();
        }
        else{
            $data = array();
            $data['id_obat'] = $id; 

            $data['title'] = "Detail Obat";
            $data['url'] = "Detail_obat/"; 
            $data['SubFitur'] =null; 

            $data['breadcrumb'] = array(
                array(
                    'fitur' => "Home", 
                    'link' => base_url(), 
                    'status' => "", 
                ),
                array(
                 'fitur' => "Obat", 
                 'link' => base_url()."Obat", 
                 'status' => "", 
                ), 
                array(
                 'fitur' => "Deail Obat", 
                 'link' => base_url()."Detail_obat/index/".$id, 
                 'status' => "active", 
                ), 
            );

            $where = array('id_obat' => $id );
            $data['data']=$this->M_obat->getBy($where);   
            $data['id_obat']=$id;  
            if ($id==null)
            { 
                $data['SubFitur'] =null; 
            }
            else
            { 
                $data['SubFitur'] =$data['data']['nama']; 
            } 

            $this->load->view('include2/sidebar',$data); 
            $this->load->view('detail_obat/index',$data);
            $this->load->view('include2/footer');
        } 
    }  

    public function invalid_page()
    {
         $data['title'] = "Invalid Page";
            $data['url'] = ""; 
            $data['SubFitur'] =null; 

            $data['breadcrumb'] = array(
                array(
                    'fitur' => "Invalid Page", 
                    'link' => "", 
                    'status' => "", 
                ), 
            );

            $this->load->view('include2/sidebar',$data); 
            $this->load->view('invalid_page',$data);
            $this->load->view('include2/footer');
    }

    public function form_tambah($id_obat)
    {  

        $data['suplier'] = $this->Suplier_model->getAll();  

        $obat  =  $this->db->get_where('obat', array('id_obat' => $id_obat ))->row();

        $data['jenis_aksi']="add";  
        $data['data']['nama_obat']=$obat->nama;  
        $data['data']['kandungan_obat']=$obat->kandungan;  
        $data['data']['deskripsi_obat']=$obat->deskripsi;  
        $data['data']['id_obat']=$id_obat;  
        $data['data']['id_user']= null;
        $data['data']['stok']=null; 
        $data['data']['tgl_exp']=null;
        $data['data']['no_batch']=null;  
        $data['data']['no_batch_old']=null; 

         $data['url_inquery']="Detail_obat/inquery"; 
        $this->load->view('detail_obat/form',$data);
    }

    public function get_form()
    {       
        $nama_obat =  $this->db->get_where('obat', array('id_obat' => $id_obat ))->row()->nama;

        $data['suplier'] = $this->Suplier_model->getAll();  

        $data['jenis_aksi']="add";
        $data['data']['id_user']= null;
        $data['data']['stok']=null;
        $data['data']['id_obat']=$id_obat;
        $data['data']['tgl_exp']=null;
        $data['data']['no_batch']=null;  
        $data['data']['no_batch_old']=null; 
        $data['data']['nama_obat']=$nama_obat;  
        $data['url_inquery']="Detail_obat/inquery"; 
        $this->load->view('detail_obat/form',$data);
    }

    public function getDetail($id)
    {
        $data_detail = array();
        $data = array();
        $data['title']= "Detail Obat";
        $kategori  =  $this->db->query("select * from kategori where kd_kategori in (select kategori from obat where id_obat=$id)")->row_array();
        $obat  =  $this->db->query("select * from obat where id_obat=$id");

        $where = array(
            'id_obat' => $id, 
            'deleted' => 0, 
        ); 
        $data['detail'] = $this->M_detail_obat->get($where);  

        if ($data['detail']->num_rows()>0)
        { 
            foreach ($data['detail']->result_array() as $key)
            { 
                $admin_input  =  $this->User_model->getByRow_array(array('id' => $key['id_user'] ))['username'];

                $data_detail[] = array(  
                    'stok' => $key['stok'], 
                    'tgl_exp' => $key['tgl_exp'],  
                    'no_batch' => $key['no_batch'],  
                    'id_user' => $admin_input,  
                    'time' => $key['time'],  
                    'kategori' => $kategori['nm_kategori'],  
                ); 
            }
        }  

        $data['nama_obat'] = $obat->row()->nama; 
        $data['detail']=$data_detail;
        $data['id_obat']=$id;
        $data['nama_obat']=$data['nama_obat']; 
        return $data;
    }

      public function detail($no_batch_old)
    {
        $data_detail = array();
        $data = array();
        $data['title']= "Detail Obat";
        
        //get data detail oobat 
        $data['detail'] = $this->M_detail_obat->get(array("no_batch"=>$no_batch_old))->row_array();   

        //get data obat 
        $obat  =  $this->db->get_where('obat', array('id_obat' => $data['detail']['id_obat'] ))->row_array();

        $data['data']['kandungan_obat']=$obat['kandungan'];  
        $data['data']['deskripsi_obat']=$obat['deskripsi'];         
        $data['data']['nama_obat'] = $obat['nama']; 
        $data['data']['detail']=$data_detail;
        $data['data']['id_obat']=$obat['id_obat']; 
        $data['data']['no_batch_old']=$no_batch_old; 
        $data['data']['no_batch']=$no_batch_old; 
        $data['data']['stok']=$data['detail']['stok']; 
        $data['data']['tgl_exp']=$data['detail']['tgl_exp']; 

        $data['jenis_aksi']="update";
        $data['url_inquery']="Detail_obat/inquery"; 


        $this->load->view('detail_obat/form',$data);
        
    }

     public function reload_data($id=null)
    { 
       $data = $this->getDetail($id);
       $this->load->view('detail_obat/data',$data);
    }   

    public function save()
    {    
        $jenis_aksi = $this->input->post('jenis_aksi');    
        $no_batch =$this->input->post('no_batch');
        $data = array(
            'id_user'=>$this->input->post('id_user'),
            'stok'=>$this->input->post('stok'), 
            'tgl_exp'=>$this->input->post('tgl_exp'),
            'no_batch'=>$no_batch,  
            'id_obat'=>$this->input->post('id_obat'),  
         ); 

        $hasil = array();
        //cek apakah ada no_bach yang sama 
        $where = array('no_batch' => $no_batch );
        $cek = $this->M_detail_obat->cek_detail_obat($where);
        if ($cek->num_rows()>0)
        {
            $hasil['pesan'] ="<div class='alert alert-danger' role='alert'>
                              No. Batch ".$no_batch." sudah ada, silahkan masukan No. Batch lain atau edit No. Batch yang sudah ada
                            </div>"; 
        }  
        else
        {
            if($jenis_aksi=="add"){
                $hasil['status'] = $this->M_detail_obat->add($data);   
            }   
            else{
                $where = array('no_batch' => $this->input->post('no_batch_old') );
                $hasil['status'] = $this->M_detail_obat->update($where, $data);
            } 

            if($hasil['status']==true && $jenis_aksi=="add"){
                $hasil['pesan'] ="<div class='alert alert-success' role='alert'>
                                  Proses Tambah detail obat berhasil
                                </div>";
            }
            else if ($hasil['status']==false && $jenis_aksi=="add")
            {
                $hasil['pesan'] ="<div class='alert alert-danger' role='alert'>
                                  Proses Tambah detail obat gagal
                                </div>"; 
            }
            else if ($hasil['status']==true && $jenis_aksi=="update")
            {
                $hasil['pesan'] ="<div class='alert alert-success' role='alert'>
                                  Proses update detail obat berhasil
                                </div>"; 
            }
            else
            {
                $hasil['pesan'] ="<div class='alert alert-danger' role='alert'>
                                  Proses update detail obat gagal, silahkan coba kembali
                                </div>";  
            }  
        } 
 
        $hasil['jenis_aksi']=$jenis_aksi;  

        $this->session->set_flashdata('pesan', $hasil['pesan']);  
        redirect('Detail_obat/index/'.$this->input->post('id_obat')); 
    } 

     public function hapus($no_batch)
    { 
        $where = array('no_batch' => $no_batch );
        $data = array('deleted' => 1 );
        $hasil['status']=$this->M_detail_obat->update($where,$data);  
        $pesan="";  
        
        if ($hasil['status']==true)
        {
            $pesan ="<div class='alert alert-success' role='alert'>
                              Proses hapus obat berhasil
                            </div>"; 
        }
        else
        {
            $pesan ="<div class='alert alert-danger' role='alert'>
                              Proses hapus obat gagal, silahkan coba kembali
                            </div>";
        }
        echo $pesan;
    }

}