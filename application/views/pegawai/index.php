  <script src="<?php echo base_url()."assets/" ?>plugins/jquery-datatable/jquery.dataTables.js"></script>
  <script src="<?php echo base_url()."assets/" ?>plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>

 
                                <div class="body form_container">
                                    <div class="table-responsive"> 

                                        <script type="text/javascript">
                                            $(function () {
                                            $('.js-basic-example').DataTable({
                                                responsive: true, 
                                                autoWidth : false, 
                                            });   
                                        });
                                        </script>   


                                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                            <thead>
                                                <tr>
                                                    <th></th>  
                                                    <th></th>   
                                                    <th>KD Pegawai</th>
                                                    <th>Nama</th>
                                                    <th>Alamat</th>
                                                    <th>No. HP</th>
                                                    <th>E-mail</th> 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($data as $key => $value) {
                                                ?>
                                                    <tr> 
                                                          <td>
                                                             <button class="btn btn-warning waves-effect m-r-20 " onclick="detail(<?php echo "'".$value['id_pegawai']."'"; ?>)">Edit</button> 
                                                        </td> 
                                                        <td>
                                                           <button class="btn btn-danger waves-effect m-r-20" onclick="hapus(<?php echo $value['id_pegawai'] ?>)">Hapus</button>
                                                        </td>

                                                        <td><?=$value['id_pegawai'];?></td>
                                                        <td style="cursor: pointer;"><?=$value['nama'];?> 
                                                        <td><?=$value['alamat'];?></td>
                                                        <td><?=$value['no_hp'];?></td>
                                                        <td><?=$value['email'];?></td> 
                                                    </tr>
                                                <?php
                                                    }
                                                ?>
                                            </tbody>
                                        </table>     
 
 

                                    </div>   
                                </div>
                            </div> 

    <script type="text/javascript">  

     function hapus(primary) { 
            var url_controller  = $('#url').val();    
            var url = "<?php echo base_url() ?>"+url_controller+'hapus/'+primary; 

            console.log(url);
              $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "json",
                success: function( response ) {   
                    console.log(response);
                    try{       
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }     

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
                        $('.table-responsive').html(response); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }   
        

       function detail(id_pegawai) { 

            $('.button_tambah').hide();  
            var url_controller  = $('#url').val(); 
            var url = "<?php echo base_url() ?>"+url_controller+"detail/"+id_pegawai;
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
 
