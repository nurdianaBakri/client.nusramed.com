<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VerivikasiPembelian extends CI_Controller
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
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="verifikasi") {
                $this->cart->remove($key['rowid']);
            }}
        }
        $data['title'] = "Verifikasi Barang";
        $data['url'] = "transaksi/VerivikasiPembelian/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Verifikasi Barang",
             'link' => base_url()."transaksi/VerivikasiPembelian",
             'status' => "active",
            ),
        );

        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();
        // $data['Obat'] = $this->M_obat->getAll();

        $data['list_faktur'] = $this->M_trxVerifikasiPembelian->getAll();
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('transaksiVerifikasi/index', $data);
        $this->load->view('include2/footer');
    }

    public function get_kandungan()
    {
        $barcode = $this->input->post('barcode');
        $this->db->select('kandungan, nama');
        $this->db->where('barcode', $barcode);
        $data= $this->db->get('obat')->row_array();
        echo json_encode($data);
    }


    public function get_trx_tmp()
    {
        $no_faktur = $this->input->post('no_faktur');
        $where = array(
            'no_faktur' => $no_faktur,
            'user_verified' => "",
        );
        $data=$this->M_trxVerifikasiPembelian->detail_trx_po($where)->result_array();

        // var_dump($this->db->last_query());
        if (sizeof($data)>0) {
            // $this->cart->destroy();
            foreach ($data as $key) {
                $tgl_exp_full = $key['tgl_exp'];

                $key['tgl_exp'] = strtotime($key['tgl_exp']);
                $tgl_exp =  date('d M Y', $key['tgl_exp']);

                //get name form barcode
                $obat=$this->M_obat->getBy(array('barcode' => $key['barcode'] ));

                $this->db->where('kd_satuan', $obat['kd_satuan']);
                $nm_satuan = $this->db->get('satuan')->row_array()['nm_satuan'];  

                $this->cart->product_name_rules = '[:print:]';
                $data2 = array(
                    'id' => $key['no_batch'],
                    'name' => $obat['nama'],
                    'satuan' => $nm_satuan,
                    'price' => $key['harga_beli'],
                    'barcode' => $key['barcode'],
                    'no_reg' => $key['no_reg'],
                    'tgl_exp' => $tgl_exp,
                    'tgl_exp_full' => $tgl_exp_full,
                    'diskon' => $key['diskon_beli'],
                    'stok_awal' => $key['sisa_stok'],
                    'qty' => $key['stok_awal'],
                    'qty_tdk_baik' => 0,
                    'qty_verified' => $key['stok_awal'],
                    'jenis_cart'=>'verifikasi',
                    "options"=>array('jenis'=>"verifikasi"),
                    'id_detail_obat'=>$key['id_detail_obat']
                );
                $this->cart->insert($data2);
            }
            echo $this->show_cart();
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    }

    public function get_detail_trx($no_faktur=null)
    { 
        $where = array(
            'no_faktur' => $no_faktur,
            'user_verified' => "",
        );
        $data=$this->M_trxVerifikasiPembelian->detail_trx_po($where)->row_array(); 

        if (sizeof($data)>0) {
            ///get nama metode pembayaran
            $this->db->where('kd_pembayaran', $data['kode_pembayaran']);
            $data2['nama_metode_pembayaran']=$this->db->get('metode_pembayaran')->row_array()['nama_metode_pembayaran'];

            $data2['kode_pembayaran'] = $data['kode_pembayaran'];
            $data2['id_user'] = $data['id_user'];
            $data2['tgl_jatuh_tempo'] = $data['tgl_jatuh_tempo'];
            $data2['tgl_input'] = $data['time'];
            echo json_encode($data2);
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    }
    
    public function get_barang_by_barcode()
    {
        $barcode = $this->input->post('barcode');
        $no_faktur = $this->input->post('no_faktur');

        $where = array(
            'barcode' => $barcode,
            'no_faktur' => $no_faktur,
        );

        $data['obat']=$this->M_trxVerifikasiPembelian->detail_trx_po($where)->row_array();

        if ($data['obat']==null) {
            echo "Obat $barcode tidak ditemukan di dalam faktur $no_faktur";
        } else {
            $cek="";
            if (count($this->cart->contents())>0) {
                // $cek=$this->M_detail_obat->get_last_exp_pengambilan($barcode, $no_faktur);
            }
            $num_rows = 0;
            if ($cek->num_rows()>0) {
                $data['data']=$cek->row_array();
                //check if qty sudah melebihi
                $qty_verified=1;
                $row_id="";
                $last_exp="";
                // echo $this->db->last_query();
                foreach ($this->cart->contents() as $key => $val) {
                    if(isset($val['jenis_cart'])){
                    if ($val['jenis_cart']=='verifikasi') {
                        if ($val['id']==$data['data']['no_batch']) {
                            $qty_verified =  $val['qty_verified'];
                            $row_id =  $key;
                            //jika stok memenuhi
                            if ((int) $qty_verified>=(int)$data['data']['qty_verified']) {
                                // if ($qty_verified<(1+((int)$data['data']['qty_verified'])) || $qty_verified+1==(int)$data['data']['qty_verified']) {
                                //tambah ke kart
                                // var_dump($this->db->last_query());
                                $val['qty_verified']=(int)$val['qty_verified']+1;
                                // var_dump($val);
                                if ($this->cart->update($val)) {
                                    echo $this->show_cart();
                                }
                                // $this->add_to_cart($data);
                                return;
                            // var_dump($data);
                            } else {
                             
                            } 
                        }
                    }
                }
                }
            } else {
                // var_dump($this->db->last_query());
                echo "Verified Barang $barcode tidak Boleh Melewati Jumlah QTY";
            }
            // echo "s";
        }
    }

    public function add_to_cart($data)
    {
        $tgl_exp_full = $data['data']['tgl_exp'];

        $data['data']['tgl_exp'] = strtotime($data['data']['tgl_exp']);
        $tgl_exp =  date('d M Y', $data['data']['tgl_exp']);

        //get name form barcode
        $obat=$this->M_obat->getBy(array('barcode' => $data['obat']['barcode'] ));

        $kd_satuan = $data['obat']['kd_satuan']; 
        $this->db->where('kd_satuan', $kd_satuan);
        $nm_satuan = $this->db->get('satuan')->row_array()['nm_satuan']; 

        $this->cart->product_name_rules = '[:print:]';
        $data2 = array(
            'id' => $data['data']['no_batch'],
            'name' => $obat['nama'],
            'satuan' => $nm_satuan,
            'price' => $data['data']['harga_jual'],
            'barcode' => $data['data']['barcode'],
            'no_reg' => $data['data']['no_reg'],
            'tgl_exp' => $tgl_exp,

            'tgl_exp_full' => $tgl_exp_full,
            'diskon' => $data['data']['diskon_per_item'],
            'stok_awal' => $data['data']['sisa_stok'],
            'qty_tdk_baik' => 0,
            'qty' => 2,
            'qty_verified' => $data['data']['qty_verified'],
            'jenis_cart'=>'verifikasi',
            "options"=>array('jenis'=>"verifikasi"),
            'id_detail_obat'=>$key['id_detail_obat']

        );
        $this->cart->insert($data2);
        echo $this->show_cart();
    }

    public function show_cart()
    {
        $output = '';
        $no = 0;
        foreach ($this->cart->contents() as $items) {
            if(isset($items['jenis_cart'])){
            if ($items['jenis_cart']=='verifikasi') {
                $no++;
                $output .='
                    <tr>
                        <td>'.$no.'</td>
                        <td><input type="hidden" class="form-control" name="barcode[]" value="'.$items['barcode'].'" >'.$items['barcode'].'</td>
    
                        <td>'.$items['name'].'</td>
                        <td>'.$items['satuan'].'</td>
                        <td><input type="hidden" class="form-control no_batch" name="no_batch[]" value="'.$items['id'].'" >'.$items['id'].'</td>
    
                         <td><input type="hidden" class="form-control no_reg" name="no_reg[]" value="'.$items['no_reg'].'" >'.$items['no_reg'].'</td>
    
                        <td><input type="hidden" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" >'.$items['tgl_exp'].'</td>
    
                        <td><input type="hidden" class="form-control harga" name="harga[]" value="'.$items['price'].'" >'.number_format($items['price']).'</td>
    
                        <td><input type="hidden" class="form-control diskon"  name="diskon[]" value="'.$items['diskon'].'" > '.$items['diskon'].'</td>
    
                        <td>'.'<input type="hidden" class="form-control qty" name="qty[]" value="'.$items['qty'].'" >'.$items['qty'].'</td>
    
                        <td>'.'<input type="number" class="form-control qty_verified" name="qty_verified[]" value="'.$items['qty_verified'].'" ></td>

                        <td>'.'<input type="number" class="form-control qty_tdk_baik" name="qty_tdk_baik[]" value="'.$items['qty_tdk_baik'].'" ></td>
    
                        <td><span class="money subtotal">'.number_format($items['subtotal']).'</span>
    
                        <input type="hidden" class="form-control stok_awal" name="stok_awal[]" value="'.$items['stok_awal'].'" >
                        <input type="hidden" class="form-control id_detail_obat" name="id_detail_obat[]" value="'.$items['id_detail_obat'].'" >
    
                        <input type="hidden" class="form-control harga_setelah_diskon" name="harga_setelah_diskon[]" value="0" >                     
                        <input type="hidden" class="form-control tgl_exp_full" name="tgl_exp_full[]" value="'.$items['tgl_exp_full'].'" >                     
                        <input type="hidden" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" >   
                    </tr>
                ';
            }
        }
        }
        return $output;
    }

    public function load_cart()
    { //load data cart
        echo $this->show_cart();
    }

    
    public function reload_data()
    {
        $data_balikan['data']= array();
        $data['data']= $this->M_trxPenjualan->getAll();
        $this->load->view('trxPenjualan/data', $data);
    }

    public function verifikasiTransaksi()
    {
        $pesan="";
        $return="";
        $data = array();

        if (isset($_POST['barcode'])) {
            $qty_verified = $this->input->post('qty_verified');
            $qty_tidak_baik = $this->input->post('qty_tdk_baik');
            $id_detail_obat = $this->input->post('id_detail_obat');
            // $size = sizeof($barcode);
          
            foreach ($qty_verified as $key =>$value) {
                $data[] = array(
                    'id_detail_obat' => $id_detail_obat[$key],
                    'qty_verified' => $qty_verified[$key],
                    'qty_tidak_baik' => $qty_tidak_baik[$key],
                    'user_verified' => $this->session->userdata('username'),
                );
            }

            // try the select.
            $return=$this->db->update_batch('detail_obat', $data, 'id_detail_obat');
            // var_dump($this->db->error());

            if ($return>0) {
                $return=1;
                $pesan= "Proses Verifikasi pembelian berhasil ";
            } else {
                $return=0;
                $pesan= "Proses Verifikasi pembelian gagal ";
            }
        } else {
            $return=0;
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        }

        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="verifikasi") {
                $this->cart->remove($key['rowid']);
            }}
        } 

        $data = array(
            'return' => $return,
            'pesan' => $pesan,
        );
        // var_dump($data);
        echo  json_encode($data);
    } 
      
}
