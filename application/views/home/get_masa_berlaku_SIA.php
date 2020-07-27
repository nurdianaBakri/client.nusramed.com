
<script type="text/javascript">
    $(function () {
    $('.js-basic-example6').DataTable({
        responsive: true, 
        autoWidth : false, 
        scrollY:        '50vh',
        scrollCollapse: true,
    });   
});
</script> 

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Masa Berlaku SIA yang hampir habis</h3> 
        </div>
        <div class="box-body">  

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example6 dataTable">
                        <thead>
                            <tr>
                                <th>#</th>  
                                <th>Nama Outlet</th>
                                <th>Masa berlaku</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no=1;
                                foreach ($masa_izin as $key => $value){
                            ?>
                                <tr>   
                                    <td><?=$no++;?></td>  
                                    <td><?= $value['nama'];?></td>
                                    <td><?= date_from_datetime($value['masa_izin'],2);?></td>   
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>    
                </div>  
        </div> 
        <!-- /.box-footer-->
      </div> 