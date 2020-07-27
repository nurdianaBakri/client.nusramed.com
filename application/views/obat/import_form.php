  <form id="myForm" enctype="multipart/form-data">      
      <input type="hidden" id="jenis_aksi" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" >  

      <div class="alert alert-warning" role="alert">
      Format file import dapat di download di <a href="<?= base_url()."excel/formatdataobat.xlsx" ?>">Sini</a>
        <br> <b>File harus ber-extensi .xlsx</b>
      </div>

        <div class="row">
          <div class="col-md-2">
              <label for="file">File Obat</label>
          </div>
          <div class="col-md-10">
              <input type="file" name="file" class="form-control input-visible"  required> 
          </div>
      </div>
  </form>
  <input class="btn btn-success btn-xs" type="submit" name="submit" value="Simpan" onclick="do_import()">
  <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Close</button> 
      
