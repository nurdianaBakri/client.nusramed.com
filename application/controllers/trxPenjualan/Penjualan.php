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
        $data['url'] = base_url()."trxPenjualan/Penjualan/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Transaksi Penjualan",
             'link' => base_url().base_url()."trxPenjualan/Penjualan",
             'status' => "active",
            ),
        );

        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();
        $data['outlet'] = $this->M_outlet->getAll();  

        $original_string = '1234567890';
        $data['no_faktur'] = $this->M_trxPenjualan->get_random_string($original_string, 4);

        // var_dump($this->db->last_query()); 
        $data['Obat'] = $this->db->query("CALL obat_fitur_penjualan()")->result_array();
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('transaksiPenjualan/index', $data);
        $this->load->view('include2/footer');
    }

    public function get_data_obat()
    { 
        $data= $this->db->query('SELECT *  from form_trx_pembelian_obat2')->result();  
        echo json_encode($data); 
    } 

  /*  public function get_kandungan()
    {
        $barcode = $this->input->post('barcode');
        $this->db->select('kandungan, nama');
        $this->db->where('barcode', $barcode);
        $data= $this->db->get('obat')->row_array();
        echo json_encode($data);
    }*/

    public function delete_cart()
    { 
        $data = array(
            'rowid' => $this->input->post('id'),
            'qty' => 0,
        );
        $this->cart->update($data); 
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

    
    public function get_barang_by_barcode()
    {
        $hasil = array();
        $qty_valid = 0;
        $id_obat = $this->input->post('theHiddenBarcode', TRUE);
        $qty = $this->input->post('qty', TRUE); 
        $no_faktur = $this->input->post('no_faktur', TRUE);
        $jenis_aksi = $this->input->post('jenis_aksi', TRUE);
        $kd_outlet = $this->input->post('kd_outlet', TRUE);
        $kode_pembayaran = $this->input->post('kode_pembayaran', TRUE);
        $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo', TRUE);
        $ppn = $this->input->post('ppn', TRUE); 
        $id_master_trx="";


        //check if transaksi exixting
        $this->db->where('no_faktur', $no_faktur);
        $this->db->where('kd_outlet', $kd_outlet);
        $this->db->where('kode_pembayaran', $kode_pembayaran);
        $this->db->where('tgl_jatuh_tempo', $tgl_jatuh_tempo);
        $this->db->where('ppn', $ppn);
        $check = $this->db->get('master_trx_penjualan_tmp');
        if ($check->num_rows()>0)
        {
            //get master_trx_penjualan_tmp 
            $id_master_trx = $check->row_array()['id_master_trx'];  
        } 
        else
        {
            // tambahkan data master   
            $data_insert = array(
                'no_faktur'=> $no_faktur,
                'kd_outlet'=> $kd_outlet,
                'kode_pembayaran'=> $kode_pembayaran,
                'tgl_jatuh_tempo'=> $tgl_jatuh_tempo,
                'ppn'=> $ppn,
            ); 
            $hasil['status'] = $this->db->insert('master_trx_penjualan_tmp',$data_insert); 
            $id_master_trx = $this->db->insert_id(); 
        } 

        //get satuan
        $data_detail = $this->db->query("SELECT SUM(sisa_stok) as jumlah_sisa_stok, label, nm_satuan FROM kalkulasi_item_pembelian WHERE id_obat=$id_obat AND sisa_stok>0 and tgl_exp >(SELECT CURDATE())");  

        if ($data_detail->num_rows()>0)
        {
            $data_detail = $data_detail->row_array();
            if ($data_detail['jumlah_sisa_stok']<$qty)
            { 
                $hasil['status'] = false; 
                $hasil['pesan']="Sisa stok tidak cukup, sisa stok = ".$data_detail['jumlah_sisa_stok']; 
            }
            else
            {
                $hasil['status'] = $this->insert_tmpKasir($id_master_trx, $id_obat, $ppn, $no_faktur, $qty, $data_detail ); 
                $hasil['pesan']="Berhasil menambah obat";
                $hasil['last_query']=$this->db->last_query();

            }
        }
        else
        {
            $hasil['status'] = false; 
            $hasil['pesan']="sisa stok = 0";  
        }  

        echo json_encode($hasil);
 
    } 

    public function insert_tmpKasir($id_master_trx, $id_obat, $ppn, $no_faktur, $qty, $data)
    {
        //tambah data ke table trx_tmp 
        $data_insert = array(
            'id_master_trx' => $id_master_trx, 
            'id_obat' => $id_obat,  
            'ppn' => $ppn,    
            'no_faktur' => $no_faktur,    
            'label' => $data['label'],     
            'satuan' => $data['nm_satuan'],      
        ); 


        //check if data existing
        $this->db->where($data_insert);
        $check=  $this->db->get('tmp_kasir');  
        if ($check->num_rows()>0)
        { 
            //update qty
            $data_insert['qty'] = $qty;
            $this->db->where('id', $check->row_array()['id']);
            $update = $this->db->update('tmp_kasir', $data_insert);  
            if ($update)
            {
                //hapus data obat existing  
                $delete = $this->db->query("DELETE FROM trx_penjualan_tmp WHERE id_master_trx='".$id_master_trx."' and id_obat=$id_obat");   
            }
        } 
        else
        {
            //insert if not esisting  
            $data_insert['qty'] = $qty;
            $simpan = $this->db->insert('tmp_kasir',$data_insert);  
        }  

        if ($update==true || $simpan==true)
        {
            $_insert = 0;
            $_insert = $qty;

            //MASUKKAN DAN GENERAETE NO_BATCH, NO_REG, DAN TGL_EXP 
            $data_pembelian = $this->db->query("SELECT id_obat, id_detail_obat, sisa_stok, id_master_detail, sisa_stok, harga_jual FROM kalkulasi_item_pembelian WHERE id_obat=$id_obat AND sisa_stok>0 and tgl_exp >(SELECT CURDATE()) ORDER BY tgl_exp asc")->result_array(); 
            foreach ($data_pembelian as $key )
            { 
                $sisa_stok = $key['sisa_stok'];
                if ($sisa_stok>=$_insert)
                {
                    $input_stok = $_insert; 
                    $data_insert_penjulan = array(
                        'id_user_input' => $this->session->userdata('username'), 
                        'harga_jual' => $key['harga_jual'],   
                        'qty' => $input_stok,  
                        'id_obat' => $key['id_obat'],  
                        'id_master_trx' => $id_master_trx,  
                        'id_detail_obat' => $key['id_detail_obat'],  
                    );
                    $this->db->insert('trx_penjualan_tmp', $data_insert_penjulan); 
                    $_insert=0;
                    break; 
                }
                //jika sisa stok ke X tidak cukup, insert data sisa stok dr data pembelian 
                else if ($sisa_stok<$_insert)
                {
                    $input_stok = $sisa_stok; 
                    $data_insert_penjulan = array(
                        'id_user_input' => $this->session->userdata('username'), 
                        'harga_jual' => $key['harga_jual'],   
                        'qty' => $input_stok,  
                        'id_obat' => $key['id_obat'],  
                        'id_master_trx' => $id_master_trx,  
                        'id_detail_obat' => $key['id_detail_obat'],  
                    );
                    $this->db->insert('trx_penjualan_tmp', $data_insert_penjulan); 
                    $_insert=$_insert-$sisa_stok;
                } 
                else if ($_insert==0)
                {
                    break;
                }  
            }  

        }

        return true;
    }

    public function insert_tocart($data)
    { 
        $this->cart->product_name_rules = '[:print:]';
        $data2 = array(
            'id' => $data['id_detail_obat'],  
            'name' => $data['label'], 
            'id_obat' => $data['id_obat'], 
            'id_master_trx' => $data['id_master_trx'], 
            'diskon' => '0',
            'ppn' => $data['ppn'],
            'satuan' => $data['satuan'], 
            'no_batch' => $data['no_batch'], 
            'no_reg' => $data['no_reg'], 
            'qty' => 1, 
            'price' => $data['harga_jual'], 
            'tgl_exp' => $data['tgl_exp'], 
            'kosong' => 0,
            'jenis_cart'=>'penjualan',
            "options"=>array('jenis'=>"penjualan"),
            // 'subtotal' => 0,
        );
       echo $this->cart->insert($data2);  
    }

    public function get_trx_tmp()
    {
        $output  = array();
        $no = 1;
        $total = 0; $ppn = 0; $diskon = 0;

        $no_faktur = $this->input->post('no_faktur');
        $id_master_trx = $this->db->query("SELECT id_master_trx FROM master_trx_penjualan_tmp where no_faktur='$no_faktur'");
        if ($id_master_trx->num_rows()>0)
        {
            $id_master_trx  = $id_master_trx->row_array()['id_master_trx'];
        }
        else
        {
            $id_master_trx= 0;
        } 

        $items2 = $this->db->query("SELECT * FROM tmp_kasir where id_master_trx=$id_master_trx")->result_array();
        foreach ($items2 as $items) { 

            $id_obat = $items['id_obat']; 
            $subtotal_per_obat=0;
            $nilai_ppn_per_obat=0;
            $data_transaksi2 = array();
            $data_transaksi = $this->db->query("SELECT 
                A.id_trx, 
                A.qty, 
                B.no_batch, 
                B.no_reg, 
                DATE( B.tgl_exp) AS tgl_exp, 
                A.harga_jual,
                A.diskon_per_item
                FROM trx_penjualan_tmp A
                LEFT JOIN detail_obat B on B.id_detail_obat = A.id_detail_obat
                where id_master_trx=(SELECT id_master_trx FROM master_trx_penjualan_tmp WHERE no_faktur='".$no_faktur."' AND A.id_obat=$id_obat)")->result_array();

            foreach ($data_transaksi as $key ) 
            { 
                $subtotal = $key['qty']*$key['harga_jual'];
                $jumlah_diskon = ($subtotal)-($subtotal-$subtotal*((int)$key['diskon_per_item']/100));  
                $nilai_ppn = ($items['ppn']/100)*($subtotal-($subtotal*($key['diskon_per_item']/100))); 
 
                $data_transaksi2[] = array(
                    'nilai_ppn' => number_format($nilai_ppn,2), 
                    'no_batch' => $key['no_batch'],  
                    'no_reg' => $key['no_reg'],  
                    'tgl_exp' => $key['tgl_exp'],  
                    'harga_jual' => number_format($key['harga_jual'], 2) ,  
                    'qty' => $key['qty'],  
                    'subtotal' => number_format($subtotal,2),  
                );
                $subtotal_per_obat+= $subtotal; 
                $nilai_ppn_per_obat+= $nilai_ppn; 
            }

            $output[] = array( 
                'row_id' => $items['id'], 
                'subtotal' => number_format($subtotal_per_obat,2), 
                'nilai_ppn' => number_format($nilai_ppn_per_obat,2),  
                'name' => $items['label'],  
                'id' => $items['id'], 
                'satuan' => $items['satuan'], 
                'ppn' => $items['ppn'],  
                'diskon' => 0, 
                'qty' => $items['qty'],  
                'kosong' => $items['kosong'],  
                'no' => $no++, 
                'data_transaksi' => $data_transaksi2, 
            ); 

            // $ppn+=((float)$items['ppn']/100)*($items['subtotal']-((float)$items['diskon']/100)*$items['subtotal']);
           /* $diskon+=($items['diskon']/100)*$items['subtotal'];
            $total +=$items['harga_jual']*$items['qty'];*/

            // $total_ppn=$items['ppn'];  
 
        } 

    
        $data_return = array(
            'cart_' => $output, 
            'no_faktur' => $no_faktur, 
            'id_master_trx' => $id_master_trx, 
            /*'grand_diskon' => number_format($diskon,2), 
            'grand_total' => number_format(($total+$ppn)-$diskon,2), */
        );

        echo json_encode($data_return); 
    }

    /*public function get_trx_tmp()
    {
        $output  = array();
        $no = 1;
        $total = 0; $ppn = 0; $diskon = 0;

        foreach ($this->cart->contents() as $items) {
            if(isset($items['jenis_cart'])){
                if($items['jenis_cart']=='penjualan'){ 
                  
                    $jumlah_diskon = ($items['subtotal'])-($items['subtotal']-$items['subtotal']*((int)$items['diskon']/100));  
                    $nilai_ppn = number_format(($items['ppn']/100)*($items['subtotal']-($items['subtotal']*($items['diskon']/100))),2);
                    $subtotal = number_format($items['subtotal']-$items['subtotal']*($items['diskon']/100),2);
                    $harga = number_format($items['price'],2);

                    $output[] = array(
                        'jumlah_diskon' => $jumlah_diskon, 
                        'row_id' => $items['rowid'], 
                        'nilai_ppn' => $nilai_ppn, 
                        'subtotal' => $subtotal, 
                        'name' => $items['name'], 
                        'rowid' => $items['rowid'], 
                        'id' => $items['id'], 
                        'satuan' => $items['satuan'], 
                        'ppn' => $items['ppn'], 
                        'tgl_exp' => $items['tgl_exp'], 
                        'harga' => number_format( $items['price'],2), 
                        'no_batch' => $items['no_batch'], 
                        'no_reg' => $items['no_reg'], 
                        'diskon' => $items['diskon'], 
                        'qty' => $items['qty'], 
                        'kosong' => $items['kosong'], 
                        'no' => $no++, 
                    ); 

                    $ppn+=((float)$items['ppn']/100)*($items['subtotal']-((float)$items['diskon']/100)*$items['subtotal']);
                    $diskon+=($items['diskon']/100)*$items['subtotal'];
                    $total +=$items['price']*$items['qty'];

                    // $total_ppn=$items['ppn'];  

                }else{
                    // var_dump($this->cart->contents());
                }
            }
        }

    
        $data_return = array(
            'cart_' => $output, 
            'grand_diskon' => number_format($diskon,2), 
            'grand_total' => number_format(($total+$ppn)-$diskon,2), 
        );

        echo json_encode($data_return); 
    }*/


    /*public function get_barang_by_barcode3($barcode)
    {
        $where = array('barcode' => $barcode );
        $data['obat']=$this->M_obat->getBy($where);
        // $data['id_obat']=$data['obat']['id_obat'];
        if ($data['obat']==null) {
            echo "Data Barang tidak ditemukan";
        } else {

            //cek apakah barcode ada di table detail obat
            $this->db->where(array('id_obat'=>$data['obat']['id_obat']));
            $cek2 = $this->db->get('detail_obat');
            if ($cek2->num_rows()>0) {
                $cek="";
                if (count($this->cart->contents())>0) { 

                    $tgl_exp_old = array();
                    $no_batch_old = array();
                    foreach ($this->cart->contents() as $items) { 
                        if ($items['jenis_cart']=='penjualan') {
                            $tgl_exp_old[] =  "'".$items['tgl_exp_full']."'";
                            $no_batch_old[] =  "'".$items['id']."'";
                        }
                    } 
                    $tgl_exp_old =  implode(",",$tgl_exp_old); 
                    $no_batch_old =  implode(",",$no_batch_old); 
                   
                    foreach ($this->cart->contents() as $items) {
                        if ($items['jenis_cart']=='penjualan') {
                            if ($items['barcode']==$barcode) {
                                $cek=$this->M_detail_obat->get_last_exp($barcode, $items['tgl_exp_full'], $items, $tgl_exp_old, $no_batch_old);
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

                    $data['data']['barcode']=$barcode;
                    // var_dump ($this->cart->contents());
                    //jika stok memenuhi
                    if ($data['data']['harga_jual']<1)
                    {
                    	echo "Harga jual barang belum diset silahkan set harga jual";
                    }
                    if ($data['data']['user_verified']=="")
                    {
                        echo "Barang ini belum di verifikasi silahkan verifikasi barang agar dapat menjualnya";
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
                     // echo ($this->db->last_query());
                    echo "Stok Barang $barcode kosong silahkan tambahkan stok melalui menu transaksi pembelian 2";
                }
            } else {
                echo "Stok Barang $barcode kosong silahkan tambahkan stok melalui menu transaksi pembelian";
            }
        }  
    }*/

   /* public function add_to_cart($data)
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
            'no_batch' => $data['data']['no_batch'],
            'tgl_exp' => $tgl_exp,
            'tgl_exp_full' => $tgl_exp_full,
            'diskon' => '0',
            'satuan' => $nm_satuan,
            'stok_awal' => $data['data']['sisa_stok'],
            'id_obat' => $data['data']['id_obat'],
            'qty' => 1,
            'ppn' => 10,
            'kosong' => 0,
            'jenis_cart'=>'penjualan',
            "options"=>array('jenis'=>"penjualan"),
            // 'subtotal' => 0,
        );
        $this->cart->insert($data2);
        echo $this->show_cart();
    }*/

     
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
        /*$data = array(
            'rowid' => $this->input->post('row_id'),
            'qty' => 0,
        );
        $this->cart->update($data);
        echo $this->show_cart();*/

        $this->cart->destroy();

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
        $update="";
        $table_column = $this->input->post('table_column');
        $row_id = $this->input->post('id');

        if ($table_column=="qty")
        {
            $data = array(
                'rowid' => $row_id,
                'qty' => $this->input->post('value'),
            );
            $update = $this->cart->update($data);
        }
        else if ($table_column=="kosong") {
            
            $data = array(
                'rowid' => $row_id,
                'kosong' => $this->input->post('value'),
            );
            $update = $this->cart->update($data);
        }
        else if ($table_column=="diskon") {
            
            $data = array(
                'rowid' => $row_id,
                'diskon' => $this->input->post('value'),
            );
            $update = $this->cart->update($data);
        }

        if ($update) {
            // echo "berhasil";
            echo $this->db->last_query();
        } else {
            // echo "gagal"; 
            echo $this->db->last_query();
        } 
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

            //get nomor faktur baru
            $no_faktur = $this->input->post('no_faktur');
            $kode_pembayaran = $this->input->post('kode_pembayaran');
            $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo');
            $total = $this->input->post('total');
            // $stok_awal = $this->input->post('stok_awal');
            $diskon = $this->input->post('diskon');
            // $kd_outlet = $this->input->post('kd_outlet');
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

            $check = $this->db->query("SELECT no_faktur, kd_outlet from master_trx_penjualan_tmp where no_faktur='".$no_faktur."' and kd_outlet='".$kd_outlet."'");
            if ($check->num_rows()>0)
            {
                $no_faktur = $no_faktur;
            }
            else
            {
                //buat nomor faktur baru
                $original_string = '1234567890';
                $no_faktur = $this->M_trxPenjualan->get_random_string($original_string, 4);
            }

          
            foreach ($stok_awal as $key =>$value) {

                // $id_trx = $this->db->query("SELECT max(id_trx)+1 AS id_trx FROM trx_penjualan_tmp")->row_array()['id_trx'];

                if ($qty[$key]=="") {
                    $qty[$key]=0;
                }
                 if ($diskon[$key]=="") {
                    $diskon[$key]=0;
                }

                if ($kode_pembayaran==100 || $kode_pembayaran=="100")
                {
                    $tgl_jatuh_tempo ="0000-00-00 00:00:00";
                }


                $data[] = array(
                    // 'id_trx' => $id_trx,
                   /* 'barcode' => $barcode[$key],
                    'no_batch' => $no_batch[$key],
                    'stok_awal' => $stok_awal[$key],*/
                  /*  'kd_outlet' => $kd_outlet,
                    'no_faktur' => $no_faktur,*/
                    'qty' => $qty[$key],
                    'time' => date("Y-m-d H:m:s"),
                    /*'sisa_stok' => $value - $qty[$key],
                    'kode_pembayaran' => $kode_pembayaran,*/
                    'harga_jual' => str_replace(",", "", $harga[$key]),
                    'diskon_per_item' => str_replace(",", "",$diskon[$key]),
                    'harga_setelah_diskon' => str_replace(",", "",$harga_setelah_diskon[$key]),
                    'no_reg' => $no_reg[$key],
                    'tgl_jatuh_tempo' => $tgl_jatuh_tempo,
                    'ppn' => str_replace(",", "",$ppn),
                    'tgl_exp' => $tgl_exp_full[$key],
                    'id_user_input' => $this->session->userdata('username'),
                );

                if ($kosong[$key]!="" || $kosong[$key]>=1)
                {
                    $data_kosong[] = array(
                        'barcode' => $barcode[$key], 
                        'no_faktur' => $no_faktur,
                        'jumlah_kosong' => $kosong[$key],
                    );
                } 
                    
            };

             
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
        $data['url'] = base_url()."trxPenjualan/Penjualan/";
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

    public function notaPenjualan($no_faktur,$kode_outlet){
       
        $data['invoice'] = $this->db->query("SELECT a.*,c.nama, b.nm_satuan FROM trx_penjualan_tmp a, obat c, satuan b where a.barcode=c.barcode and c.kd_satuan=b.kd_satuan and a.no_faktur='".$no_faktur."'")->result();

        $data['outlet'] = $this->db->query("SELECT a.nama,a.npwp,a.alamat,a.id_outlet FROM outlet1 a where a.id_outlet='".$kode_outlet."'")->row();

        $data['nota_kosong'] = $this->db->query("SELECT a.*,b.nama, c.nm_satuan FROM obat_kosong a, obat b, satuan c WHERE a.barcode=b.barcode and b.kd_satuan=c.kd_satuan and no_faktur='".$no_faktur."'")->result();

        $data['jumlah_kosong'] = $this->db->query("select SUM(jumlah_kosong) as jumlah_kosong FROM obat_kosong where no_faktur='".$no_faktur."'")->row()->jumlah_kosong;

        $data['no_faktur'] = $no_faktur;

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
        $data = $this->M_trxPenjualan->get_random_string($original_string, 4);
        echo $data;
    }

    public function kk()
    {
        $tgl_exp_old = array();
        $no_batch_old = array();
        foreach ($this->cart->contents() as $items) { 
            $tgl_exp_old[] =  "'".$items['tgl_exp_full']."'";
            $no_batch_old[] =  "'".$items['id']."'";
        } 

         $tgl_exp_old =  implode(",",$tgl_exp_old); 
        $no_batch_old =  implode(",",$no_batch_old); 

        echo "SELECT * FROM detail_obat WHERE barcode='8992858664713 ' and  tgl_exp not in($tgl_exp_old) and no_batch not in ($no_batch_old)  and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC limit 1";
        
    }

    public function update_data_penjualan_tmp()
    {
        $data = $this->db->get('master_trx_penjualan_tmp')->result_array();
        foreach ($data as $key) {
            $this->db->query("UPDATE trx_penjualan_tmp set no_faktur='".$key['id_master_trx']."' where no_faktur='".$key['no_faktur']."'"); 
        }
    }
}
