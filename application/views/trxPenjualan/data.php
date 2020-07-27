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
            <th>KD Trx</th>
            <th>Tgl Trx</th>
            <th>Outlet</th> 
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($data as $key => $value) {
        ?>
            <tr> 
                <td>
                    <button class="btn btn-success waves-effect m-r-20 " onclick="detail(<?php echo "'".$value['kd_trx']."'"; ?>)">Detail</button> 
                </td> 
                <td>
                   <button class="btn btn-danger waves-effect m-r-20" onclick="hapus(<?php echo $value['kd_trx'] ?>)">Hapus</button>
                </td>

                <td><?=$value['kd_trx'];?></td>
                <td style="cursor: pointer;"><?=$value['tgl_trx'];?> 
                <td><?=$value['id_outlet'];?></td> 
            </tr>
        <?php
            }
        ?>
    </tbody>
</table>     

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

 