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
                
                <form role="form" id="myForm1">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Mulai</label>
                                    <input type="date" class="form-control myForm1" id="tgl_mulai" name="tgl_mulai" value="<?= date('Y-m-d') ?>" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Tanggal Akhir</label>
                                    <input type="date" class="form-control myForm1" id="tgl_akhir" name="tgl_akhir" value="<?= date('Y-m-d') ?>" >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Status Verifikasi Peng. Barang</label>
                                    <select name="status_pengambilan" class="form-control js-example-basic-single" required
                                        id="status_pengambilan" onchange="get_faktur()">
                                        <option value="0">Belum diverifikasi </option>
                                        <option value="1">Sudah diverifikasi </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">No Faktur Penjualan</label>
                                    <select name="no_faktur" class="form-control js-example-basic-single" required
                                        id="no_faktur" onchange="get_trx_tmp()">
                                        
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Metode Pembayaran</label>
                                    <select class="form-control" name="kd_pembayaran" id="metode_pembayaran" onchange="change_metode_pembayaran()">
                                        <?php foreach ($metode_pembayaran as $key): ?>
                                        <option value="<?= $key['kd_pembayaran'] ?>"> <?= $key['kd_pembayaran']." - ".$key['nama_metode_pembayaran'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Jatuh Tempo</label>
                                    <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo">
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
                                <button class="btn btn-success btn-block" id="verifikasi">Verifikasi Pengambilan barang
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-success">
                            <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >
                            <div class="table-responsive">
                                <form id="myForm2">
                                    <table class="table table-bordered table-hover" id="tab_logic"  >
                                        <thead>
                                            <tr class="text-center">
                                                <th> # </th>
                                                <th style="width:15%;"> Barcode-Nama</th>
                                                
                                                <th style="width:12%;"> No. Batch</th>
                                                <th style="width:10%;"> No. Reg</th>
                                                <th style="width:10%;"> Tgl Exp</th>
                                                <th style="width:10%;"> Harga</th>
                                                <th style="width:10%;"> Diskon %</th>
                                                <th style="width:10%;"> Qty</th>
                                                <th style="width:10%;"> Qty Verified</th>
                                                <th>Subtotal</th>
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
            </div>
        </div>
    </div>
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

    //disable the submit button
    $('.loading').hide();    
}); 

function change_metode_pembayaran() {
     var metode_pembayaran=$('#metode_pembayaran').val();
     if (metode_pembayaran=="100")
     {
        $('#tgl_jatuh_tempo').hide();  
     }
     else
     {
        $('#tgl_jatuh_tempo').show();  
     }
}
 
function reload_cart() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "load_cart";
    $('#element_table').load(url);
} 

$("#myForm1").submit(function(e) {
    e.preventDefault();

    console.log('submit');
    // cek_transaksi();
});

function get_faktur() {   
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_faktur"; 
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        data: {
            status_pengambilan: $('#status_pengambilan').val(),
            tgl_mulai: $('#tgl_mulai').val(),
            tgl_akhir: $('#tgl_akhir').val()
        },
        success: function(data) {  
            $('#no_faktur').html(data);   

            //get detail
            get_trx_tmp();
        }
    }); 
} 
 

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
    var no_faktur = $('#no_faktur').val();
    var status_pengambilan = $('#status_pengambilan').val(); 
    var url = "<?php echo base_url() ?>" + $('#url').val() + "get_trx_tmp/"+no_faktur+"/"+status_pengambilan;

    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        // dataType: "html",
        data: {
            no_faktur: no_faktur,
            status_pengambilan: status_pengambilan, 
        },
        success: function(data) {

            console.log(data);
            add_row2(data);  
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
            no_faktur: no_faktur,
            status_pengambilan: $('#status_pengambilan').val(), 
        },
        success: function(data) {
            console.log(data); 
            $('#tgl_input').val(data.tgl_input);
            if (data.tgl_jatuh_tempo=='-0001-11-30')
            {
                $('#tgl_jatuh_tempo').hide(); 
            }
            else{ 
                $('#tgl_jatuh_tempo').val(data.tgl_jatuh_tempo);
                $('#tgl_jatuh_tempo').show();  
            } 

            $("#metode_pembayaran").val(data.kode_pembayaran).trigger("change"); 
            $('#id_penginput').val(data.id_user_input);
        }
    });
}

  //Hapus Item Cart
$(document).on('click', '.close', function() {
    location.reload(); 
    
    $('#myModal').modal({
        show: 'false',
    });  
}); 

function cek_transaksi() {  
    //chhekc apakah form invalid  
    var no_faktur = $('#no_faktur').val();
    console.log(no_faktur);
    $('#verifikasi').attr('disabled', false);

    if (no_faktur == "") {
        alert("Silahkan isi form dengan lengkap");
    } else {
        $("#myForm1 select").attr("disabled", "disabled");
        $("#myForm1 .myForm1").attr("readonly", true);  
    }
} 

$('#verifikasi').click(function() {
    var r = confirm("Yakin Ingin verifikasi transaksi ini ? ");
    if (r == true) {
        $('.loading').show();   

        var url_controller = $('#url').val();
        var no_faktur = $('#no_faktur').val();
        var tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
        var metode_pembayaran = $('#metode_pembayaran').val();
        var url = "<?php echo base_url() ?>" + url_controller + "verifikasiTransaksi";
        // alert();
        $.ajax({
                type: "POST",
                url: url,
                dataType: "Json",
                data: $('#myForm2').serialize()+"&no_faktur="+no_faktur+"&metode_pembayaran="+metode_pembayaran+"&tgl_jatuh_tempo="+tgl_jatuh_tempo
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