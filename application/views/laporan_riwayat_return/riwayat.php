
 <script type="text/javascript">
    $(function () {
    $('.js-basic-example4').DataTable({
        responsive: true, 
        autoWidth : false, 
    });   
});
</script>   


<table class="table table-bordered table-striped table-hover js-basic-example4 dataTable">
  <thead>
      <tr>
        <th style="width:10%;">Tanggal Input</th>
        <th>Jumlah</th>
        <th>Status </th>
        <th>User</th>   
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ($data as $key) { ?>
      <tr>
        <td><?= date_from_datetime($key['tgl_angsuran'],2)?></td>  
        <td><?= number_format($key['angsuran'],2)?></td>
        <td><?php  
        if ($key['lunas']==1 ||  $key['lunas']=="1")
        {
          echo "<span class='label label-success'>Lunas</span>";
        }
        else if ($key['lunas']==0 ||  $key['lunas']=="0"){ 
          echo "<span class='label label-danger'>Belum Lunas</span>";

        }  ?></td> 
        <td><?=$key['nama']?></td>      
      </tr> 
      <?php }  ?>
    </tbody>