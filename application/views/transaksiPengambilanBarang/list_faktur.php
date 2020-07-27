<?php
foreach ($list_faktur as $key) { ?>
	<option value="<?=$key['no_faktur']?>">
	<?= $key['no_faktur']. "- ".$key['nama']." (".date_from_datetime($key['time'],3).")"?></option>
<?php } ?>