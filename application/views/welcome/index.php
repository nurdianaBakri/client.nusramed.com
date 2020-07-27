<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Beranda | Nusra Medika</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()."assets/user_assets/AdminLTE/" ?>dist/css/AdminLTE.min.css">

  <link rel="stylesheet" href="<?= base_url()."assets/user_assets/AdminLTE/" ?>dist/css/skins/_all-skins.min.css">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<?php /*$this->load->view('welcome/style1');*/ ?>
<?php $this->load->view('welcome/style2'); ?>
</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-green layout-top-nav">
<div class="wrapper">

  <header class="main-header">
    <?php $this->load->view('include_user/nav'); ?>
  </header>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container"> 

      <!-- Main content -->
      <section class="content" style="padding-top: 50px;">
 
        <?php $this->load->view('welcome/slider'); ?>
          

        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><b>Terlaris</b></h3>
          </div>
          <div class="box-body">
            <?php $this->load->view('welcome/produk'); ?>
          </div>
          <!-- /.box-body -->
        </div>


        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Produk <b>Terlaris</b></h3>
          </div>
          <div class="box-body">
            <?php $this->load->view('welcome/contoh'); ?>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="container">
      <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.18
      </div>
      <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
      reserved.
    </div>
    <!-- /.container -->
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?= base_url()."assets/user_assets/AdminLTE/" ?>dist/js/demo.js"></script>
</body>
</html>
