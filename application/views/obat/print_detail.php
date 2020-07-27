
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Outlet</title> 
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
      <link rel="stylesheet" href="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/css/bootstrap.min.css"> 
      <script src="<?= base_url()."assets" ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> 
      <!-- DataTables --> 
      <link rel="stylesheet" href="<?php echo base_url()."assets/" ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css"> 
      <script src="<?php echo base_url()."assets/" ?>bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script> 
     
</head>
<!-- <body onload=""> -->
<body onload="window.print()"> 
	<img src="<?= base_url().'assets/barcode/'.$data['barcode'].'.jpg' ?>"  height="150" width="250"><br>
	<?php echo $data['nama'] ?>
</body>
</html>
