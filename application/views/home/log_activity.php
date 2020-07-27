
<script type="text/javascript">
    $(function () {
    $('.js-basic-example').DataTable({
        responsive: true, 
        autoWidth : false, 
        scrollY:        '50vh',
        scrollCollapse: true,
    });   
});
</script> 

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Log Aktivity Anda dari <?= date_from_datetime($today,2) ?> sampai <?= date_from_datetime($today_1,2) ?></h3> 
        </div>
        <div class="box-body"> 
            <?php 
            if ($log_activity->num_rows()>0)
            {
                ?>  
                 <div class="table-responsive"> 
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>#</th>  
                                <th>Tanggal </th>
                                <th>Aktivitas</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no=1;
                                foreach ($log_activity->result_array() as $key => $value) {
                            ?>
                                <tr>   
                                    <td><?=$no++;?></td> 
                                    <td><?= date_from_datetime($value['tgl_log'],3);?></td>
                                    <td><?= $value['keterangan'];?></td> 
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
                echo "Anda tidak memiliki log hari ini";
            }
            ?>
        </div> 
        <!-- /.box-footer-->
      </div> 