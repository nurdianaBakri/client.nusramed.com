<?php
class M_metode_pembayaran extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "metode_pembayaran";

    function getAll(){
        $this->db->where('deleted',0);
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
	
	function update($id_outlet, $data) { 
        $this->db->where('id_outlet',$id_outlet);
        return $this->db->update($this->table, $data);
    } 
	
	function hapus($where, $data){
		$this->db->where($where);
		return $this->db->update($this->table, $data);
	}
}