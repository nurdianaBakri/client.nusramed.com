<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlet extends CI_Controller {

   public function __construct()
    {
        parent ::__construct(); 
        $this->logged_in();  
        $this->load->model('M_outlet_ajax');

    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }  

    public function  index()
    {    
        $data['title'] = "Outlet";
        $data['url'] = base_url()."master/Outlet/";
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ), 
              array(
                'fitur' => "Outlet", 
                'link' => base_url()."master/Outlet", 
                'status' => "active", 
            ), 
        ); 
        $this->load->view('include2/sidebar', $data); 
        $this->load->view('outlet/index',$data);
        $this->load->view('include2/footer');  
    } 



    public function ajax_list()
    {
        $list = $this->M_outlet_ajax->get_datatables();
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
                        <a  href='#' onclick='edit(".'"'.$items->id_outlet.'"'.")'>Edit </a>  
                        </li>
                      <li>
                        <a  href='#' onclick='hapus(".'"'.$items->id_outlet.'"'.")'>Hapus </a> 
                       </li> 
                    </ul>
                  </div> "; 

            $row[] = $items->id_outlet;
            $row[] = $items->nama;
            $row[] = $items->alamat;
            $row[] = $items->no_telp;
            $row[] = $items->npwp;
            $row[] = $items->nm_pemilik;
            $row[] = $items->no_telp_pemilik; 

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
                "recordsTotal" => $this->M_outlet_ajax->count_all(),
                "recordsFiltered" => $this->M_outlet_ajax->count_filtered(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function exportToExel()
    {   
        $list = $this->db->get("export_outlet")->result(); 
        // $list = $this->M_obat_ajax->get_datatables();
        $data = array();
        $row = array(  );
        
        $row[]['text'] = "No. ";
        $row[]['text'] = "KD-OUTLET";
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
            $row[]['text'] = $obat->id_outlet;
            $row[]['text'] = $obat->nama; 
            $row[]['text'] = $obat->alamat;  
            $row[]['text'] = $obat->no_telp;
            $row[]['text'] = $obat->npwp;
            $row[]['text'] = $obat->nm_pemilik;
            $row[]['text'] = $obat->no_telp_pemilik;
            $row[]['text'] = $obat->nama_pj;
            $row[]['text'] = $obat->no_str_pj;
            $row[]['text'] = $obat->no_sipa_pj;
            $row[]['text'] = $obat->alamat_pj;
            $row[]['text'] = $obat->no_telp_pj;  

            $data[] = $row;
        } 
        // $data['data']= $this->M_obat->getAll(); 
        echo json_encode($data); 
    }



    public function get_form()
    {     
        $id_outlet = "OUT-".date('dmy')."-".rand('0987654321',4); 
        $data['jenis_aksi'] ="add"; 
        $where = array('id_outlet' => "0" );  
        $where2 = array('id_penanggung_jawab' => "0" );  
        $where3 = array('id_ttk' => "0" );  

        $data['outlet']= $this->M_outlet->detail($where)->row_array();  
        $data['penanggung_jawab']= $this->M_outlet_PJ->detail($where2)->row_array();  
        $data['ttk']= $this->M_outlet_ttk->detail($where3)->row_array(); 
        $data['kab_kota']= $this->db->get('kab_kota')->result_array(); 
        $data['id_kec']="";
        $data['id_kab_kota']="";

       /*  $data['id_kec']= $outlet['id_kec'];  
        $data['kab_kota']= $this->db->get('kab_kota')->result_array(); */

        $data['url_inquery']="master/outlet/inquery"; 
        $data['url']="outlet/"; 
        $data['title']="Tambah"; 
        $data['id_outlet']=$id_outlet; 
        $this->load->view('outlet/form',$data);
    } 

    public function get_kecamatan($id_kab_kota)
    {
        $this->db->where('id_kab_kota',$id_kab_kota);
        $data['kecamatan'] = $this->db->get('kecamatan')->result_array();
        $this->load->view('outlet/kecamatan',$data);
    }

      
    public function reload_data()
    {  
        $data['data']= $this->M_outlet->getAll();  
        $this->load->view('outlet/data', $data); 
    }  


   /* public function exportToExel()
    {
        $data['title'] = "Export Outlet";
        $data['url'] = "Outlet/";
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ), 
              array(
                'fitur' => "Outlet", 
                'link' => base_url()."Outlet", 
                'status' => "active", 
            ), 
        ); 


        $data['data']= $this->db->query("SELECT outlet1.*, a.nama_pj, a.no_str_pj, a.no_sipa_pj, a.alamat_pj, a.no_telp_pj FROM outlet1, outlet_penanggung_jawab a WHERE a.id_penanggung_jawab = outlet1.id_penanggung_jawab")->result_array();   
        $this->load->view('include2/sidebar', $data);  
        $this->load->view('outlet/data_export', $data); 
        $this->load->view('include2/footer');   
    }*/

    public function save()
    {    
        $hasil1="";
        $hasil2=""; 
        $id_ttk=""; 
        $hasil = array();
        $data3 = array();

        $jenis_aksi = $this->input->post('jenis_aksi', TRUE);  
        $id_outlet = $this->input->post('id_outlet', TRUE); 

        $no_ktp_pj = $this->input->post('no_ktp_pj', TRUE);
        $email_pj = $this->input->post('email_pj', TRUE);
        $nm_pj = $this->input->post('nm_pj', TRUE);
        $alamat_pj = $this->input->post('alamat_pj', TRUE);
        $no_str_pj = $this->input->post('no_str_pj', TRUE);
        $no_sipa_pj = $this->input->post('no_sipa_pj', TRUE);
        $masa_str_pj = $this->input->post('masa_str_pj', TRUE);
        $masa_sipa_pj = $this->input->post('masa_sipa_pj', TRUE);
        $no_sia = $this->input->post('no_sia', TRUE);
        $no_ijin_rs = $this->input->post('no_ijin_rs', TRUE);
        $no_ijin_klinik = $this->input->post('no_ijin_klinik', TRUE);
        $no_telp_pj = $this->input->post('no_telp_pj', TRUE);

        $nik_ttk = $this->input->post('nik_ttk', TRUE);
        $nama_ttk = $this->input->post('nama_ttk', TRUE);
        $alamat_ttk = $this->input->post('alamat_ttk', TRUE);
        $no_telp_ttk = $this->input->post('no_telp_ttk', TRUE);
        $no_sikttk = $this->input->post('no_sikttk', TRUE);
        $masa_sikttk = $this->input->post('masa_sikttk', TRUE);
        $no_strttk = $this->input->post('no_strttk', TRUE);
        $masa_strttk = $this->input->post('masa_strttk', TRUE);  
         

        //masukkan data penanggung jawab jawab 
        $id_penanggung_jawab="";   
        $data1 = array(  
            'no_ktp_pj' => $no_ktp_pj,
            'email_pj' => $email_pj,
            'nama_pj' => $nm_pj,
            'alamat_pj' => $alamat_pj,
            'no_str_pj' => $no_str_pj,
            'no_sipa_pj' => $no_sipa_pj,
            'masa_sipa' => $masa_sipa_pj,
            'masa_str' => $masa_str_pj, 
            'no_telp_pj' => $no_telp_pj,
        );   

         // masukkan data outlet
        $data2 = array( 
            'nama' => $this->input->post('nama', TRUE),
            'id_outlet' => $id_outlet,
            'no_telp' => $this->input->post('no_telp', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'alamat_pemilik' => $this->input->post('alamat_pemilik', TRUE),
            'npwp' => $this->input->post('npwp', TRUE),
            'nm_pemilik' => $this->input->post('nm_pemilik', TRUE),
            'no_telp_pemilik' => $this->input->post('no_telp_pemilik', TRUE),
            'no_ktp_pemilik' => $this->input->post('no_ktp_pemilik', TRUE), 
            'no_izin' => $this->input->post('no_izin', TRUE), 
            'masa_izin' => $this->input->post('masa_izin', TRUE), 
            'id_kab_kota' => $this->input->post('id_kab_kota', TRUE), 
            'id_kec' => $this->input->post('id_kec', TRUE), 
            'deleted' => 0, 
        );  

         if($jenis_aksi=="add")
         {
            $id_penanggung_jawab= $this->M_outlet_PJ->get_last_id();

            $data1['id_penanggung_jawab']=$id_penanggung_jawab;  
            $data2['id_penanggung_jawab']=$id_penanggung_jawab;  

            //tetap masukkan id ttk
            $id_ttk= $this->M_outlet_ttk->get_last_id();  
            $data3 = array(  
                'id_ttk' => $id_ttk,
                'nik' => $nik_ttk,
                'nama' => $nama_ttk,
                'alamat' => $alamat_ttk,
                'no_sikttk' => $no_sikttk,
                'masa_sikttk' => $masa_sikttk,
                'no_strttk' => $no_strttk,
                'masa_strttk' => $masa_strttk, 
                'no_telp' => $no_telp_ttk, 
            );    

            $hasil['status'] = $this->M_outlet_ttk->save($data3);    
            if($hasil['status']==true)
            {
                $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses tambah TTK berhasil";            
            }
            else
            {
                $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses tambah TTK gagal";    
            }  
            
            $hasil['status'] = $this->M_outlet_PJ->save($data1);  
            if($hasil['status']==true)
            {
                
                $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses tambah penaggung jawab  outlet berhasil";  


                $data2['id_ttk'] = $id_ttk;  
                $hasil['status'] = $this->M_outlet->save($data2);   

                // var_dump($data2); 
                if($hasil['status']==true && $jenis_aksi=="add"){
                     $hasil['icon_swal'] = "success";
                    $hasil['title_swal'] = "Berhasil !"; 
                    $hasil['pesan'] ="Proses Tambah outlet berhasil";   
                }
                else
                {
                      $hasil['icon_swal'] = "error";
                    $hasil['title_swal'] = "Gagal !"; 
                    $hasil['pesan'] ="Proses Tambah outlet gagal";
                }
            }
            else
            {
                  $hasil['icon_swal'] = "error";
                    $hasil['title_swal'] = "Gagal !"; 
                    $hasil['pesan'] ="Proses Tambah penanggung jawab outlet gagal";
            }                    
        }   

        //proses update
        else{

            if ($_POST['nama_ttk']!="")
            { 
                $where = array('id_ttk' => $this->input->post('id_ttk', TRUE));
                $data3 = array(  
                    'id_ttk' => $this->input->post('id_ttk', TRUE),
                    'nik' => $nik_ttk,
                    'nama' => $nama_ttk,
                    'alamat' => $alamat_ttk,
                    'no_sikttk' => $no_sikttk,
                    'masa_sikttk' => $masa_sikttk,
                    'no_strttk' => $no_strttk,
                    'masa_strttk' => $masa_strttk, 
                    'no_telp' => $no_telp_ttk, 
                );    

                $hasil['status'] = $this->M_outlet_ttk->update($where, $data3);    
                if($hasil['status']==true)
                {
                     $hasil['icon_swal'] = "success";
                    $hasil['title_swal'] = "Berhasil !"; 
                    $hasil['pesan'] ="Proses ubah TTK Berhasil";                
                }
                else
                {
                     $hasil['icon_swal'] = "error";
                    $hasil['title_swal'] = "Gagal !"; 
                    $hasil['pesan'] ="Proses ubah TTK gagal";    
                }  
            }

            // var_dump($data3);
            $where = array('id_penanggung_jawab' => $this->input->post('id_penanggung_jawab', TRUE) );
            $hasil['status'] = $this->M_outlet_PJ->update($where, $data1);  
            if($hasil['status']==true)
            { 
                 $hasil['icon_swal'] = "success";
                $hasil['title_swal'] = "Berhasil !"; 
                $hasil['pesan'] ="Proses ubah penaggung jawab outlet Berhasil";  


                $hasil['status'] = $this->M_outlet->update($id_outlet, $data2);    
                if($hasil['status']==true && $jenis_aksi=="edit"){ 
                    $hasil['icon_swal'] = "success";
                    $hasil['title_swal'] = "Berhasil !"; 
                    $hasil['pesan'] ="Proses ubah outlet Berhasil";   
                }
                else
                {
                    $hasil['icon_swal'] = "error";
                    $hasil['title_swal'] = "Gagal !"; 
                    $hasil['pesan'] ="Proses ubah outlet gagal"; 
                }
            }
            else
            {
                 $hasil['icon_swal'] = "error";
                $hasil['title_swal'] = "Gagal !"; 
                $hasil['pesan'] ="Proses ubah penanggung jawab outlet gagal";  
            }                   
        }   
         echo json_encode($hasil);  
    }

    public function edit()
    {  
        $id_outlet = $this->input->post('id_outlet', TRUE); 
        $data['title'] = "Outlet";
        $data['url'] = "outlet/"; 
        $where = array('id_outlet' => $id_outlet );  
        $data['id_outlet']=$id_outlet;  

        if ($id_outlet==null)
        {
            $data['jenis_aksi']="add";
        }
        else
        {
            $data['jenis_aksi']="edit";
        }  

        //cek apakah id_outlet ada di database 
        $cek= $this->M_outlet->detail($where); 
        if ($cek->num_rows()>0)
        {  
            $outlet = $cek->row_array();

            $data['penanggung_jawab']= $this->M_outlet_PJ->detail( array('id_penanggung_jawab' => $outlet['id_penanggung_jawab'] ))->row_array();  

            $data['ttk']= $this->M_outlet_ttk->detail( array('id_ttk' => $outlet['id_ttk'] ))->row_array();  

            $data['outlet']= $outlet; 
            $data['id_kab_kota']= $outlet['id_kab_kota']; 
            $data['id_kec']= $outlet['id_kec']; 

            $data['kab_kota']= $this->db->get('kab_kota')->result_array(); 
            $this->load->view('outlet/form',$data); 
        }
        else
        {
            $pesan="<div class='alert alert-danger' role='alert'>
                            Outlet ".$id_outlet." Tidak terdaftar, silahkan pilih outlet lain
                                </div>";   
            // $this->session->set_flashdata('pesan', $pesan);  
            echo $pesan; 
        }  
    }

    public function hapus()
    {
        $hasil = array(); 
          $pesan="";  
        $icon_swal = "";
        $title_swal = "";  
        $where = array('id_outlet' => $this->input->post('id_outlet', TRUE) );  
        $data = array('deleted' => 1 );
 
        $hasil['status']=$this->M_outlet->hapus($where, $data);   
       
        if ($hasil['status']==true)
        {
            $icon_swal = "success";
            $title_swal = "Berhasil !"; 
            $pesan = "Proses hapus outlet berhasil";
        }
        else
        {
            $icon_swal = "error";
            $title_swal = "Gagal !";
            $pesan = "Proses hapus outlet gagal, silahkan coba kembali";  
        }  

        $data = array(
            'pesan' => $pesan,  
            'icon_swal' => $icon_swal,  
            'title_swal' => $title_swal,  
            'id_obat' => $this->input->post('id_obat', TRUE),  
            'status' => $hasil['status'], 
        );   
        echo json_encode($data);  
         // echo json_encode(array("status" => TRUE));
    } 
 
 
    public function print($id_outlet=null)
    {
        if ($id_outlet==null)
        {
            $where = array('id_outlet' => $id_outlet );  
           
            //cek apakah id_outlet ada di database 
            $cek= $this->M_outlet->detail($where); 
            
            $outlet = $cek->row_array(); 
            $data['penanggung_jawab']= $this->M_outlet_PJ->detail( array('id_penanggung_jawab' => $outlet['id_penanggung_jawab'] ))->row_array();  
            $data['outlet']= $outlet;    

             $data['ttk']= $this->M_outlet_ttk->detail( array('id_ttk' => $outlet['id_ttk'] ))->row_array();  

            $this->load->view('outlet/print2',$data); 
        }
        else
        {
            $where = array('id_outlet' => $id_outlet );  
            $data['id_outlet']=$id_outlet;  
           
            //cek apakah id_outlet ada di database 
            $cek= $this->M_outlet->detail($where); 
            if ($cek->num_rows()>0)
            {  
                $outlet = $cek->row_array(); 
                $data['penanggung_jawab']= $this->M_outlet_PJ->detail( array('id_penanggung_jawab' => $outlet['id_penanggung_jawab'] ))->row_array();  
                $data['outlet']= $outlet;    

                 $data['ttk']= $this->M_outlet_ttk->detail( array('id_ttk' => $outlet['id_ttk'] ))->row_array();  

                $this->load->view('outlet/print2',$data); 
            }
            else
            {
                $pesan="<div class='alert alert-danger' role='alert'>
                                Outlet ".$id_outlet." Tidak terdaftar, silahkan pilih outlet lain
                                    </div>";  

                $this->session->set_flashdata('pesan', $pesan);
                redirect('Outlet');
            }  
        }
            
    }

 
}