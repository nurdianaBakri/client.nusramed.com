<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Nota Penjualan</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 4 -->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url()."assets/" ?>/dist/css/adminlte.min.css">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style> 
    .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
        font-size: 11px;
    } 
    html  { background-color: #FFFFFF; margin: 0px; }
    body  { margin: 0mm 0mm 0mm 0mm; }
    table {width:100%}

    /* style sheet for "A4" printing */
    @media print and (width: 9.5in) and (height: 5.5in) {
         @page {
            margin: 1in;
         }
    } 

    .table-bordered>thead>tr>th,
    .table-bordered>thead>tr>td {
        border-bottom-width: 2px;
        vertical-align: middle;  
    }

    .wrapper { 
        height: 100%;
        position: relative;
        overflow-x: visible;
        overflow-y: visible;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Main content -->
        <section class="invoice nota-penjualan">
            <!-- title row --> 
            <center>
                <p style="font-size: 12px;">  
                    FAKTUR PENJUALAN 
                </p>
            </center> 
                      
            <!-- info row -->
            <div class="row invoice-info">

            <center>
                <img src="<?php echo base_url()."assets/data_image/kopfaktur.png" ?>" alt="Logo" width="600" >  
            </center> 
                </div>
                <!-- /.col -->
                <div class="col-xs-4 invoice-col">
                    No. Invoice : <?=$no_faktur?><br>
                    Tanggal Cetak : <?=date("d-m-Y H:i:s")?><br>
                    Tanggal Jatuh Tempo : <br> 
                    Salesman :  <br>
                    <p style="font-size: 14px;"> Bank Transfer : BCA 0564040408 </p>
                </div> 
                 <div class="col-xs-4 invoice-col"> 
                </div> 

                <!-- /.col -->
                <div class="col-xs-4 invoice-col">
                    <!-- Kepada Yth. <br> -->
                    Nama : <?=$outlet->nama?><br>
                    Alamat : <?=$outlet->alamat?><br>
                    NPWP : <?=$outlet->npwp?><br>
                    Kode Customer : <?=$outlet->id_outlet?><br>
                    Kredit : <?= $tgl_jatuh_tempo ?>
                    <br> 
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 ">
                    <table cellpadding="0" cellspacing="0" style="height:100%;">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th style="width: 30%;">Nama Barang</th>
                                <th>Satuan</th>
                                <th>ED / No. Batch</th> 
                                <th>Harga/Unit</th> 
                                <th>Diskon %</th> 
                                <th>Jumlah</th>
                            </tr> 
                        </thead>
                        <tbody>
                            <?php 
                            $total=0; 
                            $sub_total=0;
                            $ppn=0; 
                            foreach ($invoice as $key) { ?>
                            <tr>
                                <td><?=$key->qty?></td> 
                                <td><?=$key->nama?></td>
                                <td><?=$key->nm_satuan?></td>
                                <td><?php 
                                $s = $key->tgl_exp;
                                $dt = new DateTime($s); 
                                $date = $dt->format('d M Y');  
                                echo $date. "/ ". $key->no_batch?></td>
                                <td><?= number_format($key->harga_jual,2)?></td>
                                <td><?=$key->diskon_per_item?></td>
                                <td><?php
                                $diskon_per_item = ($key->diskon_per_item*$key->harga_jual)/100;
                                $sub_total1 = ($key->qty*$key->harga_jual) - $diskon_per_item;

                                echo number_format($sub_total1,2)?></td>  
                            </tr> 
                            <?php $total = $total+$sub_total1;

                            $sub_total= $sub_total+$diskon_per_item;
                          $ppn=$key->ppn;  
                        }  ?>
                        </tbody> 

                        <tfoot >

                            <tr >  
                                <td colspan="7"> <br> <br> </td>
                            </tr> 
                            <tr>
                                <td style="text-align: right;">Total 1 </td> 
                                <td style="text-align: right;">Potongan </td> 
                                <td style="text-align: right;">Total 2 </td> 
                                <td style="text-align: right;">PPN </td> 
                                <td style="text-align: right;">B. Kirim </td> 
                                <td style="text-align: right;">Materai </td> 
                                <td style="text-align: right;">Jmlh Tagihan </td> 
                            </tr>
                            <tr>
                                <td style="text-align: right;"><?=number_format($total,2)?></td>
                                <td style="text-align: right;"><?=number_format($sub_total,2)?></td>
                                <td style="text-align: right;"><?php 
                                $total2 = $total-$sub_total;
                                // var_dump($total);
                                if ($total<=0)
                                {
                                    $total2 = 0;
                                }
                                 echo number_format($total2,2)?></td>
                                <td style="text-align: right;"><?php

                                $ppn2 = ($ppn/100)*$total;
                                echo number_format($ppn2,2)?></td>
                                <td style="text-align: right;"><?php

                                $b_kirim=0;
                                echo number_format($b_kirim,2)?></td>
                                <td style="text-align: right;"><?php

                                $materai=0;
                                echo number_format($materai,2);

                                $total_tagihan = $total2+$ppn2+$b_kirim+$materai;
                                ?></td>
                                <td style="text-align: right;"><?= number_format($total_tagihan,2)?></td>
                            </tr>
                            <tr>
                                <td colspan="7" style="text-align: left;">Terbilang : <?= terbilang($total_tagihan);  ;

                                ?></td> 
                            </tr>
                            <tr> 
                                <td colspan="7" style="text-align: center;">Barang Diterima Dengan Baik<br><br></td>   
                            </tr> 
                            <tr> 
                                
                                <td colspan="3" style="text-align: center;">
                                    <p>Tgl : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p><br><br><br><br>
                                    (………………………………………)<br>Nama Terang
                                    Penerima</td>  

                                <td colspan="4" style="text-align: center;"><br><br><br><br><br> <br>(Rahmatinnisa,S.Farm.,Apt)<br>Apoteker Penanggung
                                    Jawab</td>                           </tr>
                            <tr>
                                <td colspan="3" style="text-align: center;">No. SIPA/SIKA : </td> 
                                <td colspan="4" style="text-align: center;">No. SIKA : 19920805/SIKA_.52.71/2020/2.097</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <br><br>
        <?php if($jumlah_kosong>0) { ?>
        <!-- <p>Gunting Disini ----------------------------------------------------------------------------------</p> -->
        <br><br>
        <section class="invoice nota-kosong">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-6">
                    <div class="">
                        <img src="<?php echo base_url()."assets/data_image/logo_nama.PNG" ?>" alt="Logo" width="400">
                    </div>
                </div>
                <div class="col-xs-6">
                    <h3 class="">
                        NOTA PESANAN OBAT KOSONG <br>
                        No. Invoice : <?=$no_faktur?><br>
                    </h3>
                </div>
                <!-- /.col -->
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table cellpadding="0" cellspacing="0" style="height:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($nota_kosong as $key) { ?>
                            <tr>
                                <td><?=$no++?></td>
                                <td><?=$key->barcode?></td>
                                <td><?=$key->nama?></td>
                                <td><?=$key->jumlah_kosong . " ".$key->nm_satuan?></td>
                            </tr>
                            <?php  } ?>
                        </tbody>
                        <tfoot > 
                            <tr> 
                                
                                <td colspan="2" style="text-align: center;">
                                    <p>Tgl : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </p><br><br><br><br>
                                    (………………………………………)<br>Nama Terang
                                    Penerima</td>  

                                <td colspan="2" style="text-align: center;"><br><br><br><br><br> <br>(Rahmatinnisa,S.Farm.,Apt)<br>Apoteker Penanggung
                                    Jawab</td>                           </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">No. SIPA/SIKA : </td> 
                                <td colspan="2" style="text-align: center;">No. SIKA : 19920805/SIKA_.52.71/2020/2.097</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </section>
        <?php } ?>
        <!-- /.content -->
    </div>
    <!-- ./wrapper -->

    <script>
    function ready() {
        window.print();
    } 
    document.addEventListener("DOMContentLoaded", ready);
    </script>
</body>

</html>