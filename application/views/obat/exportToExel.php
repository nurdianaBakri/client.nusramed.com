 
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
           /* {
                extend: 'pdfHtml5',
                 orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },*/
            'colvis'
        ]
    } );
} ); 
</script> 

<!-- Main content -->
<section class="content">
    
    <div class="pesan"></div>
    
    <!-- Default box -->
    <div class="box"> 
        <div class="box-body">
            
            <div class="table-responsive">
                <table id="example" class="display table table-bordered table-striped table-hover " style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Industri</th> 
                            <th>Kadungan</th>
                            <th>Deskripsi</th>
                            <th>Jenis Terapi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no=1;
                        foreach ($data as $key => $value) {
                        ?>
                        <tr>
                            <td><?=$no++;?></td>
                            <td><?=$value['barcode'];?></td>
                            <td><?=$value['nama'];?> </td>
                            <td><?= $value['nm_satuan'];  ?> </td>
                            <td><?= $value['nm_industri']; ?>   </td>
                            <td><?=$value['kandungan'];?></td>
                            <td><?=$value['deskripsi'];?></td>
                            <td><?=$value['jenis_terapi'];?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
    <!-- /.box -->
</section>
