 <?php if($act==""){ ?>  
	<!-- iCheck -->
    <link href="<?php echo URLBASE;?>/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?php echo URLBASE;?>/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URLBASE;?>/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URLBASE;?>/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URLBASE;?>/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo URLBASE;?>/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">  
	<link rel="stylesheet" type="text/css" href="<?php echo URLBASE;?>/vendors/select2/dist/css/select2.min.css">
<?php    
$query = $mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();

$settings=unserialize($dados['settings']);
$check1=unserialize($settings['importar']);


?>
    
 <?php }  ?>