<option value="">Pilih salah satu</option>  
<?php foreach ($kecamatan as $key ): ?>
 <option value="<?= $key['id_kec'] ?>"><?= $key['nama'] ?></option>
<?php endforeach ?>