<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FakturJualBeli extends CI_Controller
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
        $data['title'] = "Laporan Faktur Penjualan dan Pembelian";
        $data['url'] = "laporan/FakturJualBeli/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Laporan Faktur Penjualan dan Pembelian",
             'link' => base_url()."laporan/FakturJualBeli",
             'status' => "active",
            ),
        );  
        
        $data['list_faktur'] = $this->M_trxPenjualan->getAll_trx_tmp();
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('lapFakturJualBeli/index', $data);
        $this->load->view('include2/footer');
    } 

    public function get_laporan()
    {
        $jenis_laporan = $this->input->post('jenis_faktur'); 
        $data = array();
        // var_dump($jenis_laporan);

        if ($jenis_laporan=="penjualan")
        { 
            $no_faktur = $this->db->query("SELECT no_faktur, A.time, A.kode_pembayaran, B.nama_metode_pembayaran, C.nama, A.harga_jual, B.kd_pembayaran, A.id_user_verifikasi, A.id_user_input, A.stok_awal, A.alasan_return, A.tgl_jatuh_tempo, A.kd_outlet from trx_penjualan_tmp A, metode_pembayaran B, outlet1 C where C.id_outlet = A.kd_outlet and B.kd_pembayaran = A.kode_pembayaran GROUP BY no_faktur"); 
            $data2 = array(); 
            foreach ($no_faktur->result_array() as $key) {
                
                //get jumlah belanja
                $sub_total1 = 0;
                $sub_total2 = 0;
                $jumlah_potongan = 0;
                $ppn = 0;
                $no_faktur2 = $key['no_faktur']; 
                $key['harga_stl_pajak']=$this->M_trxPenjualan->get_total_tagihan_per_faktur($no_faktur2);
 
                if ($key['id_user_verifikasi']=="")
                { 
                    $key['id_user_verifikasi'] = "<span class='badge badge-pill badge-danger'>Belum di verifikasi</span>";
                } 
                else
                {
                    $key['id_user_verifikasi'] = "<span class='badge badge-pill badge-success'>Sudah di verikasi oleh <br>".$key['id_user_verifikasi']."</span>";
                }  
                $data2[]=$key;
            }

            $data['laporan'] = $data2; 
            $this->load->view('lapFakturJualBeli/lap_penjualan',$data);
        }
        else
        {
            $no_faktur = $this->db->query("SELECT no_faktur, id_detail_obat, A.user_verified, A.time, A.kode_pembayaran, B.nama_metode_pembayaran, C.nama, A.harga_beli, A.id_user, A.stok_awal, A.tgl_jatuh_tempo, A.kd_suplier from detail_obat A, metode_pembayaran B, suplier C where C.kd_suplier = A.kd_suplier AND  B.kd_pembayaran = A.kode_pembayaran GROUP BY no_faktur");

            $data2 = array(); 
            foreach ($no_faktur->result_array() as $key) {
                
                //get jumlah belanja
                 //get jumlah belanja
                $sub_total1 = 0;
                $sub_total2 = 0;
                $jumlah_potongan = 0;
                $ppn = 0;
                $no_faktur2 = $key['no_faktur']; 
                $jumlah_belanja1 = $this->db->query("SELECT * from detail_obat where no_faktur='$no_faktur2'")->result();

                foreach ($jumlah_belanja1 as $key2) {
                      //rumus
                    $sum_harga1 = $key2->stok_awal*$key2->harga_beli;

                    $sub_total1 = ($sum_harga1)-($sum_harga1*($key2->diskon_beli/100));
                    $sub_total2 =  $sub_total2+$sub_total1; 

                    $Potongan = $sum_harga1*($key2->diskon_beli/100);
                    $jumlah_potongan= $jumlah_potongan + $Potongan; 
                    $ppn=$key2->ppn; 
                }  

                if ($key['user_verified']=="")
                { 
                    $key['user_verified'] = "<span class='badge badge-pill badge-danger'>Belum di verifikasi</span>";
                } 
                else
                {
                    $key['user_verified'] = "<span class='badge badge-pill badge-success'>Sudah di verikasi oleh <br>".$key['user_verified']."</span>";
                }

                $key['harga_beli'] = $sub_total2;
                $key['jumlah_potongan'] =$jumlah_potongan;
                $key['harga_kurang_pot'] = $sub_total2-$jumlah_potongan;
                $key['nilai_pajak'] = $key['harga_kurang_pot']*($ppn/100);
                $key['harga_stl_pajak'] = $key['harga_kurang_pot']+$key['nilai_pajak'];

                $data2[]=$key;
            }

            $data['laporan'] = $data2; 
            $this->load->view('lapFakturJualBeli/lap_pmbelian',$data);
        } 
    }

    public function detail_pembelian($id_detail_obat)
    { 
        $data['title'] = "Laporan Faktur Penjualan dan Pembelian";
        $data['url'] = "laporan/FakturJualBeli/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Laporan Faktur Penjualan dan Pembelian",
             'link' => base_url()."laporan/FakturJualBeli",
             'status' => "active",
            ),
        );  
 

        $this->db->where('id_detail_obat', $id_detail_obat); 
        $query_kedua = $this->db->get('detail_obat')->row_array();

        //get data suplier 
        $this->db->where('kd_suplier', $query_kedua['kd_suplier']);
        $data['suplier'] = $this->db->get('suplier')->row_array(); 
        
        if ($query_kedua['tgl_jatuh_tempo']=='0000-00-00 00:00:00')
        {
            $data['tgl_jatuh_tempo']="COD";
        } 
        else{
             $data['tgl_jatuh_tempo']= $query_kedua['tgl_jatuh_tempo'];
        }
        $data['ppn'] =$query_kedua['ppn'];  
        $no_faktur = $query_kedua['no_faktur'];

        $data['data']= $this->db->query("SELECT A.ppn, A.tgl_exp, A.no_faktur, A.barcode, A.no_reg, A.lokasi, A.harga_beli, A.diskon_beli, A.stok_awal, A.no_batch, B.nama FROM detail_obat A 
INNER JOIN obat B WHERE A.barcode = B.barcode AND A.no_faktur='$no_faktur'")->result_array();

        // var_dump($this->db->last_query()); 
        $data['no_faktur'] = $no_faktur;
        $data['data_row'] =$query_kedua;
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('lapFakturJualBeli/detail_pembelian', $data);
        $this->load->view('include2/footer');
    }


    public function detail_pejualan($no_faktur, $kode_outlet)
    { 
        $data['title'] = "Laporan Faktur Penjualan dan Pembelian";
        $data['url'] = "laporan/FakturJualBeli/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Laporan Faktur Penjualan dan Pembelian",
             'link' => base_url()."laporan/FakturJualBeli",
             'status' => "active",
            ),
        );   

        $data['invoice'] = $this->db->query("SELECT a.*,c.nama, b.nm_satuan FROM trx_penjualan_tmp a, obat c, satuan b where a.barcode=c.barcode and c.kd_satuan=b.kd_satuan and a.no_faktur='".$no_faktur."'")->result();
        $data['outlet'] = $this->db->query("SELECT a.nama,a.npwp,a.alamat,a.id_outlet FROM outlet1 a where a.id_outlet='".$kode_outlet."'")->row();
        $data['nota_kosong'] = $this->db->query("SELECT a.*,b.nama, c.nm_satuan FROM obat_kosong a, obat b, satuan c WHERE a.barcode=b.barcode and b.kd_satuan=c.kd_satuan and no_faktur='".$no_faktur."'")->result();
        $data['jumlah_kosong'] = $this->db->query("select SUM(jumlah_kosong) as jumlah_kosong FROM obat_kosong where no_faktur='".$no_faktur."'")->row()->jumlah_kosong;
        $data['no_faktur'] = $no_faktur;
        $data['kode_outlet'] = $kode_outlet;

        $this->db->where('no_faktur', $no_faktur); 
        $query_kedua = $this->db->get('trx_penjualan_tmp')->row_array();

        if ($query_kedua['tgl_jatuh_tempo']=='0000-00-00 00:00:00')
        {
            $data['tgl_jatuh_tempo']="-";
            $data['kredit']="COD";
        }   
        else{ 
            $data['tgl_jatuh_tempo']=date_from_datetime($query_kedua['tgl_jatuh_tempo'],2);

            $date1 = strtotime($query_kedua['time']);  
            $date2 = strtotime($query_kedua['tgl_jatuh_tempo']);  
              
            // Formulate the Difference between two dates 
            $diff = abs($date2 - $date1);    
            $years = floor($diff / (365*60*60*24));  
            $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));   
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
             $data['kredit']=$days+1;
             $data['kredit'] = $data['kredit']." Hari";
        }
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('lapFakturJualBeli/detail_penjualan', $data);
        $this->load->view('include2/footer');
    }

    public function get_total_tagihan_per_faktur($no_faktur)
    {
        echo $this->M_trxPenjualan->get_total_tagihan_per_faktur($no_faktur);
    }

 
}
