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
            <th>No</th>  
            <th>Username</th>
            <th>Email</th>
            <th></th>     
        </tr>
    </thead>
    <tbody>
        <?php
            $i=1;
            $level = "";
            foreach ($data as $key => $value) { ?>
            <tr>  
                <td><?= $i++;?></td> 
                <td style="cursor: pointer;"><?=$value['username'];?> 
                <td><?=$value['email'];?></td> 
                <td>
                    <button class="btn btn-success waves-effect m-r-20 " onclick="detail(<?php echo "'".$value['id']."'"; ?>)"><i class="fa fa-fw fa-pencil"></i></button> 
                     <button class="btn btn-danger waves-effect m-r-20"  onclick="hapus(<?php echo $value['id'] ?>)"><i class="fa fa-fw fa-trash"></i></button>
                </td>  
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
        if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
            $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "json",
                success: function( response ) {   
                    console.log(response);
                    try{        
                        sukses2(response.return, response.pesan); 
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }
        return false; 
    }   

</script>

 