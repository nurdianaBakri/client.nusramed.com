<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script> 
    
<div id="collapseOne_2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_2">
    <div class="panel-body">
        <div class="col-xs-12 col-sm-12 col-sm-12 col-lg-12">
          <form id="myForm" action="<?= base_url()."Detail_obat/save" ?>" method="post" class="form-horizontal">  
            <input type="hidden" name="url_inquery" value="<?php echo $url_inquery ?>" id="url_inquery"> 
                  <?php 
                    $id = $this->session->userdata('id');  
                 ?>  
              <input type="hidden" id="id_user" name="id_user" value="<?php echo $id ?>" > 
              <input type="hidden" id="jenis_aksi" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
              <input type="hidden" id="no_batch_old" name="no_batch_old" value="<?php echo $data['no_batch_old'] ?>" > 

              <input type="hidden" id="id_obat" name="id_obat" value="<?php echo $data['id_obat'] ?>" > 

              <div class="form-group">
                    <label class="col-sm-2" for="nama_obat">Nama Obat</label>
                <div class="col-sm-10">
                    <input type="text" id="nama_obat" name="nama_obat" class="form-control input-visible" value="<?php echo $data['nama_obat'] ?>" readonly> 
                </div>
            </div>

            <div class="form-group">
                    <label class="col-sm-2" for="kandungan">Kandungan</label>
                <div class="col-sm-10">
                    <input type="text" id="kandungan" name="kandungan" class="form-control input-visible" value="<?php echo $data['kandungan_obat'] ?>" readonly> 
                </div>
            </div>

            <div class="form-group">
                    <label class="col-sm-2" for="kandungan">Deskripsi</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="kandungan" readonly><?= $data['deskripsi_obat'] ?></textarea> 
                </div>
            </div> 

            <div class="form-group"> 
                <label class="col-sm-2" for="kd_suplier">Suplier</label>
                <div class="col-sm-4">
                  <select name="kd_suplier" class="form-control js-example-basic-single" required="">
                    <?php
                      foreach ($suplier as $key => $value) {
                        ?>
                        <option   value="<?=$value['kd_suplier']?>"><?=$value['nama']?></option>
                        <?php
                      } 
                    ?>
                  </select>
                </div>

                    <label class="col-sm-2" for="no_batch">No Batch</label>
                <div class="col-sm-4">
                    <input type="text" id="no_batch" name="no_batch" class="form-control input-visible" value="<?php echo $data['no_batch'] ?>"> 
                </div> 
            </div> 

            <div class="form-group">

                <label class="col-sm-2" for="no_faktur">Nomor Faktur</label>
                <div class="col-sm-4">
                    <input type="text" id="no_faktur" name="no_faktur" class="form-control input-visible" value=""> 
                </div> 

                <label class="col-sm-2" for="foto_faktur">Foto Faktur</label>
                <div class="col-sm-4">
                    <input type="file" id="foto_faktur" name="foto_faktur" class="form-control input-visible" value=""> 
                </div> 
            </div> 


            <div class="form-group">
                  <label class="col-sm-2" for="stok">Stok</label>
                <div class="col-sm-4">
                  <input type="number" id="stok" name="stok" class="form-control input-visible" value="<?php echo $data['stok'] ?>"> 
                </div>

                  <label class="col-sm-2" for="tgl_exp">Tanggal Exp</label>
                <div class="col-sm-4">
                  <input type="date" id="tgl_exp" name="tgl_exp" class="form-control input-visible" value="<?php echo $data['tgl_exp'] ?>"> 
                  
                </div>
            </div> 

            <div class="form-group">
                    <label class="col-sm-2" for="harga_beli">Harga Beli</label>
                <div class="col-sm-4">
                    <input type="number" id="harga_beli" name="harga_beli" class="form-control input-visible" value="" required>  
                </div>

                    <label class="col-sm-2" for="diskon_beli">Diskon Beli (%)</label>
                <div class="col-sm-4">
                    <input type="number" id="diskon_beli" name="diskon_beli" class="form-control input-visible" value=""> 
                  
                </div>
            </div>

              <div class="form-group">
                <label class="col-sm-2" for="harga_jual">Harga Jual</label>
                <div class="col-sm-4">
                    <input type="number" id="harga_jual" name="harga_jual" class="form-control input-visible" value="" required> 
                </div>

                <label class="col-sm-2" for="diskon_jual">Diskon Jual (%)</label>
                <div class="col-sm-4">
                    <input type="number" id="diskon_jual" name="diskon_jual" class="form-control input-visible" value="">  
                </div>
            </div>


             <div class="form-group">
                <label class="col-sm-2" for="lokasi_rak">Lokasi Rak</label>
                <div class="col-sm-4">
                    <input type="text" id="lokasi_rak" name="lokasi_rak" class="form-control input-visible" value="" required>  
                </div> 

                <label class="col-sm-2" for="lokasi_rak">Print Barcode</label>
                <div class="col-sm-4">
                    <button class="btn btn-success">Print Barcode</button>  
                </div> 
                
            </div>  

            <input class="btn btn-success" type="submit" name="submit" value="Simpan">
        </form> 
        </div>
    </div>
</div>  

<script type="text/javascript">
  // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
        $('select').select2();
    });

</script>
  