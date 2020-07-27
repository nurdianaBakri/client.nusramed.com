var save_method; //for save method string
var table;
 
  $(document).ready(function() {

    var url_controller  = $('#url').val();   

    //datatables
    table = $('#reminders2').DataTable({ 
        "dom": 'Blfrtip', 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
         "initComplete": function() {
                $('.buttons-pdf').html('<span class="glyphicon glyphicon-file" data-toggle="tooltip" title="Export Ke PDF (hanya yang tampil)"/> PDF');
                $('.buttons-print').html('<span class="glyphicon glyphicon-print" data-toggle="tooltip" title="Print (hanya yang tampil)"/> Print');
                $('.buttons-excel').html('<span class="glyphicon glyphicon-print" data-toggle="tooltip" title="Export Ke PDF (hanya yang tampil)"/> Excel');

              $("#reminders2").show();
            },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": url_controller+"/ajax_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            { 
                "targets": [ -1 ], //last column
                "orderable": false, //set not orderable
            },
        ],
         "scrollX": true,  
        "lengthMenu": [
          [10, 25, 50, 100, -1],
          [10, 25, 50, 100, "All"]
        ],
        "info": true,
        "buttons": [ 
              {
                    extend: 'print',
                    customize: function ( win ) {
                        $(win.document.body)
                            .css( 'font-size', '10pt' )
                            .prepend(
                                '<img src="http://datatables.net/media/images/logo-fade.png" style="position:absolute; top:0; left:0;" />'
                            );
     
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    },
                    exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6, 7, 8 ]
                    }  
                },
                {
                    extend: 'excelHtml5',
                     exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6, 7, 8 ]
                    } 
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 2, 3, 4, 5, 6, 7, 8 ]
                    } 
                }
            ]

    }); 
          table.buttons().container().appendTo('.button-datatable');
});

  function togle_jatuh_tempo(kode) {
    if(kode == 100){
         $("#tgl_jatuh_tempo").attr("readonly", true); 
     }
     else{ 
         $("#tgl_jatuh_tempo").attr("readonly", false); 
     } 
}


   function summary(id_master_detail) {
            var url_controller  = $('#url').val(); 
            var url = url_controller+"summary/"+id_master_detail;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:{},
                dataType: "html",
                success: function( response ) { 
                    try{   
                        console.log(response);
                        // console.log(response);
                        $('.modal-body').html(response);  
                        $('#myModal').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Ringkasan Pembelian'); // Set title to Bootstrap modal title

                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        } 

        function get_detail_kategori(kategori) {
            var url_controller  = $('#url').val(); 
            var url = url_controller+"get_detail_kategori/"+kategori;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:{},
                dataType: "html",
                success: function( response ) { 
                    try{   
                        $('.kategori_obat').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        } 
         
        function detail(id_obat)
        {
            var url_controller  = $('#url').val();   

            save_method = 'update'; 
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
         
            //Ajax Load data from ajax
            $.ajax({
                url : $url_controller+"/detail",
                type: "POST",
                data :{
                    id_obat : id_obat
                },
                dataType: "html",
                success: function(data)
                {  
                    // console.log(response);
                    $('.modal-body').html(data);  
                    $('#myModal').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Detail Pembelian'); // Set title to Bootstrap modal title
         
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
         
        function reload_table()
        {
            table.ajax.reload(null,false); //reload datatable ajax 
            // reload_table2();
        }
         
        function save()
        {
            $('#btnSave').text('Menyimpan...'); //change button text
            $('#btnSave').attr('disabled',true); //set button disable 
            var url;
            var url_controller  = $('#url').val();   

         
            if(save_method == 'add') {
                url = url_controller+"/Obat/save";
            } else {
                url = url_controller+"/Obat/save";
            }
         
            // ajax adding data to database
            $.ajax({
                url : url,
                type: "POST",
                data: $('#myForm').serialize(),
                dataType: "JSON",
                success: function(data)
                { 
                    console.log(data.status);
                    if(data.status) //if success close modal and reload ajax table
                    {
                        $('#myModal').modal('hide');
                        reload_table();
                    }

                    swal({
                      title: data.title_swal,
                      text: data.pesan,
                      icon: data.icon_swal,
                      button: false,
                      timer: 2000,
                    });
         
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled',false); //set button enable  
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error adding / update data');
                    $('#btnSave').text('save'); //change button text
                    $('#btnSave').attr('disabled',false); //set button enable  
                }
            });
        }
 
         
        function hapus(id_obat)
        {
            var url_controller  = $('#url').val();  

            if(confirm('Apakah anda yakin ingin merubah data?'))
            {
                // ajax delete data to database
                $.ajax({
                    url : url_controller+"obat/hapus",
                    type: "POST",
                    data: {
                        id_obat:id_obat
                    },
                    dataType: "JSON",
                    success: function(data)
                    {  
                        swal({
                          title: data.title_swal,
                          text: data.pesan,
                          icon: data.icon_swal,
                          button: false,
                          timer: 2000,
                        });
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error deleting data');
                    }
                }); 
            }
        }  
         
     
        function sukses3(kode, pesan) {
            swal({
              title: "Success !",
              text: pesan,
              icon: "success",
              button: false,
              timer: 2000,
            });
        }

        

         function import_form() { 
            var url_controller  = $('#url').val();
            var data = $('#myForm').serialize();
            var url = url_controller+"import_form";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:data,
                dataType: "html",
                success: function( response ) { 
                    try{   
                         // console.log(response);
                        $('.modal-body').html(response);   
                        $('#myModal').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Import Obat'); // Set title to Bootstrap modal title
                         
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        } 

        function exportToExel() { 

            $('#btnExport').text('Mengeksport...'); //change button text
            // $('#btnExport').attr('disabled',true); //set button disable 

            var url_controller  = $('#url').val();
            var data = $('#myForm').serialize();
            var url = url_controller+"exportToExel";
            // console.log(url); 
            $.ajax( {
                type: "POST",
                url: url,
                data:data,
                dataType: "json",
                success: function( response ) { 
                    try{     
                        // console.log(response); 
                        /*$("#dvjson").excelexportjs({
                            containerid: "dvjson"
                               , datatype: 'json'
                               , dataset: response
                               , columns: getColumns(response)     
                        });*/  

                         var tableData = [
                            {
                                "sheetName": "Sheet1", 
                                "data": response
                            }
                        ];
                        var options = {
                            fileName: "Export data obat PT. Nusara Raya Medika"
                        };
                        Jhxlsx.export(tableData, options);

                        /*$('#btnExport').text('Export ke Excel'); //change button text
                        $('#btnExport').attr('disabled',false); //set button enable  */

                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  

                   
        }   


        function do_import() { 
            var url_controller  = $('#url').val();
            var data = $('#myForm').serialize();
            var url =url_controller+"do_import";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:data,
                dataType: "Json",
                success: function( response ) { 
                    console.log(response);
                    try{   
                        sukses3(response.status, response.pesan);

                        //reload this  page
                        // location.reload();
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        } 

        function form_generate_barcode_obat() { 
            var url_controller  = $('#url').val(); 
            var url = +url_controller+"form_gentBarObat";
            console.log(url);  
            window.open(url, '_blank');
        } 

        
        function form_search() {   
            $('.box-title').text('Cari Obat');

            var url_controller  = $('#url').val(); 
            var url = +url_controller+"get_form";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {  },
                dataType: "html",
                success: function( response ) { 
                    try{    
                        // console.log(response);
                        $('.modal-body').html(response); 

                        save_method = 'add';
                        $('#myForm')[0].reset(); // reset form on modals
                        $('.form-group').removeClass('has-error'); // clear error class
                        $('.help-block').empty(); // clear error string
                        $('#myModal').modal('show'); // show bootstrap modal
                        $('.modal-title').text('Tambah Obat'); // Set Title to Bootstrap modal title 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }      