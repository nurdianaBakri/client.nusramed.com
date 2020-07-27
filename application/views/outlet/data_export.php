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



<!-- Main content -->
<section class="content">
    
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?PHP echo $title; ?></h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">
            <div class="table-responsive">
                <table id="example" class="display table table-bordered table-striped table-hover " style="width:100%">
                    <thead>
                        <tr>
                            <th>KD</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th>NPWP</th>
                            <th>Nama Pemilik</th>
                            <th>No.Telp. Pemilik</th>
                            <th>Nama PJ</th>
                            <th>Alamat PJ</th>
                            <th>No STR PJ</th>
                            <th>No SIPA PJ</th>
                            <th>No.Telp. PJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $key => $value) {
                        ?>
                        <tr>
                            <td><?=$value['id_outlet'];?></td>
                            <td><?=$value['nama'];?>
                                <td><?=$value['alamat'];?></td>
                                <td><?=$value['no_telp'];?></td>
                                <td><?=$value['npwp'];?></td>
                                <td><?=$value['nm_pemilik'];?></td>
                                <td><?=$value['no_telp_pemilik'];?></td>
                                <td><?=$value['nama_pj'];?></td>
                                <td><?=$value['no_str_pj'];?></td>
                                <td><?=$value['no_sipa_pj'];?></td>
                                <td><?=$value['alamat_pj'];?></td>
                                <td><?=$value['no_telp_pj'];?></td>
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
             
 