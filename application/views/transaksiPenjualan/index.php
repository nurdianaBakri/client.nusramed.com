
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


<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>

<section class="content">
    <!-- Default box -->
    <div class="box box-success">
        <div class="box-header with-border">
            <form role="form" id="myForm1">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-3">
                                <label >Outlet</label>
                                <select name="kd_outlet" class="form-control js-example-basic-single" required id="kd_outlet">
                                    <?php
                                    foreach ($outlet as $key) {
                                    ?>
                                    <option value="<?=$key['id_outlet']?>">
                                    <?= $key['nama']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label >No Faktur Penjualan</label>
                                <input type="text" class="form-control" name="no_faktur"
                                id="no_faktur" value="<?= $no_faktur; ?>">
                            </div>
                            <div class="col-sm-3">
                                <label >Metode Pembayaran</label>
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
                            <div class="col-sm-3 tgl_jatuh_tempo">
                                <?php  $date=date('Y-m-d') ?>
                                <label >Jatuh Tempo</label>
                                <input type="date" class="form-control" id="tgl_jatuh_tempo"
                                name="tgl_jatuh_tempo" min="<?= $date; ?>">
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-sm-4 ">
                        <div class="row">
                            <div class="col-sm-6">
                                <label >Grand Total</label>
                                <input type="text" id="total" class="form-control" readonly="true" value="">
                            </div>
                            <div class="col-sm-6">
                                <br>
                                <button class="btn btn-success btn-block simpan_penjualan" onclick="simpan_penjualan()">Simpan
                                Transaksi</button>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
            </form>
            
        </div>
        <div class="box-body">
            
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
            <div class="row">

                  <form role="form" id="myForm3">
                    <div class="col-sm-7">
                        <label >Cari obat berdasarkan barcode/ Nama</label> 
                         <input type="text" name="barcode" id="barcode" class="form-control input_barcode" placeholder="cari barcode/ nama obat di sini" >
                            <input name="theHiddenBarcode" id="theHiddenBarcode" value="" type="hidden" />  
                            <input name="diskon" id="diskon" type="hidden" value="0" /> 
                            <input name="jenis_aksi" id="jenis_aksi" type="hidden" value="add" /> 
                    </div>
                    <div class="col-sm-1">
                        <label >qty</label> 
                         <input type="number" name="qty" id="qty" class="form-control" value="1" > 
                         <input name="sisa_stok" id="sisa_stok" class="form-control" type="hidden" > 
                    </div> 
                    <div class="col-sm-4">
                        <label >Kandungan</label>  
                        <input name="nama_obat" id="nama_obat" type="hidden" >
                        <input type="text"  name="kandungan" id="kandungan" class="form-control" readonly> 
                            <input name="label" id="label" class="form-control" type="hidden"> 
                    </div>
                </form>  

                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <form id="myForm2">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tab_logic" style="font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> # </th>
                                            <th class="text-center" style="width:15%;"> Barcode</th>
                                            <th class="text-center"> Satuan</th>
                                            <th class="text-center"> No. Batch</th>
                                            <th class="text-center"> No. Reg</th>
                                            <th class="text-center"> Tgl Exp</th>
                                            <th class="text-center" style="width:10%;"> Harga</th>
                                            <th class="text-center" style="width:10%;">Nilai PPN</th>
                                            <th class="text-center" style="width:7%;"> Diskon %</th>
                                            <th class="text-center" style="width:7%;"> Qty</th>
                                            <th class="text-center" style="width:7%;"> Kosong</th>
                                            <th class="text-center"> Sub Total</th>
                                            <th class="text-center"> </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                  
                                </table>


                                  <tr>
                                    <td colspan="4">
                                        Diskon : <input type="text" name="jumlah_diskon" class="form-control jumlah_diskon" id="jumlah_diskon" readonly>
                                    </td>
                                    <td>
                                        PPN : <input type="number" id="ppn" name="ppn" class="form-control" min=10
                                        value=10>
                                    </td>
                                </tr>

                            </div>
                        </form>
                        
                    </div>
                </div>
               
            </div>
            
        </div>
        
    </div>
    <!-- /.box -->
</section>

<script type="text/javascript" src="<?= base_url()."assets" ?>/simple.money.format.js"></script>
  <script src="<?php echo base_url()."assets/fgs/" ?>fgsKasir.js"></script>  
 