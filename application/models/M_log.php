<?php
class M_log extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

	private $table = "log_activity_2";

    function getAll(){ 
        $hasil=$this->db->get($this->table); 
        return $hasil->result_array();
    }
 
    function tambah($id_user, $ket_log){ 

        $data = array(
            'id_user' => $id_user, 
            'keterangan' => $ket_log,  
            'tgl_log' => date('Y-m-d H:m:s'),  
        );
        $hasil=$this->db->insert($this->table, $data);
        return $hasil;
    }

    public function tambah_2($id_user, $ket_log)
    {
        $data = array(
            'id_user' => $id_user, 
            'keterangan' => $ket_log,  
            'tgl_log' => date('Y-m-d H:m:s'),  
        );
        $hasil=$this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        return  $insert_id;
    }

    function tambah_detail($data){   
        $hasil=$this->db->insert('log_activity_detail', $data);
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

    public function insert_history($jenis_log, $keterangan, $data_baru)
    {
        //get user yang sedang login, 
        $id_user = $this->session->userdata('id');
        $data = array(
            'data_lama' => json_encode( array()), 
            'data_baru' => json_encode($data_baru), 
            'id_user' => $id_user, 
            'tgl_log' => date('Y-m-d h:m:s'), 
            'jenis_log' => $jenis_log, 
            'keterangan' => $keterangan, 
        );
        return $this->db->insert('log_activity', $data);
    }

    public function update_history($jenis_log, $keterangan, $data_lama, $data_baru)
    { 
        //get user yang sedang login, 
        $id_user = $this->session->userdata('id');
        $data = array(
            'data_lama' => json_encode($data_lama), 
            'data_baru' => json_encode($data_baru), 
            'id_user' => $id_user, 
            'tgl_log' => date('Y-m-d h:m:s'), 
            'jenis_log' => $jenis_log, 
            'keterangan' => $keterangan, 
        );
        return $this->db->insert('log_activity', $data);
    }

     public function delete_history($jenis_log, $keterangan, $data_lama)
    {
        //get user yang sedang login, 
        $id_user = $this->session->userdata('id');
        $data = array(
            'data_lama' => json_encode($data_lama), 
            'data_baru' => json_encode(array( )), 
            'id_user' => $id_user, 
            'tgl_log' => date('Y-m-d h:m:s'), 
            'jenis_log' => $jenis_log, 
            'keterangan' => $keterangan, 
        );
        return $this->db->insert('log_activity', $data);
    }
}