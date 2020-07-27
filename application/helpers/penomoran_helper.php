<?php  if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
} 

    if (!function_exists('date_from_datetime')) {
        function date_from_datetime($date, $jenis)
        {  
            if ($jenis==1){
                 $dt = new DateTime($date); 
                $date = $dt->format('Y-m-d'); 
                return $date;
            } 
            else if ($jenis==2)
            {
               $dt = new DateTime($date); 
                $date = $dt->format('d M Y'); 
                return $date;
            }
            else if ($jenis==3)
            {
               $dt = new DateTime($date); 
                $date = $dt->format('d M Y H:m:s'); 
                return $date;
            }

             else if ($jenis==4)
            {
               $dt = new DateTime($date); 
                $date = $dt->format('d-m-Y H:m'); 
                return $date;
            }
            else
            {   

            }
        } 
    }

    if (!function_exists('date_beetwen')) {
        function date_beetwen($date1, $date2)
        {  
            // Declare and define two dates  
              
            // Formulate the Difference between two dates 
            $diff = abs($date2 - $date1);  
              
              
            // To get the year divide the resultant date into 
            // total seconds in a year (365*60*60*24) 
            $years = floor($diff / (365*60*60*24));  
              
              
            // To get the month, subtract it with years and 
            // divide the resultant date into 
            // total seconds in a month (30*60*60*24) 
            $months = floor(($diff - $years * 365*60*60*24) 
                                           / (30*60*60*24));  
              
              
            // To get the day, subtract it with years and  
            // months and divide the resultant date into 
            // total seconds in a days (60*60*24) 
            $days = floor(($diff - $years * 365*60*60*24 -  
                         $months*30*60*60*24)/ (60*60*24)); 
              
              
            // To get the hour, subtract it with years,  
            // months & seconds and divide the resultant 
            // date into total seconds in a hours (60*60) 
            $hours = floor(($diff - $years * 365*60*60*24  
                   - $months*30*60*60*24 - $days*60*60*24) 
                                               / (60*60));  
              
              
            // To get the minutes, subtract it with years, 
            // months, seconds and hours and divide the  
            // resultant date into total seconds i.e. 60 
            $minutes = floor(($diff - $years * 365*60*60*24  
                     - $months*30*60*60*24 - $days*60*60*24  
                                      - $hours*60*60)/ 60);  
              
              
            // To get the minutes, subtract it with years, 
            // months, seconds, hours and minutes  
            $seconds = floor(($diff - $years * 365*60*60*24  
                     - $months*30*60*60*24 - $days*60*60*24 
                            - $hours*60*60 - $minutes*60));  
              
            // Print the result 
            return  $days;  
        }
    } 
      
    
    if (!function_exists('rupiah')) {
        function rupiah($angka)
        {
            $jd = number_format($angka, 2, ',', '.');
            return "Rp".$jd;
        }
    }
    if (!function_exists('terbilang')) {
        function terbilang($angka)
        {
            $angka = (float)$angka;
            $bilangan = array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan','Sepuluh','Sebelas');
            if ($angka < 12) {
                return $bilangan[$angka];
            } elseif ($angka < 20) {
                return $bilangan[$angka - 10] . ' Belas';
            } elseif ($angka < 100) {
                $hasil_bagi = (int)($angka / 10);
                $hasil_mod = $angka % 10;
                return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
            } elseif ($angka < 200) {
                return sprintf('Seratus %s', terbilang($angka - 100));
            } elseif ($angka < 1000) {
                $hasil_bagi = (int)($angka / 100);
                $hasil_mod = $angka % 100;
                return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
            } elseif ($angka < 2000) {
                return trim(sprintf('Seribu %s', terbilang($angka - 1000)));
            } elseif ($angka < 1000000) {
                $hasil_bagi = (int)($angka / 1000);
                $hasil_mod = $angka % 1000;
                return sprintf('%s Ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
            } elseif ($angka < 1000000000) {
                $hasil_bagi = (int)($angka / 1000000);
                $hasil_mod = $angka % 1000000;
                return trim(sprintf('%s Juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
            } elseif ($angka < 1000000000000) {
                $hasil_bagi = (int)($angka / 1000000000);
                $hasil_mod = fmod($angka, 1000000000);
                return trim(sprintf('%s Milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
            } elseif ($angka < 1000000000000000) {
                $hasil_bagi = $angka / 1000000000000;
                $hasil_mod = fmod($angka, 1000000000000);
                return trim(sprintf('%s Triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
            } else {
                return 'Data Salah';
            }
        }
    }

     if (! function_exists('tgl_indo')) {
         function date_indo($tgl)
         {
             $ubah = gmdate($tgl, time()+60*60*8);
             $pecah = explode("-", $ubah);
             $tanggal = $pecah[2];
             $bulan = bulan($pecah[1]);
             $tahun = $pecah[0];
             return $tanggal.' '.$bulan.' '.$tahun;
         }
     }
     if (! function_exists('tgl_indo')) {
         function tgl_bulan($tgl)
         {
             $ubah = gmdate($tgl, time()+60*60*8);
             $pecah = explode("-", $ubah);
             $tanggal = $pecah[2];
             $bulan = $pecah[1];
             $tahun = $pecah[0];
             return $tanggal.'/'.$bulan;
         }
     }
    if (! function_exists('tahun')) {
        function tahun($tgl)
        {
            $ubah = gmdate($tgl, time()+60*60*8);
            $pecah = explode("-", $ubah);
            $tanggal = $pecah[2];
            $bulan = bulan($pecah[1]);
            $tahun = $pecah[0];
            return $tahun;
        }
    }
      
    if (! function_exists('bulan')) {
        function bulan($bln)
        {
            switch ($bln) {
                case 1:
                    return "Januari";
                    break;
                case 2:
                    return "Februari";
                    break;
                case 3:
                    return "Maret";
                    break;
                case 4:
                    return "April";
                    break;
                case 5:
                    return "Mei";
                    break;
                case 6:
                    return "Juni";
                    break;
                case 7:
                    return "Juli";
                    break;
                case 8:
                    return "Agustus";
                    break;
                case 9:
                    return "September";
                    break;
                case 10:
                    return "Oktober";
                    break;
                case 11:
                    return "November";
                    break;
                case 12:
                    return "Desember";
                    break;
            }
        }
    }
 
    //Format Shortdate
    if (! function_exists('shortdate_indo')) {
        function shortdate_indo($tgl)
        {
            $a= str_replace(" ", "-", $tgl);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            $jam = $pecah[3];
            $min = $pecah[4];
            $sec = $pecah[5];
            $bulan = short_bulan($pecah[1]);
            return $tgl.'/'.$bulan.'/'.$thn;
        }
    }
      
    if (! function_exists('short_bulan')) {
        function short_bulan($bln)
        {
            switch ($bln) {
                case 1:
                    return "01";
                    break;
                case 2:
                    return "02";
                    break;
                case 3:
                    return "03";
                    break;
                case 4:
                    return "04";
                    break;
                case 5:
                    return "05";
                    break;
                case 6:
                    return "06";
                    break;
                case 7:
                    return "07";
                    break;
                case 8:
                    return "08";
                    break;
                case 9:
                    return "09";
                    break;
                case 10:
                    return "10";
                    break;
                case 11:
                    return "11";
                    break;
                case 12:
                    return "12";
                    break;
            }
        }
    }
 
    //Format Medium date
    if (! function_exists('mediumdate_indo')) {
        function mediumdate_indo($tgl)
        {
            $ubah = gmdate($tgl, time()+60*60*8);
            $pecah = explode("-", $ubah);
            $tanggal = $pecah[2];
            $bulan = medium_bulan($pecah[1]);
            $tahun = $pecah[0];
            return $tanggal.'-'.$bulan.'-'.$tahun;
        }
    }
      
    if (! function_exists('medium_bulan')) {
        function medium_bulan($bln)
        {
            switch ($bln) {
                case 1:
                    return "Jan";
                    break;
                case 2:
                    return "Feb";
                    break;
                case 3:
                    return "Mar";
                    break;
                case 4:
                    return "Apr";
                    break;
                case 5:
                    return "Mei";
                    break;
                case 6:
                    return "Jun";
                    break;
                case 7:
                    return "Jul";
                    break;
                case 8:
                    return "Ags";
                    break;
                case 9:
                    return "Sep";
                    break;
                case 10:
                    return "Okt";
                    break;
                case 11:
                    return "Nov";
                    break;
                case 12:
                    return "Des";
                    break;
            }
        }
    }
     
    //Long date indo Format
    if (! function_exists('longdate_indo')) {
        function longdate_indo($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            // $jam = $pecah[3];
            // $min = $pecah[4];
            // $sec = $pecah[5];
            $bulan = bulan($pecah[1]);
      
            $nama = date("l", mktime(12, 30, 30, $bln, $tgl, $thn));
            $nama_hari = "";
            if ($nama=="Sunday") {
                $nama_hari="Minggu";
            } elseif ($nama=="Monday") {
                $nama_hari="Senin";
            } elseif ($nama=="Tuesday") {
                $nama_hari="Selasa";
            } elseif ($nama=="Wednesday") {
                $nama_hari="Rabu";
            } elseif ($nama=="Thursday") {
                $nama_hari="Kamis";
            } elseif ($nama=="Friday") {
                $nama_hari="Jumat";
            } elseif ($nama=="Saturday") {
                $nama_hari="Sabtu";
            }
            // return $nama_hari.','.$tgl.' '.$bulan.' '.$thn.' '.$jam.':'.$min;
            return $nama_hari.', '.$tgl.' '.$bulan.' '.$thn;
        }
    }
    //Long date indo Format 2
    if (! function_exists('longdate_indo2')) {
        function longdate_indo2($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            // $jam = $pecah[3];
            // $min = $pecah[4];
            // $sec = $pecah[5];
            $bulan = bulan($pecah[1]);
      
            $nama = date("l", mktime(12, 30, 30, $bln, $tgl, $thn));
            $nama_hari = "";
            if ($nama=="Sunday") {
                $nama_hari="Minggu";
            } elseif ($nama=="Monday") {
                $nama_hari="Senin";
            } elseif ($nama=="Tuesday") {
                $nama_hari="Selasa";
            } elseif ($nama=="Wednesday") {
                $nama_hari="Rabu";
            } elseif ($nama=="Thursday") {
                $nama_hari="Kamis";
            } elseif ($nama=="Friday") {
                $nama_hari="Jumat";
            } elseif ($nama=="Saturday") {
                $nama_hari="Sabtu";
            }
            // return $nama_hari.','.$tgl.' '.$bulan.' '.$thn.' '.$jam.':'.$min;
            return $nama_hari.' tanggal '.$tgl.' '.$bulan.' '.$thn;
        }
    }
    if (! function_exists('periode')) {
        function periode($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            // $jam = $pecah[3];
            // $min = $pecah[4];
            // $sec = $pecah[5];
            $bulan = bulan($pecah[1]);
      
            $nama = date("l", mktime(12, 30, 30, $bln, $tgl, $thn));
            $nama_hari = "";
            if ($nama=="Sunday") {
                $nama_hari="Minggu";
            } elseif ($nama=="Monday") {
                $nama_hari="Senin";
            } elseif ($nama=="Tuesday") {
                $nama_hari="Selasa";
            } elseif ($nama=="Wednesday") {
                $nama_hari="Rabu";
            } elseif ($nama=="Thursday") {
                $nama_hari="Kamis";
            } elseif ($nama=="Friday") {
                $nama_hari="Jumat";
            } elseif ($nama=="Saturday") {
                $nama_hari="Sabtu";
            }
            // return $nama_hari.','.$tgl.' '.$bulan.' '.$thn.' '.$jam.':'.$min;
            return $bulan.' '.$thn;
        }
    }
    if (! function_exists('id_hari')) {
        function id_hari($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            $jam = $pecah[3];
            $min = $pecah[4];
            $sec = "00";
            $bulan = bulan($pecah[1]);
      
            $id_hari = date("N", mktime((int)$jam, (int)$min, (int)$sec, $bln, $tgl, $thn));
            return $id_hari;
        }
    }
    if (! function_exists('waktu')) {
        function waktu($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            //$ubah = gmdate($tanggal, time()+60*60*8);
            $pecah = explode("-", $b);
            $tgl = $pecah[2];
            $bln = $pecah[1];
            $thn = $pecah[0];
            $jam = $pecah[3];
            $min = $pecah[4];
            $sec = "00";
            $bulan = bulan($pecah[1]);
      
            $id_hari = date("l", mktime((int)$jam, (int)$min, (int)$sec, $bln, $tgl, $thn));
            return $jam.':'.$min.':'.$sec;
        }
    }
    if (! function_exists('format_date')) {
        function format_date($tanggal)
        {
            $a= str_replace(" ", "-", $tanggal);
            $b= str_replace(":", "-", $a);
            
            return $b;
        }
    }
