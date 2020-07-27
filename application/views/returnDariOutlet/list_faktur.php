<?php
  foreach ($data as $key) {
    ?>
    <option value="<?=$key['no_faktur']?>">
        <?= $key['no_faktur']. " ( ".date_from_datetime($key['time'],3)." )"?></option>
    <?php
  } 
?>