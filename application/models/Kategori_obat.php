<?php
class Kategori_obat extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "obat_kategori";

    function getAll(){
        $hasil=$this->db->get($this->table);
        return $hasil->result_array();
    }
 
    function save($data){
        $hasil=$this->db->insert($this->table, $data);
        return $hasil;
    }
 
    function getBy($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->row_array(); 
        return $hsl;
    }

      function getByObject($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->result(); 
        return $hsl;
    }   
	
	function get($where = NULL){
		$this->db->select('*');
		$this->db->from('kategori');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('kd_kategori','ASC');
		return $this->db->get();
	}
	
	function add($data){
		$query = $this->db->insert('kategori', $data);
		// $this->db->insert();
		return $query;
	}
	
	function update($id, $data) { 
        $this->db->where('kd_kategori',$id);
        return $this->db->update('kategori', $data);
    }
	
	function edit($kd_kategori, $data){
		$this->db->where('kd_kategori', $kd_kategori);
		$this->db->update('kategori', $data);
		return $kd_kategori;
	}
	
	function delete($kd_kategori){
		$this->db->where('kd_kategori', $kd_kategori);
		return $this->db->delete('kategori');
	}
}