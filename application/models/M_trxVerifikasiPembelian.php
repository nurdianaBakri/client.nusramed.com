<?php
class M_trxVerifikasiPembelian extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

    private $table = "detail_obat";
    private $keterangan = "";
	private $table_trx_penjualan_tmp = "trx_penjualan_tmp"; 

    //get all transaksi TMP
    function getAll(){  
        $data= $this->db->query("SELECT * from form_verifikasi_pembelian WHERE id_master_detail in (SELECT id_master_detail FROM detail_obat WHERE user_verified is null or user_verified='' GROUP BY id_master_detail)")->result_array();
        return $data;
    }

     function get_faktur_lap_pembelian(){  
        $data= $this->db->query("SELECT A.no_faktur, A.kd_suplier, B.nama FROM master_detail_obat A INNER JOIN suplier B ON A.kd_suplier=B.kd_suplier")->result_array();
        return $data;
    }

    function detail_trx_po($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table); 
        return $hsl;
    }

     function detail_trx_po2($id_master_detail){  
        $data= $this->db->query("SELECT metode_pembayaran, id_user, tgl_jatuh_tempo, tgl_input from data_pembelian WHERE id_master_detail ='".$id_master_detail."'");
        return $data;
    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);
        return $hasil;
    } 

    public function get_data_lamaBaru($id_detail_obat)
    {
        $this->db->where('id_detail_obat', $id_detail_obat); 
        $this->db->select('no_reg, id_detail_obat, id_master_detail, no_batch, tgl_exp, qty_verified, diskon_beli, harga_beli');
        $data =  $this->db->get('detail_obat')->result_array();
        return $data;
    }

    public function update_dtPembelian($data, $id_detail_obat, $where5)
    {  
        $data_return = array();
        //get data lama 
        $data_lama =  $this->get_data_lamaBaru($id_detail_obat);

        //update data 
        $this->db->where('id_detail_obat', $id_detail_obat);  
        $return=$this->db->update('detail_obat', $data);  

        //get data baru 
        $data_baru =  $this->get_data_lamaBaru($id_detail_obat); 
 
        //get nomor faktur 
        $this->db->where('id_master_detail', $data_lama[0]['id_master_detail']);
        $this->db->select('no_faktur');
        $no_faktur= $this->db->get('master_detail_obat')->row_array()['no_faktur']; 

        if ($return==true)
        { 
            //get data lama 
            $data5 = array(
                'no_batch' => $data['no_batch'],
                'no_reg' => $data['no_reg'], 
                'tgl_exp' => $data['tgl_exp'],  
            ); 

             $this->db->where($where5);  
             $hasil=$this->db->update('trx_penjualan_tmp', $data5); 

            if ($hasil==true)
            {
                $this->keterangan="Update data transaksi pembelian obat pada faktur '".$no_faktur."' berhasil";
            }
            else
            {
                $this->keterangan="Update data transaksi pembelian obat pada faktur '".$no_faktur."' gagal, Pesan : ". $this->db->error()['message'] ; 
            } 

            $hasil = $this->M_log->update_history("Update transaksi pembelian", $this->keterangan, $data_lama, $data_baru);
           
            $data_return = array(
                'status' => $hasil, 
                'pesan' => $this->keterangan, 
            );
            return $data_return; 
        }  
        else
        { 
            $this->keterangan="Update data transaksi pembelian obat pada faktur '".$no_faktur."' gagal, Pesan : ". $this->db->error()['message'] ;  

            $hasil = $this->M_log->update_history("Update transaksi pembelian", $this->keterangan, $data_lama, $data_baru);
            
            $data_return = array(
                'status' => $hasil, 
                'pesan' => $this->keterangan, 
            );
            return $data_return; 
        }
    }

    public function hapus_dtPenjualan($id_master_detail, $id_detail_obat=null)
    { 
        //get nomor faktur 
        $this->db->where('id_master_detail', $id_master_detail);
        $this->db->select('no_faktur');
        $no_faktur= $this->db->get('master_detail_obat')->row_array()['no_faktur']; 

        if ($id_detail_obat==null)
        {
            //get data lama
            $this->db->where('id_master_detail', $id_master_detail); 
            $this->db->select('no_reg, id_detail_obat, id_master_detail, no_batch, tgl_exp, qty_verified, diskon_beli, harga_beli');
            $data_lama =  $this->db->get('detail_obat')->result_array();

            $query = "UPDATE detail_obat set deleted=1 where id_master_detail=$id_master_detail";
            if ($query==true)
            {
                $this->keterangan="Hapus semua transaksi pembelian obat pada faktur '".$no_faktur."' berhasil";
            }
            else
            {
                $this->keterangan="Hapus semua transaksi transaksi pembelian obat pada faktur '".$no_faktur."' gagal, Pesan : ". $this->db->error()['message'] ; 
            } 

            $hasil = $this->M_log->delete_history("Update transaksi pembelian", $this->keterangan, $data_lama);
           
            $data_return = array(
                'status' => $hasil, 
                'pesan' => $this->keterangan, 
            );
            return $data_return;
        }
        else
        { 
             //get data lama
            $this->db->where_in('id_detail_obat', $id_detail_obat); 
            $this->db->select('no_reg, id_detail_obat, id_master_detail, no_batch, tgl_exp, qty_verified, diskon_beli, harga_beli');
            $data_lama =  $this->db->get('detail_obat')->result_array();

            $data_detail_obat = implode( ", ", $id_detail_obat );
            $hasil = "UPDATE detail_obat set deleted=1 where id_master_detail=$id_master_detail and id_detail_obat NOT IN (".$data_detail_obat.")";
            if ($hasil==true)
            {
                $this->keterangan="Hapus obat transaksi pembelian pada faktur '".$no_faktur."' berhasil";
            }
            else
            {
                $this->keterangan="Hapus obat transaksi pembelian pada faktur '".$no_faktur."' gagal, Pesan : ". $this->db->error()['message'] ; 
            } 

            $hasil = $this->M_log->delete_history("Update transaksi pembelian", $this->keterangan, $data_lama);
           
            $data_return = array(
                'status' => $hasil, 
                'pesan' => $this->keterangan, 
            );
            return $data_return;
        } 
    }

}