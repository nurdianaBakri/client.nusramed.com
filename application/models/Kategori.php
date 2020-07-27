<?php
class Kategori extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "kategori";

     function getAll(){
        $hasil=$this->db->get($this->table);
        return $hasil->result_array();
    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);
        return $hasil;
    }
 
    function detail_row_array($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->row_array(); 
        return $hsl;
    }

      function detail_result_array($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->result_array(); 
        return $hsl;
    }   
	
	function update($id_outlet, $data) { 
        $this->db->where('id_outlet',$id_outlet);
        return $this->db->update($this->table, $data);
    }
	
	
	function delete($id_outlet){
		$this->db->where('id_outlet', $id_outlet);
		return $this->db->delete($this->table);
	}
}