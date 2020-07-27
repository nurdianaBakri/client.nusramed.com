<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>

    <form id="myForm" method="post" class="form-horizontal"> 
        <h3>Data Outlet</h3> 
      <?php  
          // var_dump($outlet);
          $id = $this->session->userdata('id'); 
       ?>  
      <input type="hidden" name="USER_INPUT" value="<?php echo $id ?>" > 
      <input type="text" name="jenis_aksi" value="<?php echo $jenis_aksi ?>" > 
      <input type="text" name="id_outlet" id="id_outlet" value="<?php echo $id_outlet; ?>" > 
      <input type="text" name="id_penanggung_jawab" value="<?php echo $penanggung_jawab['id_penanggung_jawab']; ?>" > 
      <input type="text" id="id_kab_kota_old" value="<?php echo $id_kab_kota; ?>" > 
      <input type="text" id="id_kec_old" value="<?php echo $id_kec; ?>" > 

      <div class="form-group">
        <label class="col-sm-2" for="nama">Nama</label>
        
        <div class="col-sm-10">
            <input type="text" id="nama" required name="nama" class="form-control input-visible" value="<?php echo $outlet['nama'] ?>"> 
        </div>
    </div>

      <div class="form-group">
        <label class="col-sm-2" for="alamat">Alamat</label>
        
        <div class="col-sm-10"><textarea name="alamat" class="form-control input-visible" required><?php echo $outlet['alamat'] ?></textarea>  
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="alamat">Kab/Kota</label>
        
        <div class="col-sm-4">
         <select class="form-control" name="id_kab_kota" id="id_kab_kota" onchange="get_kecamatan()">
             <option value="">Pilih salah satu</option> 
            <?php foreach ($kab_kota as $key ): ?>
             <option value="<?= $key['id_kab_kota'] ?>"><?= $key['nama'] ?></option>
            <?php endforeach ?>
         </select>
        </div>

        <label class="col-sm-2" for="alamat">Kecamatan</label>
        
        <div class="col-sm-4">
             <select class="form-control kecamatan" name="id_kec" id="id_kec">
                   
             </select>
        </div>
    </div>

      <div class="form-group">
        <label class="col-sm-2" for="npwp">NPWP</label>
        
        <div class="col-sm-4">
          <input type="text" id="npwp"  name="npwp" class="form-control input-visible" value="<?php echo $outlet['npwp'] ?>">
        </div>
         <label class="col-sm-2" for="no_telp">No. Telp</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_telp" name="no_telp" class="form-control input-visible" value="<?php echo $outlet['no_telp'] ?>"> 
        </div>
    </div>  

      <div class="form-group">
          <label class="col-sm-2" for="no_izin">No. SIA/klinik/RS/TO</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_izin"  name="no_izin" class="form-control input-visible" value="<?php echo $outlet['no_izin'] ?>"> 
        </div>

        <label class="col-sm-2" for="masa_izin">Tanggal Masa berlaku No. SIA/klinik/RS/TO </label>

         <div class="col-sm-4">
            <input type="date" id="masa_izin"  name="masa_izin" class="form-control input-visible" value="<?php echo $outlet['masa_izin'] ?>"> 
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
        
        <div class="col-sm-10"> <textarea  name="alamat_pemilik" class="form-control input-visible" required><?php echo $outlet['alamat_pemilik'] ?></textarea> 
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="no_telp_pemilik">No.Telp</label>
        
        <div class="col-sm-10">
          <input type="number" id="no_telp_pemilik" name="no_telp_pemilik" class="form-control input-visible" value="<?php echo $outlet['no_telp_pemilik'] ?>" >
        </div>
    </div>   

   
   
    <hr>  

    <h3>Data Penaggung Jawab</h3>  
    <div class="form-group">
        <label class="col-sm-2" for="no_ktp_pj">NIK</label>
        
        <div class="col-sm-4">
            <input type="number" id="no_ktp_pj" required name="no_ktp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ktp_pj'] ?>"> 
        </div>

         <label class="col-sm-2" for="no_telp_pj">No.Telp</label>
        
        <div class="col-sm-4">
          <input id="no_telp_pj" type="number" name="no_telp_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_telp_pj'] ?>">
        </div>
        
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
        <label class="col-sm-2" for="no_str_pj">No. STR</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_str_pj"  name="no_str_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_str_pj'] ?>"> 
        </div>

        <label class="col-sm-2" for="no_str">Tanggal Masa berlaku STR</label>

         <div class="col-sm-4">
            <input type="date" id="masa_str_pj"  name="masa_str_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_str'] ?>"> 
        </div> 
    </div>  

     <div class="form-group">  
        <label class="col-sm-2" for="no_sipa_pj">No. SIPA</label> 
        <div class="col-sm-4">
            <input type="text" id="no_sipa_pj"  name="no_sipa_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_sipa_pj'] ?>"> 
        </div>

         <label class="col-sm-2" for="masa_sipa_pj">Tanggal Masa berlaku SIPA </label> 
         <div class="col-sm-4">
            <input type="date" id="masa_sipa_pj"  name="masa_sipa_pj" class="form-control input-visible" value="<?php echo $penanggung_jawab['masa_sipa'] ?>"> 
        </div> 
      <!--   <label class="col-sm-2" for="no_ijin_klinik">No. Ijin Klinik</label>
        
        <div class="col-sm-4">
            <input type="text" id="no_ijin_klinik" required name="no_ijin_klinik" class="form-control input-visible" value="<?php echo $penanggung_jawab['no_ijin_klinik_pj'] ?>"> 
        </div> --> 
    </div>   

     <hr>  

    <h3>Data TTK</h3>  
    <div class="form-group">

        <input type="text" id="id_ttk" name="id_ttk" value="<?php echo $ttk['id_ttk'] ?>"> 

        <label class="col-sm-2" for="nik_ttk">NK</label>
        <div class="col-sm-4">
            <input type="number" id="nik_ttk" name="nik_ttk" class="form-control input-visible" value="<?php echo $ttk['nik'] ?>"> 
        </div> 
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="nama_ttk">Nama </label>
        
        <div class="col-sm-4">
            <input type="text" id="nama_ttk" name="nama_ttk" class="form-control input-visible" value="<?php echo $ttk['nama'] ?>"> 
        </div>

        <label class="col-sm-2" for="nama_ttk">No Telp </label>
        
        <div class="col-sm-4">
            <input type="text" id="no_telp_ttk"  name="no_telp_ttk" class="form-control input-visible" value="<?php echo $ttk['no_telp'] ?>"> 
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2" for="alamat_ttk">Alamat</label> 
        <div class="col-sm-10"><textarea  name="alamat_ttk" class="form-control input-visible"><?php echo $ttk['alamat'] ?></textarea> 
        </div>
    </div>  

    <div class="form-group">
        <label class="col-sm-2" for="no_sikttk">No. SIKTTK</label> 
        <div class="col-sm-4">
            <input type="text" id="no_sikttk"  name="no_sikttk" class="form-control input-visible" value="<?php echo $ttk['no_sikttk'] ?>"> 
        </div>

        <label class="col-sm-2" for="masa_sikttk">Tanggal Masa berlaku SIKTTK</label> 
         <div class="col-sm-4">
            <input type="date" id="masa_sikttk"  name="masa_sikttk" class="form-control input-visible" value="<?php echo $ttk['masa_sikttk'] ?>"> 
        </div>
       
    </div>  

     <div class="form-group">

        <label class="col-sm-2" for="no_strttk">No. STRTTK</label> 
        <div class="col-sm-4">
            <input type="text" id="no_strttk"  name="no_strttk" class="form-control input-visible" value="<?php echo $ttk['no_strttk'] ?>"> 
        </div>

         <label class="col-sm-2" for="masa_strttk">Tanggal Masa berlaku STRTTK </label> 
         <div class="col-sm-4">
            <input type="date" id="masa_strttk"  name="masa_strttk" class="form-control input-visible" value="<?php echo $ttk['masa_strttk'] ?>"> 
        </div>  
    </div>     

</form>  


      <button type="button" id="btnSave" onclick="save()" class="btn btn-primary btn-xs">Save</button> 
      <button type="button" class="btn btn-warning btn-xs" data-dismiss="modal">Close</button> 

<script type="text/javascript">

    $(document).ready(function(){ 
        $('select').css('width', '100%'); 
        $('select').select2();   

        var id_kab_kota_old = $('#id_kab_kota_old').val(); 
        $("#id_kab_kota").val(id_kab_kota_old).trigger("change");   

        get_kecamatan();  
    }); 

   function get_kecamatan() {
    var id_kab_kota = $('#id_kab_kota').val(); 

        var url = url_controller+"get_kecamatan/"+id_kab_kota;
        console.log(url);
        $.ajax( {
            type: "POST",
            url: url,
            data:{},
            dataType: "html",
            success: function( response ) { 
                // console.log(response);
                try{   
                    $('.kecamatan').html(response); 

                    var id_kec_old = $('#id_kec_old').val(); 
                    $("#id_kec").val(id_kec_old).trigger("change");  

                }catch(e) {  
                    alert('Exception while request..');
                }  
            }
        } );  
    } 
</script>
 