<?php
class M_outlet extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}  

	private $table = "outlet1";
    private $jenis_log = "Outlet";
    private $keterangan = "";

    function getAll(){
       /* $this->db->where('deleted','0'); 
        $hasil=$this->db->get($this->table); 
        return $hasil->result_array();*/ 
        $hasil= $this->db->query('SELECT * FROM get_outlet_limit100'); 
        return $hasil->result_array();

    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);

         //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Tambah outlet ".$data['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Tambah outlet ".$data['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        }
        
        $hasil = $this->M_log->insert_history($this->jenis_log, $this->keterangan, $data);
        return $hasil; 
    }
  
      function detail($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table); 
        return $hsl;
    }   
	
	function update($id_outlet, $data) {  

        //get data lama 
         $this->db->where('id_outlet',$id_outlet); 
        $data_lama= $this->db->get($this->table)->result_array();

        //update data 
        $this->db->where('id_outlet',$id_outlet);
        $hasil= $this->db->update($this->table, $data);
 
        //get data baru 
         $this->db->where('id_outlet',$id_outlet); 

        $data_baru= $this->db->get($this->table)->result_array();

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Update outlet ".$data_baru[0]['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Update outlet ".$data_baru[0]['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->update_history($this->jenis_log, $this->keterangan, $data_lama, $data_baru);
        return $hasil;

    } 
	
	function hapus($where, $data){
		/*$this->db->where($where);
		return $this->db->update($this->table, $data);*/

        //get data lama
        $this->db->where($where);
        $data_lama= $this->db->get($this->table)->result_array();

        //update data hapu data
        $this->db->where($where); 
        $data = array('deleted' => 1 );
        $hasil= $this->db->update($this->table,$data);

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Hapus outlet ".$data_lama[0]['nama']."  berhasil";
        }
        else
        {
            $this->keterangan="Hapus outlet ".$data_lama[0]['nama']."  gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->delete_history($this->jenis_log, $this->keterangan, $data_lama);
        return $hasil;
	}
}