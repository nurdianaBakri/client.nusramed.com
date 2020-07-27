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

            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-success">
                        <div class="body">

                            <form role="form" id="myForm1">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Outlet</label>
                                        <select name="kd_outlet" class="form-control js-example-basic-single" required
                                            id="kd_outlet">
                                            <?php
                                          foreach ($outlet as $key) {
                                            ?>
                                            <option value="<?=$key['id_outlet']?>">
                                                <?= $key['id_outlet']." - ".$key['nama']?></option>
                                            <?php
                                          } 
                                        ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputPassword1">No Faktur Penjualan</label>
                                        <input type="text" class="form-control myForm1" name="no_faktur myForm1"
                                            id="no_faktur" value="<?= $no_faktur; ?>" readonly>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Metode Pembayaran</label>
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
                                                <?php
                                                $date=date('Y-m-d')
                                            ?>
                                                <label for="exampleInputPassword1">Jatuh Tempo</label>
                                                <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo"
                                                    name="tgl_jatuh_tempo" min="<?= $date; ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                            </form>

                            <div class="box-footer">
                                <!-- <button id="input_stok" class="btn btn-success" onclick="input_stok()">Input Transaksi Penjualan</button> -->
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="panel panel-success">
                        <div class="body">

                            <div class="box-body">

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Cari obat berdasarkan barcode</label>
                                    <input type="text" name="barcode" id="barcode"
                                        class="form-control input-lg input_barcode">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Cari Obat berdasarkan nama dan barcode</label>
                                    <select name="barcode_nama"
                                        class="form-control js-example-basic-single input_nama_barang" id="barcode_nama"
                                        onchange="get_barang_by_barcode()">
                                        <?php
                                          foreach ($Obat as $key) {
                                            ?>
                                        <option value="<?=$key['barcode']?>"><?= $key['barcode']." - ".$key['nama']?>
                                        </option>
                                        <?php
                                          } 
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nama Obat</label>
                                    <input type="text" name="nama_obat" id="nama_obat" class="form-control"
                                        readonly="true">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Kandungan</label>
                                    <textarea name="kandungan" id="kandungan" class="form-control" readonly></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="panel panel-success">
                        <div class="body">

                            <div class="box-body">

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Grand Total</label>
                                    <input type="text" name="total" id="total" class="form-control input-lg" readonly="true" value=""><b id="total"></b>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Bayar [Enter]</label>
                                    <input type="text" name="bayar" id="bayar" class="form-control money input-lg">
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">Kembali</label>
                                    <textarea name="kembali" id="kembali" class="form-control"
                                        readonly="true"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-block simpan_penjualan" onclick="simpan_penjualan()">Simpan
                        Transaksi</button>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <form id="myForm2">

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="tab_logic" style="font-size: 10px">
                                    <thead>
                                        <tr>
                                            <th class="text-center"> # </th>
                                            <th class="text-center" style="width:15%;"> Barcode</th> 
                                            <th class="text-center"> Satuan</th>
                                            <th class="text-center"> No. Batch</th>
                                            <th class="text-center"> No. Reg</th>
                                            <th class="text-center"> Tgl Exp</th>
                                            <th class="text-center" style="width:10%;"> Harga</th>
                                            <th class="text-center" style="width:10%;">Nilai PPN</th>
                                            <th class="text-center" style="width:7%;"> Diskon %</th>
                                            <th class="text-center" style="width:7%;"> Qty</th>
                                            <th class="text-center" style="width:7%;"> Kosong</th>
                                            <th class="text-center"> Sub Total</th>
                                            <th class="text-center"> </th>
                                        </tr>
                                    </thead>
                                    <tbody id='element_table'>


                                    </tbody>
                                    <tr>
                                        <td colspan="4">
                                            Diskon : <input type="number" name="jumlah_diskon"
                                                class="form-control jumlah_diskon" value="" readonly>
                                        </td>
                                        <td>
                                            PPN : <input type="number" id="ppn" name="ppn" class="form-control" min=10
                                                value=10>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                                
                        </form>
                    </div>
                </div>
            </div> 
        </div>
       
    </div>
    <!-- /.box -->
</section>

<script type="text/javascript" src="<?= base_url()."assets" ?>/simple.money.format.js"></script>

<script>

$(document).ready(function() {

    $('.js-basic-example').DataTable({
        responsive: false, 
        autoWidth : false, 
    });  

    document.getElementById("barcode").focus();
    $('.money').simpleMoneyFormat();

    $('select').select2();
    $('.tgl_jatuh_tempo').hide();
    // $(".form-group .input_nama_barang").attr("disabled","disabled");

    //disable the submit button
    $(".simpan_penjualan").attr("disabled", true);
    reload_cart();
    setTimeout(() => {
        total_diskon()
    }, 10000);
    
});

function togle_jatuh_tempo(kode) {
    if (kode == 100) {
        $('.tgl_jatuh_tempo').hide();
    } else {
        $('.tgl_jatuh_tempo').show();
    }
}

$("#myForm1").submit(function(e) {
    e.preventDefault();
    input_stok();
});


function reload_cart() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "load_cart";
    $('#element_table').load(url);
    get_grand_total(); 
}

function get_grand_total() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "get_grand_total/";
    console.log(url);

    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "json",
        success: function(response) {
            console.log(response);
            try {
                if($('#ppn').val()!=response.ppn){
                    if(response.ppn<10){
                        $('#ppn').val(10);
                    }else{
                        $('#ppn').val(response.ppn);
                    }
                } 
                $('#total').val(response.grand_total);
            } catch (e) {
                // alert('Exception while request..');
            }
        }
    });
}


$("#myForm1").submit(function(e) {
    e.preventDefault();
    input_stok();
});

function add_row(response, barcode) {
    // console.log(response);
    if (/^[a-zA-Z0-9- ]*$/.test(response) == false) {
        // alert('Your search string contains illegal characters.');
        get_kandungan(barcode);
        // $(response).prependTo($("#element_table")); 
        reload_cart();
    } else {
        // swal("Perhatian", response, "error");
        sukses2(0, response);

    }
}

function kalkulasiDiskonPerItem(x) {
    var rowIndex = x.rowIndex - 1;
    console.log("Row index is: " + rowIndex);
    var index_val = x.rowIndex - 1;
    var harga = $(".harga")[index_val].value;
    var kosong = $(".kosong")[index_val].value;
    var diskon = $(".diskon")[index_val].value;
    var harga_jual = $(".harga")[index_val].value;
    var qty = $(".qty")[index_val].value;
    var stok_awal = $(".stok_awal")[index_val].value;
    var ppn_value = $(".ppn_value")[index_val].value; 

    if (qty > parseInt(stok_awal)) {
        var barcode = $(x).find("#barcode_item").val();
        $(x).find(".qty").val(parseInt(stok_awal));
        qty = parseInt(stok_awal);
        var harga_setelah_diskon = $('.harga_setelah_diskon')[index_val];
        harga_setelah_diskon.value =(harga_jual * (diskon/ 100)*qty);
        var total = (qty * harga_jual) - harga_setelah_diskon.value;
        console.log(total);
       $(x).find(".subtotal").text(addCommas(total.toFixed(2)));
        var ppn = parseFloat($("#ppn").val());
        // alert(ppn);
        var ppn_value = (parseFloat(ppn) / 100) * total;
        console.log("ppn = "+ppn+" nilai = "+ppn_value);

        //tampilkan di view
         $(x).find("#ppn_value").text(addCommas(ppn_value.toFixed(2)));  


        $(x).find(".ppn_value").val(formatRupiah(ppn_value.toString()));
        var total_produk = 0;       
        $('#myForm2').find("tr").not(':first').not(':last').each(function() {
            ppn_count = parseFloat($(this).find('.ppn_value').val().replace(',', ''));
            total_produk += parseFloat($(this).find('.subtotal').text().replace(',', '')) +  ppn_count;
              console.log("ppn_count ="+ppn_count);            
              console.log("perhitungan ="+total_produk+" + "+ ppn_count);            
              console.log("total product ="+total_produk);            
              console.log("fungsi 1");            
        });
        $("#total").val(addCommas((total_produk).toString()));
        var rowid = $(x).attr('data-id');
        var data = {
            rowid: rowid,
            qty: parseInt(stok_awal),
            diskon: diskon,
            kosong: kosong,
            subtotal: parseInt(total),
            ppn: parseInt(ppn)
        };
        update_cart_onchange(data);
        total_diskon();
        setTimeout(() => {
            cek_next_exp(barcode, stok_awal);
        }, 500);        
    } else {
        var harga_setelah_diskon = $('.harga_setelah_diskon')[index_val];
        harga_setelah_diskon.value =(harga_jual * (diskon/ 100)*qty);        
        var total = (qty * harga_jual) - harga_setelah_diskon.value;
        // console.log("Diskon = "+harga_setelah_diskon.value);
        // console.log("Total harga item = "+total);
        $(x).find(".subtotal").text(addCommas(total.toFixed(2)));
        var ppn = parseFloat($("#ppn").val());
        // alert(ppn);
        var ppn_value = (parseFloat(ppn) / 100) * total;
        console.log("ppn = "+ppn+" nilai = "+ppn_value);  

        //tampilkan di view
        $(x).find("#ppn_value").text(addCommas(ppn_value.toFixed(2)));  

        $(x).find(".ppn_value").val(formatRupiah(ppn_value.toString()));
        var total_produk = 0;       
        $('#myForm2').find("tr").not(':first').not(':last').each(function() {
            ppn_count = parseFloat($(this).find('.ppn_value').val().replace(',', ''));
            total_produk += parseFloat(parseInt($(this).find('.subtotal').text().replace(',', ''))) +  ppn_count;     

            console.log("ppn_count ="+ppn_value);            
            console.log("perhitungan ="+total_produk+" + "+ ppn_value);            
            console.log("total product ="+total_produk);               
        }); 

        $("#total").val(addCommas((total_produk).toString()));
        var rowid = $(x).attr('data-id');
        var data = {
            rowid: rowid,
            qty: qty,
            diskon: diskon,
            kosong: kosong,
            subtotal: parseInt(total),
            ppn: parseInt(ppn)
        };
        update_cart_onchange(data);
        total_diskon();
    }
}
$(document).find('#ppn').change(function() {
    // alert("ok")
    var x = document.getElementById("element_table").querySelectorAll("tr");
    var i;
    for (i = 0; i < x.length; i++) {
        var harga = $(".harga")[i].value;
        var kosong = $(".kosong")[i].value;
        var diskon = $(".diskon")[i].value;
        var harga_jual = $(".harga")[i].value;
        var qty = $(".qty")[i].value;
        var stok_awal = $(".stok_awal")[i].value;
        var harga_setelah_diskon = $('.harga_setelah_diskon')[i];
        harga_setelah_diskon.value =(harga_jual * (diskon/ 100)*qty);        
        var total = (qty * harga_jual) - harga_setelah_diskon.value;
        var ppn = $(this).val();
        var ppn_value = (parseInt(ppn) / 100) * total;
        $(x[i]).find(".ppn_value").val(formatRupiah(ppn_value.toString()));
        kalkulasiDiskonPerItem(x[i]);

    }
    
    // var ppn_value = (parseInt(ppn) / 100) *
    //     $('.ppn_value').val(formatRupiah());
})

function total_diskon() {
    var totalPoints = 0;
    $('.harga_setelah_diskon').each(function() {
        totalPoints += parseInt($(this).val())
        console.log("total = "+totalPoints);
    });
    $('.jumlah_diskon').val(totalPoints); 
}

function update_cart_onchange(update) {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "update_cart_onchange";
    console.log(update);
    $.ajax({
            type: "POST",
            url: url,
            data: update
        }).done(function(html) {
            console.log(html);
        })
        .fail(function(jqXHR, textStatus) {
            //    console.log(data);
            console.log("reqest error")
        });

        get_grand_total(); 
}

function update_cart() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "update_cart";
    $.ajax({
        url: url,
        method: "POST",
        data: $('#myForm2').serialize(),
        success: function(data) {
            console.log(data);
        }
    });
}

$('#barcode').keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        // console.log("fungsi get_barang_by_barcode");
        get_barang_by_barcode(13);
    }
});

$('#bayar').keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        // console.log("fungsi get_barang_by_barcode");
        // simpan_penjualan();
        var bayar = $(this).val().replace(',', '');
        var total = $('#total').val().replace(',', '');
        var kembali = parseFloat(bayar) - parseFloat(total);
        console.log("kembali " +kembali)
        console.log("kembali " +parseFloat(bayar)+" - "+parseFloat(total))
        if (kembali < 0) {
            alert("jumlah bayar kurang !")
        } else {
            $('#kembali').val(formatRupiah(kembali.toString()));
            $('.simpan_penjualan').removeAttr('disabled');
        }
    }
});


function simpan_penjualan() {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "simpan_penjualan/";
    console.log(url);

    var kd_outlet = $('#kd_outlet').val();
    var no_faktur = $('#no_faktur').val();
    var kode_pembayaran = $('#kode_pembayaran').val();
    var tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
    var total = $('#total').val();
    var ppn = $('#ppn').val();
    var bayar = $('#bayar').val();
    var kosong = $('.kosong').val();

    /*check if field bayar tidak di isi*/
    if (bayar == "") {
        // sukses2(0, "Silakan masukkan jumlah pembayaran");  
        swal("Perhatian", "Silakan masukkan jumlah pembayaran terlebih dahulu", "error");
    } else {

        console.log(bayar);
        console.log(total);

        $.ajax({
            type: "POST",
            url: url,
            data: $('#myForm2').serialize() + "&kd_outlet=" + kd_outlet + "&no_faktur=" + no_faktur +
                "&kode_pembayaran=" + kode_pembayaran + "&tgl_jatuh_tempo=" + tgl_jatuh_tempo + "&total=" +
                total,
            dataType: "json",
            success: function(response) {
                console.log(response);
                // try{    
                swal("Perhatian!", response.pesan, "error");
                // sukses2(response.return, response.pesan); 
                location.reload();
                window.open(
                    '<?php echo base_url() ?>' + url_controller + 'notaPenjualan/' + no_faktur + '/' +
                    kd_outlet,
                    '_blank' // <- This is what makes it open in a new window.
                );


                // }catch(e) {  
                //     alert('Exception while request..');
                // }  
            }
        });
    }


}

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


function get_barang_by_barcode(keycode = null) {

    var barcode = "";
    // console.log(keycode);  
    if (keycode == 13) {
        barcode = $('#barcode').val();
    } else {
        barcode = $('#barcode_nama').val();
    }

    if (barcode != "") {
        var url_controller = $('#url').val();
        var url = "<?php echo base_url() ?>" + url_controller + "get_barang_by_barcode/" + barcode;
        console.log(url);
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType: "html",
            success: function(response) {
                //check if table transaksi is empty or not    
                add_row(response, barcode);
                $('#barcode').val('');
            }
        });
    }
}

function cek_next_exp(barcode, stok_awal) {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "get_barang_by_barcode/" + barcode;
    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "html",
        success: function(response) {
            //check if table transaksi is empty or not   
            console.log("balikan = " + response);
            add_row(response, barcode);
            if (response != "1") {
                alert('Stok hanya Tersisa ' + parseInt(stok_awal));
            }
        }
    });
}


function get_kandungan(barcode) {
    var url_controller = $('#url').val();
    var url = "<?php echo base_url() ?>" + url_controller + "get_kandungan/";
    console.log(url);
    $.ajax({
        type: "POST",
        url: url,
        data: {
            barcode: barcode,
        },
        dataType: "json",
        success: function(response) {
            console.log(response);
            try {
                $('#kandungan').val(response.kandungan);
                $('#nama_obat').val(response.nama);
            } catch (e) {
                alert('Exception while request..');
            }
        }
    });
}

function input_stok() {
    //chhekc apakah form invalid 
    var id_outlet = $('#id_outlet').val();
    var no_faktur = $('#no_faktur').val();

    if ($('#kode_pembayaran').val() == 100) {
        if (id_outlet == "" || no_faktur == "") {
            alert("Silahkan isi form dengan lengkap");
        } else {
            $("#myForm1 select").attr("disabled", "disabled");
            $("#myForm1 .myForm1").attr("readonly", true);

            $("#barcode").attr("readonly", false);
            $("#kandungan").attr("readonly", true);
            $(".simpan_penjualan").attr("disabled", false);

            $(".form-group .input_nama_barang").removeAttr("disabled");
            document.getElementById("barcode").focus();
        }
    } else {
        var tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
        if (id_outlet == "" || no_faktur == "" || tgl_jatuh_tempo == "") {
            alert("Silahkan isi form dengan lengkap");
        } else {
            $(".simpan_penjualan").attr("disabled", false);

            $("#myForm1 select").attr("disabled", "disabled");
            $("#myForm1 .myForm1").attr("readonly", true);

            $("#barcode").attr("readonly", false);
            $("#kandungan").attr("readonly", true);
            $(".form-group .input_nama_barang").removeAttr("disabled");
            document.getElementById("barcode").focus();
        }
    }

}
</script>