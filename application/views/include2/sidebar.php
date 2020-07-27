  <?php
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  ?> 

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title; ?></title> 
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> 

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/font-awesome/css/font-awesome.min.css"> 
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/Ionicons/css/ionicons.min.css"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/dist/css/AdminLTE.min.css">
  
  <link rel="stylesheet" href="<?= base_url()."assets" ?>/dist/css/skins/_all-skins.min.css">

   <!-- jQuery 3 -->
  <script src="<?= base_url()."assets" ?>/bower_components/jquery/dist/jquery.min.js"></script>
<!--   <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script> -->
  <!-- Bootstrap 3.3.7 -->

  <script src="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <!-- sweet alert -->  
  <script src="<?php echo base_url()."assets/sweetalert2/" ?>sweetalert2@9.js"></script>
  <script src="<?php echo base_url()."assets/sweetalert2/" ?>sweetalert2.min.js"></script>
  <!-- Optional: include a polyfill for ES6 Promises for IE11 -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/sweetalert2/" ?>sweetalert2.min.css"> 
  <script src="<?= base_url()."assets" ?>/sweetalert.min.js"></script> 

  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet"/> -->
 <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">

 
  <style type="text/css">
    .hijau{
      background-color: #32a852;
    }
     .merah{
      background-color: #b83542;
    }
     .kuning{
      background-color: #d3d930;
    }
  </style>
  
</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition skin-green sidebar-collapse sidebar-mini" 
      <?php    
        if (isset($_GET['return'])) {
         echo ' onload="sukses('.$_GET['return'].')" '; } ?>  
  >
<!-- Site wrapper --> 

<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?= base_url() ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>N</b>RM</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Nusra</b>Med</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
           
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?= base_url()."assets" ?>/data_image/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $this->session->userdata('username'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?= base_url()."assets" ?>/data_image/user.png" class="img-circle" alt="User Image">

                <p>
                  <?= $this->session->userdata('username'); ?>
                  <small><?= $this->session->userdata('email');; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                   
                  <div class="col-xs-4 text-center">
                    <a href="#"><?= $this->session->userdata('jabatan'); ?></a>
                  </div>
                   
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                
                <div class="pull-right">
                  <a href="<?= base_url()."Login/logout" ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
           
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?= base_url()."assets" ?>/data_image/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $this->session->userdata('username'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
         

        <li class="<?php if(strpos($actual_link, 'Home') !== false){  echo "active";} ?> "><a href="<?= base_url()."Home"; ?>"><i class="fa fa-home"></i> 
          <span>Home</span></a>
        </li>

        <li class="treeview">
          <a href="#">
            <i class="fa fa-asterisk"></i> <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           
            <li class="<?php if(strpos($actual_link, 'Obat') !== false || strpos($actual_link, 'master/Obat') !== false){  echo "active";} ?> "><a href="<?= base_url()."master/Obat"; ?>"><i class="fa fa-cubes"></i> 
              <span>Master Obat</span></a>
            </li>

            <li class="<?php if(strpos($actual_link, 'master/Industri') !== false){  echo "active";} ?> "><a href="<?= base_url()."master/Industri"; ?>"><i class="fa  fa-building-o"></i> 
              <span>Master Industri</span></a>
            </li>

            <li class="<?php if(strpos($actual_link, 'master/Suplier') !== false){  echo "active";} ?> "><a href="<?= base_url()."master/Suplier"; ?>"><i class="fa fa-inbox"></i> 
              <span>Master Suplier</span></a>
            </li> 

            <li class="<?php if(strpos($actual_link, 'master/Outlet') !== false){  echo "active";} ?> "><a href="<?= base_url()."master/Outlet"; ?>"><i class="fa fa-bank"></i> 
              <span>Master Outlet</span></a>
            </li> 

            <?php 
              $jabatan = $this->session->userdata('jabatan'); 
              if ($jabatan=="super_admin" || $jabatan=="it")
              { ?>
                <li class="treeview">
                  <a href="#">
                    <i class="fa fa-users"></i> <span>Manajement User</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu">
                    <li class="<?php if(strpos($actual_link, 'User') !== false){  echo "active";} ?> ">><a href="<?= base_url()."User" ?>"><i class="fa fa-circle-o"></i>List User</a></li> 
                  </ul>
                </li>  
              <?php } ?>
 
          </ul>
        </li> 

        <li class="treeview">
          <a href="#">
            <i class="fa fa-file"></i> <span>Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu"> 
             <?php  
              if ($jabatan=="akutan" || $jabatan=="super_admin" || $jabatan=="admin")
              { ?>
                <li class="<?php if(strpos($actual_link, 'data/SetHargaObat') !== false){  echo "active";} ?> "><a href="<?= base_url()."data/SetHargaObat" ?>"><i class="fa fa-circle-o"></i> Set Harga Obat</a></li>
              <?php } ?>  
           
            <li class="<?php if(strpos($actual_link, 'StokOpname') !== false || strpos($actual_link, 'data/StokOpname') !== false){  echo "active";} ?> "><a href="<?= base_url()."data/StokOpname"; ?>"><i class="fa fa-cubes"></i> 
              <span>Stok Opname</span></a>
            </li>

          </ul>
        </li> 

        <li class="treeview <?php if(strpos($actual_link, 'trxPembelian') !== false){  echo "active";} ?>">
          <a href="#">
            <i class="fa fa-share"></i> <span>Transaksi Pembelian</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(strpos($actual_link, 'trxPembelian/Pembelian') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPembelian/Pembelian" ?>"><i class="fa fa-circle-o"></i> Input Pembelian</a></li>
             <li class="<?php if(strpos($actual_link, 'trxPembelian/VerivikasiPembelian') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPembelian/VerivikasiPembelian" ?>"><i class="fa fa-circle-o"></i>Verifikasi Pembelian</a></li>
            
            <li class="<?php if(strpos($actual_link, 'trxPembelian/ManagemenDataPembelianObat') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPembelian/ManagemenDataPembelianObat" ?>"><i class="fa fa-circle-o"></i>Data Pembelian</a></li>

            <li class="<?php if(strpos($actual_link, 'return/ReturnPbf') !== false){  echo "active";} ?> "><a href="<?= base_url()."return/ReturnPbf" ?>"><i class="fa fa-circle-o"></i>Return Pembelian</a></li>

          </ul>
        </li>
              

         <li class="treeview <?php if(strpos($actual_link, 'trxPenjualan') !== false){  echo "active";} ?>">
          <a href="#">
            <i class="fa fa-share"></i> <span>Transaksi Penjualan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(strpos($actual_link, 'trxPenjualan/penjualan') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPenjualan/penjualan" ?>"><i class="fa fa-circle-o"></i> Kasir (Input penjualan)</a></li>
             <li class="<?php if(strpos($actual_link, 'trxPenjualan/VerivikasiPembelian') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPenjualan/VerivikasiPembelian" ?>"><i class="fa fa-circle-o"></i> Verifikasi Penjualan</a></li>
            
            <li class="<?php if(strpos($actual_link, 'trxPenjualan/Penjualan') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPenjualan/Penjualan" ?>"><i class="fa fa-circle-o"></i> Data Penjualan</a></li>

            <li class="<?php if(strpos($actual_link, 'trxPenjualan/PengambilanBarang') !== false){  echo "active";} ?> "><a href="<?= base_url()."trxPenjualan/PengambilanBarang" ?>"><i class="fa fa-circle-o"></i> Pengambilan <br>& Update Pengambilan<br> Barang</a></li>
          </ul>
        </li>

        <li class="treeview <?php if(strpos($actual_link, 'return') !== false){  echo "active";} ?>">
          <a href="#">
            <i class="fa fa-repeat"></i> <span>Return</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i> 
            </span>
          </a>
          <ul class="treeview-menu">

            <li class="<?php if(strpos($actual_link, 'return/ReturnPbf') !== false){  echo "active";} ?> "><a href="<?= base_url()."return/ReturnPbf" ?>"><i class="fa fa-circle-o"></i> Return Ke BPF</a></li>

             <li class="<?php if(strpos($actual_link, 'return/ReturnOutlet') !== false){  echo "active";} ?> "><a href="<?= base_url()."return/ReturnOutlet" ?>"><i class="fa fa-circle-o"></i>Return dari Outlet</a></li>  

          </ul>
        </li>

         <li class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart-o"></i> <span>Laporan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

             <li><a class="<?php if(strpos($actual_link, 'laporan/KartuStok') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/KartuStok" ?>"><i class="fa fa-circle-o"></i>Kartu Stok </a></li>

            <li><a class="<?php if(strpos($actual_link, 'laporan/FakturJualBeli') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/FakturJualBeli" ?>"><i class="fa fa-circle-o"></i> Faktur Pembelian <br>dan Penjualan</a></li>

             <li><a class="<?php if(strpos($actual_link, 'laporan/LogActivityUser') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/LogActivityUser" ?>"><i class="fa fa-circle-o"></i> Log Activity User</a></li>

            <li><a class="<?php if(strpos($actual_link, 'laporan/lapPembelian') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/lapPembelian" ?>"><i class="fa fa-circle-o"></i> Pembelian</a></li>
            <li><a class="<?php if(strpos($actual_link, 'laporan/lapPenjualan') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/lapPenjualan" ?>"><i class="fa fa-circle-o"></i> Penjualan</a></li>
            <li><a class="<?php if(strpos($actual_link, 'laporan/lapTransaksi') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/lapTransaksi" ?>"><i class="fa fa-circle-o"></i> Transaksi</a></li>
            <li><a class="<?php if(strpos($actual_link, 'laporan/lapLabarugi') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/lapLabarugi" ?>"><i class="fa fa-circle-o"></i> Laba Rugi</a></li> 

             <?php  
              if ($jabatan=="akutan")
              { ?>
                <li><a class="<?php if(strpos($actual_link, 'laporan/Keuangan') !== false){  echo "active";} ?> "><a href="<?= base_url()."laporan/Keuangan" ?>"><i class="fa fa-circle-o"></i> Keuangan</a></li>
              <?php } ?>  
          </ul>
        </li>
      
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $title; ?>
        <small><?= $SubFitur; ?></small>
      </h1>
      <ol class="breadcrumb">
        <?php 
        foreach ($breadcrumb as $value) { ?>
          <li class="<?= $value['status'] ?>"><a href="<?= $value['link']; ?>">
             <?= $value['fitur']; ?></a></li>
        <?php } ?> 
      </ol>
    </section>

      
    