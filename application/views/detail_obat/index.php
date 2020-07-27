
    <!-- Main content -->
    <section class="content">
    
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Detail Obat</h3>
            <button  class="btn pull-right btn-success waves-effect m-r-20 button_tambah" onclick="get_form(<?php echo $id_obat ?>)">
                                    Tambah
            </button> 
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
            <input type="hidden" name="id_obat" value="<?php echo $id_obat ?>" id="id_obat">  

          <?php
            if ($this->session->flashdata('pesan')!="")
            {
                echo $this->session->flashdata('pesan');
            } 
            ?> 
            <div class="pesan"></div>
            
                <div class="body">
                    <div class="table-responsive">

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

    <script type="text/javascript">   

        $(document).ready(function(){ 
            reload_data();     
        }); 

         function reload_data() { 
            var url_controller  = $('#url').val(); 
            var id_obat  = $('#id_obat').val(); 
            var data = $('#myForm').serialize();
            var url = "<?php echo base_url() ?>"+url_controller+"reload_data/"+id_obat;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:{data},
                dataType: "html",
                success: function( response ) { 
                    console.log(response);
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

            var id_obat = $('#id_obat').val();
            var url = "<?php echo base_url() ?>"+url_controller+"form_tambah/"+id_obat;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {},
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

        function detail(no_batch) { 

            $('.button_tambah').hide();  
            var url_controller  = $('#url').val(); 
            var url = "<?php echo base_url() ?>"+url_controller+"detail/"+no_batch;
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {},
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
 
        function update_and_save()
        {    
          var url_controller  = $('#url').val();   
          var data  = $("#myForm").serialize();
          var url = "<?php echo base_url() ?>"+url_controller+'save';
          console.log(url); 
          console.log(data); 
           $.ajax( {  
                type: "POST",
                url: url,
                data: data,
                success: function( response ) {   
                    console.log(response);
                    try{    
                        var obj = jQuery.parseJSON(response);   
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        } 

         function hapus(primary) { 
            var url_controller  = $('#url').val();  
            var url = "<?php echo base_url() ?>"+url_controller+'/hapus/'+primary; 
            console.log(url);
              $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "html",
                success: function( response ) {   
                    try{ 
                        $('.pesan').html(response);
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }  

        function edit(primary) {  
            $('.modal-footer').hide();
            var url_controller  = $('#url').val();   

            //GET DATA DARI DATABASE 
            var data  = $("#myForm").serialize(); 
            var url ="<?php echo base_url()?>"+url_controller+"get_form"; 
             $.ajax( {
                type: "POST",
                url: url,
                data: {
                    id_obat : $('#id_obat').val(), 
                    no_batch : primary, 
                },
                success: function( response ) { 
                    console.log(response);
                    try{   
                        //masukkan form ke modal 
                        $(".form_container").html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }
    </script>
 
