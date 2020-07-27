
<!-- DATA TABEL--> 
  <link href="<?php echo base_url('assets/plugins/datatables/css/jquery.dataTables.min.css')?>" rel="stylesheet"> 
  <script src="<?php echo base_url()."assets/" ?>bower_components/datatables.net/js/jquery.dataTables.min.js"></script>   
 
  <link href="<?php echo base_url()."assets/" ?>buttons.bootstrap.min.css"> 
  <script src="<?php echo base_url()."assets/" ?>dataTables.buttons.min.js"></script>
  <script src="<?php echo base_url()."assets/" ?>buttons.bootstrap.min.js"></script>
  <script src="<?php echo base_url()."assets/" ?>jszip.min.js"></script>
  <script src="<?php echo base_url()."assets/" ?>pdfmake.min.js"></script>
  <script src="<?php echo base_url()."assets/" ?>vfs_fonts.js"></script>
  <script src="<?php echo base_url()."assets/" ?>buttons.html5.min.js"></script>
  <script src="<?php echo base_url()."assets/" ?>buttons.print.min.js"></script> 
 
  <script src="<?php echo base_url()."assets/excel_export/js/" ?>FileSaver.js"></script>
  <script src="<?php echo base_url()."assets/excel_export/js/" ?>jhxlsx.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.4/xlsx.core.min.js"></script>  
  
<!-- Main content -->
    <section class="content">
 
        <div class="pesan"></div>
     
      <!-- Default box -->
      <div class="box box-success">
        <div class="box-header with-border"> 
            <button class="btn btn-xs btn-success pull-right waves-effect m-r-20 button_tambah" onclick="form_search()"> Tambah
            </button>    
            <button class="btn btn-xs btn-warning pull-right waves-effect m-r-20" id="btnExport" onclick="exportToExel()">
                Export semua data ke Excel
            </button>  
            <div class="button-datatable"></div>
        </div>

        <div class="box-body">
           <?php if ($this->session->flashdata('pesan')!="") {
               echo $this->session->flashdata('pesan');
            } ?> 

            <div id="dvjson"></div>  
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url"> 

            <table id="reminders" class="display nowrap" cellspacing="0" width="100%">  
                <thead>
                    <tr> 
                        <th>No</th>
                        <th></th>  
                        <th>KD Industri</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>   
                        <th>Email</th> 
                        <th>Status</th> 
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                    <tr> 
                        <th>No</th>
                        <th></th>  
                        <th>KD Industri</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telp</th>   
                        <th>Email</th> 
                        <th>Status</th>  
                    </tr>
                </tfoot>
            </table> 
        </div>
        <!-- /.box-body --> 
        <div class="box-footer"> </div>
 
      </div>
      <!-- /.box --> 
    </section>  


    <!-- Modal -->

    <div class="modal fade bd-example-modal-xl" tabindex="-1"  id="myModal"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Cari Obat</h4>
      </div>
      <div class="">
          <div class="modal-body form-group">
             
          </div>  
          <div class="container_table_search"> </div>  

      </div> 
    </div>
  </div>
</div><!-- 

    <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Cari Obat</h4>
                </div>
                <div class="">
                    <div class="modal-body form-group">
                       
                    </div>  
                    <div class="container_table_search"> </div>  

                </div> 
            </div>
        </div>
    </div> 
 -->
  <script src="<?php echo base_url()."assets/fgs/" ?>fgsIndustri.js"></script>  