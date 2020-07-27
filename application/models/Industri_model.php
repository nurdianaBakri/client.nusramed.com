<?php
class Industri_model extends CI_Model {


	private $table = "industri"; 
    private $jenis_log = "Industri";
    private $keterangan = "";
	
	function __construct() {
		parent::__construct();
	}
	
	function get($where = NULL){
		$this->db->select('*');
		$this->db->from($this->table);
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('time','DESC');
		return $this->db->get();
	} 

    function getAll(){
        $this->db->where('deleted',0);
        $hasil=$this->db->get($this->table);
        return $hasil->result_array();
    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);
       //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Tambah industri ".$data['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Tambah industri ".$data['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        }
        
        $hasil = $this->M_log->insert_history($this->jenis_log, $this->keterangan, $data);
        return $hasil;
    }
 
    function getBy($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->row_array(); 
        return $hsl;
    }

     function detail($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table); 
        return $hsl;
    }

      function getByObject($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->result(); 
        return $hsl; 
	}

	function update($where, $data) {  

        //get data lama
        $this->db->where($where);
        $data_lama= $this->db->get($this->table)->result_array();

        //update data
        $this->db->where($where);
        $hasil= $this->db->update($this->table, $data);

        //get data baru
        $this->db->where($where);
        $data_baru= $this->db->get($this->table)->result_array();

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Update industri ".$data_baru[0]['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Update industri ".$data_baru[0]['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->update_history($this->jenis_log, $this->keterangan, $data_lama, $data_baru);
        return $hasil;  
    } 
	
	function hapus($where){ 

        //get data lama
        $this->db->where($where);
        $data_lama= $this->db->get($this->table)->result_array();

        //hapus
        /*$this->db->where($where);
        $hasil= $this->db->delete($this->table); */
        $this->db->where($where); 
        $data = array('deleted' => 1 );
        $hasil= $this->db->update($this->table,$data);

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Hapus industri '".$data_lama[0]['nama']."'  berhasil";
        }
        else
        {
            $this->keterangan="Hapus industri '".$data_lama[0]['nama']."'  gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->delete_history($this->jenis_log, $this->keterangan, $data_lama);
        return $hasil;
	}
}