<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siswa extends CI_Controller {
	private $filename = "import_data"; // Kita tentukan nama filenya

	public function __construct()
    {
        parent ::__construct(); 

        $this->logged_in();  
		$this->load->model('SiswaModel');
    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    }  
    
	public function index(){
		$data['siswa'] = $this->SiswaModel->view();
		$this->load->view('view', $data);
	}
  
	
	public function form(){
		$data = array(); // Buat variabel $data sebagai array
		
		if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form
			// lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php 

    		$data['KD_KASDA']=$_POST['KD_KASDA'];  

			$upload = $this->SiswaModel->upload_file($this->filename);
			
			if($upload['result'] == "success"){ // Jika proses upload sukses
				// Load plugin PHPExcel nya
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';
				
				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang tadi diupload ke folder excel
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
				
				// Masukan variabel $sheet ke dalam array data yang nantinya akan di kirim ke file form.php
				// Variabel $sheet tersebut berisi data-data yang sudah diinput di dalam excel yang sudha di upload sebelumnya
				$data['sheet'] = $sheet; 
			}else{ // Jika proses upload gagal
				$data['upload_error'] = $upload['error']; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
    			$data['KD_KASDA']="";  
			}
		}

    	$data['data_kasda'] = $this->MPemeliharaanKasda->getAll();  
    	$data['jenis_aksi']="add"; 
		
		$this->load->view('form', $data);
	}
 
   
	
	public function import(){
		// Load plugin PHPExcel nya
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
		$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		
		// Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
		$data_urusan = array();
		$data_bidang = array();
		$data_unit = array();
		$data_subunit = array();
		
		$numrow = 1; $KD_2=""; $KD_3=""; $KD_4=""; 

		$KD_KASDA = $this->input->post('KD_KASDA2');

		$user_id="000001";

		foreach($sheet as $row){
			// Cek $numrow apakah lebih dari 1
			// Artinya karena baris pertama adalah nama-nama kolom
			// Jadi dilewat saja, tidak usah diimport
			if($numrow > 1){  

				//ccek panjang string di code 
				$kode = strlen($row['A']); 

				//MASUKKAN DATA URUSAN
				if($kode==1)
				{ 
					// Kita push (add) array data ke variabel data
					array_push($data_urusan, array(
						// 'nis'=>$row['A'], // Insert data nis dari kolom A di excel
						'KD_URUSAN'=>$row['A'], // Insert data nama dari kolom B di excel 
						'NM_URUSAN'=>$row['B'], // Insert data alamat dari kolom D di excel
						'USER_UPDATE'=>$user_id, // Insert data alamat dari kolom D di excel
						'USER_INPUT'=>$user_id, // Insert data alamat dari kolom D di excel
					));
				}
				// masukkan data bidang
				else if ($kode==6) {

					//get string  
					$KD_2 = substr($row['A'], 4, 2); // Ambil data NIS 
					$KD_1 = substr($row['A'], 0, 1); // Ambil data NIS    

					// Kita push (add) array data ke variabel data
					array_push($data_bidang, array(
						// 'nis'=>$row['A'], // Insert data nis dari kolom A di excel
						'KD_URUSAN'=>$KD_1, // Insert data nama dari kolom B di excel 
						'KD_BIDANG'=>$KD_2, // Insert data nama dari kolom B di excel 
						'NM_BIDANG'=>$row['B'], // Insert data alamat dari kolom D di excel
						'USER_UPDATE'=>$user_id, // Insert data alamat dari kolom D di excel
						'USER_INPUT'=>$user_id, // Insert data alamat dari kolom D di excel
					));
				}
				//masukkan data unit
				else if ($kode==10) { 

					$KD_2 = substr($row['A'], 4, 2); // Ambil data NIS 
					$KD_1 = substr($row['A'], 0, 1); // Ambil data NIS   
					$KD_3 = substr($row['A'], 8, 2); // Ambil data NIS  

					// Kita push (add) array data ke variabel data
					array_push($data_unit, array(
						// 'nis'=>$row['A'], // Insert data nis dari kolom A di excel
						'KD_URUSAN'=>$KD_1, // Insert data nama dari kolom B di excel 
						'KD_BIDANG'=>$KD_2, // Insert data nama dari kolom B di excel 
						'KD_UNIT'=>$KD_3, // Insert data nama dari kolom B di excel 
						'KD_KASDA'=>$KD_KASDA, // Insert data nama dari kolom B di excel 
						'NM_UNIT'=>$row['B'], // Insert data alamat dari kolom D di excel
						'USER_UPDATE'=>$user_id, // Insert data alamat dari kolom D di excel
						'USER_INPUT'=>$user_id, // Insert data alamat dari kolom D di excel
					));
				}

				//masukkan daat sub unit
				else if ($kode>=14) { 

					$KD_2 = substr($row['A'], 4, 2); // Ambil data NIS 
					$KD_1 = substr($row['A'], 0, 1); // Ambil data NIS   
					$KD_3 = substr($row['A'], 8, 2); // Ambil data NIS 
					$KD_4 = substr($row['A'], 13, 3); // Ambil data NIS  
					// Kita push (add) array data ke variabel data
					array_push($data_subunit, array(
						// 'nis'=>$row['A'], // Insert data nis dari kolom A di excel
						'KD_URUSAN'=>$KD_1, // Insert data nama dari kolom B di excel 
						'KD_BIDANG'=>$KD_2, // Insert data nama dari kolom B di excel 
						'KD_UNIT'=>$KD_3, // Insert data nama dari kolom B di excel 
						'KD_SUB_UNIT'=>$KD_4, // Insert data nama dari kolom B di excel 
						'KD_KASDA'=>$KD_KASDA, // Insert data nama dari kolom B di excel  
						'NM_SUB_UNIT'=>$row['B'], // Insert data alamat dari kolom D di excel
						'USER_UPDATE'=>$user_id, // Insert data alamat dari kolom D di excel
						'USER_INPUT'=>$user_id, // Insert data alamat dari kolom D di excel
					));
				}
				else
				{

				}    
			}
			
			$numrow++; // Tambah 1 setiap kali looping
		}

		// Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		$this->SiswaModel->insert_multiple("ref_urusan", $data_urusan);
		$this->SiswaModel->insert_multiple("ref_bidang", $data_bidang);
		$this->SiswaModel->insert_multiple("ref_unit", $data_unit);
		$this->SiswaModel->insert_multiple("ref_sub_unit", $data_subunit);
		
		redirect("parameterorganisasi/Skpd"); // Redirect ke halaman awal (ke controller siswa fungsi index) 
		// var_dump($data_urusan);
		// var_dump($data_bidang);
		// var_dump($data_unit);
		// var_dump($data_subunit);
	}

	
}
