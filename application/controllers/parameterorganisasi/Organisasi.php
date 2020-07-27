<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organisasi extends CI_Controller {

	 
	public function  pm_urusan()
	{
		$this->load->view('pemeliharaan_urusan');
	}
		public function  pm_bidang()
	{
		$this->load->view('pemeliharaan_bidang/pemeliharaan_bidang');
	}
		public function  pm_unit()
	{
		$this->load->view('pemeliharaan_unit');
	}
		public function  pm_sunit()
	{
		$this->load->view('pemeliharaan_subunit');
	}
		public function  import_skpd()
	{
		$this->load->view('import_skpd');
	}
}
