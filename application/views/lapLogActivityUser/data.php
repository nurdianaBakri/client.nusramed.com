<script type="text/javascript"> 
    $(document).ready(function() {

    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [ 
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                 orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
    } );
} ); 
</script>  


 <div class="table-responsive"> 
    <table id="example" class="display table table-bordered table-striped table-hover " style="width:100%">
        <thead>
            <tr>
                <th>Tanggal Aktivity</th>
                <th>Nama User</th>
                <th>Keterangan</th> 
            </tr>
        </thead>
        <tbody>

            <?php foreach ($laporan->result_array() as $key) 
            //GET SUPLIER 
            
            //GET  
            : ?>
                <tr>
                    <td><?= date("d M Y g:i A", strtotime($key['tgl_log']))  ?></td>
                    <td><?= $key['username'] ?> </td> 
                    <td><?= $key['keterangan'] ?> </td> 
                </tr>
            <?php endforeach ?> 
        </tbody>
        <tfoot>
            <tr>
                <th>Tanggal Aktivity</th>
                <th>Nama User</th>
                <th>Keterangan</th> 
            </tr>
        </tfoot>
    </table>
</div>
