<!-- Main content -->
    <section class="content">
 
        <div class="pesan"></div>
     
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?= $title; ?></h3> 
           <button class="btn btn-success pull-right waves-effect m-r-20 button_tambah" onclick="get_form()"> Tambah
            </button>  
            
        </div>
        <div class="box-body">
           <?php if ($this->session->flashdata('pesan')!="") {
               echo $this->session->flashdata('pesan');
            } ?> 

            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">  
            <div class="form_import_container"> </div>

            <div class="table-responsive"> </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer"> </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>
 
    <script type="text/javascript">    
        $(document).ready(function(){ 
            reload_data();  
            $('.button_tambah_peg').hide();  
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
            $('.box-title').html('Tambah User');

            var url_controller  = $('#url').val(); 
            var url = "<?php echo base_url() ?>"+url_controller+"get_form";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data: {  },
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
    </script>
 
