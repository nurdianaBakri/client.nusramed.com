<?php
class M_detail_obat extends CI_Model {

	private $table = "detail_obat";
	private $jenis_log = "Set Harga Obat";
	private $keterangan = "";
	private $log_trx_pembelian = "transaksi pembelian";
	
	function __construct() {
		parent::__construct();
	}
	
	function get($where = NULL){  
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('tgl_exp','ASC');
		return $this->db->get($this->table);
	}

	public function cek_detail_obat($where)
	{
		$this->db->where($where);
		$data = $this->db->get($this->table);
		return $data;
	}
	
	function add($data){ 
 
		$hasil=$this->db->insert($this->table, $data);

        //insert data log 
        if ($hasil==true)
        { 
        	//masukkan data ke table kartu_stok 
        	//get id suplier
        	$master_detail_obat = $this->db->query('SELECT no_faktur, tgl_input from master_detail_obat where id_master_detail='.$data['id_master_detail'])->row_array();
    	 	
    	 	$data2 = array(
                'id_obat' => $data['id_obat'], 
                'no_reg' => $data['no_reg'], 
                'no_batch' => $data['no_batch'], 
                'tgl_exp' => $data['tgl_exp'], 
                'masuk' => $data['sisa_stok'],   
                'jenis_faktur' => "pembelian",  
                'no_faktur' => $master_detail_obat['no_faktur'],   
                'tanggal' => $master_detail_obat['tgl_input'],  
                // 'id_uaser' => $this->session->userdata('username'), 
            );   
			$hasil=$this->db->insert("kartu_stok", $data2);
			if ($hasil==true) {
				
        		$this->keterangan="Proses input pembelian obat berhasil"; 
			}
			else
			{
				$this->keterangan="Proses input pembelian obat gagal, Pesan : ". $this->db->error()['message'] ;
			} 
        }
        else
        { 
        	//get nama obat berdasar
        	// $ket_log[]['ket'] = "Proses input pembelian ".$name[$key]." (No Batch : ".$no_batch[$key].", No Reg : ".$no_reg[$key].") berhasil."; 

        	$this->keterangan="Proses input pembelian obat gagal, Pesan : ". $this->db->error()['message'] ;
        }
        
        $hasil = $this->M_log->insert_history($this->log_trx_pembelian, $this->keterangan, $data);
        return $hasil; 
	}
	
	function update($where, $data) { 
        $this->db->where($where);
        return $this->db->update($this->table, $data);
    }
 
	function edit($no_batch, $data){
		$this->db->where('no_batch', $no_batch);
		$this->db->update($this->table, $data);
		return $no_batch;
	}
	
	function hapus($where){
		$this->db->where($where);
		return $this->db->delete($this->table);
	}

	public function get_max_primary()
	{ 
		$maxid =0;
		$row = $this->db->query('SELECT MAX(no_batch) AS `no_batch` FROM `detail_obat`')->row();
		if ($row->no_batch==NULL || $row->no_batch=="NULL" || $row->no_batch==0)  {
			return $row->no_batch+1;
		} 
		else
		{
			return $row->no_batch+1; 
		}
	}

	public function get_last_exp($barcode, $tgl_exp=null, $cartContent=null, $tgl_exp_old=null, $no_batch_old=null)
	{ 

		$where = array('barcode' => $barcode );
        $id_obat=$this->M_obat->getBy($where)['id_obat']; 

		if ($tgl_exp==null)
		{
			$date = date('Y-m-d');
			$data = $this->db->query("SELECT * FROM detail_obat WHERE  tgl_exp > '$date' and id_obat=$id_obat and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
			return $data;
		}
		else
		{  
			$no_batch=$cartContent['id'];
			$tgl_exp_full=$cartContent['tgl_exp_full'];
			
			$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE id_obat=$id_obat and no_batch='$no_batch' AND sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
			if ($cek1->num_rows()>0)
			{
				$data = $cek1->row_array();
				// //cek apakah stok sudah habis
				// check qyalitynya sama
				if ($cartContent['qty']==$data['sisa_stok'])
				{  
					$no_batch2 = $data['no_batch']; 

					//jika stok sudah habis, ambil data detail obat yang memiliki taggal expired di atasnya
					$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE id_obat=$id_obat and  tgl_exp not in($tgl_exp_old) and no_batch not in ($no_batch_old)  and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC limit 1");
				}  
			}
			else{
				$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE id_obat=$id_obat and no_batch!='$no_batch' AND sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
				$data = $cek1->row_array(); 
				if ($cartContent['qty']==$data['sisa_stok'])
				{ 
					//jika stok sudah habis, ambil data detail obat yang memiliki taggal expired di atasnya
					$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE id_obat=$id_obat and  tgl_exp >= '$tgl_exp'  and no_batch!='$no_batch'  and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
				}  
			} 	 
			return $cek1;
		} 
	}

	public function get_last_exp2($barcode, $tgl_exp=null, $cartContent=null)
	{
		if ($tgl_exp==null)
		{
			$date = date('Y-m-d');
			$data = $this->db->query("SELECT * FROM detail_obat WHERE  tgl_exp > '$date' and barcode='$barcode' and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
			return $data;
		}
		else
		{  
			$no_batch=$cartContent['id'];
			$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE no_batch='$no_batch'  and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC");
			if ($cek1->num_rows()>0)
			{
				$data = $cek1->row_array();
				// //cek apakah stok sudah habis
				if ($cartContent['qty']<=$data['sisa_stok'])
				{ 
					//jika stok sudah habis, ambil data detail obat yang memiliki taggal expired di atasnya
					$cek1 = $this->db->query("SELECT * FROM detail_obat WHERE barcode='$barcode' and  tgl_exp >= '$tgl_exp'  and sisa_stok>0 and deleted='0'  ORDER BY tgl_exp ASC limit 1");
				}  
			} 	 
			return $cek1;
		} 
	}


	public function get_last_exp_pengambilan($barcode, $no_faktur)
	{
		foreach ($this->cart->contents() as $items)
        { 
        	if(isset($items['jenis_cart'])){
			if($items['jenis_cart']=='pengambilan'){
				if ($items['barcode']==$barcode)
				{
					$tgl_exp = $items['tgl_exp_full'];	
					$no_batch=$items['id'];	
					$cek1 = $this->db->query("SELECT * FROM trx_penjualan_tmp WHERE no_batch='$no_batch' and no_faktur='$no_faktur' ORDER BY tgl_exp ASC");
					if ($cek1->num_rows()>0)
					{
						$data = $cek1->row_array();
	
						//cek apakah stok sudah habis
						if ((int)$items['qty_verified']<(int)$data['qty'])
						{ 
							//jika stok sudah habis, ambil data detail obat yang memiliki taggal expired di atasnya
							// $cek1 = $this->db->query("SELECT * FROM trx_penjualan_tmp WHERE  tgl_exp < '$tgl_exp' and barcode='$barcode' and no_faktur='$no_faktur' ORDER BY tgl_exp ASC");
							return $cek1;
						}
						elseif ((int)$items['qty_verified']==(int)$data['qty'])
						{ 
							//jika stok sudah habis, ambil data detail obat yang memiliki taggal expired di atasnya
							$cek1 = $this->db->query("SELECT * FROM trx_penjualan_tmp WHERE  tgl_exp > '$tgl_exp' and barcode='$barcode' and no_faktur='$no_faktur' ORDER BY tgl_exp ASC");
						}
					} 	 
				}
			}
		}
        }	
			return $cek1; 
	}
 
	public function set_harga_obat($where, $data, $keterangan) {  
	 
        //get data lama
        $this->db->where($where);
        $this->db->select("id_obat, no_reg, no_batch, harga_jual,diskon_maximal, tgl_exp");
		$data_lama= $this->db->get($this->table)->result_array();  
        
		//check jika datanya sama 
		// Check for equality 
		$data_baru_tmp= array_merge_recursive($where, $data); 
        if ($data_lama == $data_baru_tmp)
        {
            //echo "Both arrays are same\n"; 
        }
        else{
        	//echo "Both arrays are not same\n";
        	//update data
	        $this->db->where($where);
	        $hasil =  $this->db->update($this->table, $data);

	        //get data baru
	        $this->db->where($where);
	        $this->db->select("id_obat, no_batch, no_reg, tgl_exp, harga_jual,diskon_maximal");
			$data_baru= $this->db->get($this->table)->result_array();


	        //insert data log 
	        if ($hasil==true)
	        {
	        	$this->keterangan=$keterangan." berhasil";
	        }
	        else
	        {
	        	$this->keterangan=$keterangan." gagal, Pesan : ". $this->db->error()['message'] ;
	        } 

	        $hasil = $this->M_log->update_history($this->jenis_log, $this->keterangan, $data_lama, $data_baru);
	        return $this->keterangan; 
        } 
    }

    public function update_data_pembelian($where, $data) { 

	 
        //get data lama
        $this->db->where($where);
		$data_lama= $this->db->get('obat')->result_array();

		//update data
        $this->db->where($where);
        $hasil =  $this->db->update('obat', $data); 

        //get data baru
        $this->db->where($where);
		$data_baru= $this->db->get('obat')->result_array();

        //insert data log 
        if ($hasil==true)
        {
        	$this->keterangan="Update obat ".$data_baru[0]['nama']." berhasil";
        }
        else
        {
        	$this->keterangan="Update obat ".$data_baru[0]['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->update_history($this->jenis_log, $this->keterangan, $data_lama, $data_baru);
        return $hasil;
 
    }

    public function insert_master_detail($data)
    {  
    	//check apakah ada data yang sama 
        $ppn = $data['ppn'];
        $kd_suplier = $data['kd_suplier'];
        $no_faktur = $data['no_faktur']; 
        $kode_pembayaran = $data['kode_pembayaran']; 
        $tgl_jatuh_tempo = $data['tgl_jatuh_tempo'];
        $tgl_faktur = $data['tgl_faktur']; 
        $tgl_input = $data['tgl_input']; 
        
        $check = $this->db->query("SELECT * from master_detail_obat WHERE no_faktur='$no_faktur' and kd_suplier='$kd_suplier' and tgl_faktur='$tgl_faktur' and kode_pembayaran='$kode_pembayaran' and tgl_jatuh_tempo='$tgl_jatuh_tempo' and tgl_input=''");
        if ($check->num_rows()>0)
        {
        	//get id 
        }
        else
        {

        }

        $hasil = $this->db->insert('master_detail_obat', $data); 
        $insert_id = $this->db->insert_id();  

        //insert data log 
        if ($hasil==true)
        {
        	$this->keterangan="Transaksi Pembelian No. Faktur '".$data['no_faktur']."' berhasil";
        }
        else
        {
        	$this->keterangan="Transaksi Pembelian No. Faktur '".$data['no_faktur']."' gagal, Pesan : ". $this->db->error()['message'] ;
        } 
        $hasil = $this->M_log->insert_history($this->log_trx_pembelian, $this->keterangan, $data);
        return $insert_id;
    }
	

	 
}