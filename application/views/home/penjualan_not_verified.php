
<script type="text/javascript">
    $(function () {
    $('.js-basic-example4').DataTable({
        responsive: true, 
        autoWidth : false, 
        scrollY:        '50vh',
        scrollCollapse: true,
    });   
});
</script> 

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Penjualan Yang Belum di Verifikasi</h3> 
        </div>
        <div class="box-body"> 
            <?php 
            if ($penjualan_not_verified->num_rows()>0)
            {
                ?> 
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example4 dataTable">
                        <thead>
                            <tr>
                                <th>#</th>  
                                <th>Tanggal Input Penjualan </th>
                                <th>No Faktur </th>
                                <th>Nama Outlet</th>  
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no=1;
                                foreach ($penjualan_not_verified->result_array() as $key => $value){
                            ?>
                                <tr>   
                                    <td><?=$no++;?></td>  
                                    <td><?= date_from_datetime($value['time'],3);?></td>  
                                    <td><?= $value['no_faktur'];?></td>
                                    <td><?= $value['nama'];?></td> 
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