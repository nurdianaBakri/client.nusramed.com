 
 <!DOCTYPE html>
 <html>
 <head>
     <title>Print Stok Opname</title>

      <script type="text/javascript">

        window.onload = function() { 
         window.print();  
        } 
    </script>
 </head>
 <body>

     <table class="table table-bordered table-hover js-basic-example2" id="tab_logic" style="font-size: 12px">
        <thead>
          <tr >
            <th> # </th>
            <th> Barcode</th>
            <th> Satuan</th>
            <th> No. Batch</th>
            <th> No. Reg</th> 
            <th> Sisa Stok</th> 
            <th> Stok Real</th> 
          </tr>
        </thead>
        <tbody id='element_table'>

            <?php 
            $no=1; 
            foreach ($detail_obat->result() as $items ):

                $this->db->where('barcode', $items->barcode);
                $obat = $this->db->get('obat');

                if ($obat->num_rows()>0) {
                    $obat = $obat->row(); 

                    $this->db->where('kd_satuan', $obat->kd_satuan);
                    $satuan = $this->db->get('satuan')->row(); 

                    $subtotal = $items->harga_beli*$items->stok_awal;
                    ?> 
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>  
                            <?php echo $items->barcode. " - ".$obat->nama ?></td> 
                             
                            <td><?= $satuan->nm_satuan ?></td> 
                            <td><?= $items->no_batch ?></td>
                            <td><?= $items->no_reg ?></td> 
     
                            <td> <?= $items->sisa_stok_total ?></td> 
                            <td>  </td>  
                        </tr> 
                    <?php
                }  ?>  
             <?php endforeach ?> 
        </tbody> 
    </table> 
 
 </body>
 </html>
 