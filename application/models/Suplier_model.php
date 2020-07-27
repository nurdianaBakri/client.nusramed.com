<?php
class Suplier_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	} 

	private $table = "suplier";
	private $jenis_log = "Suplier";
    private $keterangan = "";

    function getAll($limit=null){

    	if ($limit==NULL) {
    		$this->db->where('deleted',0);
	        $hasil=$this->db->get($this->table);
	        return $hasil->result_array();
    	}
    	else
    	{
    		$this->db->where('deleted',0); 
    		$this->db->limit($limit); 
	        $hasil=$this->db->get($this->table);
	        return $hasil->result_array();
    	}
	    	
    }

    public function getSuplierForTrxPembelian()
    {
        return $this->db->query('SELECT * from form_trx_pembelian_suplier')->result_array(); 
    } 

    public function getInti()
    {
    	return $this->db->query('SELECT kd_suplier, nama from suplier where deleted=0')->result_array();
    }
 
    function save($data){
         $hasil=$this->db->insert($this->table, $data);
       //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Tambah suplier ".$data['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Tambah suplier ".$data['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        }
        
        $hasil = $this->M_log->insert_history($this->jenis_log, $this->keterangan, $data);
        return $hasil;
    }
 
    function getByRow_array($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->row_array(); 
        return $hsl;
    }

      function getByObject($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->result(); 
        return $hsl;
    }   

     function getByResultArray($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table)->result_array(); 
        return $hsl;
    }
	
	function get($where = NULL){
		$this->db->select('*');
		$this->db->from('suplier');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('kd_industri','ASC');
		return $this->db->get();
	}
	
	function add($data){
		$query = $this->db->insert($this->table, $data); 
		return $query;
	}
	
	function update($where, $data) { 

		//get data lama
        $this->db->where($where);
        $data_lama= $this->db->get($this->table)->result_array();

        //update data
        $this->db->where($where);
        $hasil= $this->db->update($this->table, $data); 

        //get data baru
        $this->db->where($where);
        $data_baru= $this->db->get($this->table)->result_array();

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Update suplier ".$data_baru[0]['nama']." berhasil";
        }
        else
        {
            $this->keterangan="Update suplier ".$data_baru[0]['nama']." gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->update_history($this->jenis_log, $this->keterangan, $data_lama, $data_baru);
        return $hasil;   
    }
	
	function edit($kd_suplier, $data){
		$this->db->where('kd_suplier', $kd_suplier);
		$this->db->update('industri', $data);
		return $kd_suplier;
	}
	
	function delete($where){
		/*$data = array('deleted' => 1 );
		$this->db->where($where); 
		return $this->db->update($this->table, $data);*/

		//get data lama
        $this->db->where($where);
        $data_lama= $this->db->get($this->table)->result_array();

        //hapus 
        $this->db->where($where); 
        $data = array('deleted' => 1 );
        $hasil= $this->db->update($this->table,$data);

        //insert data log 
        if ($hasil==true)
        {
            $this->keterangan="Hapus suplier '".$data_lama[0]['nama']."'  berhasil";
        }
        else
        {
            $this->keterangan="Hapus suplier '".$data_lama[0]['nama']."'  gagal, Pesan : ". $this->db->error()['message'] ;
        } 

        $hasil = $this->M_log->delete_history($this->jenis_log, $this->keterangan, $data_lama);
        return $hasil; 
	}
}