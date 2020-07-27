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
                <th>Data Sebelum</th>  
                <th>Data Sesudah</th> 
            </tr>
        </thead>
        <tbody>

            <?php foreach ($laporan->result_array() as $key) 
            //GET SUPLIER 
            
            //GET  
            : ?>
                <tr>
                    <td><?= date("d M Y g:i A", strtotime($key['tgl_log']))  ?></td>
                    <td><?= $key['nama_user'] ?> </td> 
                    <td><?= $key['keterangan'] ?> </td> 
                    <td><?= '<pre>'. json_encode( json_decode($key['data_lama']), JSON_PRETTY_PRINT) . '</pre>' ?> </td> 
                    <td><?= '<pre>'. json_encode( json_decode($key['data_baru']), JSON_PRETTY_PRINT) . '</pre>'?> </td> 
                </tr>
            <?php endforeach ?> 
        </tbody>
        <tfoot>
            <tr>
                <th>Tanggal Aktivity</th>
                <th>Nama User</th>
                <th>Keterangan</th> 
                <th>Data Sebelum</th>  
                <th>Data Sesudah</th> 
            </tr>
        </tfoot>
    </table>
</div>
