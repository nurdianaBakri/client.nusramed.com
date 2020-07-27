

<script src="http://cdn.datatables.net/plug-ins/1.10.21/sorting/date-euro.js"></script>

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
        ],
        paging : "false",
        ordering : "true",
        scrollCollapse : "true",
        searching : "false",
        columnDefs : [{targets:0, type:"date-euro"}],
        bInfo: "true"
    } );
} ); 
</script>  


 <div class="table-responsive"> 
    <table id="example" class="display table table-bordered table-striped table-hover " style="width:100%">
        <thead>
            <tr>
                <th>Tanggal Input</th>
                <th>No Faktur</th>
                <th>Uraian</th>    
                <th>No Batch</th>
                <th>Tgl Exp</th> 
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Sisa</th> 
                <th>Paraf </th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($laporan as $key) 
            //GET SUPLIER 
            
            //GET  
            : ?>
                <tr>
                    <td><?= $key['time']  ?></td>
                    <td><?= $key['no_faktur'] ?> </td>
                    <td><?= $key['keterangan'];?> </td>
                    <td><?= $key['no_batch'] ?></td>  
                    <td><?= $key['tgl_exp']  ?> </td> 
                    <td><?= $key['masuk'] ?> </td>
                    <td><?=$key['keluar']?> </td> 
                    <td><?= $key['sisa'] ?> </td> 
                    <td><?= "-" ?> </td> 
                </tr>
            <?php endforeach ?> 
        </tbody>
        <tfoot>
            <tr>
                <th>Tanggal Input</th>
                <th>No Faktur</th>
                <th>Uraian</th>    
                <th>No Batch</th>
                <th>Tgl Exp</th> 
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Sisa</th> 
                <th>Paraf </th>
            </tr>
        </tfoot>
    </table>
</div>
