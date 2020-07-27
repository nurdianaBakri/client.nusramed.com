<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industri extends CI_Controller {



   public function __construct()
    {
        parent ::__construct(); 
        $this->logged_in();  
        $this->load->model('M_industri_ajax');
    } 


    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }  

    public function  index()
    {  
        $data['title'] = "Data industri";
        $data['url'] = base_url()."master/industri/";
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ), 
            array(
                'fitur' => "Industri", 
                'link' => base_url()."industri", 
                'status' => "active", 
            ), 
        );

        $this->load->view('include2/sidebar', $data); 
        $this->load->view('industri/index',$data);
        $this->load->view('include2/footer');  
    }  

    public function ajax_list()
    {
        $list = $this->M_industri_ajax->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
 
            $row[] = $no;
            //add html for action
            $row[] = "<div class='dropdown'>
                    <button class='btn btn-xs btn-success dropdown-toggle' type='button' data-toggle='dropdown'>&#x2636;
                    <span class='caret'></span></button>
                    <ul class='dropdown-menu'>
                      <li>
                        <a  href='#' onclick='edit(".'"'.$items->kd_industri.'"'.")'>Edit </a>  
                        </li>
                      <li>
                        <a  href='#' onclick='hapus(".'"'.$items->kd_industri.'"'.")'>Hapus </a> 
                       </li> 
                    </ul>
                  </div> "; 

            $row[] = $items->kd_industri;
            $row[] = $items->nama;
            $row[] = $items->alamat;
            $row[] = $items->no_telp;
            $row[] = $items->email; 

            if ($items->deleted==0)
            {
                $row[] = "Aktif";  
            }
            else
            {

                $row[] = "Tidak Aktif"; 
            }

            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->M_industri_ajax->count_all(),
                "recordsFiltered" => $this->M_industri_ajax->count_filtered(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function exportToExel()
    {  

        $list = $this->db->get('industri')->result(); 
        // $list = $this->M_obat_ajax->get_datatables();
        $data = array();
            $row = array(  );
        
        $row[]['text'] = "No. ";
        $row[]['text'] = "KD-INDUSTRI";
        $row[]['text'] = "Nama "; 
        $row[]['text'] = "Alamat"; 
        $row[]['text'] = "email"; 
        $row[]['text'] = "Nomor Telp";  

        $data[] = $row;

        $no = 1;
        foreach ($list as $obat) {
            $no++;
            $row = array(  );

            $row[]['text']= $no;
            $row[]['text'] = $obat->kd_industri;
            $row[]['text'] =  $obat->nama; 
            $row[]['text'] = $obat->alamat;  
           
            if ($obat->email==null) { 
                 $row[]['text'] ="-"; 
            }
            else
            { 
                 $row[]['text'] =$obat->email;
            }

            if ($obat->no_telp==null || $obat->no_telp=="0") {
                $row[]['text'] = " - ";
            }
            else
            { 
                $row[]['text'] = $obat->no_telp;
            }
 

            $data[] = $row;
        } 
        // $data['data']= $this->M_obat->getAll(); 
        echo json_encode($data); 
    }



    public function get_form()
    {     
        $kd_industri = "ids-".date('dmy')."-".rand('0987654321',4); 
        $data['url'] = "/master/industri/";

        $data['jenis_aksi'] ="add";  
        $where = array('kd_industri' => "0" );   

        $data['data']= $this->Industri_model->getBy($where);  
        $data['url']="industri/"; 
        $data['title']="Tambah Industri";  
        $data['data']['kd_industri'] = $kd_industri; 
        $data['data']['nama'] = null; 
        $data['data']['alamat'] = null; 
        $data['data']['email'] = null; 
        $data['data']['no_telp'] = null;  
        $data['data']['no_rek'] = null;  
        $data['data']['kd_bank'] = null;  

        $this->load->view('industri/form',$data);
    }  

    public function reload_data()
    { 
        $data_balikan['data']= array();
        $data['data']= $this->Industri_model->getAll();  
        $this->load->view('industri/data', $data); 
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

    public function save()
    {     
        $hasil2=""; 
        $hasil = array();

        $jenis_aksi = $this->input->post('jenis_aksi', TRUS);  
        $kd_industri = $this->input->post('kd_industri', TRUS);  

        $data = array(   
            'email' => $this->input->post('email', TRUS),
            'alamat' => $this->input->post('alamat', TRUS),
            'no_telp' => $this->input->post('no_telp', TRUS),
            'nama' => $this->input->post('nama', TRUS), 
            'no_rek' => $this->input->post('no_rek', TRUS), 
            'kd_bank' => $this->input->post('kd_bank', TRUS), 
        ); 
  
        if($jenis_aksi=="add")
        {   
            $data['kd_industri']= $kd_industri;

            $hasil['status'] = $this->Industri_model->save($data);  
            if($hasil['status']==true)
            {
               $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses Tambah industri berhasil";
            }
            else
            {
                 $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses Tambah industri gagal"; 
            } 
        }   
        else{
            $where = array('kd_industri' => $kd_industri ); 
            $hasil['status'] = $this->Industri_model->update($where, $data); 
            if($hasil['status']==true)
            {
                 $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses ubah industri berhasil"; 
            }
            else
            {
                 $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses ubah industri gagal";  
            } 
        }   

         echo json_encode($hasil);   
    }

    public function edit()
    {  
        $kd_industri = $this->input->post('kd_industri', TRUS);
        // echo $kd_industri;
        $data['title'] = "industri";
        $data['url'] = "industri/"; 
        $where = array('kd_industri' => $kd_industri );   

        if ($kd_industri==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }  

        //cek apakah kd_industri ada di database 
        $cek= $this->Industri_model->detail($where); 
        if ($cek->num_rows()>0)
        {  
            $industri = $cek->row_array(); 
            $data['data']= $industri;    
            $this->load->view('industri/form',$data); 
        }
        else
        {
            $pesan="<div class='alert alert-danger' role='alert'>
                            industri ".$kd_industri." Tidak terdaftar, silahkan pilih industri lain
                            </div>";  

            $this->session->set_flashdata('pesan', $pesan); 
            $this->load->view('industri/form',$data); 

        }  
    }

     public function hapus()
    {
        $kd_industri = $this->input->post('kd_industri', TRUS);
        $pesan = "";  
        $icon_swal = "";  
        $title_swal = "";  
        $where = array('kd_industri' => $kd_industri ); 
        $hasil['status']=$this->Industri_model->hapus($where);   

        if ($hasil['status']==true)
        {
            $icon_swal = "success";
            $title_swal = "Berhasil !"; 
            $pesan = "Proses hapus industri berhasil";
        }
        else
        {
            $icon_swal = "error";
            $title_swal = "Gagal !";
            $pesan = "Proses hapus industri gagal, silahkan coba kembali";  
        } 

        $data = array(
            'pesan' => $pesan,  
            'icon_swal' => $icon_swal,  
            'title_swal' => $title_swal,   
            'status' => $hasil['status'], 
        );   
        echo json_encode($data);  
 
    }
  
}