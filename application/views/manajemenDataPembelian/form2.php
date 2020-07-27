<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {  
 setInterval(function() {
    $('.info_button').animate( { backgroundColor: 'red' }, 300)
    .animate( { backgroundColor: '#edbc0c' }, 300); 
    }, 1000); 
 });

</script>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Update Data Pembelian</h3> 
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">

            <p> 
              <button class="btn btn-xs btn-warning btn-block info_button" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                KLIK DI SINI UNTUK MELIHAT TIPS !!
              </button>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body alert alert-warning" role="alert"> 
                  YAY!, fitur ini berfungsi untuk mengubah data pembelian, baik pembelian yang sudah di verifikasi maupun yang belum di verifikasi berdasarkan Nomor Faktur pembelian. Silahkan pilih nomor faktur yang Anda inginkan dan lakukan verifikasi dengan teliti.
                  <p style="font-weight: bold;">TIPS:</p>
                  <li>Cek kesesuian nomor batch, no registrasi, tanggal expired yang ada pada form dengan fisik Obat</li> 
                  <li>Ingat ! nomor batch tidak boleh kosong </li> 
                  <li>Tidak boleh ada nomor Batch yang sama</li>  
                </div> 
            </div> 

            <div class="row">   
                    <div class="col-sm-4"> 
                        <div class="form-group">
                          <label >Faktur Pembelian dari tanggal</label>  
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai datepicker" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div> 

                   <div class="col-sm-4">
                        <div class="form-group">
                          <label >Sampai tanggal</label>
                            <input type="date" id="tanggal_sampai" name="tanggal_sampai" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div> 

                    <div class="col-sm-4">
                        <div class="form-group">
                          <label >Lihat Faktur</label>
                            <button class="btn btn-xs btn-success form-control" onclick="get_list_faktur()">Lihat Faktur</button>
                        </div>
                    </div> 
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <div class="body">  
                           
                            <form role="form" id="myForm1">
                                <div class="box-body">  
                                    <div class="row">
                                        <div class="col-sm-6">  
                                            <div class="row">
                                                <div class="col-sm-9">  
                                                    <div class="form-group">
                                                        <label >No Faktur Pembelian</label>
                                                        <select name="id_master_detail" class="form-control js-example-basic-single" required id="id_master_detail">  
                                                        </select>
                                                    </div> 
                                                </div>
                                                <div class="col-sm-3">  
                                                    <label >Lihat Item Beli</label>
                                                    <button class="btn btn-xs btn-success form-control" onclick="cek_transaksi()">Open</button> 
                                                </div>
                                            </div>

                                             <div class="form-group">
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

                                            <div class="form-group">
                                                <label >Tanggal Input Transaksi</label>
                                                <input type="text" class="form-control myForm1" id="tgl_input" readonly name="tgl_input">
                                            </div> 

                                        </div>
                                         <div class="col-sm-6 ">  
                                            <div class="form-group tgl_jatuh_tempo">
                                                <label >Jatuh Tempo</label>
                                                <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo"
                                                    readonly name="tgl_jatuh_tempo">
                                            </div>

                                             <div class="form-group id_penginput">
                                                <label >Nama Penginput</label>
                                                <input type="text" class="form-control myForm1" id="id_penginput"
                                                    readonly name="id_penginput">
                                            </div> 

                                            <button class="btn btn-success btn-block" id="verifikasi" disabled>Update Pembelian
                                                </button> 
                                        </div>
                                    </div> 
                                       
 
                                </div>
                                <!-- /.box-body --> 
                            </form>

                            <div class="box-footer"> 
                                <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >
                            </div>

                        </div>
                    </div>
                </div>

                
                
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <h3>Data Transaksi Pembelian</h3>
                        <form id="myForm2">
                            <table class="table table-bordered table-hover table-responsive" id="tab_logic" style="font-size: 10px">
                                <thead>
                                    <tr>
                                        <th class="text-center"> # </th>
                                        <th class="text-center" style="width:10%;"> Barcode</th> 
                                        <th class="text-center"> Satuan</th>
                                        <th class="text-center" style="width:20%;"> No. Batch</th>
                                        <th class="text-center" style="width:20%;"> No. Reg</th>
                                        <th class="text-center" style="width:5%;"> Tgl Exp</th>
                                        <th class="text-center" style="width:10%;"> Harga</th>
                                        <th class="text-center" style="width:7%;"> Diskon %</th>
                                        <th class="text-center" style="width:7%;"> Qty</th> 
                                    </tr>
                                </thead>
                                <tbody id='element_table'> 

                                </tbody> 
                            </table>
                        </form>
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


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Informasi !</h4>
            </div>
            <div class="modal-body"> 
                
            </div>  
            <div class="modal-footer"> 
            </div>
        </div>
    </div>
</div> 
</section> 

<script>
$(document).ready(function() {
    $('select').select2();
    $(".form-group .input_nama_barang").attr("disabled", "disabled");

    //disable the submit button 
    $('.loading').hide();  
}); 
 

function reload_cart() {
    $('.loading').show();
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "load_cart";
    $('#element_table').load(url);
    $('.loading').hide();
} 

$("#myForm1").submit(function(e) {
    e.preventDefault();
    cek_transaksi();
}); 
 

function add_row2(response) { 

     if (response=="berhasil") {
        reload_cart();
    } else {
        // swal("Perhatian", response, "error");
        sukses2(0, response);
    }
} 

$(document).on('click', '.update_cart', function() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "update_cart";
    console.log(url);

    var row_id = $(this).attr("id"); //mengambil row_id dari artibut id  
    $.ajax({
        url: url,
        method: "POST",
        dataType: "html",
        data: $('#myForm2').serialize() + "&row_id=" + row_id,
        success: function(data) {
            console.log(data);
            reload_cart();
        }
    });
}); 
 

function get_trx_tmp() {

    $('.loading').show();
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_trx_tmp"; 
    console.log("List faktur"); 
    $.ajax({
        url: url,
        method: "POST",
        data: $('#myForm1').serialize(),
        success: function(data) {
            // console.log(data);  
            add_row2(data);  
            get_detail_trx();  
        }
    });
    $('.loading').hide(); 
}

function get_detail_trx() {
    $('.loading').show();  
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_detail_trx/";
    console.log(' get detail Transaksi'); 

    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data:  $('#myForm1').serialize(),
        success: function(data) {   
            $('#tgl_input').val(data.time);

            $('#tgl_jatuh_tempo').val(data.tgl_jatuh_tempo); 
            if (data.tgl_jatuh_tempo=="0000-00-00 00:00:00")
            {
                $('.tgl_jatuh_tempo').hide(); 
            }
            else
            {  
                $('.tgl_jatuh_tempo').show();
            }
            $('#metode_pembayaran').val(data.kode_pembayaran);  
 
            // $('#metode_pembayaran').val(data.nama_metode_pembayaran);
            $('#id_penginput').val(data.id_user);
        }
    });
    $('.loading').hide();  
}

function cek_transaksi() {
    //chhekc apakah form invalid  
    var id_master_detail = $('#id_master_detail').val(); 
    $('#verifikasi').removeAttr('disabled');

    if (id_master_detail == "") { 
        alert("Silahkan isi form dengan lengkap");

    } else {
        // $("#myForm1 select").attr("disabled", "disabled");
        $("#myForm1 .myForm1").attr("readonly", true);

        $("#barcode").attr("readonly", false);
        $("#kandungan").attr("readonly", true); 

        // $("#cek_transaksi").attr("disabled", true);

        $(".form-group .input_nama_barang").removeAttr("disabled");
        // document.getElementById("barcode").focus(); 
        get_trx_tmp();
    }
}

function get_list_faktur() { 
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_list_faktur";
     
    $.ajax({
        url: url,
        method: "POST",
        dataType: "html",
        data:{
            tanggal_mulai : $('#tanggal_mulai').val(),
            tanggal_sampai : $('#tanggal_sampai').val(),
        },
        success: function(data) { 
            $('#id_master_detail').html(data);
 
           /* $('#myForm1 select').prop('disabled', false);
            $("#cek_transaksi").attr("disabled", false);
            $('#verifikasi').attr('disabled',true);*/
        }
    }); 
}


$('#verifikasi').click(function() {
    var r = confirm("Yakin Ingin Mengupdate Pembelian ini ? ");
    if (r == true) { 

        var url = "<?php echo base_url() ?>" + $('#url').val() + "updatePembelian"; 
        $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: $('#myForm2').serialize()+"&tgl_input="+$('#tgl_input').val()+"&id_master_detail="+$('#id_master_detail').val()+"&kode_pembayaran="+$('#kode_pembayaran').val()
            }).done(function(response) {
                console.log(response); 

                if (response.pesan=="Proses Update pembelian berhasil ")
                { 
                    sukses2(response.return, response.pesan);
                }
                else{   
                    sukses2(response.return, response.pesan); 
                }      

              /*  setInterval(function(){ 
                    //buka modal 
                    $('.modal-body').html(response.validation_errors);
                    $('#myModal').modal({
                        show: 'true',
                    }); 
                }, 6000);  */ 
 
                // reload_cart();
            })
            .fail(function(jqXHR, textStatus) {
                //    console.log(data);
                console.log("reqest error")
            });
    } else {
        x = "You pressed Cancel!";
    }
    $('.loading').hide(); 

});


//Hapus Item Cart
$(document).on('click', '.romove_cart', function() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "hapus_cart";

    var row_id = $(this).attr("id"); //mengambil row_id dari artibut id
    $.ajax({
        url: url,
        method: "POST",
        data: {
            row_id: row_id
        },
        success: function(data) {
            reload_cart();
        }
    });
});
</script>