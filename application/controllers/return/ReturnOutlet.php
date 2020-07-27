<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReturnOutlet extends CI_Controller
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
        $data['title'] = "Return barang dari Outlet";
        $data['url'] = "return/ReturnOutlet/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Return barang dari Outlet",
             'link' => base_url()."return/ReturnOutlet",
             'status' => "active",
            ),
        ); 
        
        $data['outlet'] = $this->db->query('SELECT DISTINCT(outlet1.id_outlet) as id_outlet,  outlet1.nama from trx_penjualan_tmp,outlet1 WHERE trx_penjualan_tmp.kd_outlet = outlet1.id_outlet')->result_array(); 
        // $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('returnDariOutlet/index', $data);
        $this->load->view('include2/footer');
    }

    public function get_list_faktur()
    {
        $id_outlet = $this->input->post('id_outlet');

        $data['data']=$this->db->query("SELECT DISTINCT(no_faktur) as no_faktur, time from trx_penjualan_tmp WHERE kd_outlet='".$id_outlet."' ORDER BY `time` DESC")->result_array();  
        $this->load->view('returnDariOutlet/list_faktur', $data);
    } 

    public function get_detail_trx()
    { 
        $no_faktur = $this->input->post('no_faktur');
        $kd_outlet = $this->input->post('id_outlet');

       /* $where = array(
            'no_faktur' => $no_faktur,
            'kd_outlet' => $id_outlet, 
        );
        $this->db->where($where);  */
        // $this->db->select('kode_pembayaran, id_user_input, tgl_input, tgl_jatuh_tempo');
        // $data = $this->db->get('trx_penjualan_tmp')->row_array(); 

        $data = $this->db->query("SELECT kode_pembayaran, nama_metode_pembayaran ,id_user_input, tgl_jatuh_tempo, time from trx_penjualan_tmp, metode_pembayaran  where no_faktur='".$no_faktur."' AND kd_outlet = '".$kd_outlet."' AND kode_pembayaran=kd_pembayaran")->row_array();   

        if (sizeof($data)>0) {
            ///get nama metode pembayaran 
            $data2['nama_metode_pembayaran'] = $data['nama_metode_pembayaran'];
            $data2['kode_pembayaran'] = $data['kode_pembayaran'];
            $data2['id_user'] = $data['id_user_input'];
            $data2['tgl_jatuh_tempo'] = date_from_datetime($data['tgl_jatuh_tempo'],1);
            $data2['tgl_input'] = $data['time'];
            echo json_encode($data2);
        } 
        else 
        {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    } 

     function update()
     {
        $data = array( );
        $data_log = array( );
        $pesan = "";
        $keterangan = "";
        $validation_errors="";
        $insert = false;

        $id_trx = $this->input->post('id_trx');
        $qty_return = $this->input->post('qty_return');
        $alasan_return = $this->input->post('alasan_return');  

            $this->form_validation->set_rules("alasan_return", "harus di masukkan ", "trim|required"); 
             $this->form_validation->set_rules("qty_return", "harus di masukkan ", "trim|required");  

            //get data untuk di masukkan ke log 
            $data_trx = $this->db->query("SELECT barcode, no_batch, no_reg, qty from trx_penjualan_tmp where id_trx=".$id_trx."")->row_array();


            if ($this->form_validation->run() == FALSE) {
                $validation_errors = validation_errors();

                $pesan =  "Gagal Me-return barang dengan barcode : '".$data_trx['barcode']."', nomor Batch : '".$data_trx['no_batch']."' dan Nomor reg.: '".$data_trx['no_reg']."' sebanyak ".$qty_return.". Karena tidak memasukan alsan retun atau jumlah return"; 

                // $pesan="Proses return obat dari outlet gagal .";  
                $this->M_log->tambah($this->session->userdata['id'], $pesan);
            } 
            else {  

                if ( $qty_return > $data_trx['qty']) {

                    $pesan =  "Gagal Me-return barang dengan barcode : '".$data_trx['barcode']."', nomor Batch : '".$data_trx['no_batch']."' dan Nomor reg.: '".$data_trx['no_reg']."' sebanyak ".$qty_return.". Karena Return tidak boleh lebih besar dari pembelian"; 

                     // $pesan="Proses return obat dari outlet gagal .";  
                    $this->M_log->tambah($this->session->userdata['id'], $pesan);
                }
                else{ 
                   $data= array(
                       "id_trx" => $id_trx,
                       "jumlah_return" => $qty_return,
                       "alasan_return" => $alasan_return, 
                       "id_user" => $this->session->userdata('id'), 
                       "tgl_return" => date('Y-m-d'), 
                    );  
                    $insert = $this->db->insert('return_dr_outlet', $data);  
                    if ($insert)
                    {
                        $pesan =  "Me-return barang dengan barcode : '".$data_trx['barcode']."', nomor Batch : '".$data_trx['no_batch']."' dan Nomor reg.: '".$data_trx['no_reg']."' sebanyak ".$qty_return;
                    }
                    else{
                         $pesan =  "Gagal Me-return barang dengan barcode : '".$data_trx['barcode']."', nomor Batch : '".$data_trx['no_batch']."' dan Nomor reg.: '".$data_trx['no_reg']."' sebanyak ".$qty_return;
                    } 
                   
                      // $pesan="Proses return obat dari outlet gagal .";  
                    $this->M_log->tambah($this->session->userdata['id'], $pesan); 
                } 
            }  
 
 
            $data = array( 
                'pesan' => $pesan,   
                'return' => $insert,   
                'validation_errors' => $validation_errors,  
            ); 
            echo json_encode($data);
     }
  
    public function get_trx_tmp()
    {  
        $no_faktur = $this->input->post('no_faktur'); 

        $data2 = array();
        $no_faktur = $this->input->post('no_faktur'); 
        $where = array(
            'no_faktur' => $no_faktur, 
        );
        $data=$this->M_trxPenjualan->detail_trx_tmp($where)->result_array();

        $no=1;
        if (sizeof($data)>0) {
            // $this->cart->destroy();
            foreach ($data as $key) {

                $s = $key['tgl_exp'];
                $dt = new DateTime($s); 
                $tgl_exp = $dt->format('Y-m-d');  

                //get name form barcode
                $obat=$this->M_obat->getBy(array('barcode' => $key['barcode'] )); 

                 $data2['data'][] = array(
                    'no' => $no++, 
                    'id_trx' => $key['id_trx'], 
                    'barcode_nama' => $obat['barcode']." <br>- ".$obat['nama'],
                    'harga_jual' => number_format($key['harga_jual'],2),
                    'barcode' => $key['barcode'],
                    'no_reg' => $key['no_reg'],
                    'tgl_exp' => $tgl_exp, 
                    'diskon' => number_format($key['diskon_per_item'],2),
                    'qty' => $key['qty'],  
                    'qty_return' => 0,  
                    'alasan_return' => "",  
                    'no_batch'=>$key['no_batch'], 
                );
            }

            $data2['ada']=true;
            $data2['pesan']=""; 
        } else {
            $data2['ada']=false;
            $data2['pesan']="Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        } 
        // echo json_encode($data2); 
        $this->load->view('returnDariOutlet/data2',$data2);
    } 
        
    public function insert_history($data_lama, $data_baru)
    {
        $data = array(
            'data_lama' => json_encode($data_lama->result_array()), 
            'data_baru' => json_encode($data_baru), 
            'id_user' => $this->session->userdata('username'), 
            'tgl_log' => date('Y-m-d h:m:s'), 
            'jenis_log' => "Return barang dari Outlet", 
        );
        $this->db->insert('log_activity', $data);
    } 

     public function get_riwayat_return()
    {
        $no_faktur = $this->input->post('no_faktur');
       $data['data'] = $this->db->query("SELECT obat.nama as nama_obat,  user_login.nama as nm_user, return_dr_outlet.jumlah_return, return_dr_outlet.alasan_return, return_dr_outlet.id_user, return_dr_outlet.tgl_return, trx_penjualan_tmp.barcode, trx_penjualan_tmp.no_batch, trx_penjualan_tmp.no_reg FROM return_dr_outlet, trx_penjualan_tmp, user_login, obat WHERE return_dr_outlet.id_trx in (SELECT trx_penjualan_tmp.id_trx FROM trx_penjualan_tmp WHERE no_faktur='".$no_faktur."') and return_dr_outlet.id_trx=trx_penjualan_tmp.id_trx and user_login.id = return_dr_outlet.id_user and trx_penjualan_tmp.barcode = obat.barcode")->result_array();
        $this->load->view('returnDariOutlet/riwayat_return', $data);  
    }
  
}
