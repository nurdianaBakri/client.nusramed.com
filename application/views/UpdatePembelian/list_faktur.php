<?php
  foreach ($list_faktur as $key) {
    ?>
    <option value="<?=$key['no_faktur']?>">
        <?= $key['no_faktur']. "- ".$key['nama']?></option>
    <?php
  } 
?>