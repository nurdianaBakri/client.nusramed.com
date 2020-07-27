
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
    <h3>Nota Pembelian Barang</h3>
    </center>

    <table class="table table-striped">

        <thead>
          <tr >
            <th> # </th>
            <th> Barcode</th>
            <th> Nama Obat</th>
            <th> No Registrasi</th>
            <th> No Batch</th>
            <th style="width:10%;"> Tgl Exp</th>
            <th> Qty</th>
            <th style="width:10%;"> Harga Beli</th>
            <th> Diskon %</th>
            <th> Harga setelah diskon</th>
            <!-- <th style="width:10%;"> Harga Jual</th> -->
            <th style="width:10%;">Lokasi</th>
          </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            foreach ($data as $key => $value) {
                ?> 
                <tr>
                    <td> <?= $i++; ?> </td>
                    <td> <?= $value['barcode'] ?> </td>
                    <td> <?php
                        $this->db->where('barcode',$value['barcode']);
                        $this->db->select('nama');
                        echo $this->db->get('obat')->row_array()['nama'];  ?>  </td>
                    <td> <?= $value['no_reg'] ?>  </td>
                    <td> <?= $value['no_batch'] ?>  </td>
                    <td> <?php  
                        $s = strtotime($value['tgl_exp']); 
                        echo date('d m Y', $s);   ?>  </td>
                    <td> <?= $value['stok_awal'] ?>  </td>
                    <td> <?= number_format($value['harga_beli']) ?>  </td>
                    <td> <?= $value['diskon_beli'] ?>  </td>
                    <td> <?= number_format($value['harga_setelah_diskon']) ?>  </td>
                    <!-- <td> <?= number_format($value['harga_jual']) ?>  </td> -->
                    <td> <?= $value['lokasi'] ?>  </td> 
                </tr>  
                <?php
            }  ?> 
        </tbody> 
    </table> 
 
</body>
</html>
