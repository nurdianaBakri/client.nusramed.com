<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>

<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Update Data Pembelian</h3>

        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">

            <div class="row">
                <div class="col-sm-8">
                    <div class="panel panel-success">
                        <div class="body">

                           
                            <form role="form" id="myForm1">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">No Faktur Pembelian</label>
                                        <select name="no_faktur" class="form-control js-example-basic-single" required
                                            id="no_faktur">
                                            <?php
                                          foreach ($list_faktur as $key) {
                                            ?>
                                            <option value="<?=$key['no_faktur']?>">
                                                <?= $key['no_faktur'] ."-". $key['nama']?></option>
                                            <?php
                                          } 
                                        ?>
                                        </select>
                                    </div> 

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Metode Pembayaran</label>
                                                <!-- <input type="text" class="form-control myForm1" id="metode_pembayaran"
                                                    readonly name="metode_pembayaran"> -->
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
                                        </div>
                                        <div class="col-sm-6 tgl_jatuh_tempo">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Jatuh Tempo</label>
                                                <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo"
                                                    readonly name="tgl_jatuh_tempo">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Tanggal Input Transaksi</label>
                                                <input type="text" class="form-control myForm1" id="tgl_input" readonly
                                                    name="tgl_input">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 id_penginput">
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

                            <div class="box-footer">
                                <button id="cek_transaksi" class="btn btn-success" onclick="cek_transaksi()">Open</button> 
                                <button class="btn btn-success" id="verifikasi" disabled>Update Pembelian
                                    </button> 
                                <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >
                            </div>

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
    // console.log(response);
    if (/^[a-zA-Z0-9- ]*$/.test(response) == false) {
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
    var no_faktur = $('#no_faktur').val();
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        data: {
            no_faktur: no_faktur
        },
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

    var no_faktur = $('#no_faktur').val();
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_detail_trx/";
    console.log(url+"/"+no_faktur);
    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: {
            no_faktur : no_faktur,
         },
        success: function(data) {  
            console.log(data);
            $('#tgl_input').val(data.tgl_input);

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
    var no_faktur = $('#no_faktur').val();
    console.log(no_faktur);
    $('#verifikasi').removeAttr('disabled');

    if (no_faktur == "") {
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

    $('.loading').show(); 
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_list_faktur/";
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        dataType: "html",
        data: { },
        success: function(data) { 
            $('#no_faktur').html(data);
 
            $('#myForm1 select').prop('disabled', false);
            $("#cek_transaksi").attr("disabled", false);
            $('#verifikasi').attr('disabled',true);
        }
    });
    $('.loading').hide(); 
}


$('#verifikasi').click(function() {
    var r = confirm("Yakin Ingin Mengupdate Pembelian ini ? ");
    if (r == true) {

        $('.loading').show(); 

         var kode_pembayaran = $('#kode_pembayaran').val(); //mengambil row_id dari artibut id  
        console.log(kode_pembayaran);

        var url_controller = $('#url').val();
        var url = "<?php echo base_url() ?>" + url_controller + "updatePembelian";
        // alert();
        $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: $('#myForm2').serialize()+"&tgl_input="+$('#tgl_input').val()+"&no_faktur="+$('#no_faktur').val()+"&kode_pembayaran="+kode_pembayaran
            }).done(function(response) {
                console.log(response);
                // if (response.return==1)
                // { 
                //     location.reload();
                // } 

                if (response.pesan=="Proses Update pembelian berhasil ")
                { 
                    sukses2(response.return, response.pesan);
                }
                else{   
                    sukses2(response.return, response.pesan); 
                }    

                 //buka modal 
                setInterval(function(){ 
                   $('.modal-body').html(response.validation_errors);
                   $('#myModal').modal({
                        show: 'true',
                    });  
                }, 6000); 

                setInterval(function(){ 
                    location.reload();
                }, 10000); 

                // get_list_faktur(); 
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