  <form id="myForm" enctype="multipart/form-data">         

      <input type="hidden" id="jenis_aksi" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" >  
        <div class="row">
          <div class="col-md-2">
              <label for="file">File Obat</label>
          </div>
          <div class="col-md-10">
              <input type="file" name="file" class="form-control input-visible"  required> 
          </div>
      </div>
  </form>
  <input class="btn btn-success btn-block" type="submit" name="submit" value="Simpan" onclick="do_import()">