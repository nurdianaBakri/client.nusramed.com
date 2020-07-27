
 <script type="text/javascript">
    $(function () {
    $('.js-basic-example3').DataTable({
        responsive: true, 
        autoWidth : false, 
    });   
});
</script>   


<table class="table table-bordered table-striped table-hover js-basic-example3 dataTable">
  <thead>
      <tr>
        <th style="width:1%;">Unit</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>No. Batch / No. Reg</th>  
        <th>Alasan Return </th>
        <th>Tanggal Return </th>
        <th>User </th>
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ($data as $key) { ?>
      <tr>
        <td><?=$key['jumlah_return']?></td>
        <td><?=$key['nama_obat']?></td>
        <td><?=$key['nama_obat']?></td>
        <td><?=$key['no_batch']."/".$key['no_reg']?></td> 
        <td><?=$key['alasan_return']?></td>   
        <td><?= date_from_datetime($key['tgl_return'],2)?></td>   
        <td><?=$key['nm_user']?></td>   
      </tr> 
      <?php }  ?>
    </tbody>