<section class="content">
  
  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Detail Pembelian pada Faktur Nomor : <?= $no_faktur;?></h3>
       <a class="btn btn-success" href="<?= base_url().'transaksi/Pembelian/printTrxPembelian/'.$no_faktur?>" target="_blank">Print Faktur Pembelian
      </a>
    </div>
    <div class="box-body">
      
      <center>
      <img src="<?php echo base_url()."assets/data_image/logo_nama.PNG";?>">
      <h3>Nota Pembelian Barang</h3>
      </center>
      <div class="row invoice-info" style="font-size:12px">
        <div class="col-xs-4 invoice-col">
          Nama PBF. : <?= $suplier['nama'] ?><br>
          Alamat PBF. : <?= $suplier['alamat'] ?><br>
          Nomor Telp. : <?= $suplier['no_hp'] ?><br>
          Izin PBF No. : <?= $suplier['no_izin'] ?><br>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 invoice-col">
          No. Faktur : <?= $no_faktur?><br>
          Tanggal Cetak : <?=date("d-m-Y H:i:s")?><br>
          Tanggal Jatuh Tempo : <?= $tgl_jatuh_tempo; ?><br>
          Penginput : <?= $data_row['id_user'] ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-4 invoice-col">
          
        </div>
        <!-- /.col -->
      </div>
      <table class="table table-striped">
        <thead>
          <tr >
            <th> # </th>
            <th> Barcode</th>
            <th> Nama Obat</th>
            <th> No Batch</th>
            <th> No Registrasi</th>
            <th style="width:10%;"> Tgl Exp</th>
            <th> Qty</th>
            <th style="width:10%;"> Harga Beli</th>
            <th> Diskon %</th>
            <th> Sub Total</th>
            <th style="width:10%;">Lokasi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i=1;
          $nilai_ppn=0;
          $subtotal2=0;
          foreach ($data as $key => $value) {
          $subtotal = $value['stok_awal']*$value['harga_beli'];
          $pengurangan_diskon = $subtotal*($value['diskon_beli']/100);
          
          //hitung sub-total
          $subtotal2 = $subtotal2 + ($subtotal-$pengurangan_diskon);
          ?>
          <tr>
            <td> <?= $i++; ?> </td>
            <td> <?= $value['barcode'] ?> </td>
            <td> <?= $value['nama']?>  </td>
            <td> <?= $value['no_batch'] ?>  </td>
            <td> <?= $value['no_reg'] ?>  </td>
            <td> <?php
              $s = strtotime($value['tgl_exp']);
            echo date('d M Y', $s);   ?>  </td>
            <td> <?= $value['stok_awal'] ?>  </td>
            <td> <?= number_format($value['harga_beli'],2) ?>  </td>
            <td> <?= $value['diskon_beli'] ?>  </td>
            <td> <?= number_format($subtotal2,2) ?>  </td>
            <td> <?=  $value['lokasi']; ?>
            </td>
          </tr>
          <?php
          }  ?>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="6"></td>
          <td colspan="5" >
            <p style="text-align: right;font-size:15px;">
              Jumlah  : <?= number_format($subtotal2,2);
              $nilai_ppn =$subtotal2*($ppn/100);
              ?> <br>
              PPN <?= $ppn. "%"; ?> : <?= number_format($nilai_ppn,2)?>
            </p>
            <p style="text-align: right;font-size:20px;"> Total Bayar : <?= rupiah($subtotal2 + $nilai_ppn)?></p>
          </td>
        </tr>
        <tr>
          <td colspan="11" style="text-align: center;">
            Terbilang : <?=terbilang($subtotal2+$nilai_ppn)." Rupiah"?>
            <br>Keterangan :
          Barang masih PO, barang sedang di kirim oleh distributor menggunakan expedisi</td>
        </tr>
        <tr>
          <td colspan="6" style="text-align: center;"><br><br><br><br>(………………………………………)<br>Apoteker Penanggung
          Jawab</td>
          <td colspan="5" style="text-align: center;"><br><br><br><br>(………………………………………)<br>Nama Terang
          Pelanggan/Cap</td>
        </tr>
        </tfoot>
      </table>
    </div>
    
  </div>
  <!-- /.box -->
</section>