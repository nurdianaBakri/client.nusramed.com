<?php
class M_trxPenjualan extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

    private $table = "trx_penjualan";
	private $table_trx_penjualan_tmp = "trx_penjualan_tmp";


    //get all transaksi TMP
    function getAll_trx_tmp($tgl_mulai=NULL, $tgl_akhir=NULL){ 

        $hasil ="";
        $data = array();  
        
        $this->db->where('id_user_verifikasi',NULL);
        $this->db->group_by("no_faktur"); // Produces: GROUP BY title
        $this->db->order_by("time","ASC"); // Produces: GROUP BY title

        $hasil=$this->db->get($this->table_trx_penjualan_tmp);  
        
        foreach ($hasil->result_array() as $key) 
        { 
            $this->db->where('id_outlet',$key['kd_outlet']);
            $key['kd_outlet']=$this->db->get('outlet1')->row_array()['nama']; 
            
            $key['alasan_return'] = date_from_datetime($key['time'],3);

            $data[]=$key;
        } 
        return $data;
    }

    public function get_distinct_faktur($tgl_mulai, $tgl_akhir, $status_verifikasi )
    {
        $data = array();
        if ($status_verifikasi=="0" || $status_verifikasi==0)
        {
            $data = $this->db->query("SELECT DISTINCT(no_faktur) as no_faktur, kd_outlet, nama, time from trx_penjualan_tmp, outlet1 WHERE (id_user_verifikasi=''  or id_user_verifikasi is null ) and outlet1.id_outlet=trx_penjualan_tmp.kd_outlet and  `time` between '$tgl_mulai 00:00:01' and '$tgl_akhir 23:59:00' GROUP BY no_faktur  order by time asc")->result_array();  
        }
        else
        {
            $data = $this->db->query("SELECT DISTINCT(no_faktur) as no_faktur, kd_outlet, nama, time from trx_penjualan_tmp, outlet1 WHERE (id_user_verifikasi!=''  or id_user_verifikasi is not null ) and outlet1.id_outlet=trx_penjualan_tmp.kd_outlet and  `time` between '$tgl_mulai 00:00:01' and '$tgl_akhir 23:59:00' GROUP BY no_faktur  order by time asc")->result_array();  
        }

        return $data;
    }

    //get all transaksi TMP
    function trx_tmp_verified(){ 

        $data = array(); 
        $this->db->where('id_user_verifikasi !=',NULL);
        $this->db->group_by("no_faktur"); // Produces: GROUP BY title
        $this->db->order_by("time","ASC"); // Produces: GROUP BY title

        $hasil=$this->db->get($this->table_trx_penjualan_tmp); 
        foreach ($hasil->result_array() as $key) 
        { 
            $this->db->where('id_outlet',$key['kd_outlet']);
            $key['kd_outlet']=$this->db->get('outlet1')->row_array()['nama']; 
            
            $key['alasan_return'] = date_from_datetime($key['time'],3);

            $data[]=$key;
        } 
        return $data;
    }


     function detail_trx_tmp($where){  
        $this->db->where($where);
        $hsl= $this->db->get($this->table_trx_penjualan_tmp); 
        return $hsl;
    }   

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
	
	function update($id_outlet, $data) { 
        $this->db->where('id_outlet',$id_outlet);
        return $this->db->update($this->table, $data);
    } 
	
	function delete($id_outlet){
		$this->db->where('id_outlet', $id_outlet);
		return $this->db->delete($this->table);
	}

    function generate_no_faktur(){
        $this->db->select_max('');
        $this->db->where('id_outlet', $id_outlet);
        return $this->db->delete($this->table);
    }

    
    function get_random_string($valid_chars, $length)
    {
        // start with an empty random string
        // $random_string = "";

        // // count the number of chars in the valid chars string so we know how many choices we have
        // $num_valid_chars = strlen($valid_chars);

        // // repeat the steps until we've created a string of the right length
        // for ($i = 0; $i < $length; $i++)
        // {
        //     // pick a random number from 1 up to the number of valid chars
        //     $random_pick = mt_rand(1, $num_valid_chars);

        //     // take the random character out of the string of valid chars
        //     // subtract 1 from $random_pick because strings are indexed starting at 0, and we started picking at 1
        //     $random_char = $valid_chars[$random_pick-1];

        //     // add the randomly-chosen char onto the end of our string so far
        //     $random_string .= $random_char;
        // }

        //get last nomor urut 
        $no_faktur_explode="";
        $no_faktur_baru="";

        $no_faktur = $this->db->query("SELECT max(SUBSTR(no_faktur,4)) as no_faktur FROM master_trx_penjualan_tmp WHERE nomor_faktur_urut=1");
        if ($no_faktur->num_rows()>0)
        {
            $no_faktur = $no_faktur->row_array()['no_faktur'];
            $no_faktur_baru = substr($no_faktur, 4);
            // $no_faktur = $this->db->query("SELECT max(DISTINCT( SUBSTR(no_faktur, 7, 4))) as no_faktur from trx_penjualan_tmp where nomor_faktur_urut=1")->row_array();
            // $no_faktur_baru =  $no_faktur['no_faktur']; 

        }
        else
        {
             $no_faktur_baru =  "0000";
        } 

        $no_urut_faktur = '686'.date('y').date('m').str_pad($no_faktur_baru + 1, 4, 0, STR_PAD_LEFT);



        // if ($no_faktur==""|| $no_faktur==NULL)
        // {
        //     //buat nomor faktur pertama 
        //     $no_faktur = "0001";
        // }
        // else
        // {
        //     $no_faktur = $this->db->query("SELECT max(DISTINCT( SUBSTR(no_faktur, 7, 4))) as no_faktur from trx_penjualan_tmp where nomor_faktur_urut=1")->row_array()['no_faktur'];

        // }

        // $no_urut_faktur = str_pad($no_faktur + 1, 4, 0, STR_PAD_LEFT);

        // // return our finished random string
        // return '686'.date('y').date('m').$no_urut_faktur;

        return $no_urut_faktur;
    }

    public function get_total_tagihan_per_faktur($no_faktur )
    { 

        $invoice = $this->db->query("SELECT * FROM trx_penjualan_tmp where no_faktur='$no_faktur'")->result(); 

        $total=0;
        $ppn=0;
        $sub_total2=0;
        $jumlah_potongan=0;
        $Potongan=0;
        $sub_total1=0;

        foreach ($invoice as $key) {  
              
            $sub_total1 = ($key->qty*$key->harga_jual)-($key->qty*$key->harga_jual*($key->diskon_per_item/100));
            $Potongan = $key->qty*$key->harga_jual*($key->diskon_per_item/100);
            $jumlah_potongan = $jumlah_potongan+$Potongan;

            $sub_total2 = $sub_total2+ $key->qty*$key->harga_jual; 
             
            $total = $total+$sub_total1;
              // $sub_total= $sub_total+($diskon_per_item/100);
            $ppn=$key->ppn;
 
        }    

        $total2 = $sub_total2-$jumlah_potongan; 
        $ppn2 = ($ppn/100)*$total2; 

        $b_kirim=0; 
        $materai=0; 
        $total_tagihan = $total2+$ppn2+$b_kirim+$materai; 

        return number_format($total_tagihan,2);

    }

}