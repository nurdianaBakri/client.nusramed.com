
<script type="text/javascript">
    $(function () {
    $('.js-basic-example3').DataTable({
        responsive: true, 
        autoWidth : false, 
        scrollY:        '50vh',
        scrollCollapse: true,
    });   
});
</script> 

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Obat yang belum di-set Harga Jual</h3> 
        </div>
        <div class="box-body"> 
            <?php 
            if ($obat_not_set_price->num_rows()>0)
            {
                ?> 
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example3 dataTable">
                        <thead>
                            <tr>
                                <th>#</th>  
                                <th>Barcode </th>
                                <th>No Batch </th>
                                <th>No Reg </th>  
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no=1;
                                foreach ($obat_not_set_price->result_array() as $key => $value) {
                            ?>
                                <tr>   
                                    <td><?=$no++;?></td>  
                                    <td><?= $value['barcode'] ." - <br>".$value['nama'];?></td>
                                    <td><?= $value['no_batch'];?></td> 
                                    <td><?= $value['no_reg'];?></td>  
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>    
                </div> 
                <?php 
            }
            else
            {
                echo "Harga obat sudah di-Set";
            }
            ?>
        </div> 
        <!-- /.box-footer-->
      </div> 