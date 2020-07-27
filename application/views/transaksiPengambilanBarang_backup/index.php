<link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
<script src="<?php echo base_url()."assets/" ?>select2.min.js"></script>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Input Transaksi Penjualan</h3>
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
            
            <div class="panel panel-success">
                <div class="body">
                    <form role="form" id="myForm1">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">No Faktur Penjualan</label>
                                        <select name="no_faktur" class="form-control js-example-basic-single" required
                                            id="no_faktur">
                                            <?php
                                            foreach ($list_faktur as $key) {
                                            ?>
                                            <option value="<?=$key['no_faktur']?>">
                                            <?= $key['no_faktur']. "- ".$key['kd_outlet']." (".$key['alasan_return'].")"?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Metode Pembayaran</label>
                                                <input type="text" class="form-control myForm1" id="metode_pembayaran"
                                                readonly name="metode_pembayaran">
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
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Tanggal Input Transaksi</label>
                                        <input type="text" class="form-control myForm1" id="tgl_input" readonly
                                        name="tgl_input">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Nama Penginput</label>
                                        <input type="text" class="form-control myForm1" id="id_penginput"
                                        readonly name="id_penginput">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <button id="cek_transaksi" class="btn btn-success" onclick="cek_transaksi()">Cek
                                    transaksi</button>
                                    <button class="btn btn-success" id="verifikasi" disabled>Verifikasi Pengambilan barang
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                    <div class="box-footer">
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >

                        <div class="table-responsive">
                            <form id="myForm2">
                                <table class="table table-bordered table-hover" id="tab_logic" style="font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> # </th>
                                            <th class="text-center" style="width:15%;"> Barcode-Nama</th>
                                            
                                            <th class="text-center" style="width:20%;"> No. Batch</th>
                                            <th class="text-center" style="width:20%;"> No. Reg</th>
                                            <th class="text-center"> Tgl Exp</th>
                                            <th class="text-center" style="width:10%;"> Harga</th>
                                            <th class="text-center" style="width:10%;"> Diskon %</th>
                                            <th class="text-center" style="width:10%;"> Qty</th>
                                            <th class="text-center" style="width:10%;"> Qty Verified</th>
                                            <th class="text-center">Subtotal</th>
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
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        </div>
        <!-- /.box-footer-->
    </div>
    <!-- /.box -->
</section>

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
<script>
$(document).ready(function() {
    $('select').select2();
    $(".form-group .input_nama_barang").attr("disabled", "disabled");

    //disable the submit button
    $(".simpan_penjualan").attr("disabled", true);
    $('.loading').hide();    
}); 
 
function reload_cart() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "load_cart";
    $('#element_table').load(url);
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
   
function get_trx_tmp() {
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_trx_tmp";
    var no_faktur = $('#no_faktur').val();
    $.ajax({
        url: url,
        method: "POST",
        data: {
            no_faktur: no_faktur
        },
        success: function(data) {
            add_row2(data);
            $('#barcode').val('');

            get_detail_trx();
        }
    });
}

function get_detail_trx() {
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_detail_trx";
    var no_faktur = $('#no_faktur').val();
    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: {
            no_faktur: no_faktur
        },
        success: function(data) {
            console.log(data);
            $('#tgl_input').val(data.tgl_input);
            $('#tgl_jatuh_tempo').val(data.tgl_jatuh_tempo);
            $('#metode_pembayaran').val(data.nama_metode_pembayaran);
            $('#id_penginput').val(data.id_user_input);
        }
    });
}

  //Hapus Item Cart
$(document).on('click', '.close', function() {
    location.reload();   
});


function cek_transaksi() {
    //chhekc apakah form invalid  
    var no_faktur = $('#no_faktur').val();
    console.log(no_faktur);
    $('#verifikasi').removeAttr('disabled');

    if (no_faktur == "") {
        alert("Silahkan isi form dengan lengkap");
    } else {
        $("#myForm1 select").attr("disabled", "disabled");
        $("#myForm1 .myForm1").attr("readonly", true);

        $("#barcode").attr("readonly", false);
        $("#kandungan").attr("readonly", true);
        $(".simpan_penjualan").attr("disabled", false);

        $("#cek_transaksi").attr("disabled", true);

        $(".form-group .input_nama_barang").removeAttr("disabled");
        // document.getElementById("barcode").focus();

        get_trx_tmp();
    }
} 

$('#verifikasi').click(function() {
    var r = confirm("Yakin Ingin verifikasi transaksi ini ? ");
    if (r == true) {
        $('.loading').show();   

        var url_controller = $('#url').val();
        var url = "<?php echo base_url() ?>" + url_controller + "verifikasiTransaksi";
        // alert();
        $.ajax({
                type: "POST",
                url: url,
                dataType: "Json",
                data: $('#myForm2').serialize()+"&no_faktur="+$('#no_faktur').val()
            }).done(function(response) { 

                console.log(response); 
                // if (response.return == 0) {
                //     status = "error";
                // }
                // if (response.return == 1) {
                //     status = "success";
                // }
                // swal("Perhatian!", response.pesan, status); 

                setInterval(function(){ 
                   $('.modal-body').html(response.pesan);
                   $('#myModal').modal({
                        show: 'true',
                    });  
                }, 1000);  
            })
            .fail(function(jqXHR, textStatus) {
                //    console.log(data);
                console.log("reqest error")
            });
        $('.loading').hide();   

    } else {
        x = "You pressed Cancel!";
    }
})
</script>