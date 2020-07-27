<script src="<?php echo base_url()."assets/" ?>plugins/jquery-datatable/jquery.dataTables.js"></script>
<script src="<?php echo base_url()."assets/" ?>plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script> 
<script type="text/javascript">
    $(function () {
    $('.js-basic-example').DataTable({
        responsive: true, 
        autoWidth : false, 
    });   
});
</script>     


<div class="panel">
    <div class="body row"> 
         <div class="col-md-2">
            <label for="kandungan"><h3>KD Barang</h3></label>
        </div>
        <div class="col-md-10">
            <input type="text" name="no_batch" class="form-control input-lg" onkeyup=""> 
        </div> 
    </div>
</div> 

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th></th>  
                <th></th>   
                <th>Nama Obat</th>
                <th>Qty</th>
                <th>Harga</th> 
                <th>Total</th>  
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($data as $key => $value) {
            ?>
                <tr> 
                    <td>
                         <button class="btn btn-success waves-effect m-r-20 " onclick="detail(<?php echo "'".$value['id']."'"; ?>)">Detail</button> 
                    </td> 
                    <td>
                       <button class="btn btn-danger waves-effect m-r-20" onclick="hapus(<?php echo $value['id'] ?>)">Hapus</button>
                    </td>

                    <td><?=$value['id'];?></td>
                    <td></td>
                    <td><?=$value['qty'];?> 
                    <td><?=$value['harga'];?></td> 
                    <td><?=$value['jumlah_bayar'];?></td> 
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>  
</div>


    

    <script type="text/javascript">   

        $(document).ready(function(){ 

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
            var url = "<?php echo base_url() ?>"+url_controller+"get_form";
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
                        $('.form_container').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }     
    </script>
 





<script type="text/javascript">
     function hapus(primary) { 
            var url_controller  = $('#url').val();    
            var url = "<?php echo base_url() ?>"+url_controller+'hapus/'+primary; 

            console.log(url);
              $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "html",
                success: function( response ) {   
                    console.log(response);
                    try{      
                         //reload tabel 
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }   
         
</script>

 