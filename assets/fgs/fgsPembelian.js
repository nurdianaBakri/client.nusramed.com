
var data_obat; 
var data_suplier; 

  $(document).ready(function() {
 
  $('[data-toggle="tooltip"]').tooltip();    

    document.getElementById("barcode").focus();
    $('.money').simpleMoneyFormat();

    // $('select').select2(); 
    $("#tgl_jatuh_tempo").attr("readonly", true); 

    $('.loading').hide();   
    // reload_cart();  

    get_data_suplier();  
    get_data_obat();    
            
}); 
 
  /*function get_detail_item_by_name(argument) {   
        $("#barcode").autocomplete({ 
            select: function(event, ui) { 
                $("#theHiddenBarcode").val(ui.item.id);
                console.log(ui.item.id);  
                // $('#barcode').val('');
            }
        }); 
  } 

   function select_suplier(argument) {

        // console.log(argument);  
        $("#kd_suplier2").autocomplete({ 
            select: function(event, ui) { 
                $("#theHiddenKdSuplier").val(ui.item.id);
                console.log(ui.item.id);   
            }
        }); 
  } 
*/
   

function togle_jatuh_tempo(kode) {

    if(kode == 100){
         $("#tgl_jatuh_tempo").attr("readonly", true); 
     }
     else{ 
         $("#tgl_jatuh_tempo").attr("readonly", false); 
     } 
}

$("#myForm1").submit(function(e) {
    e.preventDefault();
    input_stok();
});


function reload_cart() {
    var url_controller = $('#url').val();
    var url = url_controller + "load_cart";
    $('#element_table').load(url);

    console.log(url);
    setTimeout(() => {
        total_diskon()
        get_grand_total();
    }, 2000); 
}

function get_grand_total() {
    var url_controller = $('#url').val();
    var url = url_controller + "get_grand_total/";
    console.log(url);

    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "html",
        success: function(response) {
            console.log(response);
            try {
                $('#total').val(response);
            } catch (e) {
                // alert('Exception while request..');
            }
        }
    });
}

function get_data_obat() { 
    var url_controller = $('#url').val();
    var url = url_controller + "get_data_obat/";
    // console.log(url);

    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "json",
        success: function(response) {
            // console.log(response);
            try {
                // data_obat=response; 

                /* $("#barcode").autocomplete({
                  source: response,
                }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                      .append( "<div>" + item.id + " - " + item.value + "</li>" )
                      .appendTo( ul );
                  };  */

                    $( "#barcode" ).autocomplete({
                       minLength: 3,
                       source: response,
                       focus: function( event, ui ) {
                          $( "#barcode" ).val( ui.item.label );
                             return false;
                       },
                       select: function( event, ui ) {
                          $( "#barcode" ).val( ui.item.label );
                          $( "#theHiddenBarcode" ).val( ui.item.value ); 
                          $('#barcode').val('');

                          //get kandungan,  dan reload cart 
                          // get_kandungan(ui.item.value);

                          get_barang_by_barcode();

                          //reload cart
                          return false;
                       }
                    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                       return $( "<li>" )
                       .append( "<a>" + item.label + "</a>" )
                       .appendTo( ul );
                    };


            } catch (e) {
                alert('Exception while request..');
            }
        }
    });
}

function get_data_suplier() {  

    var url_controller = $('#url').val();
    var url = url_controller + "get_data_suplier";
    console.log(url);

    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "json",
        success: function(response) {
            // console.log(response);
            try { 
                 $( "#kd_suplier" ).autocomplete({
                   minLength: 3,
                   source: response,
                   focus: function( event, ui ) {
                      $( "#kd_suplier" ).val( ui.item.label );
                         return false;
                   },
                   select: function( event, ui ) {
                      $( "#kd_suplier" ).val( ui.item.label );
                      $( "#theHiddenKdSuplier" ).val( ui.item.value ); 
                      return false;
                   }
                }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                   return $( "<li>" )
                   .append( "<a>" + item.label + "</a>" )
                   .appendTo( ul );
                };  


               /*  $("#kd_suplier2").autocomplete({
                  source: response,
                }).autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                      .append( "<div>" + item.id + " - " + item.value + "</li>" )
                      .appendTo( ul );
                  };  */

            } catch (e) {
                alert('Exception while request..');
            }
        }
    });
}


$("#myForm1").submit(function(e) {
    e.preventDefault();
    input_stok();
});

function add_row(response, id_obat) {
    console.log(response);
    if (/^[a-zA-Z0-9- ]*$/.test(response) == false) {
        // alert('Your search string contains illegal characters.');
        get_kandungan(id_obat);
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

    // var harga = $(".harga")[index_val].value;
    var diskon = $(".diskon")[index_val].value;
    var harga_jual = $(".harga")[index_val].value;
    var no_batch = $(".no_batch")[index_val].value;
    var no_reg = $(".no_reg")[index_val].value;
    var tgl_exp = $(".tgl_exp")[index_val].value;
    var lokasi = $(".lokasi")[index_val].value;
    // var nilai_ppn = $(".nilai_ppn")[index_val].value;
    var ppn = parseFloat($("#ppn").val()).toFixed(2);
    var qty = $(".stok_awal")[index_val].value;    
    var harga_setelah_diskon = $('.harga_setelah_diskon')[index_val];
        harga_setelah_diskon.value =(harga_jual * (diskon/ 100)*qty);        
        var total = (qty * harga_jual) - harga_setelah_diskon.value;
        // console.log("Diskon = "+harga_setelah_diskon.value);
        // console.log("Total harga item = "+total);
        $(x).find(".subtotal").text(formatRupiah(total.toString()));
        var ppn = parseFloat($(".ppn").val());
        // alert(ppn);
        var ppn_value = parseFloat((parseFloat(ppn) / 100) * total).toFixed(2);
        // console.log("ppn = "+ppn+" nilai = "+ppn_value)
        $(x).find(".nilai_ppn").text(formatRupiah(ppn_value.toString()));
        var total_produk = 0;       
        $('#myForm2').find("tr").not(':first').not(':last').each(function() {
            ppn_count = parseFloat($(this).find('.nilai_ppn').text().replace(',', ''));
            total_produk += parseFloat($(this).find('.subtotal').text().replace(',', '')) +  ppn_count;
              console.log("total product ="+total_produk);            
        });
        $("#total").val(formatRupiah((total_produk).toString()));
    var rowid = $(x).attr('data-id');
    var data = {
        rowid: rowid,
        qty: parseFloat(qty).toFixed(2),
        diskon: diskon,
        no_batch: no_batch,
        no_reg : no_reg,
        tgl_exp : tgl_exp,
        lokasi : lokasi,
        price : harga_jual,
        ppn: parseFloat(ppn).toFixed(2)
    };
    update_cart_onchange(data);
    total_diskon();
}

function total_diskon() {
    var totalPoints = 0;
    $('.harga_setelah_diskon').each(function() {
        totalPoints += parseFloat($(this).val())
        console.log("total = "+totalPoints);
    });
    $('.jumlah_diskon').val(totalPoints);
}

function update_cart_onchange(update) {
    var url_controller = $('#url').val();
    var url = url_controller + "update_cart_onchange";
    console.log(update);
    console.log("update cart onchange");
    $.ajax({
        type: "POST",
        url: url,
        data: update
    }).done(function(html) {

        //check apakah form valid  
        console.log(html);
    })
    .fail(function(jqXHR, textStatus) {
        //    console.log(data);
        // console.log("reqest error")
    });
}

function update_cart() {
    var url_controller = $('#url').val();
    var url = url_controller + "update_cart";
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
  

    function simpan_pembelian() {  
  
         if ($('#no_faktur').val()=="")
         {
            sukses2(0, "Nomor Faktur tidak boleh kosong");  
         } 
         else
         {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 


                $('.loading').show();
                 var url_controller = $('#url').val();   
                    var url = url_controller+"simpan_pembelian/";
                    // console.log(url);

                    var kd_suplier =$('#theHiddenKdSuplier').val();
                    var no_faktur =$('#no_faktur').val();
                    var kode_pembayaran =$('#kode_pembayaran').val();
                    var tgl_jatuh_tempo =$('#tgl_jatuh_tempo').val();
                    var tgl_faktur =$('#tgl_faktur').val();

                    var data = $('#myForm2').serialize()+"&kd_suplier="+kd_suplier+"&no_faktur="+no_faktur+"&kode_pembayaran="+kode_pembayaran+"&tgl_jatuh_tempo="+tgl_jatuh_tempo+"&tgl_faktur="+tgl_faktur;
                    // console.log(data);

                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "json",
                        success: function( response ) { 
                            console.log(response);
                        try{     
                            //reload page
                            if (response.return=="1" || response.return==1)
                            {
                                printTrxPembelian(no_faktur); 
                            }   

                            if (response.pesan_sweet_alert=="barang tidak boleh boleh kosong, silahkan masukkan item barang kedalam cart ")
                            { 
                                sukses2(response.return, response.pesan_sweet_alert);   
                            }
                            else{   
                                sukses2(response.return, response.pesan_sweet_alert);   

                                setInterval(function(){ 
                                   $('.modal-body').html(response.pesan);
                                   $('#myModal').modal({
                                        show: 'true',
                                    });  
                                }, 6000);  
                            }  
 
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                    } );  
                $('.loading').hide(); 
            } 
        }        
     }

     //Hapus Item Cart
    $(document).on('click', '.close', function() {
        location.reload(); 
         // $('#myModal').modal('hide'); 
         // reload_cart();
         // $('#myModal').modal('hide'); 
    });

  function printTrxPembelian(no_faktur) {
        var url_controller  = $('#url').val();   
        var url = url_controller+"printTrxPembelian/"+no_faktur;
        // console.log(url); 
            $.ajax( {
                type: "POST",
                url: url,
                data:{},
                dataType: "html",
                success: function( response ) { 
                // console.log(response);
                try{  
                    var w = window.open('about:blank');
                    w.document.open();
                    w.document.write(response);
                    w.document.close();
                }catch(e) {  
                    alert('Exception while request..');
                }  
            }
        } ); 
     }

//Hapus Item Cart
$(document).on('click', '.romove_cart', function() {
    var url_controller = $('#url').val();
    var url = url_controller + "hapus_cart";

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
    var url = url_controller + "update_cart";
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

    var id_obat = $('#theHiddenBarcode').val();
    var url_controller = $('#url').val();

    //get  
    var barcode = $("#barcode").val();   
    var url="";
    if (barcode.length =='') { 
         var id_obat = $('#theHiddenBarcode').val();
         url = url_controller + "get_barang_by_barcode/" + id_obat 
    }
    else
    {
        var barcode = $('#barcode').val(); 
        url = url_controller + "get_barang_by_barcode2/" + barcode
    }  
    // console.log(url); 

    if (id_obat != "") { 
        // console.log(url);
        $.ajax({
            type: "POST",
            url: url,
            data: {},
            dataType: "html",
            success: function(response) {
                // console.log(response);
                //check if table transaksi is empty or not    
                add_row(response, id_obat);
                $('#barcode').val('');
            }
        });
    }
}
 


function get_kandungan(id_obat) {
    var url_controller = $('#url').val();
    var url = url_controller + "get_kandungan/";
    // console.log("fugsi get kandungan obat : "+url);
    $.ajax({
        type: "POST",
        url: url,
        data: {
            id_obat: id_obat,
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
            $(".simpan_pembelian").attr("disabled", false);

            $(".form-group .input_nama_barang").removeAttr("disabled");
            document.getElementById("barcode").focus();
        }
    } else {
        var tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();
        if (id_outlet == "" || no_faktur == "" || tgl_jatuh_tempo == "") {
            alert("Silahkan isi form dengan lengkap");
        } else {
            $(".simpan_pembelian").attr("disabled", false);

            $("#myForm1 select").attr("disabled", "disabled");
            $("#myForm1 .myForm1").attr("readonly", true);

            $("#barcode").attr("readonly", false);
            $("#kandungan").attr("readonly", true);
            $(".form-group .input_nama_barang").removeAttr("disabled");
            document.getElementById("barcode").focus();
        }
    }

}