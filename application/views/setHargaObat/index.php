
<!-- Main content -->
<section class="content">

<!-- Default box -->
<div class="row">
    <div class="col-sm-3">
        <div class="alert alert-success" role="alert">
            YAY!, fitur ini berfungsi untuk menentukan harga obat. Anda juga bisa merubah harga obat yang sudah di tentukan sebelumnya.
        </div>
    </div>
    <div class="col-sm-9">
        <div class="alert alert-warning" role="alert">
            <b> <h3>Penting !</h3> </b> Data yang muncul di bawah ini adalah 100 data yang terbaru, Jika obat tidak muncul di tabel ini, silahkan klik tombol "<b>cari obat</b>" untuk mencarinya
        </div>
    </div>
</div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Set Harga Obat</h3>
             <button class="btn btn-success pull-right waves-effect m-r-20" onclick="setHarga()">Update harga Obat</button> 
               <button class="btn btn-success pull-right waves-effect m-r-20 button_tambah" onclick="form_search_modal()"> Cari Obat
            </button>  
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">  
            <div class="row">
                <div class="col-sm-12">   

                    <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >

                    <form id="myForm2">
                         
                    </form>  
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

 <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Cari Obat</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class="col-sm-12">
                    Nama Obat
                </div>
                <div class="col-sm-12">
                    <input type="text" class="form-control" id="keyword_nama_obat" placeholder="Masukkan nama obat yang anda cari" name="nama">
                </div>
            </div> 

              <img class="loading" src="<?php echo base_url()."assets/data_image/loading.gif" ?>"  height="70" width="200" >

            <div class="container_table_search">
                
            </div> 
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Laporan hasil set harga obat</h4>
        </div>
        <div class="modal-body myModal2"> 
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
        </div>
        </div>
    </div>
    </div>
 
<script>   

        $(document).on({
            ajaxStart: function() { 
                $(".loading").show();    
            },
             ajaxStop: function() { 
                $(".loading").hide();    
             }    
        }); 

      $(document).ready(function(){ 
            reload_data();    
            $('.loading').hide(); 

        });  

        function reload_data() { 
 
            var url_controller  = $('#url').val();
            // var data = $('#myForm').serialize();
            var url = "<?php echo base_url() ?>"+url_controller+"reload_data";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:{},
                dataType: "html",
                success: function( response ) {  
                    try{      
                        $('#myForm2').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  

            $('.loading').hide(); 

        }     

        function form_search_modal(){
            $('#myModal').modal({
                show: 'true'
            }); 
        }

        $('#keyword_nama_obat').keypress(function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){   

                event.preventDefault(); 
                var url_controller  = $('#url').val();
                var keyword_nama_obat = $('#keyword_nama_obat').val();
                var url = "<?php echo base_url() ?>"+url_controller+"cari_nama_obat/"+keyword_nama_obat;
                console.log(url);
                $.ajax( {
                    type: "POST",
                    url: url,
                    data:{
                        keyword_nama_obat:keyword_nama_obat
                    },
                    dataType: "html",
                    success: function( response ) {  
                        try{     
                            $('.container_table_search').html(response);
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );   
            }
        });  

    function setHarga() {   
         if ($("#myForm2").valid()==false)
         {
            return false;
         }
         else
         {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 

                var url_controller  = $('#url').val(); 
                    var url = "<?php echo base_url() ?>"+url_controller+"setHarga"; 
                    var data = $('#myForm2').serialize();
                    console.log(url);

                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "html",
                        success: function( response ) { 
                            console.log(response);
                        try{  

                            $('#myModal2').modal({
                                show: 'true'
                            }); 

                            $('.myModal2').html(response);

                            //reload page 
                            // sukses2(response.return, response.pesan);  
                            reload_data();  
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );  
            }
        }        
     }
  
</script>