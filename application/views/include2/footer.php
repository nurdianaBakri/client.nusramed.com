     
    <!-- /.content -->


  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?= date('Y') ?> <a href="https://nusramed.com">Nusra Med</a>.</strong> All rights
    reserved.
  </footer> 

  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- SlimScroll -->

<script> 
    function sukses(kode) {   
      console.log(kode);
      if (kode==1)
      {
        swal({
          title: "Success !",
          text: "Data Berhasil Di Simpan!",
          icon: "success",
          button: false,
          timer: 5000,
        });
      }
      else
      {
        swal({
          title: "Gagal !",
          text: "Data Gagal Di Simpan!",
          icon: "error",
          button: false,
          timer: 5000,
        });
      } 
    }

    function sukses2(kode, pesan) { 
      if (kode==1)
      {
        swal({
          title: "Success !",
          text: pesan,
          icon: "success",
          button: false,
          timer: 5000,
        });
      }
      else
      {
        swal({
          title: "Gagal !",
          text: pesan,
          icon: "error",
          button: false,
          timer: 5000,
        });
      } 
    }   
 
  </script>

<script src="<?= base_url()."assets" ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= base_url()."assets" ?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url()."assets" ?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes --> 

<!-- <script src="<?= base_url()."assets" ?>/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url()."assets" ?>/jquery-validation/additional-methods.min.js"></script> -->

<!-- InputMask -->
<!-- <script src="<?= base_url()."assets" ?>/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?= base_url()."assets" ?>/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?= base_url()."assets" ?>/plugins/input-mask/jquery.inputmask.extensions.js"></script> -->
<!-- date-range-picker -->
<!-- <script src="<?= base_url()."assets" ?>/bower_components/moment/min/moment.min.js"></script>
<script src="<?= base_url()."assets" ?>/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script> -->
<!-- bootstrap datepicker -->
<!-- <script src="<?= base_url()."assets" ?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script> -->

<script>
  /*$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Date picker
    $('#datepicker').datepicker({
      autoclose: true
    })

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_minimal-red'
    })
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    })

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    //Timepicker
    $('.timepicker').timepicker({
      showInputs: false
    })
  })*/
</script>

<script>

  function addCommas(nStr)
{
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}

function formatRupiah(angka) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? ',' : '';
        rupiah += separator + ribuan.join(',');
    }
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah ;
}
</script>
</body>
</html>
