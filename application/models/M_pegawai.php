<?php
class M_pegawai extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "pegawai";

    function getAll(){
        $hasil=$this->db->get($this->table);
        return $hasil->result_array();
    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);
        return $hasil;
    }
  
      function detail($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table); 
        return $hsl;
    }   
	
	function update($id_pegawai, $data) { 
        $this->db->where('id_pegawai',$id_pegawai);
        return $this->db->update($this->table, $data);
    } 
	
	function delete($id_pegawai){
		$this->db->where('id_pegawai', $id_pegawai);
		return $this->db->delete($this->table);
	}
}