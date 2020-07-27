

 <?php
    $date=date('Y-m-d')
?> 
 	<tr onkeyup="kalkulasiDiskonPerItem(this)">
		<td>
			1
		</td>
		<td>
			<?= $data['barcode'] ?>
			<input type="hidden" name="barcode[]" class="form-control barcode_table" value="<?= $data['barcode']?>" readonly>
		</td>
		<td>
			 <?= $data['nama'] ?> 
		</td>
		<td>
			 <?php
				 $this->db->where('kd_satuan', $data['kd_satuan']);
				 echo $this->db->get('satuan')->row_array()['nm_satuan'];
			  ?> 
		</td>
		<td>
			<input type="text" name="no_reg[]" id="no_reg" class="form-control no_reg_table" value="" required  >
		</td>
		<td>
			<input type="text" name="no_batch[]" id="no_batch" class="form-control dissable_enter" value="" required  >
		</td>
		<td>
			<input type="date" name="tgl_exp[]" id="tgl_exp" class="form-control dissable_enter" value="" required  min="<?= $date; ?>" >
		</td>
		<td>
			<input type="number" name="stok_awal[]" id="stok_awal" class="form-control stok_awal" value="1" min="1" >
		</td>
		<td>
			<input type="number" name="harga_beli[]" class="form-control harga_beli" value="1000" >
		</td>
		<td>
			<input type="number" name="diskon_beli[]" class="form-control diskon_beli" value="0" >

			<input type="hidden" name="harga_setelah_diskon[]" class="form-control harga_setelah_diskon" value="0" >
		</td>
		<td>
			<input type="number" name="ppn_nilai[]" class="form-control ppn_nilai" value="" >
		</td>
		<td>
			<input type="text" name="lokasi[]" id="lokasi" class="form-control lokasi" value="" required >
		</td>
		<td>
			<button onclick="deleteRow(this)" class="btn btn-danger"><i class="fa fa-fw fa-trash"></i></button>
		</td>
	</tr> 	 