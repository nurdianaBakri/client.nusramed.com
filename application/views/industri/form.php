
    <form id="myForm" method="post"  class="form-horizontal">
    
        <input type="hidden" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
        <input type="hidden" name="kd_industri" value="<?php echo $data['kd_industri']; ?>" >  

        <!-- <h3>Data Penaggung Jawab</h3>   -->
        <div class="form-group">
            <label class="col-sm-2" for="nama">Nama</label>
            <div class="col-sm-10">
            <input type="text" id="nama" required name="nama" class="form-control input-visible" value="<?php echo $data['nama'] ?>"> 
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2" for="alamat">Alamat</label>
            <div class="col-sm-10">
            <textarea required name="alamat" class="form-control input-visible"><?php echo $data['alamat'] ?></textarea> 
            </div>
        </div> 

         <div class="form-group">
            <label class="col-sm-2" for="no_telp">No.Telp</label>
            <div class="col-sm-4">
              <input type="number" id="no_telp" name="no_telp" class="form-control input-visible" value="<?php echo $data['no_telp'] ?>" required>
            </div>

            <label class="col-sm-2" for="email">Email</label>
            <div class="col-sm-4">
            <input type="email" id="email" name="email" class="form-control input-visible" value="<?php echo $data['email'] ?>">
            </div>
        </div> 

        <div class="form-group">
            <label class="col-sm-2" for="nama">Nomor Rekening</label>
            <div class="col-sm-4">
                <input type="number" id="no_rek" name="no_rek" class="form-control input-visible" value="<?php echo $data['no_rek'] ?>"> 
            </div>

            <label class="col-sm-2" for="nama">Nama Bank</label>
            <div class="col-sm-4">
                <input type="text" id="kd_bank" name="kd_bank" class="form-control input-visible" value="<?php echo $data['kd_bank'] ?>"> 
            </div>
        </div> 
 
</form>  

 <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-xs">Save</button> 
      <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Close</button> 