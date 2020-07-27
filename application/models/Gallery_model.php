<?php
class Gallery_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function get($where = NULL){
		$this->db->select('*');
		$this->db->from('provider_gallery');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('id','ASC');
		return $this->db->get();
	}
	function getdp($where = NULL){
		$this->db->select('*');
		$this->db->from('provider_gallery');
		$this->db->where('is_display_picture = 1');
		$this->db->order_by('id','ASC');
		return $this->db->get();
	}
	function delete_img($id){
		$this->db->where('id', $id);
		return $this->db->delete('provider_gallery');
	}
	function edit_img($where, $data){
		$this->db->where($where);
		return $this->db->update('provider_gallery', $data);
	}
	function add_img_gallery($data){
		$query = $this->db->insert('provider_gallery', $data);
		// $this->db->insert();
		return $this->db->insert_id(); 
	}
}