<script type="text/javascript">
    $(function () {
    $('.js-basic-example').DataTable({
        responsive: false, 
         // paging:   false, 
        autoWidth : false, 
    });   
});
</script> 

<div class="table-responsive">
                       
<table class="table table-bordered table-hover js-basic-example" id="tab_logic" style="font-size: 10px">
        <thead>
           <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Age</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id='element_table'>

            <?php   
            $html="";
            for($count = 0; $count < count($data); $count++)
            {
              $html.= '<tr>';
              $html.= '<td class="table_data" data-row_id="'.$data[$count]['id_obat'].'" data-column_name="barcode" contenteditable>'.$data[$count]['barcode'].'</td>';

              $html.= '<td class="table_data" data-row_id="'.$data[$count]['id_obat'].'" data-column_name="nama">'.$data[$count]['nama'].'</td>';

              $html.= '<td class="table_data" data-row_id="'.$data[$count]['id_obat'].'" data-column_name="kd_satuan" contenteditable>'.$data[$count]['kd_satuan'].'</td>';

              $html.= '<td><button type="button" name="delete_btn" id="'.$data[$count]['id_obat'].'" class="btn btn-xs btn-danger btn_delete"><span class="glyphicon glyphicon-remove"></span></button></td></tr>';
            } 

            echo $html;

            ?>

        </tbody>
       
    </table>

</div>