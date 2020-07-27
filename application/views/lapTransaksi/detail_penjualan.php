 
<!-- Main content -->
<section class="content">
  
  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Detail Pembelian pada Faktur Nomor : <?= $no_faktur;?></h3>
      
      <a class="btn btn-success" href="<?= base_url().'transaksi/Penjualan/notaPenjualan/'.$no_faktur.'/'.$kode_outlet?>" target="_blank">Print Faktur
      </a>

    </div>
    <div class="box-body">
      
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
        <!-- /.col -->
        <div class="col-xs-4 invoice-col">
          No. Invoice : <?=$no_faktur?><br>
          Tanggal Cetak : <?=date("d-m-Y H:i:s")?><br>
          Tanggal Jatuh Tempo : <?= $tgl_jatuh_tempo ?><br>
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
          Kredit : <?= $kredit ?>
          <br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 ">
          <table class="table table-bordered" style="height:100%;">
            <thead>
              <tr>
                <th>Unit</th>
                <th>Nama Barang</th>
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
              $sub_total2=0;
              $jumlah_potongan=0;
              $ppn=0;
              foreach ($invoice as $key) { ?>
              <tr>
                <td><?=$key->qty?></td>
                <td><?=$key->nama?></td>
                <td><?=$key->nm_satuan?></td>
                <td><?php
                echo date_from_datetime($key->tgl_exp,2). "/ ". $key->no_batch?></td>
                <td><?= number_format($key->harga_jual,2)?></td>
                <td><?= number_format($key->diskon_per_item,2)?></td>
                <td><?php
                  //rumus
                  $sub_total1 = ($key->qty*$key->harga_jual)-($key->qty*$key->harga_jual*($key->diskon_per_item/100));
                  $Potongan = $key->qty*$key->harga_jual*($key->diskon_per_item/100);
                  $jumlah_potongan = $jumlah_potongan+$Potongan;
                  echo number_format($sub_total1,2);
                  $sub_total2 = $sub_total2+ $key->qty*$key->harga_jual;
                ?></td>
              </tr>
              <?php $total = $total+$sub_total1;
              // $sub_total= $sub_total+($diskon_per_item/100);
              $ppn=$key->ppn;
              }  ?>
            </tbody>
            <tfoot >
            <tr >
              <td colspan="7"> <br> <br> </td>
            </tr>
            <tr>
              <td style="text-align: right;">Total Sebelum Diskon </td>
              <td style="text-align: right;">Potongan </td>
              <td style="text-align: right;">Total Setelah Diskon </td>
              <td style="text-align: right;">PPN </td>
              <td style="text-align: right;">B. Kirim </td>
              <td style="text-align: right;">Materai </td>
              <td style="text-align: right;">Jmlh Tagihan </td>
            </tr>
            <tr>
              <td style="text-align: right;"><?=number_format($sub_total2,2)?></td>
              <td style="text-align: right;"><?=number_format($jumlah_potongan,2)?></td>
              <td style="text-align: right;"><?php
                $total2 = $sub_total2-$jumlah_potongan;
                // var_dump($total);
                if ($total2<=0)
                {
                $total2 = 0;
                }
              echo number_format($total2,2)?></td>
              <td style="text-align: right;"><?php
                $ppn2 = ($ppn/100)*$total2;
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
      </div>
    </div>
    <?php if($jumlah_kosong>0) { ?>
    <!-- <p>Gunting Disini ----------------------------------------------------------------------------------</p> -->
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Detail Nota Kosong pada Faktur Nomor : <?= $no_faktur;?></h3>
      </div>
      <div class="box-body">
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
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Barcode</th>
                  <th>Nama Barang</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php $no=1;
                foreach ($nota_kosong as $key)
                {
                if ($key->jumlah_kosong>0)
                { ?>
                <tr>
                  <td><?=$no++?></td>
                  <td><?=$key->barcode?></td>
                  <td><?=$key->nama?></td>
                  <td><?=$key->jumlah_kosong . " ".$key->nm_satuan?></td>
                </tr>
                <?php }
                } ?>
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

        </div>
      </div>
      <?php } ?>
      <!-- /.box -->
    </section>