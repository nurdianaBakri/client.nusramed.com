<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Skpd extends CI_Controller {

	private $filename = "import_data"; // Kita tentukan nama filenya 
	public function __construct(){
        parent::__construct();
        $this->load->model('SiswaModel'); 
    }
	  
	public function  index()
	{  
		$this->load->view('include/header');
		$this->load->view('import_skpd/index');
        $this->load->view('include/footer');   
	} 

    public function get_form()
    {   
        $data['data_kasda'] = $this->MPemeliharaanKasda->getAll();   
        $data['jenis_aksi'] = "add";
        $this->load->view('import_skpd/form', $data); 
    }

    public function detail($KD_SKPD=null )
    { 
        $where = array('KD_SKPD' => $KD_SKPD );
        $data['data']=$this->MImpoertSKPD->getBy($where); 
        if ($KD_SKPD==null)
        {  
            $data['jenis_aksi']="add";
        	$data['data_kasda'] = $this->MPemeliharaanKasda->getAll();   
        }
        else
        {
            $data['jenis_aksi']="edit";  
        } 

        $data['urusan'] = $this->MPemeliharaanUrusan->getAll();
        // $this->load->view('form', $data); 
        $this->load->view('import_skpd/form', $data); 
    }

 	public function hapus($KD_SKPD)
    {
        $hasil = array(); 
        $where = array('KD_SKPD' => $KD_SKPD );
        $hasil['status']=$this->MImpoertSKPD->hapus($where);    
        
        if ($hasil['status']==true)
        {
            $hasil['pesan'] = "Proses hapus data SKPD berhasil";
        }
        else
        {
            $hasil['pesan'] = "Proses hapus data SKPD berhasil";  
        }
        echo json_encode($hasil);
    }

     public function save2()
	{   
        $jenis_aksi = $this->input->post('jenis_aksi'); 
        $data = array( 
            'KD_KASDA' => $this->input->post('KD_KASDA'), 
        );

        // //aksi Tambah 
        $hasil = array();
        if($jenis_aksi=="add"){
            $hasil['status'] = $this->MImpoertSKPD->save($data);  
        }

        //aksi edit
        else{
            $where = array('KD_DATA_BIDANG' => $this->input->post('KD_DATA_BIDANG') );
            $hasil['status'] = $this->MImpoertSKPD->update($where, $data);   
        } 

        if($hasil['status']==true && $jenis_aksi=="add"){
            $hasil['pesan'] ="Proses Tambah data Bidang berhasil";
        }
        else if ($hasil['status']==false && $jenis_aksi=="add")
        {
            $hasil['pesan'] ="Proses Tambah data Bidang gagal"; 
        }
        else if ($hasil['status']==true && $jenis_aksi=="edit")
        {
            $hasil['pesan'] ="Proses ubah data Bidang berhasil"; 
        }
        else
        {
            $hasil['pesan'] ="Proses ubah data Bidang gagal";  
        }  

        $hasil['data'] = $data;
        $hasil['jenis_aksi']=$jenis_aksi; 
        echo json_encode($hasil);
	} 

    public function cek_file()
    {
        $data = array();
        $upload = $this->SiswaModel->upload_file($this->filename);
        $KD_KASDA=$this->input->post('KD_KASDA');
            
        if($upload['result'] == "success"){ // Jika proses upload sukses
            // Load plugin PHPExcel nya
            include APPPATH.'third_party/PHPExcel/PHPExcel.php';
            
            $excelreader = new PHPExcel_Reader_Excel2007();
            $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang tadi diupload ke folder excel
            $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
            
            // Masukan variabel $sheet ke dalam array data yang nantinya akan di kirim ke file form.php
            // Variabel $sheet tersebut berisi data-data yang sudah diinput di dalam excel yang sudha di upload sebelumnya
            $data['sheet'] = $sheet; 
            $data['KD_KASDA'] = $KD_KASDA;  

            $data['data_kasda'] = $this->MPemeliharaanKasda->getAll();   
            
        }else{ // Jika proses upload gagal
            $data['upload_error'] = $upload['error']; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
            $data['KD_KASDA']="";  
        }

        $this->load->view('import_skpd/cek_file',$data);
    }


    public function form(){
        $data = array(); // Buat variabel $data sebagai array
        
        if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form
            // lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php


            $data['KD_KASDA']=$_POST['KD_KASDA2'];  

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
        else
        {
            $data['KD_KASDA']=0;
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

        $KD_KASDA = $this->input->post('KD_KASDA');

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
        $cek1 = $this->MImpoertSKPD->insert_multiple("ref_urusan", $data_urusan);
        $cek2 =$this->SiswaModel->insert_multiple("ref_bidang", $data_bidang);
        $cek3 =$this->SiswaModel->insert_multiple("ref_unit", $data_unit);
        $cek4 =$this->SiswaModel->insert_multiple("ref_sub_unit", $data_subunit); 

        $this->session->set_flashdata('pesan',"Proses import data selesai ");
        
        redirect("parameterorganisasi/Skpd"); // Redirect ke halaman awal (ke controller siswa fungsi index)  
    }
	 

}
