
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
  <script src="<?php echo base_url()."assets/excel_export/js/" ?>xlsx.core.min.js"></script>  
  
<!-- Main content -->
    <section class="content">
 
        <div class="pesan"></div>
     
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?= $title; ?></h3> 
            <center> 
                <div class="button-datatable"></div>
            </center>
        </div>

        <div class="box-body">
           <?php if ($this->session->flashdata('pesan')!="") {
               echo $this->session->flashdata('pesan');
            } ?> 

            <div id="dvjson"></div>  
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url"> 

            <table id="reminders2" class="display nowrap" cellspacing="0" width="100%">  
            <thead>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Tanggal</th>
                <th>No Faktur</th>
                <th>Suplier</th> 
                <th>Kode Pembayaran</th> 
                <th>Tgl Jatuh Tempo</th> 
                <th>Jumlah Belanja</th>
                <th>Status Verifikasi</th>
            </tr>
        </thead>
        <tbody> 
        </tbody>
        <tfoot>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>Tanggal</th>
                <th>No Faktur</th>
                <th>Suplier</th> 
                <th>Kode Pembayaran</th> 
                <th>Tgl Jatuh Tempo</th> 
                <th>Jumlah Belanja</th>
                <th>Status Verifikasi</th> 
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
    <div class="modal fade" id="myModal"  role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detail Transaksi Pembelian</h4>
                </div>
                <div class="">
                    <div class="modal-body form-group">
                       
                    </div>  
                    <div class="container_table_search"> </div>  

                </div> 
            </div>
        </div>
    </div> 

  <script src="<?php echo base_url()."assets/fgs/" ?>fgsDataPembelian.js"></script>  