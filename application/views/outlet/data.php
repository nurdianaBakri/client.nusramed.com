<script type="text/javascript"> 
    $(document).ready(function() {

    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [ 
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                 orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
    } );
} ); 
</script>  

<table id="example" class="display table table-bordered table-striped table-hover " style="width:100%">
    <thead>
        <tr>
            <th></th>  
            <th>KD</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. Telp</th>
            <th>NPWP</th>
            <th>Nama Pemilik</th>
            <th>No. Tel. Pemilik</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($data as $key => $value) {
        ?>
            <tr>  
                <td>
                   <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Aksi
                    <span class="caret"></span></button>
                    <ul class="dropdown-menu"> 
                      <li>
                        <a  href="#" onclick="detail(<?php echo "'".$value['id_outlet']."'"; ?>)">Edit
                        </a> 
                    </li>
                    <li>                         
                        <a  href="#" onclick="hapus(<?php echo "'".$value['id_outlet']."'"; ?>)">Hapus
                        </a> 
                    </li>
                    <li>
                         <a  target="_blank" href="<?php echo base_url()."Outlet/print/".$value['id_outlet'] ?>">Print
                        </a> 
                       </li> 
                    </ul>
                  </div> 
                </td> 

                <td><?=$value['id_outlet'];?></td>
                <td style="cursor: pointer;"><?=$value['nama'];?> 
                <td><?=$value['alamat'];?></td>
                <td><?=$value['no_telp'];?></td>
                <td><?=$value['npwp'];?></td>
                <td><?=$value['nm_pemilik'];?></td>
                <td><?=$value['no_telp_pemilik'];?></td> 
            </tr>
        <?php
            }
        ?>
    </tbody>
</table>     



<script type="text/javascript"> 
    function hapus(primary) { 
        var url_controller  = $('#url').val();    
        var url = "<?php echo base_url() ?>"+url_controller+'hapus/'+primary; 

        console.log(url); 
        if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
            $.ajax( {
                type: "POST",
                url: url,
                data: {},
                dataType: "json",
                success: function( response ) {   
                    console.log(response);
                    try{        
                        sukses2(response.return, response.pesan); 
                        reload_data(); 
                    }catch(e) {  
                        alert('Exception while request..');
                    }  
                }
            } );  
        }
        return false; 
    }   

    function detail_2(id_outlet) { 

        $('.button_tambah').hide();  
        $('.button_tambah_peg').show();  
        $('.title').html("<h2>Daftar pegawai "+id_outlet+"<h2>");  

        var url_controller  = $('#url').val(); 
        var url = "<?php echo base_url() ?>"+"Pegawai/index/"+id_outlet;
        console.log(url);
        $.ajax( {
            type: "POST",
            url: url,
            data: {},
            dataType: "html",
            success: function( response ) { 
                try{   
                    $('.form_container').html(response); 
                }catch(e) {  
                    alert('Exception while request..');
                }  
            }
        } );  
    }   


    function sukses2(kode, pesan) { 
      if (kode==1)
      {
        swal({
          title: "Success !",
          text: pesan,
          icon: "success",
          button: false,
          timer: 2000,
        });
      }
      else
      {
        swal({
          title: "Gagal !",
          text: pesan,
          icon: "error",
          button: false,
          timer: 2000,
        });
      } 
    }   
</script> 

 