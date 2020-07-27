<?php
class Transaksi_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	function get($where = NULL){
		$this->db->select('transaksi_obat.*,pegawai.nama as pegawai,count(detail_transaksi.id_obat) as jml_obat,group_concat(obat.nama,": ",detail_transaksi.total) as detail_obat');
		$this->db->from('transaksi_obat');
		$this->db->join('pegawai','transaksi_obat.id_pegawai = pegawai.id_pegawai');
		$this->db->join('detail_transaksi','detail_transaksi.id_transaksi = transaksi_obat.id');
		$this->db->join('obat','detail_transaksi.id_obat = obat.id_obat');
		if($where != NULL){
			$this->db->where($where);
		}
		// $this->db->order_by('id','ASC');
		$this->db->group_by('id_transaksi');
		return $this->db->get();
	}

	function get_detail($where = NULL){
		$this->db->select('detail_transaksi.*,obat.nama as obat,obat.harga as harga_obat');
		$this->db->from('detail_transaksi');
		$this->db->join('obat','detail_transaksi.id_obat = obat.id_obat');
		if($where != NULL){
			$this->db->where($where);
		}
		$this->db->order_by('id_transaksi','ASC');
		// $this->db->group_by('id_transaksi');
		return $this->db->get();
	}
	
	function add($data){
		$query = $this->db->insert('transaksi_obat', $data);
		// $this->db->insert();
		return $query;
	}
	function add_detail($data){
		$query = $this->db->insert('detail_transaksi', $data);
		// $this->db->insert();
		return $query;
	}

	function send_mail($message,$subject,$to,$from){

	    $config = Array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'ssl://smtp.googlemail.com',
		  'smtp_port' => 465,
		  'smtp_user' => 'kfebrianto0@gmail.com', // change it to yours
		  'smtp_pass' => 'bloodyrazz', // change it to yours
		  'mailtype' => 'html',
		  'charset' => 'iso-8859-1',
		  'wordwrap' => TRUE
		);

        // $message = 'testing';
	    $this->load->library('email', $config);
	    $this->email->set_newline("\r\n");
	    $this->email->from($from); // change it to yours
	    $this->email->to($to);// change it to yours
	    $this->email->subject($subject);
	    $this->email->message($message);
	    if($this->email->send())
	    {
	    	// echo 'Email sent.';
	    }else{
	     	show_error($this->email->print_debugger());
	    } 
    }
    
	function update($id, $data) {
        // if($data['password'] != NULL){
        //  $data['password'] = $this->get_hash($data['username'], $data['password']);
        // }
        $this->db->where('kode_transaksi_obat',$id);
        return $this->db->update('transaksi_obat', $data);
    }
	
	function edit($kode_transaksi_obat, $data){
		$this->db->where('kode_transaksi_obat', $kode_transaksi_obat);
		$this->db->update('transaksi_obat', $data);
		return $kode_transaksi_obat;
	}
	
	function delete($id_transaksi_obat){
		$this->db->where('id_transaksi_obat', $id_transaksi_obat);
		return $this->db->delete('transaksi_obat');
	}
	function get_trans($where = NULL){
		$this->db->select('transaksi_obat.*,lapang.kode_lapang as kode_lapang,provider.nama as nama_provider, provider.user_login_id as user_id');
		$this->db->from('customer');
		$this->db->from('transaksi_obat');
		$this->db->join('lapang','transaksi_obat.id_lapang = lapang.id_lapang');
		$this->db->join('provider','lapang.id_provider = provider.id_provider');
		if($where != NULL){
			$this->db->where($where);
			$this->db->where('transaksi_obat.tgl_transaksi_obat IS NOT NULL');
		}
		$this->db->order_by('kode_transaksi_obat','ASC');
		return $this->db->get();
	}
	function get_transconf($where = NULL){
		$this->db->select('transaksi_obat.*,lapang.kode_lapang as kode_lapang,provider.nama as nama_provider, provider.user_login_id as user_id');
		$this->db->from('customer');
		$this->db->from('transaksi_obat');
		$this->db->join('lapang','transaksi_obat.id_lapang = lapang.id_lapang');
		$this->db->join('provider','lapang.id_provider = provider.id_provider');
		if($where != NULL){
			$this->db->where($where);
			$this->db->where('transaksi_obat.tgl_transaksi_obat IS NULL');
		}
		$this->db->order_by('kode_transaksi_obat','ASC');
		return $this->db->get();
	}
}