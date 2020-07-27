
<script type="text/javascript">
   $(document).ready(function(){  
    var url = "<?php echo base_url() ?>" + $('#url').val() + "update";
    console.log(url);

     $('#editable_table').Tabledit({
      url:url,
      hideIdentifier: true,
      columns:{
       identifier:[0, "id_trx"],
       editable:[
           [8, 'qty_return'], 
           [9, 'alasan_return']
       ]
      },
      // Example #1
    buttons: {
        edit: {
            class: 'btn btn-sm btn-warning',
            html: '<span class="glyphicon glyphicon-pencil"></span>',
            action: 'edit'
        },
        save: {
            class: 'btn btn-sm btn-success',
            html: '<span class="glyphicon glyphicon-save"></span>'
        }
    },
      restoreButton:false,
      deleteButton:false,
      onSuccess:function(data, textStatus, jqXHR)
      {
        console.log(data);
       /*if(data.action == 'delete')
       {
        $('#'+data.id_trx).remove();
       }*/
      },
      onFail: function(jqXHR, textStatus, errorThrown) {
          console.log('onFail(jqXHR, textStatus, errorThrown)');
          console.log(jqXHR);
          console.log(textStatus);
          console.log(errorThrown);
     },
     });
 
});  
</script> 

<table class="table table-bordered table-hover table-responsive" id="editable_table">
    <thead>
        <tr>
            <th class="text-center" style="width:2%;"> ID</th>
            <th class="text-center" style="width:2%;"> # </th>
            <th class="text-center" style="width:20%;"> Barcode-Nama</th>
            <th class="text-center" style="width:10%;"> No. Batch / No. Reg</th> 
            <th class="text-center" style="width:10%;"> Tgl Exp</th>
            <th class="text-center" style="width:10%;"> Harga</th>
            <th class="text-center" style="width:5%;"> Diskon %</th>
            <th class="text-center" style="width:5%;"> Qty</th>
            <th class="text-center" style="width:5%;"> Qty return</th>
            <th class="text-center" style="width:5%;">Alasan return</th> 
        </tr>
    </thead>
    <tbody>
        
        <?php
        foreach ($data as $row) { ?>
        <tr>
            <td><?= $row['id_trx']; ?></td>
            <td><?= $row['no']; ?></td>
            <td><?= $row['barcode_nama']; ?></td>
            <td><?= $row['no_batch'] ."<br>/ ".$row['no_reg']; ?></td>
            <td><?= $row['tgl_exp']; ?></td>
            <td><?= $row['harga_jual']; ?></td>
            <td><?= $row['diskon']; ?></td>
            <td><?= $row['qty']; ?></td>
            <td><?= $row['qty_return']; ?></td>
            <td><?= $row['alasan_return']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>