<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Return Barang dari outlet</h3>

        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-success">
                        <div class="body">

                           
                            <form role="form" id="myForm1">
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Outlet <b>(Daftar outlet yang muncul adalah outlet yang pernah melakukan transaksi pembelian)</b></label>
                                                    <select name="id_outlet" class="form-control js-example-basic-single" required id="id_outlet" onchange="get_list_faktur()">
                                                        <?php
                                                      foreach ($outlet as $key) {
                                                        ?>
                                                        <option value="<?=$key['id_outlet']?>">
                                                            <?= $key['id_outlet'] ."-". $key['nama']?></option>
                                                        <?php
                                                      } 
                                                    ?>
                                                    </select>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Nomor Faktur</label>
                                                 <select name="no_faktur" class="form-control js-example-basic-single" required id="no_faktur" onchange="get_detail_trx()">

                                                 </select> 
                                                <p class="loading">Loading...</p>
                                            </div>
                                        </div>
                                    </div>

                                            

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Metode Pembayaran</label> 
                                                 <input type="text" class="form-control myForm1" id="metode_pembayaran" readonly name="metode_pembayaran">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12 tgl_jatuh_tempo">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Jatuh Tempo</label>
                                                <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo" readonly name="tgl_jatuh_tempo">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Tanggal Input Transaksi</label>
                                                <input type="text" class="form-control myForm1" id="tgl_input" readonly
                                                    name="tgl_input">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12 id_penginput">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Nama Penginput</label>
                                                <input type="text" class="form-control myForm1" id="id_penginput"
                                                    readonly name="id_penginput">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body --> 
                            </form> 

                        </div>
                    </div>
                </div>

                 <div class="col-sm-4">
                     <div class="alert alert-success" role="alert">
                      YAY!, fitur ini berfungsi untuk mengubah data pembelian, baik pembelian yang sudah di verifikasi maupun yang belum di verifikasi berdasarkan Nomor Faktur pembelian. Silahkan pilih nomor faktur yang Anda inginkan dan lakukan verifikasi dengan teliti.
                      <p style="font-weight: bold;">TIPS:</p>
                      <li>Cek kesesuian nomor batch, no registrasi, tanggal expired yang ada pada form dengan fisik Obat</li> 
                      <li>Ingat ! nomor batch tidak boleh kosong </li> 
                      <li>Tidak boleh ada nomor Batch yang sama</li> 
                    </div>
                </div> 
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <h3>Data Transaksi Pembelian</h3>  
                        <div id="myForm2"></div> 
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

<script>

$(document).on({
    ajaxStart: function() { 
       Swal.fire({
          position: 'top-end',
          type: 'success',
          title: 'Loading...',
          showConfirmButton: false,
          timer: 1500
        });
    },
    ajaxStop: function() { 
      // $(".loading").hide();    
     }    
});

$(document).ready(function() {
    $('select').select2();
    $(".loading").hide();     
}); 
 

function get_list_faktur() {   
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_list_faktur"; 
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        data: {
            id_outlet: $('#id_outlet').val()
        },
        success: function(data) {  
            $('#no_faktur').html(data); 
            get_detail_trx();  
        }
    }); 
}


function get_trx_tmp() { 
 
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_trx_tmp";
    var no_faktur = $('#no_faktur').val();
    console.log(url);

    $.ajax({
      url: url,
      dataType:"html",
      data: {
            no_faktur: no_faktur
        },
        method: "POST",
      success:function(message){ 
        $('#myForm2').html(message); 
      }
    });  
}

 
function get_detail_trx() { 

    var no_faktur = $('#no_faktur').val();
    var id_outlet = $('#id_outlet').val();
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_detail_trx/";

    console.log(url+"/"+no_faktur);
    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: {
            no_faktur : no_faktur,
            id_outlet : id_outlet,
         },
        success: function(data) {  
            console.log(data);
            $('#tgl_input').val(data.tgl_input);

            $('#tgl_jatuh_tempo').val(data.tgl_jatuh_tempo); 

            if (data.tgl_jatuh_tempo=="-0001-11-30")
            {
                $('.tgl_jatuh_tempo').hide(); 
            }
            else
            {  
                $('.tgl_jatuh_tempo').show();
            } 
            $('#metode_pembayaran').val(data.nama_metode_pembayaran);  


            // $('#metode_pembayaran').val(data.nama_metode_pembayaran);
            $('#id_penginput').val(data.id_user);

            get_trx_tmp();
        }
    }); 
}   

</script>