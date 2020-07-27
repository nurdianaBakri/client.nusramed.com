 

<script type="text/javascript">
    $(function () {
    $('.js-basic-example').DataTable({
        responsive: false, 
        autoWidth : false, 
    });   
});
</script> 
 

    <button class="btn btn-success pull-right waves-effect m-r-20" onclick="setHarga()">Update harga Obat</button>  

    <form id="myForm3"> 
        <table class="table table-bordered table-hover js-basic-example2" id="tab_logic" style="font-size: 10px">
            <thead>
              <tr > 
                <th> Barcode</th>
                <th> Satuan</th>
                <th> No. Batch/No. Reg</th> 
                <th> Tgl Exp</th>
                <th> Diskon %</th>
                <th> Nilai PPN</th>
                <th> Qty</th> 
                <th> Harga Beli</th>
                <th> Diskon Maximal</th>
                <th> Harga Jual</th>
              </tr>
            </thead>
            <tbody id='element_table'>

                <?php 
            $no=1; 
            foreach ($detail_obat as $items ):  

                    $subtotal = $items->subtotal;

                    $s = strtotime($items->tgl_exp); 
                    $date_2 =  date('Y-m-d', $s);
                    ?> 
                        <tr> 
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
                             <td><?= number_format($items->harga_beli,2); ?></td> 
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
    </form>    


<script type="text/javascript">

      

    function setHarga() {   
         if ($("#myForm2").valid()==false)
         {
            return false;
         }
         else
         {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 

                var url_controller  = $('#url').val(); 
                    var url = "<?php echo base_url() ?>"+url_controller+"setHarga"; 
                    var data = $('#myForm3').serialize();
                    console.log(url);

                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "json",
                        success: function( response ) {  
                        try{     
                            //reload page
                            reload_data(); 
                            $('#myModal').modal('hide'); 
                            sukses2(response.return, response.pesan);  
                             
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );  
            }
        }        
     }
  
</script>


       