
    <link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url()."assets/" ?>select2.min.js"></script> 

<!-- Main content -->
    <section class="content">
    
      <!-- Default box -->
      <div class="box"> 
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url"> 

             <div class="table-responsive">
               <div id="collapseOne_2" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_2">
                      <div class="panel-body">
                          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                              <h4>ID obat Tidak cocok</h4>
                          </div>
                      </div>
                  </div>  
             </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button class="btn btn-success" onclick="goBack()">Kembali</button>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>
    <script>
      function goBack() {
        window.history.back();
      }
    </script>
 