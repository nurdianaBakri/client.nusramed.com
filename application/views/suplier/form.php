
    <form id="myForm" method="post" action="#" class="form-horizontal">
    
        <input type="hidden" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
        <input type="hidden" name="kd_suplier" value="<?php echo $data['kd_suplier']; ?>" >   
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
            
                <label class="col-sm-2" for="no_hp">No.Telp</label>
            <div class="col-sm-4">
              <input type="number" id="no_hp" name="no_hp" class="form-control input-visible" value="<?php echo $data['no_hp'] ?>" required>
            </div>

            
                <label class="col-sm-2" for="email">Email</label>
            <div class="col-sm-4">
                <input type="email" id="email" name="email" class="form-control input-visible" value="<?php echo $data['email'] ?>">
            </div>
        </div> 


    <div class="form-group">
        <label class="col-sm-2" for="no_izin">No. Izin PBF</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_izin" required name="no_izin" class="form-control input-visible" value="<?php echo $data['no_izin'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_izin">Tanggal Masa berlaku Izin PBF</label>

         <div class="col-sm-4">
            <input type="date" id="masa_izin" required name="masa_izin" class="form-control input-visible" value="<?php echo $data['masa_izin'] ?>"> 
        </div> 
    </div>  

    <div class="form-group">
        <label class="col-sm-2" for="no_sika_sipa">No. SIKA/SIPA</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_sika_sipa" required name="no_sika_sipa" class="form-control input-visible" value="<?php echo $data['no_sika_sipa'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_sika_sipa">Tanggal Masa berlaku SIKA/SIPA</label>

         <div class="col-sm-4">
            <input type="date" id="masa_sika_sipa" required name="masa_sika_sipa" class="form-control input-visible" value="<?php echo $data['masa_sika_sipa'] ?>"> 
        </div> 
    </div> 

    <div class="form-group">
        
        <label class="col-sm-2" for="nama_apoteker_pj">Nama Apoteker PJ</label>
        <div class="col-sm-4">
          <input type="text" id="nama_apoteker_pj" name="nama_apoteker_pj" class="form-control input-visible" value="<?php echo $data['nama_apoteker_pj'] ?>" required>
        </div> 
            
    </div> 

    <div class="form-group">
        
        <label class="col-sm-2" for="bank">Bank</label>
        <div class="col-sm-4">
          <input type="text" id="bank" name="bank" class="form-control input-visible" value="<?php echo $data['bank'] ?>">
        </div>
        
        <label class="col-sm-2" for="no_rek">No. Rek</label>
        <div class="col-sm-4">
            <input type="number" id="no_rek" name="no_rek" class="form-control input-visible" value="<?php echo $data['no_rek'] ?>">
        </div>
    </div> 
 
</form>  


 <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-xs">Save</button> 
      <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Close</button> 