
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
     
</head>
<!-- <body onload=""> -->
<body onload="window.print()">

    <center>
        <img src="<?php echo base_url()."assets/data_image/logo_nama.PNG";?>">
        <hr>  
    <h3>DATA BARCODE OBAT</h3>
    </center>

    <?php
    $obat = $this->db->get('obat')->result();

    // var_dump($obat)
    foreach ($obat as $key => $data) {
        if ($key % 3 == 0) {
            echo '<div class = "row">';
        }

        echo "<div class ='col-md-4'>
               "; 
            echo "<img src='".base_url()."assets/barcode/".$data->barcode.".jpg'>";
            echo "<br>".$data->nama."<hr> </div> ";

        if ($key % 3 == 2) {
            echo '</div>';
        }
    }
    ?>
 
</body>
</html>
