
<script type="text/javascript">  
    var ratesandcharges1;

    $(document).ready(function() {

        /* Init the table*/
        $("#ratesandcharges1").dataTable({
            "bRetrieve": false,
            "bFilter": true,
            "bSortClasses": false,
            "bLengthChange": false,
            "bPaginate": true,
            "bInfo": true,
            "bJQueryUI": true,
            "bAutoWidth": false, 
        });

        ratesandcharges1.fnDraw();

    });
</script>  
 
<div class="table-responsive">
                       
<table width="100%" class="table table-bordered table-hover" id="ratesandcharges1" style="font-size: 12px">
        <thead>
          <tr >
            <th> # </th>
            <th> Barcode</th>
            <th> Satuan</th>
            <th> No. Batch / No. Reg</th> 
            <th> Tgl Exp</th>
            <th> Diskon %</th>
            <th> Nilai PPN</th>
            <th> Qty</th>
            <th> Sub Total</th> 
            <th> Harga Beli</th>
            <th> Last Update</th>
            <th > Diskon Maximal</th>
            <th > Harga Jual</th>
          </tr>
        </thead>
        <tbody id='element_table'>

            <?php 
            $no=1; 
            foreach ($detail_obat->result() as $items ):  

                    $subtotal = $items->subtotal;

                    $s = strtotime($items->tgl_exp); 
                    $date_2 =  date('Y-m-d', $s);
                    ?> 
                        <tr>
                            <td><?= $no++; ?></td>
                            <td> 
                                <input type="hidden"  id="barcode_item" name="id_obat[]" value="<?= $items->id_obat ?>" >
                        
                                <input type="hidden" name="no_batch[]" value="<?= $items->no_batch ?>" >

                                <input type="hidden"  name="no_reg[]" value="<?= $items->no_reg ?>" >  
                                <input type="hidden"  name="nama[]" value="<?= $items->nama_ori ?>" >  
                            <?php echo $items->nama ?></td> 
                             
                            <td><?= $items->nm_satuan ?></td> 
                            <td><?= $items->display_no_reg; ?></td>
                            <td>  
                                <input type="date" name="tgl_exp[]" value="<?php echo $date_2 ?>" class="form-control" size="1" style="width:150px">
                            </td> 
                             
                            <td><?= $items->diskon_beli ?></td>

                            <td> <?php 
                                echo number_format(($items->ppn/100)*($subtotal-($subtotal*($items->diskon_beli/100))),2) ?></b>
                            </td> 

                            <td> <?= $items->sisa_stok ?></td>
                            <td> <?= number_format($subtotal,2) ?> </td>  
                             <td><?= number_format($items->harga_beli,2); ?></td>
                             <td><?= $items->time; ?></td>
                              <td >
                                <input type="text" name="diskon_maximal[]" value="<?= $items->diskon_maximal ?>" class="form-control" min="0"  size="2">
                            </td>
                            <td >
                                <input type="text" name="harga_jual[]" value="<?= number_format($items->harga_jual,2) ?>" class="form-control"  size="5">
                            </td>
                             
                        </tr> 

             <?php endforeach ?>

        </tbody>
       
    </table>

</div>