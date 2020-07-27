
 

	 <section class="content">
 
        <div class="pesan"></div>
     
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><?= $title; ?></h3> 
           <button class="btn btn-xs btn-success pull-right waves-effect m-r-20 button_tambah" onclick="form_search()"> Tambah
            </button>    
             <button class="btn btn-xs btn-warning pull-right waves-effect m-r-20 button_inport" onclick="import_form()">Inport
            </button>  
             <button class="btn btn-xs btn-success pull-right waves-effect m-r-20 button_form_genbar" onclick="form_generate_barcode_obat()">
                Generete Barcode Obat
            </button> 

            <button class="btn btn-xs btn-warning pull-right waves-effect m-r-20" id="btnExport" onclick="exportToExel()">
                Export Ke Excel
            </button>  
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
        <!-- /.box-body --> 
        <div class="box-footer"> </div>
 
      </div>
      <!-- /.box --> 
    </section>   

  <script src="<?php echo base_url()."assets/fgs/" ?>fgsObat.js"></script>  

   