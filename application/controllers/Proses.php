<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proses extends CI_Controller {

	public function rekon_pencairan_sp2d()
	{
		$this->load->view('rekon_pencairan_sp2d');
	}
	public function bt_kasda()
	{
		$this->load->view('buka_tutup_kasda');
	}
}
