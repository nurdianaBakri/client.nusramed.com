<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan extends CI_Controller
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
        $data['title'] = "Transaksi Penjualan";
        $data['url'] = "transaksi/Penjualan/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Transaksi Penjualan",
             'link' => base_url()."transaksi/Penjualan",
             'status' => "active",
            ),
        );

        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();
        $data['outlet'] = $this->M_outlet->getAll(); 
        $data['Obat'] = $this->db->query("CALL obat_fitur_penjualan()")->result_array();

        // var_dump($data_obat);
        // if ($data_obat->num_rows()>0)
        // {
        //     $data['Obat']  = array();
        // }
        // else
        // {
        //      $data['Obat'] = $data_obat->result_array();
        // }
        
        $original_string = '1234567890';
        $data['no_faktur'] = $this->M_trxPenjualan->get_random_string($original_string, 4);
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('transaksiPenjualan/index', $data);
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

    //original
    public function get_barang_by_barcode2($barcode)
    {
        $where = array('barcode' => $barcode );
        $data['obat']=$this->M_obat->getBy($where);
        if ($data['obat']==null) {
            echo "Data Barang tidak ditemukan";
        } else {
            $cek=$this->M_detail_obat->get_last_exp($barcode);
            $num_rows = 0;
            if ($cek->num_rows()>0) {
                $data['data']=$cek->row_array();

                //check if qty sudah melebihi
                $qty=1;
                $last_exp="";
                // echo $this->db->last_query();
                foreach ($this->cart->contents() as $items) {
                    if ($items['id']==$data['data']['no_batch']) {
                        $qty =  $items['qty'];
                        $last_exp =  $items['tgl_exp_full'];
                    }
                }

                //jika stok memenuhi
                if ($qty<$data['data']['sisa_stok']+0 || $qty+1==$data['data']['sisa_stok']) {
                    //tambah ke kart
                    $this->add_to_cart($data);
                } else {
                    //jika stok tidak memenuhi
                    echo "Stok Tidak Mencukupi";
                }
                // $this->load->view('transaksiPenjualan/add_row',  $data);
            } else {
                echo "Stok Barang $barcode kosong silahkan tambahkan stok melalui menu transaksi pembelian";
            }
        }
    }

    public function get_barang_by_barcode($barcode)
    {
        $where = array('barcode' => $barcode );
        $data['obat']=$this->M_obat->getBy($where);
        if ($data['obat']==null) {
            echo "Data Barang tidak ditemukan";
        } else {

            //cek apakah barcode ada di table detail obat
            $this->db->where($where);
            $cek2 = $this->db->get('detail_obat');
            if ($cek2->num_rows()>0) {
                $cek="";
                if (count($this->cart->contents())>0) {
                    foreach ($this->cart->contents() as $items) {
                        if ($items['jenis_cart']=='penjualan') {
                            if ($items['barcode']==$barcode) {
                                $cek=$this->M_detail_obat->get_last_exp($barcode, $items['tgl_exp_full'], $items);
                            // var_dump($cek);
                            } else {
                                $cek=$this->M_detail_obat->get_last_exp($barcode);
                            }
                        }else{
                            $cek=$this->M_detail_obat->get_last_exp($barcode);
                        }
                        // var_dump($cek);
                    }
                } else {
                    $cek=$this->M_detail_obat->get_last_exp($barcode);
                }
                // var_dump(count($this->cart->contents()));
                
                // var_dump($cek->row_array());
                // var_dump($this->db->last_query());

                $num_rows = 0;
                if ($cek->num_rows()>0) {
                    $data['data']=$cek->row_array();
                    //check if qty sudah melebihi
                    $qty=1;
                    $last_exp="";
                    // echo $this->db->last_query();
                    foreach ($this->cart->contents() as $items) {
                        if($items['jenis_cart']=="penjualan"){
                            if ($items['id']==$data['data']['no_batch']) {
                                $qty =  $items['qty'];
                                $last_exp =  $items['tgl_exp_full'];
                            }
                        }
                    }
                    // var_dump ($this->cart->contents());
                    //jika stok memenuhi
                    if ($data['data']['harga_jual']<1)
                    {
                    	echo "Harga jual barang belum diset silahkan set harga jual";
                    }
                    else if ($qty=$data['data']['sisa_stok']+0 || $qty+1==$data['data']['sisa_stok']) {
                        //tambah ke kart
                     // echo ($this->db->last_query());
                        $this->add_to_cart($data);
                        
                    // echo "1";
                    } 
                    else if ($qty=$data['data']['sisa_stok']) {
                        $this->add_to_cart($data);
                    }
                    else {
                        //jika stok tidak memenuhi
                        // echo ($this->db->last_query());
                        // echo $qty."<".$data['data']['sisa_stok'];
                        // echo "||". $qty."+1==".$data['data']['sisa_stok'];
                        // var_dump($qty<$data['data']['sisa_stok']+0 || $qty+1==$data['data']['sisa_stok']);
                        echo "Stok Tidak Mencukupi";
                    }
                    // $this->load->view('transaksiPenjualan/add_row',  $data);
                } else {
                     echo ($this->db->last_query());
                    echo "Stok Barang $barcode kosong silahkan tambahkan stok melalui menu transaksi pembelian 2";
                }
            } else {
                echo "Stok Barang $barcode kosong silahkan tambahkan stok melalui menu transaksi pembelian";
            }
        }
    }

    public function add_to_cart($data)
    {
        $tgl_exp_full = $data['data']['tgl_exp'];

        $data['data']['tgl_exp'] = strtotime($data['data']['tgl_exp']);
        $tgl_exp =  date('d M Y', $data['data']['tgl_exp']);

        //get nama satuan 
        $kd_satuan = $data['obat']['kd_satuan'];
        $this->db->where('kd_satuan', $kd_satuan);
        $nm_satuan = $this->db->get('satuan')->row_array()['nm_satuan'];

        $this->cart->product_name_rules = '[:print:]';
        $data2 = array(
            'id' => $data['data']['no_batch'],
            'name' => $data['obat']['nama'],
            'price' => $data['data']['harga_jual'],
            'barcode' => $data['data']['barcode'],
            'no_reg' => $data['data']['no_reg'],
            'tgl_exp' => $tgl_exp,
            'tgl_exp_full' => $tgl_exp_full,
            'diskon' => '0',
            'satuan' => $nm_satuan,
            'stok_awal' => $data['data']['sisa_stok'],
            'qty' => 1,
            'ppn' => 10,
            'kosong' => 0,
            'jenis_cart'=>'penjualan',
            "options"=>array('jenis'=>"penjualan"),
            // 'subtotal' => 0,
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
            if($items['jenis_cart']=='penjualan'){
                $jumlah_diskon = ($items['subtotal'])-($items['subtotal']-$items['subtotal']*((int)$items['diskon']/100));

                $nilai_ppn = number_format(($items['ppn']/100)*($items['subtotal']-($items['subtotal']*($items['diskon']/100))),2);

                $no++;
                $output .='
                    <tr id="row-data" data-id="'.$items['rowid'].'" onchange="kalkulasiDiskonPerItem(this)">
                        <td>'.$no.'</td>
                        <td><input type="hidden" class="form-control" id="barcode_item" name="barcode[]" value="'.$items['barcode'].'" >'.$items['barcode'].'-<br>'.$items['name'].'</td>
     
                        <input type="hidden" class="form-control no_batch" name="no_batch[]" value="'.$items['id'].'" >'.$items['id'].'
                        <td>'.$items['satuan'].'</td> 
                        <td>'.$items['id'].'</td> 
    
                         <td><input type="hidden" class="form-control no_reg" name="no_reg[]" value="'.$items['no_reg'].'" >'.$items['no_reg'].'</td>
    
                        <td><input type="hidden" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" >'.$items['tgl_exp'].'</td>
    
                        <td><input type="hidden" class="form-control harga" name="harga[]" value="'.$items['price'].'" >'.number_format($items['price'],2).'</td> 
                        <td> 

                        <input type="hidden" class="form-control ppn_value" name="ppn[]" min="0" value="'.$nilai_ppn.'" readonly><b id="ppn_value">'.$nilai_ppn.'</b></td>
                        <td><input type="number" class="form-control diskon" name="diskon[]" min="0" value="'.$items['diskon'].'" ></td>
    
                        <td>'.'<input type="number" class="form-control qty" name="qty[]" min="1" value="'.$items['qty'].'" ></td>
    
                        <td>'.'<input type="number" class="form-control kosong" name="kosong[]" min="0" value="'.$items['kosong'].'" ></td>
    
                        <td><span class="money subtotal">'.number_format($items['subtotal']-$items['subtotal']*($items['diskon']/100),2).'</span>
    
                        <input type="hidden" class="form-control stok_awal" name="stok_awal[]" value="'.$items['stok_awal'].'" >
    
                        <input type="hidden" class="form-control harga_setelah_diskon" name="harga_setelah_diskon[]" value="'.$jumlah_diskon.'">                     
                        <input type="hidden" class="form-control tgl_exp_full" name="tgl_exp_full[]" value="'.$items['tgl_exp_full'].'" >                     
                        <input type="hidden" class="form-control tgl_exp" name="tgl_exp[]" value="'.$items['tgl_exp'].'" >                     
    
                        <td><button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i></button> 
                        </td>
                    </tr>
                ';
            }else{
                // var_dump($this->cart->contents());
            }
        }
        }
        return $output;
    }

    public function get_grand_total()
    {
        $ppn =0;
        $total_ppn =0;
        $diskon=0;
        $total=0;
        $cart = $this->cart->contents();
        // var_dump($cart);
        foreach ($cart as $key) {
            if(isset($key['jenis_cart'])){
	            if($key['jenis_cart']=="penjualan"){
	                $ppn+=((float)$key['ppn']/100)*($key['subtotal']-((float)$key['diskon']/100)*$key['subtotal']);
	                $diskon+=($key['diskon']/100)*$key['subtotal'];
	                $total +=$key['price']*$key['qty'];

	                $total_ppn=$key['ppn']; 
	            }
        	}
        } 

        $data = array(
        	'ppn' => $total_ppn, 
        	'grand_total' => number_format(($total+$ppn)-$diskon,2), 
        ); 
        echo json_encode($data); 
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
    public function update_cart_onchange()
    {
        // $diskon = $this->input->post('data');
        // $_POST['subtotal']=90000;
        if ($this->cart->update($_POST)) {
            // var_dump($_POST);
            // var_dump($this->cart->contents());
            echo "berhasil";
        } else {
            echo "gagal";
        }
        // echo $this->show_cart();
    }
     
    public function reload_data()
    {
        $data_balikan['data']= array();
        $data['data']= $this->M_trxPenjualan->getAll();
        $this->load->view('trxPenjualan/data', $data);
    }

    public function simpan_penjualan()
    {
        $pesan="";
        $return="";
        $data = array();
        $data_kosong=array();

        if (isset($_POST['barcode'])) {
            $no_faktur = $this->input->post('no_faktur');
            $kode_pembayaran = $this->input->post('kode_pembayaran');
            $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo');
            $total = $this->input->post('total');
            $stok_awal = $this->input->post('stok_awal');
            $diskon = $this->input->post('diskon');
            $kd_outlet = $this->input->post('kd_outlet');
            $qty = $this->input->post('qty');
            $barcode = $this->input->post('barcode');
            $no_batch = $this->input->post('no_batch');
            $harga = $this->input->post('harga');
            $tgl_exp_full = $this->input->post('tgl_exp_full');
            $tgl_exp = $this->input->post('tgl_exp');
            $ppn = $this->input->post('ppn');
            $kosong = $this->input->post('kosong');
            $no_reg = $this->input->post('no_reg');
            $harga_setelah_diskon = $this->input->post('harga_setelah_diskon');
            // $size = sizeof($barcode);
          
            foreach ($stok_awal as $key =>$value) {

                if ($qty[$key]=="") {
                    $qty[$key]=0;
                }
                 if ($diskon[$key]=="") {
                    $diskon[$key]=0;
                }

                $data[] = array(
                    'barcode' => $barcode[$key],
                    'no_batch' => $no_batch[$key],
                    'stok_awal' => $stok_awal[$key],
                    'kd_outlet' => $kd_outlet,
                    'no_faktur' => $no_faktur,
                    'qty' => $qty[$key],
                    'sisa_stok' => $value - $qty[$key],
                    'kode_pembayaran' => $kode_pembayaran,
                    'harga_jual' => str_replace(",", "", $harga[$key]),
                    'diskon_per_item' => str_replace(",", "",$diskon[$key]),
                    'harga_setelah_diskon' => str_replace(",", "",$harga_setelah_diskon[$key]),
                    'no_reg' => $no_reg[$key],
                    'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
                    'ppn' => str_replace(",", "",$ppn),
                    'tgl_exp' => $tgl_exp_full[$key],
                    'id_user_input' => $this->session->userdata('username'),
                );
            };
            // var_dump($_POST);
            $barcode_length=count(array_keys($barcode));
            $tmp_barcode='';
            $tmp_kosong=0;           
            $barcode[]='qwertyuiop';
            // var_dump($barcode);
            // echo "panjang ". count(array_keys($barcode));
            foreach($barcode as $key =>$value) {                
                if($tmp_barcode!=$value){                
                    if(!isset($barcode[$key+1])&&$barcode_length!=1){
                        // $tmp_barcode=$barcode[$key];
                        $data_kosong[] = array(
                        'barcode' => $tmp_barcode,
                        'no_faktur' => $no_faktur,
                        'jumlah_kosong'=> $tmp_kosong
                        );

                        // echo "\ntidak sama akhir + kosong = ".$tmp_kosong;
                    }elseif(isset($barcode[$key+1])){
                        $tmp_kosong=$kosong[$key];
                        $tmp_barcode=$value;
                        if($barcode[$key+1]!=$value){
                            $data_kosong[] = array(
                        'barcode' => $barcode[$key],
                        'no_faktur' => $no_faktur,
                        'jumlah_kosong'=> $tmp_kosong
                        );
                        $tmp_kosong= $kosong[$key];
                        // echo "\ntidak sama tengah + kosong = ".$tmp_kosong;

                        }else{            
                            $tmp_kosong= $kosong[$key];            
                        // echo "\ntidak sama awal + kosong = ".$tmp_kosong;
                        }
                    }
                }elseif ($tmp_barcode==$value) {
                   $tmp_kosong+= $kosong[$key];   
                    // echo "\nsama + kosong = ".$tmp_kosong;                 
                }
                // echo "temp barcode =" .$tmp_barcode;
                
            };
            // var_dump($data_kosong);
            // try the select.
            $return=$this->db->insert_batch('trx_penjualan_tmp', $data);
            if ($return>0) {                
                if (!empty($data_kosong)) {
                    $return=$this->db->insert_batch('obat_kosong', $data_kosong);
                    if ($return) {
                        $return=1;
                        $pesan= "Proses simpan transaksi penjualan berhasil ";
                    } else {
                        $return=0;
                        $pesan= "Proses simpan data ksosong gagal, Transaksi Berhasil Disimpan ";
                    }
                }else{
                    $return=1;
                    $pesan= "Proses simpan transaksi penjualan berhasil ";
                }
            } else {
                $return=0;
                $pesan= "Proses simpan transaksi penjualan gagal ";
            }
        } else {
            $return=0;
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        }


        $this->cart->destroy();
        $data = array(
            'return' => $return,
            'pesan' => $pesan,
        );
        // var_dump($data);
        echo  json_encode($data);
    }

    public function edit($id_transaksi)
    {
        $data['title'] = "Penjulan";
        $data['url'] = "transaksi/Penjualan/";
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
    public function notaPenjualan($no_faktur='68620034634',$kode_outlet='OUT-210220-499712697'){
        $data['invoice'] = $this->db->query("SELECT a.*,c.nama, b.nm_satuan FROM trx_penjualan_tmp a, obat c, satuan b where a.barcode=c.barcode and c.kd_satuan=b.kd_satuan and a.no_faktur=".$no_faktur)->result();
        $data['outlet'] = $this->db->query("SELECT a.nama,a.npwp,a.alamat,a.id_outlet FROM outlet1 a where a.id_outlet='".$kode_outlet."'")->row();
        $data['nota_kosong'] = $this->db->query("SELECT a.*,b.nama, c.nm_satuan FROM obat_kosong a, obat b, satuan c WHERE a.barcode=b.barcode and b.kd_satuan=c.kd_satuan and no_faktur=".$no_faktur)->result();
        $data['jumlah_kosong'] = $this->db->query("select SUM(jumlah_kosong) as jumlah_kosong FROM obat_kosong where no_faktur=".$no_faktur)->row()->jumlah_kosong;
        $data['no_faktur'] = $no_faktur;

        $this->db->where('no_faktur', $no_faktur); 
        $query_kedua = $this->db->get('trx_penjualan_tmp')->row_array();

        if ($query_kedua['tgl_jatuh_tempo']=='0000-00-00 00:00:00')
        {
            $data['tgl_jatuh_tempo']="COD";
        }  
        $this->load->view('invoice/nota-penjualan',$data);  
    }

    public function load_ppn(){   
        $ppn=0;     
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if($key['jenis_cart']=="penjualan")
            $ppn=$key['ppn'];
        };
    };
        echo $ppn;
        // var_dump($this->cart->contents());
    }

    public function get_no_fakt()
    {
        $original_string = '1234567890';
        $data['no_faktur'] = $this->M_trxPenjualan->get_random_string($original_string, 4);
        echo $data['no_faktur'];
    }
}
