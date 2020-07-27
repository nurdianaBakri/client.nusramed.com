<table class="table table-bordered">
  <thead>
      <tr>
        <th style="width:1%;">Unit</th>
        <th>Nama Barang</th>
        <th>Satuan</th>
        <th>ED / No. Batch</th>  
        <th>Alasan Return </th>
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ($return as $key) { ?>
      <tr>
        <td><?=$key->qty?></td>
        <td><?=$key->nama?></td>
        <td><?=$key->nm_satuan?></td> 
        <td><?=$key->nm_satuan?></td> 
        <td><?=$key->nm_satuan?></td> 
      </tr> 
      }  ?>
    </tbody>