<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StokOpname extends CI_Controller
{
   public function __construct()
    {
        parent ::__construct();  
        $this->load->library('pdf');
        $this->logged_in();   
    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }  

    public function index()
    {
        // $this->cart->destroy();
        $data['title'] = "Stok Opname";
        $data['url'] = "data/StokOpname/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Stok Opname",
             'link' => base_url()."data/StokOpname",
             'status' => "active",
            ),
        );  
        
        $this->load->view('include2/sidebar', $data);
        $this->load->view('StokOpname/index', $data);
        $this->load->view('include2/footer');
    }  

    public function reload_data()
    {
       $where = array(
            // 'user_verified' => "", 
            'deleted' => 0, 
        ); 
        // $data['detail_obat'] = $this->M_detail_obat->cek_detail_obat($where);

       $data['detail_obat'] = $this->db->query("SELECT *, sum(sisa_stok) as sisa_stok_total from detail_obat where deleted=0 group by no_batch"); 

        $this->load->view('StokOpname/data', $data);
    }

    function cari_nama_obat(){
        $keywoard = $this->input->post('keyword_nama_obat');  

        // $this->db->like('nama', $keywoard);
        // $this->db->where('deleted',0);
        // $this->db->order_by('date', 'DESC');
        // $data['detail_obat']=$this->db->get("obat")->result();  

        $data['detail_obat'] = $this->db->query("SELECT *, sum(sisa_stok) as sisa_stok_total from detail_obat where detail_obat.id_obat in (SELECT id_obat from obat WHERE nama like '%$keywoard%' and deleted=0) group by no_batch")->result();  
        // echo "Ini hasil cari";
        $this->load->view('StokOpname/data_hasil_search', $data);   
    } 

    public function setOpname()
    { 
        $pesan="";
        $return=""; 
        $hasil_query = array(); 

        if (isset($_POST['id_obat']))
        {      
            $hasil_update="";
            $id_obat = $this->input->post('id_obat');
            $nama = $this->input->post('nama');
            $no_reg = $this->input->post('no_reg');
            $no_batch = $this->input->post('no_batch');    
            $no_batch_old = $this->input->post('no_batch_old');    
            $tgl_exp = $this->input->post('tgl_exp');  
            $tgl_exp_old = $this->input->post('tgl_exp_old');  
            $sisa_stok = $this->input->post('sisa_stok');  
            $stok_real = $this->input->post('stok_real');  
            
            // $size = sizeof($id_obat); 
            $data = array();
            foreach($id_obat as $key=>$value) {   
             
                $data = array(
                    'id_obat' => $value, 
                    'no_reg' => $no_reg[$key], 
                    'no_batch' => $no_batch[$key], 
                    'tgl_exp' => $tgl_exp[$key], 
                    'user_input' => $this->session->userdata('username'), 
                    'stok_real' => $stok_real[$key], 
                    'sisa_stok' => $sisa_stok[$key],  
                    'tanggal' => date('Y-m-d H:m:s'),  
                    'selisih' => (int)$stok_real[$key]-(int)$sisa_stok[$key], 
                ); 
                if ($_POST['stok_real'][$key]=="")
                { 
                    // var_dump($tgl_exp_old[$key]."!=".$tgl_exp[$key]." 00:00:00");
                    // var_dump($no_batch[$key]."!=".$no_batch_old[$key]);

                    if ($tgl_exp_old[$key]!=$tgl_exp[$key]|| $no_batch[$key]!=$no_batch_old[$key])
                    {
                        $data['stok_real'] = $sisa_stok[$key];
                        $data['tgl_exp'] = $tgl_exp[$key];
                        $data['selisih'] = (int)$data['stok_real']-(int)$sisa_stok[$key];
                        $hasil_insert =$this->db->insert('stok_opname', $data);
                    }
                    else
                    { 
                        $hasil_insert = true;
                    }
                }
                else
                {
                    $hasil_insert =$this->db->insert('stok_opname', $data); 
                }

                // $pesan.="<li>berhasil add history stok opname</li>"; 
                if ($hasil_insert==true)
                { 
                    $ada_perubahan=false;

                    //update data sisa stok di table detail obat 

                    //check apakah ada banyak obat yang sama  
                    $where = array(
                        'id_obat' => $value, 
                        'no_reg' => $no_reg[$key], 
                        'no_batch' => $no_batch[$key],  
                        'tgl_exp' => $tgl_exp_old[$key],  
                    ); 
                    $data2 = array(
                        'sisa_stok' => $stok_real[$key], 
                    );

                    $this->db->where($where);
                    $check = $this->db->get('detail_obat');
                    if ($check->num_rows()>1)
                    {   
                        $rawdate = htmlentities($tgl_exp[$key]);
                        $date = date('Y-m-d', strtotime($rawdate));

                        if ($_POST['stok_real'][$key]=="")
                        { 
                            $hasil_update= $this->db->query("UPDATE detail_obat set no_batch='".$no_batch[$key]."' , tgl_exp='".$date."' WHERE no_batch='".$no_batch_old[$key]."' and no_reg='".$no_reg[$key]."' and id_obat='".$id_obat[$key]."' "); 
                            $ada_perubahan=true; 
                        }
                        else
                        { 
                            //update sisa stok = 0  
                            $data6 = array(
                                'sisa_stok' => 0, 
                            );
                            $this->db->where($where);
                            $hasil_update = $this->db->update('detail_obat',$data6);
 
                            $hasil_update= $this->db->query("UPDATE detail_obat set no_batch='".$no_batch[$key]."' , tgl_exp='".$date."' , sisa_stok=".$stok_real[$key]." WHERE no_batch='".$no_batch_old[$key]."' and no_reg='".$no_reg[$key]."' and id_obat='".$id_obat[$key]."' limit 1"); 
                            $hasil_update=true;
                            $ada_perubahan=true;
/*
                            $data_old = array(
                                'no_batch' => $no_batch[$key], 
                                'no_reg' => $no_reg[$key], 
                                'id_obat' => $id_obat[$key], 
                            );*/

                            //masukkan data ke kertu stok
                            // $this->M_kartu_stok->save($data_old, $data_new);
                        }    
                    }
                    else{
                        // var_dump($no_batch_old[$key]);

                        $rawdate = htmlentities($tgl_exp[$key]);
                        $date = date('Y-m-d', strtotime($rawdate));

                        // $data5 = array();
                        if ($_POST['stok_real'][$key]=="")
                        {
                            if ($tgl_exp_old[$key]!=$tgl_exp[$key] || $no_batch[$key]!=$no_batch_old[$key])
                            { 
                                $hasil_update= $this->db->query("UPDATE detail_obat set no_batch='".$no_batch[$key]."' , tgl_exp='".$date."' WHERE no_batch='".$no_batch_old[$key]."' and no_reg='".$no_reg[$key]."' and id_obat='".$id_obat[$key]."' "); 
                                $ada_perubahan=true;
                            }
                            else
                            {
                                $hasil_update=true;
                                $ada_perubahan=false;
                            }  
                        }
                        else
                        { 
                             $hasil_update= $this->db->query("UPDATE detail_obat set no_batch='".$no_batch[$key]."' , tgl_exp='".$date."' , sisa_stok=".$stok_real[$key]." WHERE no_batch='".$no_batch_old[$key]."' and no_reg='".$no_reg[$key]."' and id_obat='".$id_obat[$key]."' "); 
                                $hasil_update=true;
                                $ada_perubahan=false; 
                        }  
                    }

                    if ($ada_perubahan==true)
                    {
                        if ($hasil_update==true)
                        { 
                            $ket_log="Proses Stok opname ".$nama[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil";
                            $this->M_log->tambah($this->session->userdata['id'], $ket_log);

                            $pesan.="<li>Proses stok opname ".$nama[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil</li>";
                        }
                        else
                        {
                             $ket_log="Proses Stok opname ".$nama[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal";
                            $this->M_log->tambah($this->session->userdata['id'], $ket_log);

                            $pesan.="<li>Proses stok opname ".$nama[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal</li>"; 
                        }
                    }
                    else{
                        $pesan.="<li> ".$nama[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") tidak ada perubahan data</li>"; 
                    }  
                }
                else{

                    $ket_log="Proses Stok opname ".$nama[$key]." gagal";
                    $this->M_log->tambah($this->session->userdata['id'], $ket_log);

                     $pesan.="<li>Proses stok opname ".$nama[$key]." gagal</li>"; 
                } 
                   
            }  
        }   
        $data2 = array( 
            'pesan' => $pesan,    
            'query' => $this->db->last_query(),    
        ); 
        echo json_encode($data2); 
    }  

    public function printStokOpname()
    {   
        $pdf = new FPDF('L','mm','A4');
        // membuat halaman baru
        $pdf->AddPage();
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Arial','B',14);
        // mencetak string 
        $pdf->Cell(270,7,'PT. NUSA RAYA MEDIKA',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(270,7,'STOK OPNAME '.date('d M Y'),0,1,'C');

        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);

        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,6,'No',1,0); 
        $pdf->Cell(150,6,'Nama Obat',1,0); 
        $pdf->Cell(30,6,'No Batch',1,0); 
        $pdf->Cell(35,6,'No Reg',1,0); 
        $pdf->Cell(25,6,'Tgl Exp',1,0); 
        $pdf->Cell(20,6,'Stok Real',1,1); 

        $pdf->SetFont('Arial','',10); 
 
        $data['data'] = $this->db->query("SELECT *, sum(sisa_stok) as sisa_stok_total from detail_obat where deleted=0 group by no_batch")->result_array();
        
        $no=1;
        foreach ($data['data'] as $row){

            $this->db->where('id_obat', $row['id_obat']);
            $obat = $this->db->get('obat')->row_array();

            $pdf->Cell(10,6,$no++,1,0); 
            $pdf->Cell(150,6,$obat['barcode']." - ".$obat['nama'],1,0); 
            $pdf->Cell(30,6,$row['no_batch'],1,0); 
            $pdf->Cell(35,6,$row['no_reg'],1,0); 
            $pdf->Cell(25,6,date_from_datetime($row['tgl_exp'],2),1,0); 
            $pdf->Cell(20,6,'  ',1,1); 
        } 
        $pdf->Output(); 
    }

   
}
