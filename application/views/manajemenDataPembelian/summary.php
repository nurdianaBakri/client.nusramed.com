
 <style>
  #kd_su {
      display: block;
      font-weight: bold;
      margin-bottom: 1em;
  }
  #project-icon {
  float: left;
  height: 32px;
  width: 32px;
  }
  #project-description {
  margin: 0;
  padding: 0;
}

.ui-autocomplete-input {
  border: none; 
  font-size: 14px;
  width: 300px;
  height: 24px;
  margin-bottom: 5px;
  padding-top: 2px;
  border: 1px solid #DDD !important;
  padding-top: 0px !important;
  z-index: 1511;
  position: relative;
}
.ui-menu .ui-menu-item a {
  font-size: 12px;
}
.ui-autocomplete {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1510 !important;
  float: left;
  display: none;
  min-width: 160px;
  width: 160px;
  padding: 4px 0;
  margin: 2px 0 0 0;
  list-style: none;
  background-color: #ffffff;
  border-color: #ccc;
  border-color: rgba(0, 0, 0, 0.2);
  border-style: solid;
  border-width: 1px;
  -webkit-border-radius: 2px;
  -moz-border-radius: 2px;
  border-radius: 2px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  -webkit-background-clip: padding-box;
  -moz-background-clip: padding;
  background-clip: padding-box;
  *border-right-width: 2px;
  *border-bottom-width: 2px;
}
.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 18px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}
.ui-state-hover, .ui-state-active {
      color: #ffffff;
      text-decoration: none;
      background-color: #0088cc;
      border-radius: 0px;
      -webkit-border-radius: 0px;
      -moz-border-radius: 0px;
      background-image: none;
}
</style>

<form role="form" id="myForm2"> 
    <div class="form-group">
        <label for="exampleInputEmail1">Suplier</label>
        
        <input type="text" id="kd_suplier" class="form-control" style="width: 100%; " value="<?= $nama_suplier ?>">
        <input type="hidden" name="kd_suplier" id="theHiddenKdSuplier" value="<?= $data['kd_suplier'] ?>">
        <input type="hidden" name="id_master_detail" id="id_master_detail" value="<?= $data['id_master_detail'] ?>">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">No Faktur Penjualan</label>
        <input type="text" class="form-control" name="no_faktur"
        id="no_faktur" value="<?= $data['no_faktur'] ?>" >
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Metode Pembayaran</label>
        <select name="kode_pembayaran" class="form-control js-example-basic-single" required
            id="kode_pembayaran" onchange="togle_jatuh_tempo(this.value)">
            <?php
            foreach ($metode_pembayaran as $key) {
            ?>
            <option value="<?=$key['kd_pembayaran']?>">
                <?= $key['kd_pembayaran']." - ".$key['nama_metode_pembayaran']?>
            </option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <?php
            $date=date('Y-m-d')
        ?>
        <label for="exampleInputPassword1">Jatuh Tempo</label>
        <input type="date" class="form-control" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" min="<?= $date; ?>" readonly="readonly">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Tanggal Faktur</label>
        <input type="date" class="form-control" id="tgl_faktur"
        name="tgl_faktur" value="<?= $date; ?>">
    </div> 

</form>
    <button class="btn btn-success btn-block" onclick="update_master_detail_obat()"> Update </button>

    <script type="text/javascript">
      $(document).ready(function() {
        get_data_suplier();   
    }); 


        function update_master_detail_obat() { 
            var url_controller  = $('#url').val();
            var data = $('#myForm2').serialize();
            var url =url_controller+"update_master_detail_obat";
            console.log(url);
            $.ajax( {
                type: "POST",
                url: url,
                data:data,
                dataType: "Json",
                success: function( response ) { 
                    // console.log(response);
                    try{   
                        sukses3(response.status, response.pesan);

                        //reload this  page
                        table.ajax.reload(null,false); //reload datatable ajax 

                        $('#myModal').modal('hide'); // show bootstrap modal when complete loade

                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }
        
 function get_data_suplier() {  
    var url_controller = $('#url').val();
    var url = url_controller + "get_data_suplier";
    // console.log(url);
    $.ajax({
        type: "POST",
        url: url,
        data: {},
        dataType: "json",
        success: function(response) {
            // console.log(response);
            try { 
                 $( "#kd_suplier" ).autocomplete({
                   minLength: 3,
                   source: response,
                   focus: function( event, ui ) {
                      $( "#kd_suplier" ).val( ui.item.label );
                         return false;
                   },
                   select: function( event, ui ) {
                      $( "#kd_suplier" ).val( ui.item.label );
                      $( "#theHiddenKdSuplier" ).val( ui.item.value ); 
                      return false;
                   }
                }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
                   return $( "<li>" )
                   .append( "<a>" + item.label + "</a>" )
                   .appendTo( ul );
                };  
            } catch (e) {
                alert('Exception while request..');
            }
        }
    });
}

    </script>