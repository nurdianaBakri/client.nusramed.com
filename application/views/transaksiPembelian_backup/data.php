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
            <th>Nama</th>
            <th>Kode barang</th>
            <th>No Batch</th>
            <th>Stok</th>
            <th>Tgl Exp</th>
            <th>ID User</th> 
            <th>Tanggal Input</th> 
            <th>Kategori</th> 
            <th></th> 
            <th></th>
        </tr>
    </thead>
     <tbody>
    <?php 
        foreach ($detail as $value) {
    ?>
        <tr idx="<?=$value['no_batch'];?>">
            <td><?=$nama_obat;?></td> 
            <td>[String kode barang]</td> 
            <td><?php echo $value['no_batch'] ?> </td>
            <td><?=$value['stok'];?></td>
            <td><?=$value['tgl_exp'];?></td>
            <td><?= $value['id_user']; ?></td> 
            <td><?=$value['time'];?></td> 
            <td><?=$value['kategori'];?></td> 
            <td>  
                <button class="btn btn-warning waves-effect m-r-20 " onclick="detail(<?php echo "'".$value['no_batch']."'"; ?>)">Edit</button>
            </td>
           
             <td>  
                <button class="btn btn-danger waves-effect m-r-20 " onclick="hapus(<?php echo "'".$value['no_batch']."'"; ?>)" onclick="return confirm('Apakah Anda yakin ingin menghapus obat ini ?')">Hapus</button>   
            </td>
        </tr>
    <?php
        }
    ?>
    </tbody>
</table>     
