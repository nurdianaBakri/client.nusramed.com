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
                <th>Qty</th>
                <th>Return</th> 
                <th>Harga Jual</th>
                <th>Diskon</th>
                <th>User Penginput</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($laporan->result_array() as $key) 
            //GET SUPLIER 
            
            //GET  
            : ?>
                <tr>
                    <td><?= date_from_datetime($key['time'],3)  ?></td>
                    <td><?= $key['no_faktur'] ?> </td>
                    <td><?php echo $key['kd_outlet']." - ".$key['nama'] ?></td>
                    <td><?php 
                        $this->db->where('barcode', $key['barcode']);
                        $dt_suplier = $this->db->get('obat')->row_array(); 
                       echo $key['barcode']." - ".$dt_suplier['nama']  ?></td>
                    <td><?= $key['no_batch'] ?> </td>
                    <td><?= $key['no_reg'] ?> </td>
                    <td><?= date_from_datetime($key['tgl_exp'],2)  ?> </td>
                    <td><?php

                    $this->db->where('kd_pembayaran', $key['kode_pembayaran']);
                    $metode_pembayaran = $this->db->get('metode_pembayaran')->row_array(); 

                    echo $key['kode_pembayaran']." - ".$metode_pembayaran['nama_metode_pembayaran'] ?> </td>
                    <td><?php  
                    if ($key['tgl_jatuh_tempo']=="0000-00-00 00:00:00") {
                        echo "COD";
                    }
                    else {
                        $s = strtotime($key['tgl_jatuh_tempo']); 
                        echo date('d M Y', $s);
                    }
                     ?> </td>
                    <td><?= $key['stok_awal'] ?> </td>
                    <td><?= $key['sisa_stok'] ?> </td>
                    <td><?= $key['qty'] ?></td>
                    <td><?= $key['return'] ?></td> 
                    <td><?= number_format($key['harga_jual'],2); ?> </td>
                    <td><?= $key['diskon_per_item'] ?></td>
                    <td><?= $key['id_user_input'] ?> </td>
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
                <th>Qty</th>
                <th>Return</th> 
                <th>Harga Jual</th>
                <th>Diskon</th>
                <th>User Penginput</th>
            </tr>
        </tfoot>
    </table>
</div>
