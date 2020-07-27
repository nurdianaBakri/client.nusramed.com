<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suplier extends CI_Controller {

   public function __construct()
    {
        parent ::__construct();  
        $this->logged_in();   
        $this->load->model('M_suplier_ajax');

    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }  



    public function  index()
    {   
        $data['title'] = "Suplier Obat/Barang"; 
        $data['url'] = base_url()."master/Suplier/";

        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ), 
              array(
                'fitur' => "Suplier", 
                'link' => base_url()."master/Suplier", 
                'status' => "active", 
            ), 
        );
 
        $this->load->view('include2/sidebar', $data); 
        $this->load->view('suplier/index',$data);
        $this->load->view('include2/footer');  
    } 


    public function ajax_list()
    {
        $list = $this->M_suplier_ajax->get_datatables();
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
                        <a  href='#' onclick='edit(".'"'.$items->kd_suplier.'"'.")'>Edit </a>  
                        </li>
                      <li>
                        <a  href='#' onclick='hapus(".'"'.$items->kd_suplier.'"'.")'>Hapus </a> 
                       </li> 
                    </ul>
                  </div> "; 

            $row[] = $items->kd_suplier;
            $row[] = $items->nama;
            $row[] = $items->alamat;
            $row[] = $items->provinsi;
            $row[] = $items->no_hp;
            $row[] = $items->no_rek; 

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
                "recordsTotal" => $this->M_suplier_ajax->count_all(),
                "recordsFiltered" => $this->M_suplier_ajax->count_filtered(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function exportToExel()
    {   
        $list = $this->db->get('suplier')->result(); 
        // $list = $this->M_obat_ajax->get_datatables();
        $data = array();
            $row = array(  );
        
        $row[]['text'] = "No. ";
        $row[]['text'] = "KD-SUPLIER";
        $row[]['text'] = "Nama "; 
        $row[]['text'] = "Alamat"; 
        $row[]['text'] = "No. HP"; 
        $row[]['text'] = "email"; 
        $row[]['text'] = "Bank";  
        $row[]['text'] = "No. Rekening";  
        $row[]['text'] = "No. Izin";  
        $row[]['text'] = "Masa Belaku Izin";  
        $row[]['text'] = "No. SIKA/SIPA";  
        $row[]['text'] = "Masa Belaku SIKA/SIPA";  
        $row[]['text'] = "Apoteker Penanggung Jawab";  

        $data[] = $row;

        $no = 1;
        foreach ($list as $obat) {
            $no++;
            $row = array(  );

            $row[]['text']= $no;
            $row[]['text'] = $obat->kd_suplier;
            $row[]['text'] =  $obat->nama; 
            $row[]['text'] = $obat->alamat;  
            
              if ($obat->no_hp==null || $obat->no_hp=="0") {
                $row[]['text'] = " - ";
            }
            else
            { 
                $row[]['text'] = $obat->no_hp;
            }

             if ($obat->email==null || $obat->email=="0") {
                $row[]['text'] = " - ";
            }
            else
            { 
                $row[]['text'] = $obat->email;
            } 

            $row[]['text'] = $obat->bank;  
            $row[]['text'] = $obat->no_rek;  
            $row[]['text'] = $obat->no_izin;  
            $row[]['text'] = $obat->masa_izin;  
            $row[]['text'] = $obat->no_sika_sipa;  
            $row[]['text'] = $obat->masa_sika_sipa;  
            $row[]['text'] = $obat->nama_apoteker_pj;   

            $data[] = $row;
        } 
        // $data['data']= $this->M_obat->getAll(); 
        echo json_encode($data); 
    }

   
    public function save_download()
    { 
        //load mPDF library
        $this->load->library('m_pdf');
        //load mPDF library 

        //now pass the data//
         $this->data['title']="MY PDF TITLE 1.";
         $this->data['description']="";
         $this->data['description']=$this->official_copies;
         //now pass the data //  
        
        $html=$this->load->view('outlet/print_form_pendaftaran',$this->data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
     
        //this the the PDF filename that user will get to download
        $pdfFilePath ="mypdfName-".time()."-download.pdf";

        
        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        //generate the PDF!
        $pdf->WriteHTML($html,2);
        //offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "D"); 
    }

    public function get_form()
    {     
        $kd_suplier = "spr-".date('dmy')."-".rand('0987654321',4); 
        $data['jenis_aksi'] ="add"; 
        $where = array('kd_suplier' => "0" );  

        $data['url']="master/suplier/"; 
        $data['title']="Tambah Suplier";  
        $data['data']['kd_suplier'] = $kd_suplier; 
        $data['data']['nama'] = null; 
        $data['data']['alamat'] = null; 
        $data['data']['email'] = null; 
        $data['data']['no_hp'] = null;  
        $data['data']['no_rek'] = null;  
        $data['data']['bank'] = null;  
        $data['data']['no_izin'] = null;  
        $data['data']['masa_izin'] = null;  
        $data['data']['no_sika_sipa'] = null;  
        $data['data']['masa_sika_sipa'] = null;  
        $data['data']['nama_apoteker_pj'] = null;  

        $this->load->view('suplier/form',$data);
    }  

     

    public function save()
    {     
        $hasil2=""; 
        $hasil = array();

        $jenis_aksi = $this->input->post('jenis_aksi', TRUE);  
        $kd_suplier = $this->input->post('kd_suplier', TRUE);  

        $data = array(   
            'nama' => $this->input->post('nama', TRUE), 
            'alamat' => $this->input->post('alamat', TRUE),
            'email' => $this->input->post('email', TRUE),
            'no_hp' => $this->input->post('no_hp', TRUE),
            'bank' => $this->input->post('bank', TRUE),
            'no_rek' => $this->input->post('no_rek', TRUE),
            'no_izin' => $this->input->post('no_izin', TRUE),
            'masa_izin' => $this->input->post('masa_izin', TRUE),
            'no_sika_sipa' => $this->input->post('no_sika_sipa', TRUE),
            'masa_sika_sipa' => $this->input->post('masa_sika_sipa', TRUE),
            'nama_apoteker_pj' => $this->input->post('nama_apoteker_pj', TRUE), 
        );    
         
        if($jenis_aksi=="add")
        {   
            $data['kd_suplier']= $kd_suplier;

            $hasil['status'] = $this->Suplier_model->save($data);  

            if($hasil['status']==true)
            {
               $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses Tambah suplier berhasil";
            }
            else
            {
                 $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses Tambah suplier gagal"; 
            } 

           
        }   
        else{
            $where = array('kd_suplier' => $kd_suplier ); 
            $hasil['status'] = $this->Suplier_model->update($where, $data); 
             if($hasil['status']==true)
            {
               $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses update suplier berhasil";
            }
            else
            {
                 $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses update suplier gagal"; 
            } 
               
        }  
         echo json_encode($hasil);   
          
    }

    public function edit()
    {    
        $kd_suplier = $this->input->post('kd_suplier', TRUE); 
        $where = array('kd_suplier' => $kd_suplier );   

        if ($kd_suplier==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }  

        //cek apakah kd_suplier ada di database 
        $cek= $this->Suplier_model->getByRow_array($where);  
        $data['data']= $cek;    
        $this->load->view('suplier/form',$data);  
    }

     public function hapus()
    {
        $kd_suplier = $this->input->post('kd_suplier', TRUE);   
         $pesan = "";  
        $icon_swal = "";  
        $title_swal = "";  
        $where = array('kd_suplier' => $kd_suplier ); 
        $hasil['status']=$this->Suplier_model->delete($where);    
        
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

    public function pegawai($kd_suplier)
    {
        $data['title'] = "Pegawai ". $kd_suplier;
        $data['url'] = "Pegawai/";
        $this->load->view('include/header'); 
        $this->load->view('pegawai/index',$data);
        $this->load->view('include/footer'); 
    }

}