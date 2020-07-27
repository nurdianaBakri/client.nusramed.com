<?php
class M_kartu_stok extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "kartu_stok";
	private $jenis_log = "Kartu Stok";
	private $keterangan = ""; 

    function getAll($id_obat){ 
        $hasil= $this->db->query('SELECT * FROM get_obat_limit100'); 
        return $hasil->result_array(); 
    } 

    function save($data){
        $hasil=$this->db->insert($this->table, $data);

        //insert data log 
        if ($hasil==true)
        {
        	$this->keterangan="Tambah Kartu stok ".$data['nama']." berhasil";
        }
        else
        {
        	$this->keterangan="Tambah Kartu stok ".$data['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        }
        
        $hasil = $this->M_log->insert_history($this->jenis_log, $this->keterangan, $data);
        return $hasil;
    }  

	
	function update($where, $data) { 

	 
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
	 
}