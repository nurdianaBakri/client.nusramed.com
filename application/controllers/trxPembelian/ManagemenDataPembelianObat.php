<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ManagemenDataPembelianObat extends CI_Controller
{
    public function __construct()
    {
        parent ::__construct();  
        $this->logged_in();   
        $this->load->model('M_data_pembelian_ajax');
    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }   

    public function index()
    { 
        $data['title'] = "Manajemen data pembelian";
        $data['url'] = "http://localhost/nusramed.com2/trxPembelian/ManagemenDataPembelianObat/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Manajemen data pembelian",
             'link' => base_url()."trxPembelian/ManagemenDataPembelianObat",
             'status' => "active",
            ),
        );
  
        // $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('manajemenDataPembelian/index', $data);
        $this->load->view('include2/footer');
    }


     public function ajax_list()
    {
        $list = $this->M_data_pembelian_ajax->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $data_) {
            $no++;
            $row = array();
 
            $row[] = $no;
            //add html for action
            $row[] = "<div class='dropdown'>
                    <button class='btn btn-xs btn-success dropdown-toggle' type='button' data-toggle='dropdown'>&#x2636;
                    <span class='caret'></span></button>
                    <ul class='dropdown-menu'>
                      <li><a  href='#' onclick='detail(".$data_->id_master_detail.")'>Detail </a>  
                        </li>
                        <li><a  href='#' onclick='summary(".$data_->id_master_detail.")'>Lihat Ringkasan </a>  
                        </li>
                      <li><a  href='".base_url().'ManagemenDataPembelianObat/print/'.$data_->id_master_detail."' target='_blank'>Print
                        </a></li>
                    </ul>
                  </div> "; 
 
            $row[]= date_from_datetime($data_->tgl_input,4);
            $row[] = $data_->no_faktur;
            $row[] = $data_->nama_suplier;
            $row[] = $data_->metode_pembayaran; 
          /*  $row[] = $data_->tgl_jatuh_tempo; 
            $row[] = $data_->hrg_stl_pajak; 
            $row[] = $data_->user_verified; */
            if ($data_->tgl_jatuh_tempo=="0000-00-00 00:00:00") {
                $row[] = "COD"; 
            }
            else {
                $row[]= date_from_datetime($data_->tgl_jatuh_tempo,2);
            }

            $row[] = number_format($data_->hrg_stl_pajak,2); 

            if ($data_->user_verified=="")
                { 
                    $row[] = "<span class='badge' style='background-color: red;'>Not Verified</span>";
                } 
                else
                {
                    $row[] = "<span class='badge' style='background-color: green;'>Verified by <br>".$data_->user_verified."</span>";
                } 

            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->M_obat_ajax->count_all(),
                "recordsFiltered" => $this->M_obat_ajax->count_filtered(),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function summary($id_master_detail)
    {
        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll();
        $data['data'] = $this->db->query("SELECT * FROM master_detail_obat WHERE id_master_detail=$id_master_detail")->row_array();

        $kd_suplier= $data['data']['kd_suplier'];
        // var_dump($kd_suplier);

        $data['nama_suplier'] = $this->db->query("SELECT nama FROM suplier WHERE kd_suplier='$kd_suplier'")->row_array()['nama'];
        $this->load->view('manajemenDataPembelian/summary', $data);

    }

    public function update_master_detail_obat()
    {
        $tgl_faktur = $this->input->post('tgl_faktur');
        $tgl_jatuh_tempo = $this->input->post('tgl_jatuh_tempo');
        $kode_pembayaran = $this->input->post('kode_pembayaran');
        $kd_suplier = $this->input->post('kd_suplier');
        $no_faktur = $this->input->post('no_faktur');
        // $ppn = $this->input->post('ppn');
        $id_master_detail = $this->input->post('id_master_detail');
        $data2 = array( 
            // 'ppn' => $ppn, 
            'kd_suplier' => $kd_suplier, 
            'no_faktur' => $no_faktur,  
            'kode_pembayaran' => $kode_pembayaran,  
            'tgl_jatuh_tempo' => $tgl_jatuh_tempo, 
            'tgl_faktur' => $tgl_faktur,  
            // 'tgl_input' => date('Y-m-d H:m:s'),  
        );    

        $this->db->where( array('id_master_detail' => $id_master_detail ));
        $update= $this->db->update("master_detail_obat", $data2); 

        $pesan="";
        if ($update==1)
        {
            $pesan="Proses update data pembelian berhasil";
            # code...
        }
        else
        {   
            $pesan="Proses update data pembelian gagal";
        }
        $data = array(
            // 'data' => $data2, 
            'status' => $update, 
            'pesan' => $pesan, 
            // 'id_master_detail' => $id_master_detail, 
            // 'query' => $this->db->last_query(), 
        );
        echo json_encode($data);
    }

     public function get_data_suplier()
    { 
        $data = $this->db->query('SELECT kd_suplier as value, nama_aja as label from form_trx_pembelian_suplier')->result();  
        echo json_encode($data); 
    }

    public function form()
    {
        foreach ($this->cart->contents() as $key) {
            if(isset($key['jenis_cart'])){
            if ($key['jenis_cart']=="verifikasi_pembelian") {
                $this->cart->remove($key['rowid']);
            }}
        } 

        $data['title'] = "Manajemen data pembelian";
        $data['url'] = "trxPembelian/ManagemenDataPembelianObat/";
        $data['SubFitur'] =null;

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home",
                'link' => base_url(),
                'status' => "",
            ),
            array(
             'fitur' => "Manajemen data pembelian",
             'link' => base_url()."trxPembelian/ManagemenDataPembelianObat",
             'status' => "active",
            ),
        );
  
        $data['metode_pembayaran'] = $this->M_metode_pembayaran->getAll(); 
  
        $this->load->view('include2/sidebar', $data);
        $this->load->view('manajemenDataPembelian/index', $data);
        $this->load->view('include2/footer');
    }

    public function get_list_faktur()
    {
        $tanggal_mulai = $this->input->post('tanggal_mulai')." 00:00:01";
        $tanggal_sampai = $this->input->post('tanggal_sampai')." 23:59:59";
        
        $data['data'] = $this->db->query("CALL MDP_get_faktur_bydate('$tanggal_mulai','$tanggal_sampai')")->result_array();

        $this->load->view('manajemenDataPembelian/list_faktur', $data);
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

        $id_master_detail = $this->input->post('id_master_detail'); 

        $data=$this->db->query("CALL detail_obat_by_idmaster($id_master_detail)")->result_array();
 
        if (sizeof($data)>0) {
            // $this->cart->destroy();
            foreach ($data as $key) {

                $s = $key['tgl_exp'];
                $dt = new DateTime($s); 
                $tgl_exp_full = $dt->format('Y-m-d');  

                $key['tgl_exp'] = strtotime($key['tgl_exp']);
                $tgl_exp =  date('d M Y', $key['tgl_exp']);
 
                $this->cart->product_name_rules = '[:print:]';
                $data2 = array(
                    'id' => $key['no_batch'],
                    'name' => $key['nama'],
                    'id_obat' => $key['id_obat'],
                    'nama_display' => $key['nama_display'],
                    'satuan' => $key['nm_satuan'],
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
            echo "berhasil";
            // echo $this->show_cart();
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$id_master_detail'";
        }
    }

    public function get_detail_trx()
    { 
        $id_master_detail = $this->input->post('id_master_detail'); 
       
        $data = $this->db->query("CALL master_detal_obat($id_master_detail)")->row_array();   

        if (sizeof($data)>0) {
            ///get nama metode pembayaran   
            echo json_encode($data);
        } else {
            echo "Tidak ada transaksi dengan nomor faktur '$id_master_detail'";
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
                        <td>
                        <input type="hidden" class="form-control" name="id_obat[]" value="'.$items['id_obat'].'" >

                         <input type="hidden" class="form-control" name="barcode[]" value="'.$items['barcode'].'" >

                        <input type="hidden" class="form-control" name="name[]" value="'.$items['name'].'" >'.$items['nama_display'].'</td>
     
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
                         <td>
                         <input type="hidden" class="form-control id_detail_obat" name="id_detail_obat[]" value="'.$items['id_detail_obat'].'" >  

                         <button type="button" id="'.$items['rowid'].'" class="romove_cart btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i></button> 
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

        if (isset($_POST['id_master_detail'])) 
        { 
            $time = strtotime($_POST['tgl_input']); 
            $tgl_input = date('Y-m-d',$time);
            $daata_detail_obat = array(  );

            $today = date("Y-m-d");
            $query =""; 
            
            $id_detail_obat = $this->input->post('id_detail_obat'); 
            $no_batch = $this->input->post('no_batch'); 
            $no_reg = $this->input->post('no_reg'); 

            $no_batch_lama = $this->input->post('no_batch_lama'); 
            $no_reg_lama = $this->input->post('no_reg_lama');  

            $tgl_exp = $this->input->post('tgl_exp_full'); 
            $harga = $this->input->post('harga'); 
            $diskon = $this->input->post('diskon'); 
            $qty = $this->input->post('qty');  
            $barcode = $this->input->post('barcode'); 
            $name = $this->input->post('name');   
            $kode_pembayaran = $this->input->post('kode_pembayaran');   
            $id_master_detail = $this->input->post('id_master_detail');    
 
            if (isset($_POST['no_batch']))
            { 
                foreach ($no_batch as $key =>$value) 
                { 
                     $this->form_validation->set_rules("no_batch[".$key."]", "Nomor batch <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required");

                    $this->form_validation->set_rules("harga[".$key."]", "Harga <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required");

                    $this->form_validation->set_rules("tgl_exp_full[".$key."]", "Tanggal Expired <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required|callback_dob_check");  

                    $this->form_validation->set_rules("qty[".$key."]", "Quality <b>".$barcode[$key]." - ".$name[$key]."</b>", "trim|required"); 

                    // https://stackoverflow.com/questions/32383743/ci-form-validation-of-date-with-input-type-date   

                    if ($this->form_validation->run() == FALSE) 
                    {
                        $validation_errors = validation_errors();
                    } 
                    else 
                    { 

                        $data = array(
                            'no_batch' => $no_batch[$key],
                            'no_reg' => $no_reg[$key], 
                            'tgl_exp' => $tgl_exp[$key],
                            'harga_beli' => $harga[$key],
                            'diskon_beli' => $diskon[$key], 
                            'stok_awal' => $qty[$key], 
                            'sisa_stok' => $qty[$key], 
                            // 'kode_pembayaran' => $kode_pembayaran, 
                        );

                        $where5 = array(
                            'no_batch' => $no_batch_lama[$key],
                            'no_reg' => $no_reg_lama[$key],  
                        );

                        $update_dtPembelian = $this->M_trxVerifikasiPembelian->update_dtPembelian($data, $id_detail_obat[$key], $where5); 

                        $query2[]  = array(
                            'pesan' => $update_dtPembelian,   
                        ); 
                    } 
                }  

                $this->M_trxVerifikasiPembelian->hapus_dtPenjualan($id_master_detail, $id_detail_obat);
                $query2[]  = array(
                    'pesan' => $this->db->last_query(), 
                ); 
                $return =1; 
            }
            else
            {
               /* $return =1; 
                $this->M_trxVerifikasiPembelian->hapus_dtPenjualan($id_master_detail);
                $query2[]  = array(
                    'pesan' => $this->db->last_query(), 
                );*/  
            } 

            if ($return>0) 
            {
                $return=1; 
                $pesan= "Proses Update pembelian berhasil ";
            }

            else 
            {
                $return=0;
                $pesan= "Proses Update pembelian gagal ";
            }
         
        } 

        else 
        {
            $return=0;
            $pesan= "data obat tidak boleh boleh kosong, silahkan data obat ";
        } 

        $data2 = array(
            'return' => $return, 
            'pesan' => $pesan,   
            'query2' => $query2,  
            'data3' => $data3,   
            'id_master_detail' => $_POST['id_master_detail'],   
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

    public function update2()
    { 
      /* $data = $this->db->query("SELECT time, detail_obat.id_master_detail from detail_obat, master_detail_obat WHERE master_detail_obat.id_master_detail=detail_obat.id_master_detail GROUP BY detail_obat.id_master_detail")->result_array();
       foreach ($data as $key) {
            $data = $this->db->query("UPDATE master_detail_obat set tgl_input='".$key['time']."' where id_master_detail=".$key['id_master_detail']."");
       }*/

       $data = $this->db->query('call get_faktur_pembelian(12)')->row_array()['no_faktur'];
       var_dump($data);
    }
}
