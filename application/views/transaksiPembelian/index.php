<style>
#project-label {
    display: block;
    font-weight: bold;
    margin-bottom: 1em;
}
#project-icon {
float: left;
height: 32px;
width: 32px;
}
#project-description {
margin: 0;
padding: 0;
}
</style>
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Input Transaksi Pembelian</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
            <div class="row">
                <div class="col-sm-12">
                </div>
            </div>
            <div class="row">  
                
                <form role="form" id="myForm1" class="col-sm-12">
                    <div class="box-body row">
                        <div class="form-group col-md-4 col-sm-6">
                            <label for="exampleInputEmail1">Suplier</label>
                            
                            <input type="text" name="kd_suplier" id="kd_suplier" class="form-control" placeholder="Silahkan cari nama suplier">
                            <input type="hidden" name="theHiddenKdSuplier" id="theHiddenKdSuplier">
                        </div>
                        <div class="form-group col-md-2 col-sm-6">
                            <label for="exampleInputPassword1">No Faktur Penjualan</label>
                            <input type="text" class="form-control myForm1" name="no_faktur myForm1"
                            id="no_faktur" placeholder="masukkan nomor faktur pembelian" >
                        </div>
                        <div class="form-group col-md-2 col-sm-6">
                            <label for="exampleInputPassword1">Metode Pembayaran</label>
                            <select name="kode_pembayaran"
                                class="form-control js-example-basic-single" required
                                id="kode_pembayaran" onchange="togle_jatuh_tempo(this.value)">
                                <?php
                                foreach ($metode_pembayaran as $key) {
                                ?>
                                <option value="<?=$key['kd_pembayaran']?>">
                                    <?= $key['kd_pembayaran']." - ".$key['nama_metode_pembayaran']?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2 col-sm-6">
                            <?php
                            $date=date('Y-m-d')
                            ?>
                            <label for="exampleInputPassword1">Jatuh Tempo</label>
                            <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" min="<?= $date; ?>" readonly="readonly">
                        </div>
                        <div class="form-group col-md-2 col-sm-6">
                            <label for="exampleInputPassword1">Tanggal Faktur</label>
                            <input type="date" class="form-control myForm1" id="tgl_faktur"
                            name="tgl_faktur" value="<?= $date; ?>">
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
            <div class="col-sm-9">
                <div class="panel panel-success">
                    <div class="body">
                        <div class="box-body row">
                            <div class="form-group col-sm-12">
                                <label for="exampleInputPassword1">Barcode/Nama Obat</label>
                                <input type="text" name="barcode" id="barcode" class="form-control input-lg input_barcode" placeholder="cari barcode/ nama obat di sini" >
                                <input name="theHiddenBarcode" id="theHiddenBarcode" value="" type="hidden" />

                                 <label for="exampleInputEmail1">kandungan</label>
                                <input name="nama_obat" id="nama_obat" type="hidden" >
                                <textarea name="kandungan" id="kandungan" class="form-control" readonly></textarea>
                            </div>
                          <!--   <div class="form-group col-sm-3">
                                <label for="exampleInputEmail1">Biaya Materai</label>
                                <input name="b_materai" id="b_materai" type="text" class="form-control" > 

                                 <label for="exampleInputEmail1">Biaya Kirim</label>
                                <input name="b_kirim" id="b_kirim" type="text" class="form-control" >  

                                 <label for="exampleInputEmail1">Jumlah PPN</label>
                                <input name="jumlah_ppn" id="jumlah_ppn" type="text" class="form-control" > 

                            </div>  --> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="panel panel-success">
                    <div class="body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Grand Total</label>
                                <input type="text" name="total" id="total" class="form-control input-lg"
                                readonly="true" value="">
                            </div>
                            <button type="submit" class="btn btn-success btn-block simpan_pembelian" onclick="simpan_pembelian()">Simpan Transaksi</button>
                            <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-success">
                    <!-- <form id="myForm2" name="myForm2" novalidate="novalidate">  -->
                    <form method="post" id="myForm2" novalidate="novalidate">
                        <table class="table table-bordered table-hover" id="tab_logic" style="font-size: 10px">
                            <thead>
                                <tr >
                                    <th class="text-center"> # </th>
                                    <th class="text-center" style="width:9%;"> Barcode</th>
                                    <th class="text-center"> Satuan</th>
                                    <th class="text-center"> No. Batch</th>
                                    <th class="text-center"> No. Reg</th>
                                    <th class="text-center"> Tgl Exp</th>
                                    <th class="text-center" style="width:10%;"> Harga</th>
                                    <th class="text-center" style="width:7%;"> Diskon %</th>
                                    <th class="text-center" style="width:7%;"> Nilai PPN</th>
                                    <th class="text-center" style="width:7%;"> Qty</th>
                                    <th class="text-center"> Sub Total</th>
                                    <th class="text-center"> Lokasi</th>
                                    <th class="text-center" > </th>
                                </tr>
                            </thead>
                            <tbody id='element_table'>
                            </tbody>
                            <tr>
                                <td colspan="4">
                                    Diskon : <input type="number" name="jumlah_diskon"
                                    class="form-control jumlah_diskon" value="" readonly>
                                </td>
                                <td>
                                    PPN (%): <input type="number" name="ppn" class="form-control ppn" value="10">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->
</section>
<script type="text/javascript" src="<?= base_url()."assets" ?>/simple.money.format.js"></script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Informasi !</h4>
        </div>
        <div class="modal-body">
            
        </div>
        <div class="modal-footer">
        </div>
    </div>
</div>
</div>
<script src="<?php echo base_url()."assets/fgs/" ?>fgsPembelian.js"></script>