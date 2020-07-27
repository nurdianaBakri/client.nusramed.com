
    <link href="<?php echo base_url()."assets/" ?>select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url()."assets/" ?>select2.min.js"></script> 

    <!-- Main content -->
    <section class="content">
    
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Verifikasi Pembelian</h3>
            
        </div>
        <div class="box-body">
            <input type="hidden" name="url" value="<?php echo $url ?>" id="url">   
        
            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-success">
                        <div class="body">

                             <form role="form" id="myForm1">
                              <div class="box-body"> 
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Suplier</label>
                                    <select name="kd_suplier" class="form-control js-example-basic-single" required id="kd_suplier">
                                        <?php
                                          foreach ($suplier as $key) {
                                            ?>
                                            <option value="<?=$key['kd_suplier']?>"><?= $key['kd_suplier']." - ".$key['nama']?></option>
                                            <?php
                                          } 
                                        ?>
                                      </select>
                                </div> 

                                <div class="form-group">
                                  <label for="exampleInputPassword1">No Faktur Pembelian</label>
                                  <input type="text" class="form-control myForm1" name="no_faktur myForm1" id="no_faktur" >
                                </div>  

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                          <label for="exampleInputPassword1">Metode Pembayaran</label>
                                           <select name="kode_pembayaran" class="form-control js-example-basic-single" required id="kode_pembayaran" onchange="togle_jatuh_tempo(this.value)">
                                               <?php
                                                  foreach ($metode_pembayaran as $key) {
                                                    ?>
                                                    <option value="<?=$key['kd_pembayaran']?>"><?= $key['kd_pembayaran']." - ".$key['nama_metode_pembayaran']?></option>
                                                    <?php
                                                  } 
                                                ?> 
                                              </select>  
                                        </div> 
                                    </div>
                                    <div class="col-sm-6 tgl_jatuh_tempo">
                                        <div class="form-group">
                                            <?php
                                                $date=date('Y-m-d')
                                            ?>
                                            <label for="exampleInputPassword1">Jatuh Tempo</label>
                                            <input type="date" class="form-control myForm1" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" min="<?= $date; ?>">
                                        </div>
                                    </div>
                                </div> 
                              </div>
                              <!-- /.box-body -->
 
                            </form> 

                            <div class="box-footer">
                                <button id="input_stok" class="btn btn-success" onclick="input_stok()">Input Stok</button>
                              </div>
                         
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="panel panel-success">
                        <div class="body">
 
                            <div class="box-body"> 

                                <div class="form-group">
                                  <label for="exampleInputPassword1">Cari obat berdasarkan barcode</label>  
                                  <input type="text" name="barcode" id="barcode" class="form-control input-lg input_barcode" readonly="true"> 
                                </div> 

                                <div class="form-group">
                                  <label for="exampleInputPassword1">Cari Obat berdasarkan nama dan barcode</label>  
                                  <select name="barcode_nama" class="form-control js-example-basic-single input_nama_barang" id="barcode_nama" onchange="get_barang_by_barcode()">
                                   <?php
                                      foreach ($Obat as $key) {
                                        ?>
                                        <option value="<?=$key['barcode']?>"><?= $key['barcode']." - ".$key['nama']?></option>
                                        <?php
                                      } 
                                    ?> 
                                  </select>  
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Nama Obat</label> 
                                    <input type="text" name="nama_obat" id="nama_obat" class="form-control" readonly="true"> 
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputPassword1">Kandungan</label>
                                  <textarea name="kandungan" id="kandungan" class="form-control" readonly="true"></textarea> 
                                </div>

                            </div>  
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-success">
                        <form id="myForm2" action="<?= base_url()."transaksi/Pembelian/simpan_pembelian" ?>" method="post">
                            <table class="table table-bordered table-hover" id="tab_logic" style="font-size: 10px">
                                <thead>
                                  <tr >
                                    <th class="text-center"> # </th>
                                    <th class="text-center"> Barcode</th>
                                    <th class="text-center"> Nama Obat</th>
                                    <th class="text-center"> Satuan</th>
                                    <th class="text-center"> No Registrasi</th>
                                    <th class="text-center"> No Batch</th>
                                    <th class="text-center" style="width:10%;"> Tgl Exp</th>
                                    <th class="text-center"> Qty</th>
                                    <th class="text-center" style="width:10%;"> Harga Beli</th>
                                    <th class="text-center"> Diskon (%)</th>
                                    <th class="text-center" style="width:10%;"> PPN (Nilai)</th>
                                    <th class="text-center" style="width:10%;">Lokasi</th>
                                    <th class="text-center" > </th>
                                  </tr>
                                </thead>
                                <tbody id='element_table'> 
                                    
                                    <tr> 
                                        <td colspan="4"> 
                                            Diskon : <input type="number" name="jumlah_diskon" class="form-control jumlah_diskon" value="" readonly>
                                        </td>
                                        <td  > 
                                            PPN (%): <input type="number" name="ppn" class="form-control" value="10">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>   
                        </form>  
                        <button type="submit" id="submit" class="btn btn-success btn-block" onclick="simpan_pembelian()">Simpan Transaksi</button>
                    </div>
                </div>
            </div>  
                
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </section>


<script>   

    $(document).ready(function(){ 
        $('select').select2();     
        $('.tgl_jatuh_tempo').hide();  
        $(".form-group .input_nama_barang").attr("disabled","disabled");
        check_valid();
    });  

    function check_valid() {
        $('input').on('blur', function() {
            if ($("#myForm2").valid()) {
                $('#submit').prop('disabled', false);  
            } else {
                $('#submit').prop('disabled', 'disabled');
            }
        });
        
        $("#myForm2").validate({
            rules: {
                no_reg: {
                    required: true, 
                },
                 no_batch: {
                    required: true, 
                },
                 tgl_exp: {
                    required: true, 
                },
                 stok_awal: {
                    required: true, 
                },
                 lokasi: {
                    required: true, 
                },

            }
        });

    }

    function togle_jatuh_tempo(kode) {
        if (kode==100)
        {
            $('.tgl_jatuh_tempo').hide();
        }
        else
        {
            $('.tgl_jatuh_tempo').show();
        }
    } 

    $("#myForm1").submit(function(e) {
        e.preventDefault();
        input_stok();
    });

    function deleteRow(row)
    {
        var i=row.parentNode.parentNode.rowIndex;
        // if (confirm("Apakah anda yakin ingin menghapus data ini ?")) { 
            document.getElementById('tab_logic').deleteRow(i);
        // } 
        total_diskon(); 
        check_valid();
    } 

    function kalkulasiDiskonPerItem(x) {
      var rowIndex = x.rowIndex-1;
      console.log("Row index is: " + rowIndex); 

      var index_val = x.rowIndex-1; 

      var harga_beli = $(".harga_beli")[index_val].value;
      var harga_jual = $(".harga_jual")[index_val].value;
      var diskon = $(".diskon_beli")[index_val].value;
      var stok_awal = $(".stok_awal")[index_val].value;

      console.log(" diskon per barang " + harga_beli+" x "+diskon+"/"+100+" x "+ stok_awal + " = "+ ((harga_beli*diskon)/100)*stok_awal);

      var harga_setelah_diskon = $('.harga_setelah_diskon')[index_val];   
      harga_setelah_diskon.value = ((harga_beli*diskon)/100)*stok_awal;  

      total_diskon();
    } 

    function total_diskon() {
      var totalPoints = 0;  
      $('.harga_setelah_diskon').each(function(){
          totalPoints += parseInt($(this).val());
        }); 

        console.log(" diskon seluruh barang :  " + totalPoints); 
      $('.jumlah_diskon').val(totalPoints);
    } 

    function add_row(response) {
        // console.log(response); 
        if(/^[a-zA-Z0-9- ]*$/.test(response) == false) {
            // alert('Your search string contains illegal characters.');
            get_kandungan(); 
            // $(response).appendTo($("#element_table"));
            $(response).prependTo($("#element_table"));
        }
        else
        {
            alert(response); 
        } 
        check_valid(); 
    }
 
    $('#barcode').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){   
            get_barang_by_barcode(13);   
        } 
    });
  
     function simpan_pembelian() {   
         if ($("#myForm2").valid()==false)
         {
            return false;
         }
         else
         {
            if (confirm("Apakah anda yakin ingin menyimpan data ini ?")) { 

                var url_controller  = $('#url').val(); 
                    var url = "<?php echo base_url() ?>"+url_controller+"simpan_pembelian/";
                    // console.log(url);

                    var kd_suplier =$('#kd_suplier').val();
                    var no_faktur =$('#no_faktur').val();
                    var kode_pembayaran =$('#kode_pembayaran').val();
                    var tgl_jatuh_tempo =$('#tgl_jatuh_tempo').val();
                    $.ajax( {
                        type: "POST",
                        url: url,
                        data: $('#myForm2').serialize()+"&kd_suplier="+kd_suplier+"&no_faktur="+no_faktur+"&kode_pembayaran="+kode_pembayaran+"&tgl_jatuh_tempo="+tgl_jatuh_tempo,
                        dataType: "json",
                        success: function( response ) { 
                        try{     
                            //reload page
                            // location.reload(); 
                            sukses2(response.return, response.pesan);  
                            printTrxPembelian(no_faktur);
                        }catch(e) {  
                            alert('Exception while request..');
                        }  
                    }
                } );  
            }
        }        
     }

     function printTrxPembelian(no_faktur) {
        var url_controller  = $('#url').val(); 
        var url = "<?php echo base_url() ?>"+url_controller+"printTrxPembelian/"+no_faktur;
        // console.log(url); 
            $.ajax( {
                type: "POST",
                url: url,
                data:{},
                dataType: "html",
                success: function( response ) { 
                console.log(response);
                try{  
                    var w = window.open('about:blank');
                    w.document.open();
                    w.document.write(response);
                    w.document.close();
                }catch(e) {  
                    alert('Exception while request..');
                }  
            }
        } ); 
     }
                
 
    function check_same_barcode(barcode) {
        $('.barcode_table').each(function(){
            var text_value=$(this).val();  
            if(text_value==barcode)
            {
                return true;
            } 
            else
            {
                return false;
            }
       })
    }


    var barcode =""; 
    function get_barang_by_barcode(keycode=null) {

        console.log(keycode);  
        if (keycode==13)
        {
            barcode  = $('#barcode').val();
        }
        else
        {
            barcode  = $('#barcode_nama').val();
        }

        var url_controller  = $('#url').val();  
        var url = "<?php echo base_url() ?>"+url_controller+"get_barang_by_barcode/"+barcode;
        console.log(url);
        $.ajax( {
            type: "POST",
            url: url,
            data:{ },
            dataType: "html",
            success: function( response ) { 

                //check if table transaksi is empty or not  
                add_row(response);  
                $('#barcode').val('');
            }
        } );  
    }
  

    function hasValue(elem) {
        return $(elem).filter(function() { return $(this).val(); }).length > 0;
    }
  
     function get_kandungan() {  
        var url_controller  = $('#url').val(); 
        var url = "<?php echo base_url() ?>"+url_controller+"get_kandungan/";
        console.log(url ); 
        // var barcode = $('#barcode').val();
        console.log(barcode );

        $.ajax( {
            type: "POST",
            url: url,
            data: {
                barcode : barcode,
            },
            dataType: "json",
            success: function( response ) { 
                console.log(response);
                try{   
                    $('#kandungan').val(response.kandungan);
                    $('#nama_obat').val(response.nama);
                }catch(e) {  
                    alert('Exception while request..');
                }  
            }
        } );  
    }    

    function input_stok() {  
        //chhekc apakah form invalid 
        var kd_suplier = $('#kd_suplier').val(); 
        var no_faktur = $('#no_faktur').val(); 

        if ($('#kode_pembayaran').val()==100)
        {  
            if (kd_suplier==""  || no_faktur=="")
            {
                alert("Silahkan isi form dengan lengkap");
            }
            else
            {   
                $("#myForm1 select").attr("disabled","disabled");
                $("#myForm1 .myForm1").attr("readonly", true); 

                $(".form-group .input_nama_barang").removeAttr("disabled"); 
                $("#barcode").attr("readonly", false); 
                $("#kandungan").attr("readonly", true);   
            } 
        }
        else
        {
            var tgl_jatuh_tempo = $('#tgl_jatuh_tempo').val();  
            if (kd_suplier==""  || no_faktur=="" || tgl_jatuh_tempo=="")
            {
                alert("Silahkan isi form dengan lengkap");
            }
            else
            {   
                $("#myForm1 select").attr("disabled","disabled");
                $("#myForm1 .myForm1").attr("readonly", true);  

                $(".form-group .input_nama_barang").removeAttr("disabled");

                $("#barcode").attr("readonly", false); 
                $("#kandungan").attr("readonly", true);   
            } 
        }  
        document.getElementById("barcode").focus();  
            
    }    
 
    </script>
 
