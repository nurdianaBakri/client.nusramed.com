<select name="kd_kategori_obat" class="form-control js-example-basic-single" required="">
<?php 

  foreach ($kategori_obat as $key) {
    ?>
    <option value="<?=$key['kd_kategori']?>"><?=$key['nm_kategori']?></option>
    <?php
  } 
?>
</select>