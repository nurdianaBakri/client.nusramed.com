

  <!DOCTYPE html>
  <html>
  <head>

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/dist/css/AdminLTE.min.css">
  
   <!-- jQuery 3 -->
  <script src="<?= base_url()."assets" ?>/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->

  <script src="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
      <title>Penfataran Outlet Baru</title>
  </head>
  <body>

   <form id="myForm" method="post" action="<?= base_url()."Outlet/save" ?>" class="form-horizontal"> 
        <h3>Data Outlet</h3>
    
      <?php  $id = $this->session->userdata('id');  ?>  
      <input type="hidden" name="USER_INPUT" value="<?php echo $id ?>" > 
      <input type="hidden" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
      <input type="hidden" name="id_outlet" id="id_outlet" value="<?php echo $id_outlet; ?>" > 
      <input type="hidden" name="id_penanggung_jawab" value="<?php echo $penanggung_jawab['id_penanggung_jawab']; ?>" > 

      <div class="form-group">
        <label class="col-sm-2" for="nama">Nama</label>
        
        <div class="col-sm-10">
            <input type="text" id="nama" required name="nama" class="form-control input-visible" value="<?php echo $outlet['nama'] ?>"> 
        </div>
    </div>

      <div class="form-group">
        <label class="col-sm-2" for="alamat">Alamat</label>
        
        <div class="col-sm-10">
             <textarea required name="alamat" class="form-control input-visible">
                <?php echo $outlet['alamat'] ?>
            </textarea>  
        </div>
    </div>

      <div class="form-group">
        <label class="col-sm-2" for="npwp">NPWP</label>
        
        <div class="col-sm-4">
          <input type="number" id="npwp" required name="npwp" class="form-control input-visible" value="<?php echo $outlet['npwp'] ?>">
        </div>
         <label class="col-sm-2" for="no_telp">No. Telp</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_telp" name="no_telp" class="form-control input-visible" value="<?php echo $outlet['no_telp'] ?>"> 
        </div>
    </div>  

      <div class="form-group">
          <label class="col-sm-2" for="no_sia">No. SIA/klinik/RS/TO</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_sia" required name="no_sia_outlet" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sia_outlet'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_str">Tanggal Masa berlaku No. SIA/klinik/RS/TO </label>

         <div class="col-sm-4">
            <input type="text" id="masa_berlaku_no_sia_outlet" required name="masa_berlaku_no_sia_outlet" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_berlaku_no_sia_outlet'] ?>"> 
        </div>
    </div>    

    <hr>   

    <h3>Data Pemilik</h3> 
     <div class="form-group"> 
       <label class="col-sm-2" for="no_ktp_pemilik">NIK</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_ktp_pemilik" required name="no_ktp_pemilik" class="form-control input-visible" value="<?php echo $outlet['no_ktp_pemilik'] ?>"> 
        </div>
         <label class="col-sm-2" for="nm_pemilik">Nama</label>
        
        <div class="col-sm-4">
            <input type="text" id="nm_pemilik" name="nm_pemilik" class="form-control input-visible" value="<?php echo $outlet['nm_pemilik'] ?>"> 
        </div>
    </div>  
     
    <div class="form-group">
        <label class="col-sm-2" for="alamat_pemilik">Alamat</label>
        
        <div class="col-sm-10"> 
            <textarea  name="alamat_pemilik" class="form-control input-visible" required>
                <?php echo $outlet['alamat_pemilik'] ?>
            </textarea> 
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="no_telp_pemilik">No.Telp</label>
        
        <div class="col-sm-10">
          <input type="number" id="no_telp_pemilik" name="no_telp_pemilik" class="form-control input-visible" value="<?php echo $outlet['no_telp_pemilik'] ?>" required>
        </div>
    </div>  

    <br> 
    <br> 
    <br> 
    <br>  

   
   
    <hr>  

    <h3>Data Penaggung Jawab</h3>  
    <div class="form-group">
        <label class="col-sm-2" for="no_ktp_pj">NIK</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_ktp_pj" required name="no_ktp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ktp_pj'] ?>"> 
        </div>

         <label class="col-sm-2" for="no_telp_pj">No.Telp</label>
        
        <div class="col-sm-4">
          <input id="no_telp_pj" type="number" required name="no_telp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_telp_pj'] ?>">
        </div>
        <!--  <label class="col-sm-2" for="email_pj">Email</label>
        
        <div class="col-sm-4">
            <input type="email" id="email_pj" required name="email_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['email_pj'] ?>"> 
        </div> -->
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="nm_pj">Nama Penaggung Jawab</label>
        
        <div class="col-sm-10">
            <input type="text" id="nm_pj" required name="nm_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['nama_pj'] ?>"> 
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="alamat_pj">Alamat</label> 
        <div class="col-sm-10">
            <textarea required name="alamat_pj" class="form-control input-visible"><?php echo $penanggung_jawab['alamat_pj'] ?></textarea> 
        </div>
    </div>  

    <div class="form-group">
        <label class="col-sm-2" for="no_str">No. STR</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_str" required name="no_str" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_str_pj'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_str">Tanggal Masa berlaku STR</label>

         <div class="col-sm-4">
            <input type="text" id="masa_berlaku_no_str" required name="masa_berlaku_no_str" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_berlaku_no_str'] ?>"> 
        </div>
       
    </div> 

   

     <div class="form-group">

        <label class="col-sm-2" for="no_sipa">No. SIPA</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_sipa" required name="no_sipa" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sipa_pj'] ?>"> 
        </div>

         <label class="col-sm-2" for="no_str">Tanggal Masa berlaku SIPA </label>

         <div class="col-sm-4">
            <input type="text" id="masa_berlaku_no_sipa_pj" required name="masa_berlaku_no_sipa_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_berlaku_no_sipa_pj'] ?>"> 
        </div>
 
    </div>   

     <hr>  

    <h3>Data TTK</h3>  
    <div class="form-group">
        <label class="col-sm-2" for="no_ktp_pj">NK</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_ktp_pj" required name="no_ktp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ktp_pj'] ?>"> 
        </div> 
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="nm_pj">Nama </label>
        
        <div class="col-sm-10">
            <input type="text" id="nm_pj" required name="nm_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['nama_pj'] ?>"> 
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="alamat_pj">Alamat</label> 
        <div class="col-sm-10">
            <textarea required name="alamat_pj" class="form-control input-visible"><?php echo $penanggung_jawab['alamat_pj'] ?></textarea> 
        </div>
    </div>  

    <div class="form-group">
        <label class="col-sm-2" for="no_str">No. SIKTTK</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_str" required name="no_str" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_str_pj'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_str">Tanggal Masa berlaku SIKTTK</label>

         <div class="col-sm-4">
            <input type="text" id="masa_berlaku_no_str" required name="masa_berlaku_no_str" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_berlaku_no_str'] ?>"> 
        </div>
       
    </div>  

     <div class="form-group">

        <label class="col-sm-2" for="no_sipa">No. STRTTK</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_sipa" required name="no_sipa" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sipa_pj'] ?>"> 
        </div>

         <label class="col-sm-2" for="no_str">Tanggal Masa berlaku STRTTK </label>

         <div class="col-sm-4">
            <input type="text" id="masa_berlaku_no_sipa_pj" required name="masa_berlaku_no_sipa_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_berlaku_no_sipa_pj'] ?>"> 
        </div> 
       
    </div>     

</form>  


<script type="text/javascript"> 
    window.onload = function() { window.print(); }
</script>
  </body>
  </html>


   