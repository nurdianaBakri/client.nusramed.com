
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
     
</head>
<!-- <body onload=""> -->
<body onload="window.print()">

    <center>
        <img src="<?php echo base_url()."assets/data_image/logo_nama.PNG";?>">
        <hr>  
    <h3>DATA OBAT</h3>
    </center> 
    
    <table class="table table-striped"> 
        <tr>
            <td style="width:30px;" >Nama</td>
            <td>: <?php echo $data['nama'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Kandungan</td>
            <td>: <?php echo $data['kandungan'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Deskpripsi</td>
            <td>: <?php echo $data['deskripsi'] ?></td>
        </tr>
         <tr>
            <td style="width:30px;" >Jenis Terapi</td>
            <td>: <?php echo $data['jenis_terapi'] ?></td>
        </tr> 
        
        <tr>
            <td colspan="2" class="judul"> 
                <img src="<?= base_url().'assets/barcode/'.$data['barcode'].'.jpg' ?>"  height="150" width="250">  
            </td> 
        </tr>  
    </table>
</body>
</html>
