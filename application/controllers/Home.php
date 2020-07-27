<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller { 


  public function __construct()
    {
        parent ::__construct(); 
        $this->logged_in();  
    } 

    private function logged_in() { 
        if($this->session->userdata('authenticated')!=true) {
            redirect('Login');
        }
    } 

	/*public function  index()
	{   
		$data['title'] ="Dashbord"; 
		$data['SubFitur'] =null; 
         $data['url'] = "Home/";

		$data['breadcrumb'] = array(
			array(
				'fitur' => "Home", 
				'link' => base_url(), 
				'status' => "active", 
			), 
		);

        $this->load->view('include2/sidebar',$data);
        $this->load->view('home/home',$data);
        $this->load->view('include2/footer'); 
	} */

    public function  index()
    {   
        /*$data['title'] ="Dashbord"; 
        $data['SubFitur'] =null; 
         $data['url'] = "Home/";

        $data['breadcrumb'] = array(
            array(
                'fitur' => "Home", 
                'link' => base_url(), 
                'status' => "active", 
            ), 
        );

        $this->load->view('include2/sidebar',$data);*/
        $this->load->view('home/home');
        // $this->load->view('include2/footer'); 
    } 

    public function get_log_activity()
    { 
        $date= date('Y-m-d');
        $date_1 = date('Y-m-d',strtotime("-1 days"));
        $id_user = $this->session->userdata('id');

        $data['today'] = $date;
        $data['today_1'] = $date_1;
        $data['log_activity']=$this->db->query("SELECT tgl_log, keterangan from log_activity_2 WHERE id_user='$id_user' and tgl_log between '$date_1 00:00:00' and '$date 23:59:59' order by tgl_log desc");  
        
        $this->load->view('home/log_activity',$data); 
    }

    public function get_obat_ed()
    {  
        $effectiveDate = date('Y-m-d');  
        $effectiveDate = date('Y-m-d', strtotime("+8 months", strtotime($effectiveDate))); 

        $data['obat_ed']=$this->db->query("SELECT detail_obat.id_obat,tgl_exp,  no_batch, no_reg, obat.nama, obat.barcode from detail_obat, obat where tgl_exp <= '".$effectiveDate." 00:00:00' and detail_obat.id_obat=obat.id_obat "); 

        // var_dump($this->db->last_query()); 
        $this->load->view('home/obat_ed',$data); 
    }

    public function get_obat_not_set_price()
    {
          $data['obat_not_set_price']=$this->db->query("SELECT detail_obat.barcode, detail_obat.harga_jual, no_batch, no_reg, nama from detail_obat, obat where detail_obat.harga_jual <= 0  and obat.barcode=detail_obat.barcode");  
        $this->load->view('home/obat_not_set_price',$data); 
    }

    public function get_penjualan_not_verified()
    {
         $data['penjualan_not_verified']=$this->db->query("SELECT no_faktur, kd_outlet, time, outlet1.nama
            from trx_penjualan_tmp, outlet1 WHERE (id_user_verifikasi='' or id_user_verifikasi is null)
            and outlet1.id_outlet=trx_penjualan_tmp.kd_outlet
            GROUP BY no_faktur ");  
        $this->load->view('home/penjualan_not_verified',$data); 
    }

    public function get_penjualan_jatuh_tempo()
    { 
        $no_faktur = $this->db->query("SELECT distinct(no_faktur), kd_outlet, tgl_jatuh_tempo, outlet1.nama from trx_penjualan_tmp, outlet1 WHERE (id_user_verifikasi!='' or id_user_verifikasi is not null) and tgl_jatuh_tempo not like '0000%' and trx_penjualan_tmp.kd_outlet=outlet1.id_outlet"); 

            $data2 = array();  
            foreach ($no_faktur->result_array() as $key) {
                
                //get jumlah belanja
                $sub_total1 = 0;
                $sub_total2 = 0;
                $jumlah_potongan = 0;
                $ppn = 0;
                $no_faktur2 = $key['no_faktur'];  
 
                 $data['show_button_angsuran']= $this->db->query("SELECT * from riwayat_angsuran WHERE no_faktur='".$no_faktur2."' and lunas=1");
                 if ($data['show_button_angsuran']->row_array()['lunas']==0)
                 {
                     $jumlah_belanja1 = $this->db->query("SELECT qty, diskon_per_item, harga_jual, ppn from trx_penjualan_tmp where no_faktur='$no_faktur2'")->result();

                    foreach ($jumlah_belanja1 as $key2) {
                          //rumus
                        $sum_harga1 = $key2->qty*$key2->harga_jual;

                        $sub_total1 = ($sum_harga1)-($sum_harga1*($key2->diskon_per_item/100));
                        $sub_total2 =  $sub_total2+$sub_total1; 

                        $Potongan = $sum_harga1*($key2->diskon_per_item/100);
                        $jumlah_potongan= $jumlah_potongan + $Potongan; 
                        $ppn=$key2->ppn; 
                    }   

                    $key['harga_jual'] = $sub_total2;
                    $key['jumlah_potongan'] =$jumlah_potongan;
                    $key['harga_kurang_pot'] = $sub_total2-$jumlah_potongan;
                    $key['nilai_pajak'] = $key['harga_kurang_pot']*($ppn/100);
                    $key['harga_stl_pajak'] = $key['harga_kurang_pot']+$key['nilai_pajak'];  

                    $data2[]=$key;
                 } 

                    
            } 

            $data['penjualan_jatuh_tempo'] = $data2;

        $this->load->view('home/penjualan_jatuh_tempo',$data); 
    }

    public function get_masa_berlaku_SIA()
    { 
 
        $effectiveDate = date('Y-m-d');  
        $effectiveDate = date('Y-m-d', strtotime("+3 months", strtotime($effectiveDate)));  

        $data['masa_izin']= $this->db->query("SELECT nama, masa_izin from outlet1 WHERE masa_izin<=$effectiveDate")->result_array();  

        // echo $this->db->last_query();
        $this->load->view('home/get_masa_berlaku_SIA',$data); 
    }

	public function data_line_chart(){
        // $this->load->model()
        // $data = $this-;
        $user = $this->M_login->get();

        $category = array();
        $category['name'] = 'Category';
        $provider = $this->provider_model->get(array('user_login_id'=>$user['id']))->row_array();
        $series1 = array();
        $series1['name'] = 'Transaction';
        $category['data'] = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        for ($i=0; $i < count($category['data']) ; $i++) { 
            # code...
            if($user['role'] == 1){
            	$tot = $this->transaksi_model->get(array('month(tgl_transaksi)'=>($i+1),'year(tgl_transaksi)'=> date('Y')))->num_rows();
			}else{
				$tot = $this->provider_model->get_provider_trans(array('month(tgl_transaksi)'=>($i+1),"provider.id_provider"=>$provider['id_provider'],'year(tgl_transaksi)'=> date('Y')))->num_rows();
			}
            
            
            $series1['data'][] = ($tot!=null)?$tot:0;
        }
        $result = array();
        array_push($result,$category);
        array_push($result,$series1);
        print json_encode($result, JSON_NUMERIC_CHECK);
    }
    
	function check_role(){
		$user = $this->M_login->get();
		if(isset($user)){
			if($user['role'] == 1){  
			}else if($user['role'] == 2){
					redirect('admin_provider');
			}else{
				redirect('welcome');
			}
		}else{
			redirect('login');
		}
	}	
}