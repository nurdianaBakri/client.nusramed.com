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
                <th>Tanggal</th>
                <th>No Faktur</th>
                <th>Suplier</th>
                <th>Barcode</th>
                <th>No Batch</th>
                <th>No Register</th>
                <th>Tgl Exp</th>
                <th>Kode Pembayaran</th>
                <th>Jatuh Tempo</th>
                <th>Stok Awal</th>
                <th>Sisa Stok</th>
                <th>Terjual</th>
                <th>Return</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Diskon</th>
                <th>Kode User</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($laporan->result_array() as $key)

            //GET SUPLIER 
            
            //GET 

            : ?>
                <tr>
                    <td><?= date("d/m/Y g:i A", strtotime($key['time']))  ?></td>
                    <td><?= $key['no_faktur'] ?> </td>
                    <td><?php 
                        $this->db->where('kd_suplier', $key['kd_suplier']);
                        $dt_suplier = $this->db->get('suplier')->row_array(); 
                       echo $key['kd_suplier']." - ".$dt_suplier['nama'] ?></td>
                    <td><?php 
                        $this->db->where('barcode', $key['barcode']);
                        $dt_suplier = $this->db->get('obat')->row_array(); 
                       echo $key['barcode']." - ".$dt_suplier['nama']  ?></td>
                    <td><?= $key['no_batch'] ?> </td>
                    <td><?= $key['no_reg'] ?> </td>
                    <td><?= date("d/m/Y", strtotime($key['tgl_exp']))  ?> </td>
                    <td><?php

                    $this->db->where('kd_pembayaran', $key['kode_pembayaran']);
                    $metode_pembayaran = $this->db->get('metode_pembayaran')->row_array(); 

                    echo $key['kode_pembayaran']." - ".$metode_pembayaran['nama_metode_pembayaran'] ?> </td>
                    <td><?php  
                    if ($key['tgl_jatuh_tempo']=="0000-00-00 00:00:00") {
                        echo "-";
                    }
                    else {
                        $s = strtotime($key['tgl_jatuh_tempo']); 
                        echo date('d M Y', $s);
                    }
                     ?> </td>
                    <td><?= $key['stok_awal'] ?> </td>
                    <td><?= $key['sisa_stok'] ?> </td>
                    <td><?= $key['terjual'] ?></td>
                    <td><?= $key['return'] ?></td>
                    <td><?= number_format($key['harga_beli'],2); ?> </td>
                    <td><?= number_format($key['harga_jual'],2); ?> </td>
                    <td><?= $key['diskon_beli'] ?></td>
                    <td><?= $key['id_user'] ?> </td>
                </tr>
            <?php endforeach ?> 
        </tbody>
        <tfoot>
            <tr>
               <th>Tanggal</th>
                <th>No Faktur</th>
                <th>Suplier</th>
                <th>Barcode</th>
                <th>No Batch</th>
                <th>No Register</th>
                <th>Tgl Exp</th>
                <th>Kode Pembayaran</th>
                <th>Jatuh Tempo</th>
                <th>Stok Awal</th>
                <th>Sisa Stok</th>
                <th>Terjual</th>
                <th>Return</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Diskon</th>
                <th>Kode User</th>
            </tr>
        </tfoot>
    </table>
</div>
