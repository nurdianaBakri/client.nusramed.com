<?php
class M_outlet_PJ extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "outlet_penanggung_jawab";

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
     
	function update($where, $data) { 
        $this->db->where($where);
        return $this->db->update($this->table, $data);
    }
	
	
	function delete($id_outlet){
		$this->db->where('id_outlet', $id_outlet);
		return $this->db->delete($this->table);
	}

    public function get_last_id()
    {
         $this->db->select_max('id_penanggung_jawab');
        $res1 = $this->db->get($this->table)->row_array()['id_penanggung_jawab'];
        $res2 =$res1+1;
        return $res2;
    }
}