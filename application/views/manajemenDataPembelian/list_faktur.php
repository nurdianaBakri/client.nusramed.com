<?php
  foreach ($data as $key) {
    ?>
    <option value="<?=$key['id_master_detail']?>">
        <?= $key['list_faktur']; ?></option>
    <?php
  } 
?>