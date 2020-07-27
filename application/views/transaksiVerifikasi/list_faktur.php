<?php
  foreach ($list_faktur as $key) {
    ?>
    <option value="<?=$key['id_master_detail']?>">
        <?= $key['label']?></option>
    <?php
  } 
?>