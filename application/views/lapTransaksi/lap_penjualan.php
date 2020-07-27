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
                pageSize: 'A4',
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
    <table id="example" class="table table-bordered table-striped table-hover ">
        <thead>
            <tr>
                <th>Aksi</th>
                <th>Tanggal Input</th>
                <th>No Faktur</th>
                <th>Outlet</th>
                <th>Kode Pembayaran</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Jumlah Belanja</th>
                <th>Kode User</th>
                <th>Status Pengambilan Obat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($laporan as $key){
            ?>
            <tr>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Aksi
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a  href="<?= base_url().'laporan/FakturJualBeli/detail_pejualan/'.$key['no_faktur'].'/'.$key['kd_outlet']?>">Detail
                            </a> </li>
                            <li><a  href="<?= base_url().'transaksi/Penjualan/notaPenjualan/'.$key['no_faktur'].'/'.$key['kd_outlet']?>" target="_blank">Print
                            </a></li>
                        </ul>
                    </div>
                </td>
                <td><?= date_from_datetime($key['time'],3)  ?></td>
                <td><?= $key['no_faktur'] ?> </td>
                <td><?php  echo $key['kd_outlet']." - ".$key['nama'] ?></td>
                <td><?php echo $key['kode_pembayaran']." - ".$key['nama_metode_pembayaran'] ?> </td>
                <td><?php
                    if ($key['tgl_jatuh_tempo']=="0000-00-00 00:00:00") {
                        echo "COD";
                    }   
                    else {
                        echo date_from_datetime($key['tgl_jatuh_tempo'],2);
                    }
                ?> </td>
                <td><?= number_format($key['harga_stl_pajak'],2);?> </td>
                <td><?= $key['id_user_input'] ?> </td>
                <td><?= $key['id_user_verifikasi'] ?> 
                 </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
        <tr>
            <th>Aksi</th>
            <th>Tanggal Input</th> 
            <th>No Faktur</th>
            <th>Outlet</th>
            <th>Kode Pembayaran</th>
            <th>Tgl Jatuh Tempo</th>
            <th>Jumlah Belanja</th>
            <th>Kode User</th>
            <th>Status Pengambilan Obat</th>

        </tr>
        </tfoot>
    </table>
</div>