

<script type="text/javascript">
    $(function () {
    $('.js-basic-example2').DataTable({
        // responsive: false, 
          // paging:   false, 
        // autoWidth : false, 
    });   
});
</script> 

<style type="text/css">
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

        padding: 5px !important; // currently 8px
    }
</style>


<button class="btn btn-success pull-right waves-effect m-r-20" onclick="setOpname2()">Set Stok Opname</button>  

<form id="myForm3">                 
<table class="table table-bordered table-hover js-basic-example2" id="tab_logic" style="font-size: 10px">
        <thead>
          <tr >
            <th> # </th>
            <th style="width:20%;"> Barcode</th>
            <th> Satuan</th>
            <th> No. Reg</th>
            <th> Lokasi</th>
            <th style="width:15%;"> Harga Beli</th>
            <th style="width:10%;"> Last Update</th>
            <th style="width:7%;"> Sisa Stok</th> 
            <th style="width:1%;"> No. Batch</th>
            <th style="width:1%;"> Tgl Exp</th> 
            <th style="width:1%;"> Stok Real</th>
          </tr>
        </thead>
        <tbody id='element_table'>

            <?php 
            $no=1; 
            foreach ($detail_obat as $items ):

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
                                <input type="hidden"  id="barcode_item" name="barcode[]" value="<?= $items->barcode ?>" >
                        
                                <input type="hidden" name="no_batch_old[]" value="<?= $items->no_batch ?>" >

                                <input type="hidden"  name="no_reg[]" value="<?= $items->no_reg ?>" >  
                                <input type="hidden"  name="tgl_exp_old[]" value="<?= $items->tgl_exp ?>" >  

                                 <input type="hidden"  name="nama[]" value="<?= $obat->nama  ?>" >  
                            <?php echo $items->barcode. " - ".$obat->nama ?></td> 
                             
                            <td><?= $satuan->nm_satuan ?></td> 
                            <td><?= $items->no_reg ?></td>  

                            <td> <?= $items->lokasi ?></td>
                             <td><?= number_format($items->harga_beli,2); ?></td>
                             <td><?= date_from_datetime($items->time,3); ?></td>
                            <td> <?= $items->sisa_stok_total ?></td> 
                            <td>
                                <input type="text" name="no_batch[]" value="<?= $items->no_batch; ?>" class="form-control" >
                            </td>
                            <td>  
                                 <?php
                                    $s = strtotime($items->tgl_exp); 
                                    $date_2 =  date('Y-m-d', $s);   
                                ?> 
                                <input type="date" name="tgl_exp[]" value="<?= $date_2; ?>" class="form-control" >
                            </td>   
                              <td> 
                                 <input type="hidden" name="sisa_stok[]"  value="<?= $items->sisa_stok_total ?>"  class="form-control" min="0" max="100">

                                <input type="number" name="stok_real[]" value="" class="form-control" min="0" >
                            </td> 
                             
                        </tr>

                    <?php
                }  ?>   

             <?php endforeach ?>

        </tbody>
       
    </table>
</form> 

<script type="text/javascript">
    
    function setOpname2() {   
         if ($("#myForm3").valid()==false)
         {
            return false;
         }
         else
         {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 

                var url_controller  = $('#url').val(); 
                    var url = "<?php echo base_url() ?>"+url_controller+"setOpname"; 
                    var data = $('#myForm3').serialize();
                    console.log(url);

                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: "Json",
                        success: function( response ) { 
                            console.log(response);
                        try{     
                            //reload page  
                            $('#modal_alert .modal-title').text("Proses Stok Opname");
                            $('#modal_alert .modal-body').html(response.pesan);
                            reload_data();  
                            $('#modal_alert').modal({
                                show: 'true'
                            }); 
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );  
            }
        }        
     }
</script>