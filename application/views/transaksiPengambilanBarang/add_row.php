 <?php
    $date=date('Y-m-d'); 
?> 
 	<tr>
		<td>
			1
		</td>
		<td>
			<input type="hidden" name="barcode[]" class="barcode_table" value="<?= $data['barcode']?>">
			<?= $data['barcode'] ?> 
		</td>
		<td>
			 <?= $obat['nama'] ?> 
		</td> 
		 
		<td>
			<input type="hidden" name="no_batch[]" class="form-control" value="<?= $data['no_batch'] ?>"  >
			<?= $data['no_batch'] ?> 
		</td>
		<td>
			<input type="hidden" name="tgl_exp[]" class="form-control" value="" >
			<?php 
				$data['tgl_exp'] = strtotime($data['tgl_exp']); 
		  		echo date('d M Y', $data['tgl_exp']); ?>  
		</td> 
		<td>
			<input type="hidden" name="harga[]" class="form-control harga" value="1000" >
			<?= number_format($data['harga_jual']); ?> 
		</td>
		<td>
			<input type="number" name="diskon[]" class="form-control diskon" value="0" >
		</td>
		<td>
			<input type="number" name="qty[]" class="form-control qty" value="1" >
		</td>
		 
		<!-- <td>
			<button onclick="deleteRow(this)" class="btn btn-success">Hapus</button>
		</td> -->
	</tr> 
			 