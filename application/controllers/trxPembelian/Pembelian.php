<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembelian extends CI_Controller
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
        $data['title'] = "Transaksi pembelian";
        $data['url'] = "http://localhost/nusramed.com2/transaksi/Pembelian/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Transaksi pembelian",
             'link' => base_url()."transaksi/Pembelian",
             'status' => "active",
            ),
        );  

        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();
       

        $this->load->view('include2/sidebar', $data);
        $this->load->view('transaksiPembelian/index', $data);
        $this->load->view('include2/footer');
    }

    public function get_kandungan()
    {
        $id_obat = $this->input->post('id_obat', true);

        $this->db->select('kandungan, nama');
        $this->db->where('id_obat', $id_obat);
        $data= $this->db->get('obat')->row_array();
        echo json_encode($data);
    }

    public function get_data_obat()
    { 
        $data= $this->db->query('SELECT *  from form_trx_pembelian_obat2')->result();  
        echo json_encode($data); 
    }

    public function get_data_suplier()
    { 
        $data = $this->db->query('SELECT kd_suplier as value, nama_aja as label from form_trx_pembelian_suplier')->result();  
        echo json_encode($data); 
    }

      public function get_barang_by_barcode2($barcode)
    {
        $where = array('barcode' => $barcode );
        $data['obat']=$this->M_obat->getBy($where); 
        if ($data['obat']==null)
        {
            echo "Data Barang tidak ditemukan";
        } 
        else 
        {
            $last = count($this->cart->contents());
            $data['id']= $last+1;
            $this->add_to_cart($data); 
        }
    }

    public function get_barang_by_barcode($id_obat)
    {
        $where = array('id_obat' => $id_obat );
        $data['obat']=$this->M_obat->getBy($where);
        if ($data['obat']==null)
        {
            echo "Data Barang tidak ditemukan";
        } 
        else 
        {
            $last = count($this->cart->contents());
            $data['id']= $last+1;
            $this->add_to_cart($data); 
        }
    }

    public function add_to_cart($data)
    {
        //get nama satuan 
        $kd_satuan = $data['obat']['kd_satuan'];
        $this->db->where('kd_satuan', $kd_satuan);
        $nm_satuan = $this->db->get('satuan')->row_array()['nm_satuan']; 

        $tgl_exp = $this->get_effectiveDate(); 

        $this->cart->product_name_rules = '[:print:]';
        $data2 = array(
           'id' => $data['id'],
            'name' => $data['obat']['nama'],
            'price' => "0",
            'no_batch'=>"",
            'barcode' => $data['obat']['barcode'],
            'id_obat' => $data['obat']['id_obat'],
            'no_reg' => "",
            'tgl_exp' => $tgl_exp,
            // 'tgl_exp_full' => date('Y-m-d'),
            'diskon' => '0',
            'ppn' => '10',
            'lokasi' => '',
            'satuan' => $nm_satuan,
            'stok_awal' => "",
            'qty' => 1,
            'jenis_cart'=>'pembelian',
            "options"=>array('jenis'=>"pembelian"),
        );
        $this->cart->insert($data2);
        echo $this->show_cart();
    }

    public function show_cart()
    {
        $output = '';
        $no = 0;
        $effectiveDate = $this->get_effectiveDate();
        foreach ($this->cart->contents() as $items) {
            if(isset($items['jenis_cart'])){
                if ($items['jenis_cart']=='pembelian') { 
                    
                    $jumlah_diskon = ($items['subtotal'])-($items['subtotal']-$items['subtotal']*((int)$items['diskon']/100));
                    $no++;
                $output .='
                    <tr data-id="'.$items['rowid'].'" onchange="kalkulasiDiskonPerItem(this)">
                        <td>'.$no.'</td>
                        <td> 
                        <input type="hidden" class="form-control" id="barcode_item" name="barcode[]" value="'.$items['barcode'].'" >

                        <input type="hidden" class="form-control" id="id_obat" name="id_obat[]" value="'.$items['id_obat'].'" >

                        <input type="hidden" class="form-control" id="name" name="name[]" value="'.$items['name'].'" >

                        <input type="hidden" class="form-control" id="satuan" name="satuan[]" value="'.$items['satuan'].'" >
                        '.$items['barcode'] ." - <br>".$items['name'].'</td>
                        <td>'.$items['satuan'].'</td>  

                        <td><input type="text" class="form-control no_batch" name="no_batch[]" id="no_batch" value="'.$items['no_batch'].'" ></td>

                         <td><input type="text" class="form-control no_reg" name="no_reg[]" value="'.$items['no_reg'].'" ></td> 

                         <td><input type="date" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" min="'.$effectiveDate.'"></td> 
                        
                        <td><input type="text" placeholder="1.0" step="0.01" min="0" max="10000000" class="form-control harga" name="harga[]" value="'.$items['price'].'" min="1"></td>

                        <td><input type="text" placeholder="1.00" step="0.01" min="0" max="100" class="form-control diskon" name="diskon[]" min="0" value="'.$items['diskon'].'" min="1"></td>

                        <td><b class="nilai_ppn">'.number_format(($items['ppn']/100)*($items['subtotal']-($items['subtotal']*($items['diskon']/100))),2).'</b>
                        </td> 

                        <td>  
                        <input type="number" class="form-control stok_awal" name="stok_awal[]" value="'.$items['qty'].'" min="1" required>

                        <input type="hidden" class="form-control harga_setelah_diskon" name="harga_setelah_diskon[]" value="'.$jumlah_diskon.'"> 

                        <td><p class="subtotal">'.number_format($items['subtotal']-($items['subtotal']*($items['diskon']/100)), 2).' 
                        </p></td>   
                        <td>  
                        <input type="text" class="form-control lokasi" name="lokasi[]" value="'.$items['lokasi'].'" ></td>

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

    public function get_grand_total()
    {
        $ppn =0;
        $diskon=0;
        $total=0;
        // $cart = ;
        // var_dump($this->cart->contents());
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if($key['jenis_cart']=="pembelian"){
                $ppn+=((int)$key['ppn']/100)*($key['subtotal']-((int)$key['diskon']/100)*$key['subtotal']);
                $diskon+=($key['diskon']/100)*$key['subtotal'];
                $total +=$key['price']*$key['qty'];
                
            }
        }
            // echo $diskon. "\n";
        } 
        echo number_format(($total+$ppn)-$diskon);
    }


    public function update_cart_onchange()
    {
        if ($this->cart->update($_POST)) {
           
            echo "berhasil";
        } else {
            echo "gagal";
        } 
    }

    public function reload_data()
    {
        $data_balikan['data']= array();
        $data['data']= $this->M_trxPenjualan->getAll();
        $this->load->view('trxPenjualan/data', $data);
    }

    public function simpan_pembelian()
    { 
        $pesan_detail="";
        $pesan="";
        $input_data="";
        $return=""; 
        $last_query = array();
        $ket_log = array();
        $hasil_query = array();
        if (isset($_POST['id_obat']))
        { 
            // $barcode = $this->input->post('barcode');
            $no_reg = $this->input->post('no_reg');
            $no_batch = $this->input->post('no_batch');
            $tgl_exp = $this->input->post('tgl_exp');
            $stok_awal = $this->input->post('stok_awal');
            $no_faktur = $this->input->post('no_faktur');
            $kd_suplier = $this->input->post('kd_suplier');
            $kode_pembayaran = $this->input->post('kode_pembayaran');
            // $harga_jual = $this->input->post('harga_jual');
            $harga_beli = $this->input->post('harga');
            $diskon_beli = $this->input->post('diskon');
            $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo');
            $harga_setelah_diskon = $this->input->post('harga_setelah_diskon');
            $lokasi = $this->input->post('lokasi');
            $ppn = $this->input->post('ppn');
            $name = $this->input->post('name');
            $id_obat = $this->input->post('id_obat');
            $tgl_faktur = $this->input->post('tgl_faktur');

            // $size = sizeof($barcode); 
            $data = array(); 
            $effectiveDate = $this->get_effectiveDate();   

            $data2 = array( 
                'ppn' => $ppn, 
                'kd_suplier' => $kd_suplier, 
                'no_faktur' => $no_faktur,  
                'kode_pembayaran' => $kode_pembayaran,  
                'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 
                'tgl_faktur' => $tgl_faktur,  
                'tgl_input' => date('Y-m-d H:m:s'),  
            );    
            // $input_Data = $this->db->insert('master_detail_obat', $data2); 
            $insert_id = $this->M_detail_obat->insert_master_detail($data2);

            foreach($id_obat as $key=>$value)
            {  
                // if ($no_reg[$key]=="" || $no_batch[$key]=="" || $tgl_exp[$key]=="")
                // if ($no_batch[$key]=="" || $tgl_exp[$key]=="")
                // {
                //      $pesan_detail.= "Silahkan isi dengan lengkap nomor registrasi, nomor batch dan tanggal expired pada barang ".$value;
                // }
                // else
                // {

                $data = array(
                    'id_obat' => $id_obat[$key], 
                    'no_reg' => $no_reg[$key], 
                    'no_batch' => $no_batch[$key], 
                    'tgl_exp' => $tgl_exp[$key], 
                    'stok_awal' => $stok_awal[$key], 
                    'harga_setelah_diskon' => $harga_setelah_diskon[$key],  
                    'sisa_stok' => $stok_awal[$key],   
                    'harga_beli' => $harga_beli[$key], 
                    'diskon_beli' => $diskon_beli[$key], 
                    'lokasi' => $lokasi[$key], 
                    'id_master_detail' => $insert_id, 
                    // 'qty_verified' => "0000-00-00 00:00:00", 
                    'id_user' => $this->session->userdata('username'), 
                );   

                if ($no_batch[$key]=="" || $stok_awal[$key]=="" ||  $harga_beli[$key]=="" || $harga_beli[$key]=="0" || $stok_awal[$key]=="0")
                {
                    $pesan_detail.= "<li>Nomor Batch, harga atau quality  obat <b>".$value."-".$name[$key]."</b> tidak boleh kosong atau tidak valid</li> ";  

                   /* $ket_log[]['data'] = $data;
                    $ket_log[]['ket'] = "Proses input pembelian obat ".$value."-".$name[$key]." gagal karena Nomor Batch, harga atau quality  obat kosong atau tidak valid ."; */
                }
                else if (($tgl_exp[$key] < $effectiveDate) || $tgl_exp[$key]==null) {

                    $pesan_detail.= "<li>Tanggal Expired obat <b>".$value."-".$name[$key]."</b> Harus >= ".$effectiveDate."(".$tgl_exp[$key]."<".$effectiveDate.") , atau tidak boleh di kosongkan</li> "; 

                  /*  $ket_log[]['data'] = $data;
                    $ket_log[]['ket'] = "Proses input pembelian obat ".$value."-".$name[$key]." gagal karena Tanggal Expired obat kurang dari 8 bulan atau Tanggal expired tidak di diisi."; */ 
                }
                else
                { 
                    // $input_Data = $this->db->insert('detail_obat', $data); 
                    $input_data=  $this->M_detail_obat->add($data);
                    if ($input_data==true)
                    {
                        $pesan_detail.= "<li>".$value."-".$name[$key]." berhasil di input </li> ";

                        //masukkin data log 
                        /*$ket_log[]['data'] = $data;
                        $ket_log[]['ket'] = "Proses input pembelian ".$name[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil.";   */
                    }
                    else
                    {
                        $pesan_detail.= "<li>".$value."-".$name[$key]." gagal di input </li> ";

                       /* $ket_log[]['data'] = $data;
                        $ket_log[]['ket'] = "Proses input pembelian ".$name[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") gagal."; */
                    }
                    $hasil_query[] =$input_data; 
                    $last_query[] =$this->db->last_query(); 
                } 
            }  

            $keterangan_log=""; 
            if ($input_data>0)
            {
                $return=1;

                //masukan ke log     
                $keterangan_log="Proses pembelian nomor faktur  ".$no_faktur." Berhasil.";

                $pesan= "Proses simpan transaksi pembelian berhasil, <br>".$pesan_detail;

                $pesan_sweet_alert = "Proses simpan transaksi pembelian berhasil";
            }
            else
            {
                $return=0; 

                $keterangan_log="Proses pembelian nomor faktur  ".$no_faktur." gagal.";

                $pesan= "Proses simpan transaksi pembelian gagal, <br>".$pesan_detail;
                $pesan_sweet_alert = "Proses simpan transaksi pembelian gagal";
            }   

            // $id_log = $this->M_log->tambah_2($this->session->userdata['id'], $keterangan_log);

           /* $data_detail = array(
                'id_log' => $id_log, 
                'data_lama' => null, 
                'data_baru' => json_encode($ket_log), 
            );
            $this->M_log->tambah_detail($data_detail); */
        }
        else
        {
            $return=0;  
            $pesan_sweet_alert= "barang tidak boleh boleh kosong, silahkan masukkan item barang kedalam cart ";
            $pesan=$pesan_sweet_alert;
        }

        //DESTROY CART  
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="pembelian") {
                $this->cart->remove($key['rowid']);
            }}
        }

        $data2 = array(
            'return' => $return, 
            'pesan' => $pesan,      
            'pesan_sweet_alert' => $pesan_sweet_alert,      
            'last_query' => $last_query,      
            // '_POST' => $_POST,      
        ); 
        echo json_encode($data2); 
    }

    public function printTrxPembelian($no_faktur)
    {  
        $this->db->where('no_faktur', $no_faktur); 
        $query_kedua = $this->db->get('master_detail_obat')->row_array();

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
        $id_master_detail = $query_kedua['id_master_detail'];

        $data['data']= $this->db->query("SELECT B.barcode, A.id_user, A.tgl_exp,  A.id_obat, A.no_reg, A.lokasi, A.harga_beli, A.diskon_beli, A.stok_awal, A.no_batch, B.nama FROM detail_obat A INNER JOIN obat B WHERE A.id_obat = B.id_obat AND A.id_master_detail=$id_master_detail")->result_array();

        // var_dump($this->db->last_query());  
        $data['no_faktur'] =$no_faktur;
        $data['data_row'] =$query_kedua;

        $this->load->view('transaksiPembelian/printTrxPembelian', $data);   
    }

    public function edit($id_transaksi)
    {
        $data['title'] = "Penjulan";
        $data['url'] = "transaksi/Pembelian/";
        $where = array('id_transaksi' => $id_transaksi );
        $data['data']=$this->M_trxPenjualan->getBy($where);
        $data['id_transaksi']=$id_transaksi;
        if ($id_transaksi==null) {
            $data['jenis_aksi']="add";
        } else {
            $data['jenis_aksi']="edit";
        }
 
        $data['url_inquery']="trxPenjualan/inquery";
        $data['industri'] = $this->Industri_model->getAll();
        $data['suplier'] = $this->Suplier_model->getAll();
        $data['kategori_obat'] = $this->Kategori_obat->getAll();
        $data['satuan'] = $this->Satuan_obat->getAll();
        $data['kategori'] = $this->Kategori->getAll();

        $this->load->view('include/header');
        $this->load->view('trxPenjualan/form', $data);
        $this->load->view('include/footer');
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

    public function get_effectiveDate()
    {
        $effectiveDate = date('Y-m-d');  
        $effectiveDate = date('Y-m-d', strtotime("+8 months", strtotime($effectiveDate))); 
        return $effectiveDate;
    }

    public function testing()
    {
        $this->load->view('transaksiPembelian/testing');
    }
}
