<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>
 

      <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
      <form id="myForm" class="form-horizontal" method="post" action="<?= base_url()."Obat/save" ?>">
        
        <?php  $id = $this->session->userdata('id');  ?>
        <input type="hidden" id="USER_INPUT" name="USER_INPUT" value="<?php echo $id ?>" >
        <input type="hidden" id="jenis_aksi" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" >
        <input type="hidden" id="id_obat" name="id_obat" value="<?php echo $data['id_obat'] ?>" >
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Barcode</label>
          <div class="col-sm-10">
            <input type="text" id="barcode" name="barcode" class="form-control input-visible" value="<?php echo $barcode ?>" <?php echo $barcode; ?> required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Nama Obat</label>
          <div class="col-sm-10">
            <input type="text" id="nama" name="nama" class="form-control input-visible" value="<?php echo $data['nama'] ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Kandungan</label>
          <div class="col-sm-10">
            <input type="text" id="kandungan" name="kandungan" class="form-control input-visible" value="<?php echo $data['kandungan'] ?>" required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
          <div class="col-sm-10">
            <textarea id="deskripsi" name="deskripsi" class="form-control input-visible" required ><?php echo $data['deskripsi'] ?></textarea>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-2" for="deskripsi">Industri</label>
          <div class="col-sm-4">
            <select name="kd_industri" class="form-control js-example-basic-single" required>
              <?php
              foreach ($industri as $key) {
              ?>
              <option <?php if($data['kd_industri']==$key['kd_industri']){ echo "selected";} ?> value="<?=$key['kd_industri']?>"><?=$key['nama']?></option>
              <?php
              }
              ?>
            </select>
          </div>
 
          <label class="control-label col-sm-2" for="deskripsi">Kategori</label>
          <div class="col-sm-4">
            <select name="kategori" class="form-control js-example-basic-single" required onchange="get_detail_kategori(this.value)">
              <?php
              foreach ($kategori as $key) {
              ?>
              <option <?php if($data['kd_kategori_obat']==$key['kd_kategori']){ echo "selected";} ?> value="<?=$key['kd_kategori']?>"><?=$key['nm_kategori']?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="deskripsi">Kategori Obat</label>
          <div class="col-sm-4">
            <select name="kd_kategori_obat" class="form-control js-example-basic-single kategori_obat">
              <?php
              foreach ($kategori_obat as $key) {
              ?>
              <option <?php if($data['kd_kategori_obat']==$key['kd_kategori']){ echo "selected";} ?> value="<?=$key['kd_kategori']?>"><?=$key['nm_kategori']?></option>
              <?php
              }
              ?>
            </select>
          </div>
          <label class="control-label col-sm-2" for="kd_satuan">Satuan</label>
          <div class="col-sm-4">
            <select name="kd_satuan" class="form-control js-example-basic-single" required="">
              <?php
              foreach ($satuan as $key => $value) {
              ?>
              <option <?php if($data['kd_satuan']==$value['kd_satuan']){ echo "selected";} ?> value="<?=$value['kd_satuan']?>"><?=$value['nm_satuan']?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="jenis_terapi">Jenis Terapi</label>
          <div class="col-sm-4">
            <input type="text" id="jenis_terapi" name="jenis_terapi" class="form-control input-visible" value="<?php echo $data['jenis_terapi'] ?>" required>
          </div>
          <label class="control-label col-sm-2" for="foto">Foto</label>
          <div class="col-sm-4">
            <input type="file" id="foto" name="foto" class="form-control input-visible">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="jenis_terapi">Barcode</label>
          <div class="col-sm-4" >
            <img src="<?= base_url()."assets/barcode/".$data['barcode'].".jpg" ?>">
          </div>
        </div>
      </form>

      <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-xs">Save</button> 
      <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Close</button> 
      
      <style type="text/css">
        .select2-container {
          width: 100% !important;
          padding: 0;
        }
      </style> 
    <script type="text/javascript">  
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {  
            $('select').select2();
        }); 
    </script>
 