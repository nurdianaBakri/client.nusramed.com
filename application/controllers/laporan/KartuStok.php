<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KartuStok extends CI_Controller {

    public function __construct()
    {
        parent ::__construct();
        $cek = $this->M_login->cek_userIsLogedIn(); 
        // var_dump($cek);
        if ($cek==FALSE)
        {
            $this->session->set_flashdata('pesan',"Anda harus login jika ingin mengakses halaman lain");
            redirect('Home');
        } 
    } 

    public function  index()
    {  
        $data['title'] = "Kartu Stok";
        $data['url'] = "laporan/KartuStok/"; 
        $data['SubFitur'] =null; 

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "", 
            ),
            array(
             'fitur' => "Kartu Stok", 
             'link' => base_url()."laporan/KartuStok", 
             'status' => "active", 
            ), 
        );

        $data['obat'] = $this->db->query('SELECT * FROM form_kartu_stok')->result_array(); 

        $this->load->view('include2/sidebar',$data); 
        $this->load->view('kartuStok/index',$data);
        $this->load->view('include2/footer');  
    }  

    public function get_laporan()
    {
        $id_obat = $this->input->post('id_obat'); 

        $data1 = array(); 
        $data2 = array(); 
         
        $query1 = "SELECT suplier.nama, master_detail_obat.no_faktur, master_detail_obat.kd_suplier, no_batch, tgl_exp, qty_verified, time FROM detail_obat, suplier, obat, master_detail_obat WHERE detail_obat.id_obat=$id_obat and master_detail_obat.kd_suplier=suplier.kd_suplier and  detail_obat.id_obat = obat.id_obat and master_detail_obat.id_master_detail = detail_obat.id_master_detail";

        $data['lap_pemb'] = $this->db->query($query1); 

        $data['lap_pen'] =$this->db->query("SELECT no_faktur,sisa_stok, time, qty_verified, no_batch, tgl_exp, outlet1.nama FROM trx_penjualan_tmp, outlet1 WHERE id_obat=$id_obat and trx_penjualan_tmp.kd_outlet=outlet1.id_outlet"); 

       /* $data['data_so'] =$this->db->query("SELECT no_batch, stok_real, tanggal, tgl_exp, no_reg FROM stok_opname  WHERE id_obat='".$id_obat."' ORDER BY tanggal DESC"); */
        
        $data['data_so'] =$this->db->query("SELECT  stok_opname.tgl_exp,  stok_opname.id_obat, stok_opname.no_reg, stok_opname.no_batch, stok_opname.stok_real, stok_opname.tanggal FROM stok_opname WHERE stok_opname.id_obat=$id_obat"); 

        foreach ($data['lap_pemb']->result_array() as $key ) { 
           $data1[] = array(
                'time'=>date("d/m/Y h:i:s", strtotime($key['time'])),
                'time_real'=>$key['time'],
                'no_faktur'=>$key['no_faktur'],
                'keterangan'=>$key['nama'],
                'no_batch'=>$key['no_batch'],
                'tgl_exp'=>date("d M Y", strtotime($key['tgl_exp'])) , 
                'masuk'=>$key['qty_verified'],
                'keluar'=>"-", 
                'sisa'=>$key['qty_verified'], 
                'paraf'=>"-", 
            );
        }

         foreach ($data['lap_pen']->result_array() as $key ) { 
           $data1[] = array(
                'time'=>date("d/m/Y h:i:s", strtotime($key['time'])),
                'time_real'=>$key['time'], 
                'no_faktur'=>$key['no_faktur'],
                'keterangan'=>$key['nama'],
                'no_batch'=>$key['no_batch'],
                'tgl_exp'=>date("d M Y", strtotime($key['tgl_exp'])) , 
                'masuk'=>"-",
                'keluar'=>$key['qty_verified'], 
                'sisa'=>$key['sisa_stok'], 
                'paraf'=>"-", 
            );
        }

         foreach ($data['data_so']->result_array() as $key ) { 

            //get nomor faktur  
            $data['no_faktur'] =$this->db->query("SELECT no_faktur from detail_obat WHERE no_batch='".$key['no_batch']."' and no_reg='".$key['no_reg']."'"); 

            if ($data['no_faktur']->num_rows()>0)
            {
                $data['no_faktur'] = $data['no_faktur']->row_array()['no_faktur'];
            }
            else
            {
                $data['no_faktur'] = "[sedang di telusuri]";
            }

           $data1[] = array(
                'time'=>date("d/m/Y h:i:s", strtotime($key['tanggal'])),
                'time_real'=>$key['tanggal'], 
                'no_faktur'=> $data['no_faktur'],
                'keterangan'=>"Stok Opname",
                'no_batch'=>$key['no_batch'],
                'tgl_exp'=>date("d M Y", strtotime($key['tgl_exp'])) , 
                'masuk'=>"-",
                'keluar'=>"-", 
                'sisa'=>$key['stok_real'], 
                'paraf'=>"-", 
            );
        }      

     
        $data['laporan'] = $data1;  
        $this->load->view('kartuStok/data',$data);
    }

    public function cmp($a, $b) {
        return strcmp($a->time_real, $b->time_real);
    } 

 
    public function testing()
    {
        //

    }
    
}