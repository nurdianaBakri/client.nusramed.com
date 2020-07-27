
 var url_controller = $('#url').val();  
var data_obat; 
var data_suplier;  


$(document).ready(function() {
 

    document.getElementById("barcode").focus();
    $('.money').simpleMoneyFormat();

    $('select').select2();
    $('.tgl_jatuh_tempo').hide();
    // $(".form-group .input_nama_barang").attr("disabled","disabled"); 
   
    // reload_cart();
    setTimeout(() => {
        total_diskon()
    }, 10000);

    get_data_obat();    
    load_data(); 
}); 
   
  
  
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
                          // console.log( ui.item.kandungan);
                          $( "#kandungan" ).val( ui.item.kandungan ); 
                          $( "#label" ).val( ui.item.label ); 
                          $('#barcode').val(''); 
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
    var url = url_controller + "load_cart";
    $('#element_table').load(url);
    get_grand_total(); 
}

function get_grand_total() {
   
    var url = url_controller + "get_grand_total/";
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


 $(document).on('blur', '.table_data', function(){
    var id = $(this).data('row_id');
    var table_column = $(this).data('column_name');
    var value = $(this).text();
 
    /*if (table_column=="qty")
    {
        console.log(Number.isInteger(value));
    }*/
    $.ajax({
      url: url_controller+"update_cart_onchange",
      method:"POST",
      data:{
        id:id, 
        table_column:table_column, 
        value:value
    },
      success:function(data)
      {
        console.log(data);
        load_data();
      }
    })
  });

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
   
    var url = url_controller + "update_cart_onchange";
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

$('#qty').keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13') {
        // console.log("fungsi get_barang_by_barcode"); 
        $('#jenis_aksi').val("update");
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
        console.log("kalkulasi " +parseFloat(bayar)+" - "+parseFloat(total))
        if (kembali < 0) {
            alert("jumlah bayar kurang !")
        } else {
            $('#kembali').val(formatRupiah(kembali.toString()));
            $('.simpan_penjualan').removeAttr('disabled');
        }
    }
});


function simpan_penjualan() {
   
    var url = url_controller + "simpan_penjualan/";
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
                     url_controller + 'notaPenjualan/' + no_faktur + '/' +
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


  $(document).on('click', '.btn_delete', function(){
    var id = $(this).attr('id');
    if(confirm("apakah Anda yakin ingin menghapus data ini ?"))
    {
      $.ajax({
        url:url_controller+"/delete_cart",
        method:"POST",
        data:{id:id},
        success:function(data){
            console.log(data);
          load_data();
        }
      })
    }
  });

   $(document).on('click', '.btn_update_qty', function(){

    var id = $(this).attr('id'); 
    console.log(id);
      /*$.ajax({
        url:url_controller+"/delete_cart",
        method:"POST",
        data:{id:id},
        success:function(data){
            console.log(data);
          load_data();
        }
      }) */
  });

  

/*function load_data()
  {
     var url = $('#url').val() + "get_trx_tmp";
        var barcode = $('#barcode').val();
        console.log(url); 
        $.ajax({
            url: url,
            method: "POST",
             dataType:"JSON", 
            data: { },
            success: function(data) { 
                var html="";

                if (data.cart_.length<=0)
                {
                     //disable the submit button
                    $(".simpan_penjualan").attr("disabled", true);
                }
                else
                {
                    //disable the submit button
                    $(".simpan_penjualan").attr("disabled", false);
                }
               
                for(var count = 0; count < data.cart_.length; count++)
                { 
                  html += '<tr>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="time" >'+data.cart_[count].no+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="no_faktur" >'+data.cart_[count].name+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="satuan">'+data.cart_[count].satuan+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="no_batch">'+data.cart_[count].no_batch+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="no_reg">'+data.cart_[count].no_reg+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="outlet">'+data.cart_[count].tgl_exp+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="label">'+data.cart_[count].harga+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="nilai_ppn">'+data.cart_[count].nilai_ppn+'</td>'; 
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="diskon" contenteditable>'+data.cart_[count].diskon+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="qty" contenteditable>'+data.cart_[count].qty+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="kosong" contenteditable>'+data.cart_[count].kosong+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="subtotal" >'+data.cart_[count].subtotal+'</td>';
                  html += '<td>';
                  html+='<button type="button" name="delete_btn" id="'+data.cart_[count].row_id+'" class="btn btn-xs btn-warning btn_update_qty"><span class="glyphicon glyphicon-pencil"></span></button>';
                  html+='<button type="button" name="delete_btn" id="'+data.cart_[count].row_id+'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button>';
                  html+='</td></tr>';
                }
                $('tbody').html(html); 
                $('#total').val(data.grand_total);
                $('#jumlah_diskon').val(data.grand_diskon);


                // Binds the hidden input to be used as datepicker.
                $('.datepicker-input').datepicker({
                    dateFormat: 'yy-mm-dd',
                    onClose: function(dateText, inst) {
                        // When the date is selected, copy the value in the content editable div.
                        // If you don't need to do anything on the blur or focus event of the content editable div, you don't need to trigger them as I do in the line below.
                        $(this).parent().find('.date').focus().html(dateText).blur();
                    }
                });
                // Shows the datepicker when clicking on the content editable div
                $('.date').click(function() {
                    // Triggering the focus event of the hidden input, the datepicker will come up.
                    $(this).parent().find('.datepicker-input').focus();
                });

                //get detail 
                // get_detail_trx();   

            }  
        }); 
  }*/

  function load_data()
  {
     var url = $('#url').val() + "get_trx_tmp"; 
        console.log(url); 
        $.ajax({
            url: url,
            method: "POST",
             dataType:"json", 
             data: {
                no_faktur : $('#no_faktur').val(),
             },
            success: function(data) { 
                // console.log(data); 
                var html="";

                if (data.cart_.length<=0)
                {
                     //disable the submit button
                    $(".simpan_penjualan").attr("disabled", true);
                }
                else
                {
                    //disable the submit button
                    $(".simpan_penjualan").attr("disabled", false);
                }
               
                for(var count = 0; count < data.cart_.length; count++)
                {  
                    console.log("testing"); 
                  html += '<tr style="background-color: #a7dfe8;">';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="time" >'+data.cart_[count].no+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="no_faktur" >'+data.cart_[count].name+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="satuan" colspan="5">'+data.cart_[count].satuan+'</td>'; ;
                  // html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="label">'+data.cart_[count].harga+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="nilai_ppn">'+data.cart_[count].nilai_ppn+'</td>'; 
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="diskon" contenteditable>'+data.cart_[count].diskon+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="qty" contenteditable>'+data.cart_[count].qty+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="kosong" contenteditable>'+data.cart_[count].kosong+'</td>';
                  html += '<td class="table_data" data-row_id="'+data.cart_[count].row_id+'" data-column_name="subtotal" >'+data.cart_[count].subtotal+'</td>';
                  html += '<td>';
                  html+='<button type="button" name="delete_btn" id="'+data.cart_[count].row_id+'" class="btn btn-xs btn-warning btn_update_qty"><span class="glyphicon glyphicon-pencil"></span></button>';
                  html+='<button type="button" name="delete_btn" id="'+data.cart_[count].row_id+'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button>';
                  html+='</td></tr>';

                  for(var count2 = 0; count2 < data.cart_[count].data_transaksi.length; count2++)
                    { 
                      html += '<tr>';
                      html += '<td></td>';
                      html += '<td></td>';
                      html += '<td></td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="no_batch">'+data.cart_[count].data_transaksi[count2].no_batch+'</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="no_reg">'+data.cart_[count].data_transaksi[count2].no_reg+'</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="outlet">'+data.cart_[count].data_transaksi[count2].tgl_exp+'</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="label">'+data.cart_[count].data_transaksi[count2].harga_jual+'</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="nilai_ppn">'+data.cart_[count].data_transaksi[count2].nilai_ppn+'</td>'; 
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="diskon">-</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="qty">'+data.cart_[count].data_transaksi[count2].qty+'</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="kosong">-</td>';
                      html += '<td class="table_data" data-row_id="'+data.cart_[count].data_transaksi[count2].id_trx+'" data-column_name="subtotal" >'+data.cart_[count].data_transaksi[count2].subtotal+'</td>';
                      html += '<td>-</td></tr>';
                    }

                }
                $('tbody').html(html); 
                $('#total').val(data.grand_total);
                $('#jumlah_diskon').val(data.grand_diskon);  
            }  
        }); 
  }

  function update_qty(row_id) { 
      alert(row_id);
      console.log(row_id);
  } 

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
    var data = $('#myForm1').serialize()+"&"+$('#myForm2').serialize()+"&"+$('#myForm3').serialize();
    // console.log(url);
    // console.log(data);  

    if (id_obat != "") {  
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function(response) {   
                // console.log(response); 
                if (response.status==true)
                {
                     document.getElementById("qty").focus(); 
                } 
                else
                { 
                    alert(response.pesan); 
                }
            }
        });
    }

    load_data();
}

function cek_next_exp(barcode, stok_awal) {
   
    var url = url_controller + "get_barang_by_barcode/" + barcode;
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
/*

function get_kandungan(barcode) {
   
    var url = url_controller + "get_kandungan/";
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
}*/

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