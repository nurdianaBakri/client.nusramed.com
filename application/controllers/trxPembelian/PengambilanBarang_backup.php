<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PengambilanBarang extends CI_Controller
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
            if ($key['jenis_cart']=="pengambilan") {
                $this->cart->remove($key['rowid']);
            }}
        }
        $data['title'] = "Transaksi Pengambilan Barang";
        $data['url'] = "transaksi/PengambilanBarang/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Transaksi Pengambilan Barang",
             'link' => base_url()."transaksi/PengambilanBarang",
             'status' => "active",
            ),
        );

        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('transaksiPengambilanBarang/index', $data);
        $this->load->view('include2/footer');
    } 
    

    public function get_faktur()
    {
        $data = array();
        $status_pengambilan = $this->input->post('status_pengambilan');
        $tgl_mulai = $this->input->post('tgl_mulai');
        $tgl_akhir = $this->input->post('tgl_akhir'); 

        $data['list_faktur'] = $this->M_trxPenjualan->get_distinct_faktur($tgl_mulai, $tgl_akhir, $status_pengambilan);  

        // echo $this->db->last_query();
        $this->load->view('transaksiPengambilanBarang/list_faktur', $data); 
    }

    public function get_trx_tmp(/*$no_faktur=null, $status_pengambilan=null*/)
    { 
        $data = "";  
        $where = array();

        /*if ($no_faktur==null)
        {*/
            $no_faktur = $this->input->post('no_faktur'); 
            $status_pengambilan = $this->input->post('status_pengambilan'); 
        /*} 
*/
        if ($status_pengambilan==0 || $status_pengambilan=="0")
        {  
            $data= $this->db->query("SELECT A.harga_jual, A.barcode, A.no_reg, A.no_batch, A.diskon_per_item, A.stok_awal, A.sisa_stok, A.tgl_exp, A.id_trx, A.qty, A.qty_verified, obat.nama from trx_penjualan_tmp A, obat WHERE no_faktur='".$no_faktur."' and A.barcode=obat.barcode and (id_user_verifikasi='' or id_user_verifikasi is null)")->result_array();
        }
        else
        {
            $data= $this->db->query("SELECT A.harga_jual, A.barcode, A.no_reg, A.no_batch, A.diskon_per_item, A.stok_awal, A.sisa_stok, A.tgl_exp, A.id_trx, A.qty, A.qty_verified, obat.nama from trx_penjualan_tmp A, obat WHERE no_faktur='".$no_faktur."' and A.barcode=obat.barcode and (id_user_verifikasi!='' or id_user_verifikasi is not null)")->result_array();
        }   

         foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="pengambilan") {
                $this->cart->remove($key['rowid']);
            }}
        }  


        if (sizeof($data)>0) {
            // $this->cart->destroy(); 
            foreach ($data as $key) 
            {  
                $s = $key['tgl_exp'];
                $dt = new DateTime($s); 
                $tgl_exp = $dt->format('Y-m-d');   

                $this->cart->product_name_rules = '[:print:]';
                $data2 = array(
                    'id' => $key['id_trx'],
                    'name' => $key['nama'],
                    'price' => $key['harga_jual'],
                    'barcode' => $key['barcode'],
                    'no_reg' => $key['no_reg'],
                    'tgl_exp' => $tgl_exp, 
                    'diskon' => $key['diskon_per_item'],
                    'stok_awal' => $key['stok_awal'],
                    'sisa_stok' => $key['sisa_stok'],
                    'qty' => $key['qty'],
                    'qty_verified' => $key['qty_verified'],
                    'jenis_cart'=>'pengambilan',
                    "options"=>array('jenis'=>"pengambilan"),
                    'no_batch'=>$key['no_batch'], 
                );
                $this->cart->insert($data2);

                // $this->cart->insert($data);  
                // print_r($data2); 
            } 

            echo $this->show_cart();

        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    }

    public function get_detail_trx()
    {
        $no_faktur = $this->input->post('no_faktur');
        $status_pengambilan = $this->input->post('status_pengambilan');

        $where = array('no_faktur' => $no_faktur );
        $data=$this->M_trxPenjualan->detail_trx_tmp($where)->row_array(); 

        if (sizeof($data)>0) {
            ///get nama metode pembayaran 

            $data2['kode_pembayaran'] = $data['kode_pembayaran'];
            $data2['id_user_input'] = $data['id_user_input'];
            $data2['tgl_jatuh_tempo'] = date_from_datetime($data['tgl_jatuh_tempo'],1);
            $data2['tgl_input'] = $data['time'];

            echo json_encode($data2);
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$no_faktur'";
        }
    }    

      public function get_effectiveDate()
    {
        $effectiveDate = date('Y-m-d');  
        $effectiveDate = date('Y-m-d', strtotime("+8 months", strtotime($effectiveDate))); 
        return $effectiveDate;
    }

    public function show_cart()
    { 
        $output = '';
        $no = 0; 
        $effectiveDate = $this->get_effectiveDate();

        foreach ($this->cart->contents() as $items) {
            if(isset($items['jenis_cart'])){
                if ($items['jenis_cart']=='pengambilan') {
                    $no++;
                    $output .='
                        <tr>
                            <td>'.$no.'</td>
                            <td><input type="hidden" class="form-control" name="barcode[]" value="'.$items['barcode'].'" >
                            <input type="hidden" class="form-control" name="name[]" value="'.$items['name'].'" >

                            '.$items['barcode']."<br>".$items['name'].'</td>
         
                            <td><input type="hidden" class="form-control no_batch" name="no_batch[]" value="'.$items['no_batch'].'">'.$items['no_batch'].'</td>
        
                             <td><input type="hidden" class="form-control no_reg" name="no_reg[]" value="'.$items['no_reg'].'" >'.$items['no_reg'].'</td>
        
                            <td><input type="hidden" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" min="'.$effectiveDate.'">'.$items['tgl_exp'].'</td>
        
                            <td><input type="hidden" class="form-control harga" name="harga[]" value="'.$items['price'].'" >'.number_format($items['price'],2).'</td>
        
                            <td><input type="number" class="form-control diskon"  name="diskon[]" value="'.number_format($items['diskon'],2).'" ></td>
        
                            <td>'.'<input type="number" class="form-control qty"  name="qty[]" value="'.$items['qty'].'" > 

                            </td> 
        
                            <td>'.'<input type="number" class="form-control qty_verified" name="qty_verified[]" value="'.$items['qty'].'" ></td>
        
                            <td><span class="money subtotal">'.number_format(($items['qty']*$items['price'])-($items['qty']*$items['price']*($items['diskon']/100)),2).'</span>
        
                            <input type="text" class="form-control stok_awal" name="stok_awal[]" value="'.$items['stok_awal'].'" >

                            <input type="text" class="form-control" name="sisa_stok[]" value="'.$items['sisa_stok'].'" > 

                            <input type="hidden" class="form-control id_trx" name="id_trx[]" value="'.$items['id'].'" >   </td>
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

    public function hapus_cart()
    { //fungsi untuk menghapus item cart
        $data = array(
            'rowid' => $this->input->post('row_id'),
            'qty' => 0,
        );
        $this->cart->update($data);
        echo $this->show_cart();
    }

    public function update_cart()
    {
        $diskon = $this->input->post('diskon');
        $qy = $this->input->post('qy');
        $data = array(
            'rowid' => $this->input->post('row_id'),
            'qty' => 0,
        );
        $this->cart->update($data);
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
        $pesan_detail = array();
        $data = array();
        $validation_errors = array();
        $update_tmp = array(); 
        $query_update_tmp = array();
        $query_update_stok = array();
        $old_data="";
        $new_data="";
        $ket_log="";

        if (isset($_POST['barcode'])) {

            $qty_verified = $this->input->post('qty_verified');
            $id_trx = $this->input->post('id_trx');
            // $size = sizeof($barcode);

            $id_detail_obat = $this->input->post('id_detail_obat'); 
            $no_batch = $this->input->post('no_batch'); 
            $no_reg = $this->input->post('no_reg'); 
            $tgl_exp = $this->input->post('tgl_exp');  
            $diskon = $this->input->post('diskon'); 
            $qty = $this->input->post('qty'); 
            $no_faktur = $this->input->post('no_faktur'); 
            $name = $this->input->post('name'); 
            $barcode = $this->input->post('barcode'); 
            $sisa_stok = $this->input->post('sisa_stok'); 
            $stok_awal = $this->input->post('stok_awal'); 

            $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo'); 
            $metode_pembayaran = $this->input->post('metode_pembayaran'); 

            $effectiveDate = $this->get_effectiveDate();   
          
            foreach ($qty_verified as $key =>$value) { 
               
                $this->form_validation->set_rules("qty_verified[".$key."]", "Quality verifikasi <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required");  

                $this->form_validation->set_rules("qty[".$key."]", "Quality <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required"); 

                if ($this->form_validation->run() == FALSE) {
                    $validation_errors = validation_errors();
                } 
                else {  
                        $update_tmp_hasil=false; 
                        if ($qty[$key]<=0 || $qty[$key]=="0")
                        {
                            //get data.
                            $this->db->where('id_trx', $id_trx[$key]);
                            $old_data = $this->db->get('trx_penjualan_tmp')->row_array();
                            $old_data = json_encode($old_data);  
                            $new_data = json_encode(array());   

                            //delete data 
                            $this->db->where('id_trx', $id_trx[$key]);
                            $update_tmp_hasil=$this->db->delete('trx_penjualan_tmp');
                            if ($update_tmp_hasil==true)
                            { 
                            	//insert ke history
                           		 $ket_log="Proses menghapus data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil";

                               $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." berhasil di hapus dari pembelian.</li>";
                            }
                            else
                            {
                            	//insert ke history
                           		 $ket_log="Proses menghapus data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal";

                                 $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." gagal di hapus dari pembelian. <br>".$this->db->last_query()."</li>";
                            }
                        }
                        else
                        {

                           if ( $qty_verified[$key]>$qty[$key]  || $qty_verified[$key]=="" || $qty_verified[$key]<0 || $qty_verified[$key]==0 ||  $qty_verified[$key]==null) {

                           		$pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." gagal di verifikasi, karena qty verified tidak sesuai.</li>";

                           		//insert ke history
           		 				$ket_log="Proses memverifikasi data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal karena qty verified tidak sesuai";

                            } 
                            else{

                                if ($metode_pembayaran=="100" || $metode_pembayaran==100) {
                                    $tgl_jatuh_tempo="0000-00-00 00:00:00";
                                }

                                $data = array(
                                    'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
                                    'kode_pembayaran' => $metode_pembayaran,
                                    'id_trx' => $id_trx[$key],
                                    'id_trx' => $id_trx[$key],
                                    'qty_verified' => $qty_verified[$key],
                                    'qty' => $qty[$key], 
                                    'sisa_stok' => $sisa_stok[$key] - $qty[$key],
                                    // 'sisa_stok' => $stok_awal[$key] - $qty[$key],
                                    'diskon_per_item' => str_replace(",", "", $diskon[$key]),
                                    // 'tgl_exp' => $tgl_exp[$key],
                                    // 'no_batch' => $no_batch[$key],
                                    // 'no_reg' => $no_reg[$key], 
                                    'id_user_verifikasi' => $this->session->userdata('username'),
                                ); 

                                $this->db->where('id_trx', $id_trx[$key]);  
                                $update_tmp_hasil=$this->db->update('trx_penjualan_tmp', $data);

                                if ($update_tmp_hasil==true)
                                {
                                    $update_tmp[]=$update_tmp_hasil; 
                                    # code...

                                     //update stok 
                                    $where = array(
                                        'no_reg' => $no_reg[$key], 
                                        'no_batch' => $no_batch[$key], 
                                        'barcode' => $barcode[$key], 
                                    );
                                    $this->db->where($where);  
                                    $data_obat = $this->db->get('detail_obat');

                                    //check apakah detail oat exixting apa tidak 
                                    if ($data_obat->num_rows()>0)
                                    {
                                        $data_obat = $data_obat->row_array(); 

                                        // $terjual = $data_obat['terjual'] + $qty[$key];
                                        // if ($terjual<=$data_obat['stok_awal'])
                                        // {
                                            $where = array(
                                                'no_reg' => $no_reg[$key], 
                                                'no_batch' => $no_batch[$key], 
                                                'barcode' => $barcode[$key], 
                                            );  
                                            
                                             $data2 = array(
                                                'sisa_stok' => $sisa_stok[$key], 
                                                'terjual' => $data_obat['terjual'] + $qty[$key]+0, 
                                            );
                                            $this->db->where($where);  
                                            $update_stok2=$this->db->update('detail_obat', $data2);
                                            if ($update_stok2==true)
                                            {
                                            	//insert ke history
                           		 				$ket_log="Proses memverifikasi data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil";

                                                 $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." berhasil di verifikasi.</li>";
                                            }
                                            else
                                            {

                                            	//insert ke history
                           		 				$ket_log="Proses memverifikasi data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal";

                                                $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." gagal di verifikasi. <br>".$this->db->last_query()."</li>";
                                            }  
                                    } 
                                    else{

                                    	//insert ke history
                           		 		$ket_log="Proses memverifikasi data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal, karena data tidak ada di pembelian";

                                        $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." tidak ada di pembelian</li>";
                                    } 
                                }
                                else{

                                	$ket_log="Proses memverifikasi data pembelian faktur ".$no_faktur." (Barcode : ". $barcode[$key].", No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal";

                                    $pesan_detail[]= "<li>".$name[$key]." dengan No. batch ".$no_batch[$key].",Nomor Reg ".$no_reg[$key]." gagal di verifikasi. <br>".$this->db->last_query()."</li>";
                                }
                            }
                                
                        } 

                    
                    $this->M_log->tambah($this->session->userdata['id'], $ket_log);
                }        
            }  

            foreach ($pesan_detail as $key) {
                $pesan.=$key;
            }
             
        } else { 
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat".$pesan_detail;
        }

        $this->cart->destroy();
        $data = array(
            'return' => $return,
            'pesan' => $pesan,   
            'validation_errors' => $validation_errors,  
        );
        // var_dump($data);
        echo  json_encode($data);
    }  

    public function hapus($id)
    {
        $hasil = array();
        $where = array('id_transaksi' => $id );
 
        $hasil['status']=$this->M_trxPenjualan->hapus($where);
        
        if ($hasil['status']==true) {
            $hasil['pesan'] = "Proses hapus data obat berhasil";
        } else {
            $hasil['pesan'] = "Proses hapus data obat berhasil";
        }
        echo json_encode($hasil);
    } 

    public function update_stok_tes()
    {
        $update_stok = array();
        $query = array();

        $data = $this->db->query("SELECT no_faktur, barcode, no_batch, no_reg, sisa_stok, stok_awal, qty, qty_verified from trx_penjualan_tmp WHERE id_user_verifikasi!='' and qty_verified!=0 ORDER BY time ASC")->result_array();
        foreach ($data as $key) {
           //update stok 
            $where = array(
                'no_reg' => $key['no_reg'], 
                'no_batch' => $key['no_batch'], 
                'barcode' => $key['barcode'], 
            );
            $this->db->where($where);  
            $data_obat = $this->db->get('detail_obat');
            if ($data_obat->num_rows()>0)
            {
                $data_obat = $data_obat->row_array(); 

                 $terjual = $data_obat['terjual'] + $key['qty'];
                if ($terjual<=$data_obat['stok_awal'])
                {
                    $where = array(
                        'no_reg' => $key['no_reg'], 
                        'no_batch' => $key['no_batch'], 
                        'barcode' => $key['barcode'], 
                    );  

                     $data2 = array(
                        'sisa_stok' => $key['sisa_stok'], 
                        'terjual' => $data_obat['terjual'] + $key['qty'], 
                    );
                    $this->db->where($where);  
                    $hasil=$this->db->update('detail_obat', $data2);
                    echo "<li>".$this->db->last_query()."(".$hasil.")</li>";
                }  
            }   
        }

        $data = array(
            'update_stok' => $update_stok, 
            'query' => $query, 
        );   
    }
}
