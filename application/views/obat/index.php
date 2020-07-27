
<?php $this->load->view('include/header'); ?>


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

<div class="app-main">
  <?php $this->load->view('include/menu'); ?>
  <div class="app-main__outer">
    <div class="app-main__inner">

      <div class="app-page-title">
        <div class="page-title-wrapper">
          <div class="page-title-heading">
            <div class="page-title-icon">
              <i class="pe-7s-car icon-gradient bg-mean-fruit">
              </i>
            </div>
            <div>Produk
              <div class="page-title-subheading">Laporan singkat
              </div>
            </div>
          </div> 
        </div>
      </div>

       

            

<div class="row"> 
  <div class="col-lg-12"> 

        <?php if ($this->session->flashdata('pesan')!="") {
               echo $this->session->flashdata('pesan');
            } ?> 

            <div id="dvjson"></div>  
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url"> 

            <!--   <button class="btn btn-xs btn-success pull-right waves-effect m-r-20 button_tambah" onclick="form_search()"> Tambah
            </button>    
             <button class="btn btn-xs btn-warning pull-right waves-effect m-r-20 button_inport" onclick="import_form()">Inport
            </button>  
             <button class="btn btn-xs btn-success pull-right waves-effect m-r-20 button_form_genbar" onclick="form_generate_barcode_obat()">
                Generete Barcode Obat
            </button>  
            <button class="btn btn-xs btn-warning pull-right waves-effect m-r-20" id="btnExport" onclick="exportToExel()">
                Export semua data ke Excel
            </button>   -->

    <div class="main-card mb-12 card">
      <div class="card-body"><h5 class="card-title">Table responsive</h5> 

        <div class="table-responsive">
          <table id="reminders" class="nowrap" cellspacing="0">  
                <thead>
                    <tr> 
                        <th>No</th>
                        <th></th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th>Satuan</th> 
                        <th>Industri</th> 
                        <th>Kadungan</th> 
                        <th>Deskripsi</th>
                        <th>Jenis Terapi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

                <tfoot>
                    <tr> 
                        <th>No</th>
                        <th></th>
                        <th>Barcode</th>
                        <th>Nama</th>
                        <th>Satuan</th> 
                        <th>Industri</th> 
                        <th>Kadungan</th> 
                        <th>Deskripsi</th>
                        <th>Jenis Terapi</th>
                    </tr>
                </tfoot>
            </table>  

        </div>
      </div>
    </div>
  </div>  
      
      
    </div>
    <?php $this->load->view('include/footer'); ?>
  </div>  
</div>
  <script src="<?php echo base_url()."assets/fgs/" ?>fgsObat.js"></script>  

<script type="text/javascript" src="<?= base_url()."assets/assets_architecui/assets/" ?>scripts/main.js"></script>
</body>
</html>
 
 
 
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

