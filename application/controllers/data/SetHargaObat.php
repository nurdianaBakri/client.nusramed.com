<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SetHargaObat extends CI_Controller
{
   public function __construct()
    {
        parent ::__construct();  
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
        $data['title'] = "Set Harga Obat";
        $data['url'] = "data/SetHargaObat/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Set Harga Obat",
             'link' => base_url()."data/SetHargaObat",
             'status' => "active",
            ),
        ); 
        
        $this->load->view('include2/sidebar', $data);
        $this->load->view('setHargaObat/index', $data);
        $this->load->view('include2/footer');
    }  

     public function reload_data()
    { 
       $data['detail_obat'] = $this->db->query('SELECT * from form_set_harga');
        // $data['detail_obat'] = $this->M_detail_obat->cek_detail_obat($where); 
        $this->load->view('setHargaObat/data', $data);
    }

    function cari_nama_obat(){

        $keyword = $this->input->post('keyword_nama_obat');

       /* $this->db->like('nama', $keywoard);
        $this->db->where('deleted',0);
        $this->db->order_by('date', 'DESC');
        $data['detail_obat']=$this->db->get("obat")->result();  */
        $data['detail_obat'] = $this->db->query("CALL search_set_harga_obat('".$keyword."')")->result(); 
        
        // var_dump($data);
        $this->load->view('setHargaObat/data_hasil_search', $data);   
    }


    public function setHarga()
    { 
        $pesan="";
        $return=""; 
        $hasil_query = array();
 
        if (isset($_POST['id_obat']))
        { 
            $id_obat = $this->input->post('id_obat');
            $no_reg = $this->input->post('no_reg');
            $nama = $this->input->post('nama');
            $no_batch = $this->input->post('no_batch'); 
            $harga_jual = $this->input->post('harga_jual'); 
            $diskon_maximal = $this->input->post('diskon_maximal');  
            $tgl_exp = $this->input->post('tgl_exp');  
            
            // $size = sizeof($id_obat); 
            $data = array();
            foreach($id_obat as $key=>$value) {  

                $rawdate = htmlentities($tgl_exp[$key]);
                $date = date('Y-m-d', strtotime($rawdate));  

                if ($harga_jual[$key]=="" || $harga_jual[$key]<=0)
                {
                    $ket_log="Proses set harga obat '".$nama[$key]."' (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") menjadi ".$harga_jual[$key]." gagal.";

                    $this->M_log->tambah($this->session->userdata['id'], $ket_log); 

                    $pesan.="<li>".$ket_log."</li>";
                }
                else
                { 
                    $where = array(
                        'id_obat' => $value, 
                        'no_reg' => $no_reg[$key], 
                        'no_batch' => $no_batch[$key], 
                    );

                    $data = array( 
                        'harga_jual' => str_replace(",", "", $harga_jual[$key]) ,  
                        'diskon_maximal' => $diskon_maximal[$key],  
                        'tgl_exp' => $date,  
                    );    

                    $ket_log="Proses set harga obat '".$nama[$key]."' (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") menjadi ".$harga_jual[$key]." ";

                    $query_set_harga = $this->M_detail_obat->set_harga_obat($where, $data, $ket_log);

                    $pesan.="<li>".$query_set_harga."</li>"; 
                } 
            }  
        }
        else
        {
            // $return=0;  
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        } 

        $data2 = array(
            // 'return' => $return, 
            'pesan' => $pesan,  
            // 'hasil_query' => $hasil_query,  
            // 'last_query' => $this->db->last_query(),  
        ); 
        // echo json_encode($data2); 
        echo $data2['pesan'];
    } 

    public function tes()
    { 
        // Sample arrays 
      /*  $arr1 = array( 'first' => 'geeks', 
                       'second' => 'ssss', 
                       'last' => 'ide'
                ); 
        $arr2 = array( 'first' => 'geeks', 
                       'last' =>'ide', 
                       'second' =>'for'
                ); 
              
        // Check for equality 
        if ($arr1 == $arr2) 
            echo "Both arrays are same\n"; 
        else
        echo "Both arrays are not same\n";


         $arr1 = array( 'first1' => 'geeks', 
                       'last1' => 'ide',
                       'second1' => 'for', 
                ); 
        $arr2 = array( 'first' => 'geeks', 
                       'last' =>'ide', 
                       'second' =>'for'
                ); 
        $result = array_merge_recursive($arr1, $arr2);
        print_r($result); */     

        $data = $this->db->query("CALL search_set_harga_obat('hydr')")->result();
        print_r($data);                                   
    }

   
}
