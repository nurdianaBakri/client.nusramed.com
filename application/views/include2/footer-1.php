     
    <!-- /.content -->


  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">Nusra Med</a>.</strong> All rights
    reserved.
  </footer>
 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
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
          button: true,
          timer: 3000,
        });
      }
      else
      {
        swal({
          title: "Gagal !",
          text: pesan,
          icon: "error",
          button: true,
          timer: 3000,
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
<script src="<?= base_url()."assets" ?>/dist/js/demo.js"></script>
</body>
</html>
