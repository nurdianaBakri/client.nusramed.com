<table class="table table-bordered table-hover" id="editable_table">
    <thead>
        <tr>
            <th class="text-center" style="width:2%;"> # </th>
            <th class="text-center" style="width:19%;"> Barcode-Nama</th>
            <th class="text-center" style="width:9%;"> No. Batch / No. Reg</th>
            <th class="text-center" style="width:8%;"> Tgl Exp</th>
            <th class="text-center" style="width:9%;"> Harga</th>
            <th class="text-center" style="width:5%;"> Diskon %</th>
            <th class="text-center" style="width:3%;"> Qty</th>
            <th class="text-center" style="width:5%;"> </th> 
        </tr>
    </thead>
    <tbody>
        
        <?php
        foreach ($data as $row) { ?> 
        <tr>
            <td><?= $row['no']; ?></td>
            <td><?= $row['barcode_nama']; ?></td>
            <td><?= $row['no_batch'] ."<br>/ ".$row['no_reg']; ?></td>
            <td><?= date_from_datetime($row['tgl_exp'],2); ?></td>
            <td><?= $row['harga_jual']; ?></td>
            <td><?= $row['diskon']; ?></td>
            <td><?= $row['qty']; ?></td>
            <td>
                <button class="btn btn-success" onclick="modal_form_return(<?= $row['id_trx'] ?>)"><i class="fa fa-repeat" aria-hidden="true"></i></button>
            </td> 
        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Set Return</h4>
            </div>
            <div class="modal-body">

                <form id="modal_form_return">

                     <input type="hidden" name="id_trx"  id="id_trx" value="">

                      <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Jumlah item return</label> 
                                    <input type="number" name="qty_return" class="form-control" id="qty_return">
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Alasan Return</label> 
                                    <textarea class="form-control" name="alasan_return"></textarea>
                                </div>
                            </div> 
                        </div>
                </form>
                <button type="button" onclick="return_barang()" class="btn btn-success btn-block" class="submit">Return Barang</button>
                     
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
      function modal_form_return(id_trx){
            $('#id_trx').val(id_trx);

            $('#myModal').modal({
                show: 'true'
            });  
        }

        function return_barang() {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 

                var url_controller  = $('#url').val(); 
                    var url = "<?php echo base_url() ?>"+url_controller+"update"; 
                    var data = $('#modal_form_return').serialize();
                    console.log(url);

                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "Json",
                        success: function( response ) { 
                            console.log(response);
                        try{    
                            $('#myModal').modal('hide'); 
                            sukses2(response.return, response.pesan);   
                            
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );  
            }
        }
 </script>