<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UpdatePembelian extends CI_Controller
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
            if ($key['jenis_cart']=="verifikasi_pembelian") {
                $this->cart->remove($key['rowid']);
            }}
        } 

        $data['title'] = "Update Data Pembelian";
        $data['url'] = "transaksi/UpdatePembelian/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Update Data Pembelian",
             'link' => base_url()."transaksi/VerivikasiPembelian",
             'status' => "active",
            ),
        );

        // $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();  
        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();

        $data['list_faktur'] = $this->db->query('SELECT DISTINCT(A.no_faktur) as no_faktur, A.kd_suplier, B.nama as nama FROM detail_obat A INNER JOIN suplier B ON A.kd_suplier=B.kd_suplier and A.deleted=0 ORDER BY `time` DESC')->result_array(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('UpdatePembelian/index', $data);
        $this->load->view('include2/footer');
    }

     public function get_list_faktur()
    {
        $data['list_faktur'] = $this->db->query('SELECT DISTINCT(A.no_faktur) as no_faktur, A.kd_suplier, B.nama FROM detail_obat A INNER JOIN suplier B ON A.kd_suplier=B.kd_suplier and A.deleted=0 ORDER BY `time` DESC')->result_array(); 
        $this->load->view('UpdatePembelian/list_faktur', $data);
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
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="verifikasi_pembelian") {
                $this->cart->remove($key['rowid']);
            }}
        } 

        $no_faktur = $this->input->post('no_faktur'); 

        $data=$this->db->query("SELECT A.id_detail_obat, A.sisa_stok, A.ppn, A.tgl_exp, A.no_faktur, B.kd_satuan, A.barcode, A.no_reg, A.lokasi, A.harga_beli, A.diskon_beli, A.stok_awal, A.no_batch, B.nama FROM detail_obat A 
INNER JOIN obat B WHERE A.barcode = B.barcode AND A.no_faktur='$no_faktur' and A.deleted=0")->result_array();

        // var_dump($this->db->last_query());
        if (sizeof($data)>0) {
            // $this->cart->destroy();
            foreach ($data as $key) {

                $s = $key['tgl_exp'];
                $dt = new DateTime($s); 
                $tgl_exp_full = $dt->format('Y-m-d');  

                $key['tgl_exp'] = strtotime($key['tgl_exp']);
                $tgl_exp =  date('d M Y', $key['tgl_exp']);

                //get name form barcode 
                $this->db->where('kd_satuan', $key['kd_satuan']);
                $nm_satuan = $this->db->get('satuan')->row_array()['nm_satuan'];  

                $this->cart->product_name_rules = '[:print:]';
                $data2 = array(
                    'id' => $key['no_batch'],
                    'name' => $key['nama'],
                    'satuan' => $nm_satuan,
                    'price' => $key['harga_beli'],
                    'barcode' => $key['barcode'],
                    'no_reg' => $key['no_reg'],
                    'tgl_exp' => $tgl_exp,
                    'tgl_exp_full' => $tgl_exp_full,
                    'diskon' => $key['diskon_beli'],
                    'stok_awal' => $key['sisa_stok'],
                    'qty' => $key['stok_awal'], 
                    'jenis_cart'=>'verifikasi_pembelian',
                    "options"=>array('jenis'=>"verifikasi_pembelian"),
                    'id_detail_obat'=>$key['id_detail_obat']
                );
                $this->cart->insert($data2);
            }
            echo $this->show_cart();
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    }

    public function get_detail_trx()
    { 
        $no_faktur = $this->input->post('no_faktur');
        $where = array(
            'no_faktur' => $no_faktur,
            'deleted' => 0,
        );
        $this->db->where($where);
        $data = $this->db->get('detail_obat')->row_array(); 

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
    
     
    public function show_cart()
    {
        $output = '';
        $no = 0;
        foreach ($this->cart->contents() as $items) {
            if(isset($items['jenis_cart'])){
            if ($items['jenis_cart']=='verifikasi_pembelian') {
                $no++;
                $output .='
                    <tr id="row-data" data-id="'.$items['rowid'].'">
                        <td>'.$no.'</td>
                        <td><input type="hidden" class="form-control" name="barcode[]" value="'.$items['barcode'].'" >
                        <input type="hidden" class="form-control" name="name[]" value="'.$items['name'].'" >'.$items['barcode'].'<br>'.$items['name'].'</td>
     
                        <td>'.$items['satuan'].'</td>
                        <td><input type="text" class="form-control no_batch" name="no_batch[]" value="'.$items['id'].'" >
                        <input type="hidden" class="form-control no_batch_lama" name="no_batch_lama[]" value="'.$items['id'].'" >
                        </td> 
                         
                         <td><input type="text" class="form-control no_reg" name="no_reg[]" value="'.$items['no_reg'].'" >
                         <input type="hidden" class="form-control no_reg_lama" name="no_reg_lama[]" value="'.$items['no_reg'].'" ></td> 

                         <td><input type="date" class="form-control tgl_exp" name="tgl_exp_full[]" value="'.$items['tgl_exp_full'].'" ></td> 
                        
                        <td><input type="text" placeholder="1.0" step="0.01" min="0" max="10000000" class="form-control harga" name="harga[]" value="'.$items['price'].'" min="1" ></td>

                        <td><input type="text" placeholder="1.0" step="0.01" min="0" max="10" class="form-control diskon" name="diskon[]" min="0" value="'.$items['diskon'].'" min="1" ></td> 
    
                        <td>'.'<input type="text" class="form-control qty" name="qty[]" value="'.$items['stok_awal'].'" ></td>  

                        <input type="hidden" class="form-control id_detail_obat" name="id_detail_obat[]" value="'.$items['id_detail_obat'].'" >  

                         <td><button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i></button> 
                        </td>
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

    public function updatePembelian()
    { 

        $pesan="";
        $return="";
        $validation_errors="";
        $data = array(); 
        $query2 = array(); 
        $data3 = array(); 

        if (isset($_POST['no_faktur'])) {

            $time = strtotime($_POST['tgl_input']); 
            $tgl_input = date('Y-m-d',$time);
            $daata_detail_obat = array(  );

            $today = date("Y-m-d");
            $query ="";

            // var_dump(date("Y-m-d"));
            //     var_dump($tgl_input); 

            // if ($tgl_input==$today)
            // {
                $id_detail_obat = $this->input->post('id_detail_obat'); 
                $no_batch = $this->input->post('no_batch'); 
                $no_reg = $this->input->post('no_reg'); 

                $no_batch_lama = $this->input->post('no_batch_lama'); 
                $no_reg_lama = $this->input->post('no_reg_lama');  

                $tgl_exp = $this->input->post('tgl_exp_full'); 
                $harga = $this->input->post('harga'); 
                $diskon = $this->input->post('diskon'); 
                $qty = $this->input->post('qty'); 
                $no_faktur = $this->input->post('no_faktur'); 
                $barcode = $this->input->post('barcode'); 
                $name = $this->input->post('name');   
                $kode_pembayaran = $this->input->post('kode_pembayaran');   
              
                if (isset($_POST['no_batch']))
                {
                    foreach ($no_batch as $key =>$value) {

                         $this->form_validation->set_rules("no_batch[".$key."]", "Nomor batch <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required");

                        $this->form_validation->set_rules("harga[".$key."]", "Harga <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required");

                        $this->form_validation->set_rules("tgl_exp_full[".$key."]", "Tanggal Expired <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required|callback_dob_check");  

                        $this->form_validation->set_rules("qty[".$key."]", "Quality <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required"); 

                        // https://stackoverflow.com/questions/32383743/ci-form-validation-of-date-with-input-type-date   

                        if ($this->form_validation->run() == FALSE) {
                            $validation_errors = validation_errors();
                        } 
                        else { 
                             $data = array(
                                'no_batch' => $no_batch[$key],
                                'no_reg' => $no_reg[$key], 
                                'tgl_exp' => $tgl_exp[$key]." 00:00:00",
                                'harga_beli' => $harga[$key],
                                'diskon_beli' => $diskon[$key], 
                                'stok_awal' => $qty[$key], 
                                'sisa_stok' => $qty[$key], 
                                'kode_pembayaran' => $kode_pembayaran, 
                            );

                            $data3[] = $data;
                            $this->db->where('id_detail_obat', $id_detail_obat[$key]); 
                            $data_lama =  $this->db->get('detail_obat');

                            $this->db->where('id_detail_obat', $id_detail_obat[$key]);  
                            $return=$this->db->update('detail_obat', $data);   

                            if ($return==true)
                            {
                                //update data trx_tmp

                                //get data lama 
                                $data5 = array(
                                    'no_batch' => $no_batch[$key],
                                    'no_reg' => $no_reg[$key], 
                                    'tgl_exp' => $tgl_exp[$key]." 00:00:00",  
                                );

                                $where5 = array(
                                    'no_batch' => $no_batch_lama[$key],
                                    'no_reg' => $no_reg_lama[$key],  
                                );
 
                                 $this->db->where($where5);  
                                 $return=$this->db->update('trx_penjualan_tmp', $data5); 

                                 if ($return==true)
                                 { 
                                    $this->insert_history($data_lama, $data);  
                                 }

                            }
                            else
                            {

                            }

                                $query2[] = $this->db->last_query(); 
                        } 
                    }  

                    $data_detail_obat = implode( ", ", $id_detail_obat );
                    $query = "UPDATE detail_obat set deleted=1 where no_faktur='$no_faktur' and id_detail_obat NOT IN (".$data_detail_obat.")";
                }
                else
                {
                    $return =1;
                    $query = "UPDATE detail_obat set deleted=1 where no_faktur='$no_faktur'";
                } 

                if ($return>0) {
                    $return=1;
                    //hapus data selain itu   
                    $this->db->query($query); 

                    // $query = "UPDATE detail_obat set deleted=1 where no_faktur='$no_faktur' and id_detail_obat NOT IN (".$data_detail_obat.")";

                    $pesan= "Proses Update pembelian berhasil ";
                } else {
                    $return=0;
                    $pesan= "Proses Update pembelian gagal ";
                }
            // }
            // else
            // { 
            //     $return=0;
            //     $pesan= "Tidak boleh mengubah data selain data yang di inputkan pada hari yang sama";
            // } 
        } else {
            $return=0;
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        } 

        $data2 = array(
            'return' => $return, 
            'pesan' => $pesan, 
            'tgl_input' => $tgl_input, 
            'today' => $today,  
            'query' => $query,  
            'query2' => $query2,  
            'data3' => $data3,  
            'validation_errors' => $validation_errors,  
        ); 
        echo json_encode($data2); 
    }

    public function insert_history($data_lama, $data_baru)
    {
        $data = array(
            'data_lama' => json_encode($data_lama->result_array()), 
            'data_baru' => json_encode($data_baru), 
            'id_user' => $this->session->userdata('username'), 
            'tgl_log' => date('Y-m-d h:m:s'), 
            'jenis_log' => "update data pembelian", 
        );
        $this->db->insert('log_activity', $data);
    }

    function checkDateFormat($date) {
        if (preg_match("/[0-31]{2}/[0-12]{2}/[0-9]{4}/", $date)) {
            if(checkdate(substr($date, 3, 2), substr($date, 0, 2), substr($date, 6, 4)))
            return true;
            else
            return false;
        } else {
        return false;
        }
    } 

    public function dob_check($str){
        if (!DateTime::createFromFormat('Y-m-d', $str)) { //yes it's YYYY-MM-DD
            $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format');
            return FALSE;
        } else {
            return TRUE;
        }
    }
 
    public function hapus_cart()
    { //fungsi untuk menghapus item cart
        $data = array(
            'rowid' => $this->input->post('row_id'),
            'qty' => 0,
        );
        $this->cart->update($data);
        echo $this->show_cart();
    }

      
}
