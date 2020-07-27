
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Outlet</title> 
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/css/bootstrap.min.css"> 
      <script src="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
      <!-- DataTables -->
      <link rel="stylesheet" href="<?php echo base_url()."assets/" ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 
      <script src="<?php echo base_url()."assets/" ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
      <style type="text/css">
          .judul{
            /*text-align: center;*/
            font-weight: bold;
            font-size: 20px;
          }
      </style>
</head>
<!-- <body onload=""> -->
<body onload="window.print()">

    <center>
        <img src="<?php echo base_url()."assets/data_image/logo_nama.PNG";?>">
        <hr>  
    <h3>DATA OUTLET</h3>
    </center>

    <table class="table table-striped">
        <tr>
            <td colspan="2" class="judul">Data Outlet</td> 
        </tr>
        <tr>
            <td style="width:30px;" >Nama</td>
            <td>: <?php echo $outlet['nama'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Alamat</td>
            <td>: <?php echo $outlet['alamat'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >NPWP</td>
            <td>: <?php echo $outlet['npwp'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. Telp</td>
            <td>: <?php echo $outlet['no_telp'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. SIA/klinik/RS/TO</td>
            <td>: <?php echo $outlet['no_izin'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Masa Berlaku</td>
            <td>: <?php echo $outlet['masa_izin'] ?></td>
        </tr>

        <tr>
            <td colspan="2" class="judul">Data Pemilik</td> 
        </tr>
        <tr>
            <td style="width:30px;" >NIK</td>
            <td>: <?php echo $outlet['no_ktp_pemilik'] ?></td>
        </tr>
        <tr>
            <td style="width:30px;" >Nama</td>
            <td>: <?php echo $outlet['nm_pemilik'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Alamat</td>
            <td>: <?php echo $outlet['alamat_pemilik'] ?></td>
        </tr>
        
        <tr>
            <td colspan="2" class="judul">Data Penanggung Jawa</td> 
        </tr>
        <tr>
            <td style="width:30px;" >NIK</td>
            <td>: <?php echo $penanggung_jawab['no_ktp_pj'] ?></td>
        </tr>
        <tr>
            <td style="width:30px;" >Nama</td>
            <td>: <?php echo $penanggung_jawab['nama_pj'] ?></td>
        </tr>
        <tr>
            <td style="width:30px;" >Nomor Telp</td>
            <td>: <?php echo $penanggung_jawab['no_telp_pj'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. STR</td>
            <td>: <?php echo $penanggung_jawab['no_str_pj'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Masa Berlaku STR</td>
            <td>: <?php echo $penanggung_jawab['masa_str'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. SIPA</td>
            <td>: <?php echo $penanggung_jawab['no_sipa_pj'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Masa Berlaku SIPA</td>
            <td>: <?php echo $penanggung_jawab['masa_sipa'] ?></td>
        </tr>

        <tr>
            <td colspan="2" class="judul">Data TTK</td> 
        </tr>
        <tr>
            <td style="width:30px;" >NIK</td>
            <td>: <?php echo $ttk['nik'] ?></td>
        </tr>
        <tr>
            <td style="width:30px;" >Nama</td>
            <td>: <?php echo $ttk['nama'] ?></td>
        </tr>
        <tr>
            <td style="width:30px;" >Nomor Telp</td>
            <td>: <?php echo $ttk['no_telp'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Alamat</td>
            <td>: <?php echo $ttk['alamat'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. SIK TTK</td>
            <td>: <?php echo $ttk['no_sikttk'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Masa berlaku SIK TTK</td>
            <td>: <?php echo $ttk['masa_sikttk'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >No. STR TTK</td>
            <td>: <?php echo $ttk['no_strttk'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Masa Berlaku STR TTK</td>
            <td>: <?php echo $ttk['masa_strttk'] ?></td>
        </tr> 
        
    </table>
</body>
</html>
