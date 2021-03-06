
    <section class="content">
        <div class="container-fluid">
            <!-- CPU Usage -->
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="header">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-6 title">
                                    <h2><?PHP echo $title; ?></h2>
                                </div> 
 
                                <button class="btn btn-success pull-right waves-effect m-r-20 button_tambah" onclick="get_form()">
                                Tambah
                                </button> 

                                 <!-- <button class="btn btn-success pull-right waves-effect m-r-20 button_tambah_item" onclick="get_form_tambah_item()">
                                Tambah Item Belanja
                                </button>  -->
                            </div>
                        </div>
                        <div class="body">

                            <?php if ($this->session->flashdata('pesan')!="") {
                               echo $this->session->flashdata('pesan');
                            } ?> 

                            <div class="pesan"></div> 

                            <input type="hidden" name="url" value="<?php echo $url ?>" id="url"> 

                           <div class="panel panel-success">
                                <div class="body form_container">
                                    <div class="table-responsive">

                                    </div>   
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> 

    <script type="text/javascript">   

        $(document).ready(function(){ 
            reload_data();   
            // $('.button_tambah_item').hide();
        }); 

         function reload_data() { 
            var url_controller  = $('#url').val();
            var data = $('#myForm').serialize();
            var url = "<?php echo base_url() ?>"+url_controller+"reload_data";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:data,
                dataType: "html",
                success: function( response ) { 
                    try{   
                        $('.table-responsive').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }     

         function get_form() { 

            $('.button_tambah').hide();

            var url_controller  = $('#url').val(); 
            var url = "<?php echo base_url() ?>transaksi/Penjualan_detail/index/0";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {  },
                dataType: "html",
                success: function( response ) { 
                    try{   
                        $('.form_container').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }   
        

       function detail(id_trx) { 

            $('.button_tambah').hide();  
            var url_controller  = $('#url').val(); 
            var url = "<?php echo base_url() ?>"+"transaksi/Penjualan_detail/index/"+id_trx;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "html",
                success: function( response ) { 
                    try{   
                       $('.title').html("<h2>Detail Transaksi "+id_trx+"</h2>"); 
                       // $('.button_tambah_item').show(); 
                        $('.panel-success').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }     
    </script>
 
