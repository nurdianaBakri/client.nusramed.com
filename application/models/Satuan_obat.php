<?php
class Satuan_obat extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "satuan";

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
		$this->db->from('satuan');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('kd_satuan','ASC');
		return $this->db->get();
	}
	
	function add($data){
		$query = $this->db->insert('satuan', $data);
		// $this->db->insert();
		return $query;
	}
	
	function update($id, $data) { 
        $this->db->where('kd_satuan',$id);
        return $this->db->update('satuan', $data);
    }
	
	function edit($kd_satuan, $data){
		$this->db->where('kd_satuan', $kd_satuan);
		$this->db->update('satuan', $data);
		return $kd_satuan;
	}
	
	function delete($kd_satuan){
		$this->db->where('kd_satuan', $kd_satuan);
		return $this->db->delete('satuan');
	}
}