 <script type="text/javascript">
    $(function () {
    $('.js-basic-example2').DataTable({
        responsive: true, 
        autoWidth : true, 
    });   
});
</script> 

<style type="text/css">
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

        padding: 5px !important; // currently 8px
    }
</style>
 
    <table class="table table-bordered table-striped table-hover js-basic-example2"  style="width:100%">
        <thead>
            <tr>
                <th></th>
                <th>No</th>
                <th>Barcode</th> 
                <th>Satuan</th>
                <th>Industri</th> 
                <th>Kadungan</th>
                <th>Deskripsi</th>
                <th>Jenis Terapi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            echo $data_hasil_search;
            foreach ($data as $key => $value) {
            ?>
            <tr>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Aksi
                        <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a  href="<?= base_url().'Obat/edit/'.$value['id_obat']?>">Detail
                            </a> </li>
                            <li>
                                <a  href="#" onclick="hapus('<?php echo $value['barcode'] ?>')">Hapus
                                </a>
                            </li>
                            <li><a  href="<?= base_url().'Obat/print/'.$value['id_obat']?>" target="_blank">Print
                            </a></li>
                        </ul>
                    </div>
                </td>
                <td><?=$value['id_obat'];?></td>
                <td><?=$value['barcode'] ." - ".$value['nama'];?></td> 
                <td>
                    <?=
                    $satuan = $this->Satuan_obat->get(array('kd_satuan' => $value['kd_satuan'] ))->row_array()['nm_satuan'];
                    ?>
                </td>
                <td>
                    <?=
                    $nama_industri = $this->Industri_model->getBy(array('kd_industri' => $value['kd_industri'] ))['nama'];
                    ?>
                </td> 
                <td><?=$value['kandungan'];?></td>
                <td><?=$value['deskripsi'];?></td>
                <td><?=$value['jenis_terapi'];?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table> 


       