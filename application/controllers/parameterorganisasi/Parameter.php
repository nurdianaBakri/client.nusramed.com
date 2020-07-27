<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parameter extends CI_Controller {

	public function  pm_bank()
	{
		$this->load->view('pemeliharaan_bank');
	}

	public function  pm_spm()
	{
		$this->load->view('pemeliharaan_jenis_spm');
	}

	public function  pm_map()
	{
		$this->load->view('pemeliharaan_map');
	}

	public function  approval_rek_kasda()
	{
		$this->load->view('approval_rek_kasda');
	}
	public function  pm_akses_rek_koran()
	{
		$this->load->view('pemeliharaan_akses_rek_koran');
	}
}