$(document).ready(function() {


    $('select').select2();
    $(".form-group .input_nama_barang").attr("disabled", "disabled");

    //disable the submit button  
    $('.loading').hide();

    var i = 0;
    function change() {
      var doc = document.getElementById("background");
      var color = ["#f5b342", "#f57842", "#f54242", "#42f551"];
      doc.style.backgroundColor = color[i];
      i = (i + 1) % color.length;
    }
    setInterval(change, 1000);
}); 

function convertRupiah(angka, prefix) {

    if (angka != "" && typeof angka != 'undefined') {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split  = number_string.split(","),
        sisa   = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
     
        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
     
        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? prefix + rupiah : "";
    }
      
}
 
function isNumberKey(evt) {
    key = evt.which || evt.keyCode;
    if (    key != 188 // Comma
         && key != 8 // Backspace
         && key != 17 && key != 86 & key != 67 // Ctrl c, ctrl v
         && (key < 48 || key > 57) // Non digit
        ) 
    {
        evt.preventDefault();
        return;
    }
}


function reload_cart() {
    var url_controller = $('#url').val();
    var url = url_controller + "load_cart";
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

function load_data()
  {
     var url = $('#url').val() + "get_trx_tmp";
        var id_master_detail = $('#id_master_detail').val();
        console.log(url); 
        $.ajax({
            url: url,
            method: "POST",
             dataType:"JSON",

            data: {
                id_master_detail: id_master_detail
            },
            success: function(data) {
                // console.log(data);

                var html="";
                /*var html = '<tr>';
                html+= '<td class="text-center" contenteditable id="barcode" style="width:10%;"> </td>'; 
                html+= '<td class="text-center" id="satuan"></td>';
                html+= '<td class="text-center" contenteditable id="no_batch"></td>';
                html+= '<td class="text-center" contenteditable id="no_reg"></td>';
                html+= '<td class="text-center" contenteditable id="tgl_exp"></td>';
                html+= '<td class="text-center" contenteditable id="harga" style="width:10%;"> </td>';
                html+= '<td class="text-center" contenteditable id="diskon" style="width:7%;">  </td>';
                html+= '<td class="text-center" contenteditable id="qty" style="width:7%;"> </td>';
                html+= '<td class="text-center" contenteditable id="qty_baik" style="width:7%;">  </td>';
                html+= '<td class="text-center" contenteditable id="qty_tidak_baik" style="width:7%;">  </td>';
                html+= '<td class="text-center" contenteditable id="total">  </td>';
                html += '<td><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span></button></td></tr>';*/
                for(var count = 0; count < data.length; count++)
                {
                  html += '<tr>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="label">'+data[count].label+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="satuan">'+data[count].nm_satuan+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="no_batch" contenteditable>'+data[count].no_batch+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="no_reg" contenteditable>'+data[count].no_reg+'</td>';
                  // html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="tgl_exp" contenteditable>'+data[count].tgl_exp+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="tgl_exp" contenteditable>'+data[count].tgl_exp+'</td>  ';
                  // html += '<div class="holder"><input name="date" class="datepicker-input" type="hidden" /><div class="date" contentEditable="true"></div></div></td>';
                  html += '<td class="table_data" id="rupiah1" data-row_id="'+data[count].id_detail_obat+'" data-column_name="harga_beli" contenteditable>'+data[count].harga_beli+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="diskon_beli" contenteditable>'+data[count].diskon_beli+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="stok_awal" contenteditable>'+data[count].stok_awal+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="qty_baik" contenteditable>'+data[count].stok_awal+'</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="qty_tidak_baik" contenteditable>0</td>';
                  html += '<td class="table_data" data-row_id="'+data[count].id_detail_obat+'" data-column_name="total">'+(data[count].harga_beli*data[count].stok_awal)+'</td>';
                  html += '<td><button type="button" name="delete_btn" id="'+data[count].id_detail_obat+'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
                }
                $('tbody').html(html);
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
                get_detail_trx();  


            }


        }); 
  }

  function load_data2()
  {
    $.ajax({
      url:$('#url').val()+"/load_data",
      dataType:"JSON",
      success:function(data){
        var html = '<tr>';
        html += '<td id="first_name" contenteditable placeholder="Enter First Name"></td>';
        html += '<td id="last_name" contenteditable placeholder="Enter Last Name"></td>';
        html += '<td id="age" contenteditable></td>';
        html += '<td><button type="button" name="btn_add" id="btn_add" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-plus"></span></button></td></tr>';
        for(var count = 0; count < data.length; count++)
        {
          html += '<tr>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="first_name" contenteditable>'+data[count].first_name+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="last_name" contenteditable>'+data[count].last_name+'</td>';
          html += '<td class="table_data" data-row_id="'+data[count].id+'" data-column_name="age" contenteditable>'+data[count].age+'</td>';
          html += '<td><button type="button" name="delete_btn" id="'+data[count].id+'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
        }
        $('tbody').html(html);
      }
    });
  }


function get_trx_tmp() {
    var url = $('#url').val() + "get_trx_tmp";
    var id_master_detail = $('#id_master_detail').val();
    console.log(url); 
    $.ajax({
        url: url,
        method: "POST",
        data: {
            id_master_detail: id_master_detail
        },
        success: function(data) {
            // console.log(data);
            /*add_row2(data);
            $('#barcode').val('');*/

             // var url = $('#url').val() + "get_detail_trx/"+id_master_detail;


            get_detail_trx();  
        }
    });
}

function get_detail_trx() {

    var id_master_detail = $('#id_master_detail').val();
    var url = $('#url').val() + "get_detail_trx/"+id_master_detail;
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        dataType: "json",
        data: { },
        success: function(data) { 
            // console.log(data);
            $('#tgl_input').text(data.tgl_input);

            $('#tgl_jatuh_tempo').text(data.tgl_jatuh_tempo); 
            if (data.tgl_jatuh_tempo=="0000-00-00 00:00:00")
            {
                $('.tgl_jatuh_tempo').hide(); 
            }
            else
            {  
                $('.tgl_jatuh_tempo').show();
            }
            $('#metode_pembayaran').text(data.metode_pembayaran);
            $('#id_penginput').text(data.id_user);
        }
    });
    $('.loading').hide(); 
}

function get_list_faktur() {

    $('.loading').show(); 
    var url = $('#url').val() + "get_list_faktur/";
    console.log(url);
    $.ajax({
        url: url,
        method: "POST",
        dataType: "html",
        data: { },
        success: function(data) { 
            // console.log(data);
            $('#id_master_detail').html(data);
 
            $('#myForm1 select').prop('disabled', false);
            $("#cek_transaksi").attr("disabled", false);
            $('#verifikasi').attr('disabled',true);
        }
    });
    $('.loading').hide(); 
}

function cek_transaksi() {
    //chhekc apakah form invalid  
    var no_faktur = $('#no_faktur').val();
    $('#verifikasi').removeAttr('disabled');

    if (no_faktur == "") {
        alert("Silahkan isi form dengan lengkap");
    } else {
        // $("#myForm1 select").attr("disabled", "disabled");
        // $("#myForm1 .myForm1").attr("readonly", true); 

        // $("#cek_transaksi").attr("disabled", false);

        // $(".form-group .input_nama_barang").removeAttr("disabled");
        // document.getElementById("barcode").focus(); 
        // get_trx_tmp();
        load_data();
    }
}

$(document).on('click', '.btn_delete', function(){
    var id = $(this).attr('id');
    if(confirm("Apakah Anda yakin ingin menghapus data ?"))
    {
      $.ajax({
        url:$('#url').val()+"/delete",
        method:"POST",
        data:{id:id},
        success:function(data){
          load_data();
        }
      })
    }
  });

  $(document).on('click', '#btn_add', function(){
    var first_name = $('#first_name').text();
    var last_name = $('#last_name').text();
    var age = $('#age').text();
    if(first_name == '')
    {
      alert('Enter First Name');
      return false;
    }
    if(last_name == '')
    {
      alert('Enter Last Name');
      return false;
    }
    $.ajax({
      url:$('#url').val()+"/insert",
      method:"POST",
      data:{first_name:first_name, last_name:last_name, age:age},
      success:function(data){
        load_data();
      }
    })
  });

  $(document).on('blur', '.table_data', function(){
    var id = $(this).data('row_id');
    var table_column = $(this).data('column_name');
    var value = $(this).text();
    $.ajax({
      url:$('#url').val()+"/update",
      method:"POST",
      data:{
        id:id, 
        table_column:table_column, 
        value:value
      },
      success:function(data)
      {
        /*if (data==1)
        {
            Swal.fire({
              icon: 'info',
              title: 'Sukses',
              text: 'Proses mengubah data pembelian berhasil',
              footer: '<a href>Why do I have this issue?</a>'
            })
        }
        else
        {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Proses mengubah data pembelian gagal, ssilahkan coba kembali lagi',
              footer: '<a href>Why do I have this issue?</a>'
            })
        } */
        console.log(data);
        // load_data();
      }
    })
  });

$('#verifikasi').click(function() {
    var r = confirm("Yakin Ingin verifikasi transaksi ini ? ");
    if (r == true) {
        $('.loading').show();

        var url_controller = $('#url').val();
        var url = url_controller + "verifikasiTransaksi";
        // alert();
        $.ajax({
                type: "POST",
                url: url,
                data: {
                    id_master_detail : $('#id_master_detail').val()
                }
            }).done(function(response) {

                console.log(response);
                var response = JSON.parse(response);
                var status = "";
                console.log(response.pesan);
                if (response.return == 0) {
                    status = "error";
                }
                if (response.return == 1) {
                    status = "success";
                }

                if (response.pesan=="Proses Verifikasi pembelian berhasil ")
                { 
                    swal("Perhatian!", response.pesan, status); 
                }
                else{   
                    swal("Perhatian!", response.pesan, status); 
                }   

                get_list_faktur();
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