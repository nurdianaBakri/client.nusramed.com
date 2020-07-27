<?php
class Fasilitas_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function get($where = NULL){
		$this->db->select('*');
		$this->db->from('fasilitas');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('id_fasilitas','ASC');
		return $this->db->get();
	}
	
	function add($data){
		$query = $this->db->insert('fasilitas', $data);
		// $this->db->insert();
		return $query;
	}
	
	function update($id, $data) {
        // if($data['password'] != NULL){
        //  $data['password'] = $this->get_hash($data['username'], $data['password']);
        // }
        $this->db->where('id_fasilitas',$id);
        return $this->db->update('fasilitas', $data);
    }
	
	function edit($id_fasilitas, $data){
		$this->db->where('id_fasilitas', $id_fasilitas);
		$this->db->update('fasilitas', $data);
		return $id_fasilitas;
	}
	
	function delete($id_fasilitas){
		$this->db->where('id_fasilitas', $id_fasilitas);
		return $this->db->delete('fasilitas');
	}
}