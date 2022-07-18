<?php include_once 'include/db_connect.php';	include_once 'include/functions.php';

sec_session_start();

$p=@end(explode('/',$_SERVER['PHP_SELF']));
$p=str_replace(".php","",$p);
 
if (login_check($mysqli) == true) {
	
if(is_file(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php') && !in_array($p,array('perfil')) ){
$mysqli->close();	
require(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php');
$mysqli = new mysqli(HOST2, USER2, PASSWORD2, DATABASE2);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) {
    header("Location: ../error.php?err=Unable to connect to MySQL");
    exit();
}
$prefs=settings_val(1);
}

if(isset($_GET['act'])){
$act=mysqli_real_escape_string($mysqli,$_GET['act']);
} else {$act="";}
if(isset($_GET['token'])){
$token=mysqli_real_escape_string($mysqli,$_GET['token']);
}
if(isset($_GET['num'])){ 
$num=mysqli_real_escape_string($mysqli,$_GET['num']);
} else { $num=""; }



$acttitle="";	
if($act=="edit"){
$acttitle="editar";	
}
if($act=="ad"){
$acttitle="adicionar";	
}


if(isset($prefs) && $prefs['store']=="woocommerce"){
include_once DOCROOT.'/include/functions.woocommerce.php';	
}
if(isset($prefs) && $prefs['store']=="prestashop"){
include_once DOCROOT.'/include/functions.prestashop.php';	
}




if(!isset($tpPag)) $tpPag=1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ERP-Sinc |<?php echo $ptitle;?></title>

<!-- Bootstrap -->
<link href="<?php echo URLBASE;?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="<?php echo URLBASE;?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<!-- NProgress -->
<link href="<?php echo URLBASE;?>/vendors/nprogress/nprogress.css" rel="stylesheet">
<!-- jQuery custom content scroller -->
<link href="<?php echo URLBASE;?>/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
<!-- PNotify -->
<link href="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="<?php echo URLBASE;?>/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
<?php 

	if (file_exists(DOCROOT."/include/$p.header.inc.php")) {	
    include(DOCROOT."/include/$p.header.inc.php"); 
	}
	?>

<!-- Custom Theme Style -->
<link href="<?php echo URLBASE;?>/build/css/custom.css" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
<div class="main_container">
<div class="col-md-3 left_col menu_fixed">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;"> <a href="<?php echo URLBASE;?>" class="site_title"><i class="fa fa-database"></i> <span>ERP SInc</span></a> </div>
    <div class="clearfix"></div>
    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <?php 
 if(is_file(DOCROOT."/images/user".$_SESSION['user_id'].".jpg")){
 echo "<img id=\"$_SESSION[token]\" class=\"img-circle profile_img\" src=\"".URLBASE."/images/user".$_SESSION['user_id'].".jpg\" alt=\"\">";	 	 
 } else {
echo "<img class=\"img-circle profile_img\" src=\"".URLBASE."/images/user.png\" alt=\"\">";	 
}
?>
      </div>
      <div class="profile_info"> <span>Bem-vindo,</span>
        <h2><?php echo $_SESSION['nome']; ?></h2>
      </div>
    </div>
    <!-- /menu profile quick info --> 
    
    <br />
    
    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Menu Geral</h3>
        <ul class="nav side-menu">
          <li><a href="<?php echo URLBASE; ?>"><i class="fa fa-home"></i> Home </a> </li>
          <li><a><i class="fa fa-edit"></i> ERP <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo URLBASE;?>/erp-artigos">Listagem de Artigos</a></li>
              <li><a href="<?php echo URLBASE;?>/erp-referencias">Listagem de Referências</a></li>    
              <li><a href="<?php echo URLBASE;?>/erp-artigos/scan/">Scanear Artigos</a></li>
              <li><a href="<?php echo URLBASE;?>/erp-familias">Listagem de Famílias</a></li>
            </ul>
          </li>
          <li><a><i class="fa fa-cog"></i> Utilitários <span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
              <li><a href="<?php echo URLBASE;?>/erp-sync/edit/1">Sincronizar Categorias</a></li>
              <li><a href="<?php echo URLBASE;?>/erp-sync-product/edit/1">Sincronizar Artigos</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    <!-- /sidebar menu --> 
    
    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small"> <a href="<?php echo URLBASE;?>/settings" data-toggle="tooltip" data-placement="top" title="Definições"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> </a> <a data-toggle="tooltip" data-placement="top" title="FullScreen"> <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span> </a> <a href="<?php echo URLBASE;?>/perfil" data-toggle="tooltip" data-placement="top" title="Perfil"> <span class="glyphicon glyphicon-user" aria-hidden="true"></span> </a> <a  data-toggle="tooltip" data-placement="top" title="Terminar sessão" href="<?php echo URLBASE;?>/logout" > <span class="glyphicon glyphicon-off" aria-hidden="true"></span> </a> </div>
    <!-- /menu footer buttons --> 
  </div>
</div>

<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
      <ul class="nav navbar-nav navbar-right">
        <li class=""> <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          <?php
 if(is_file(DOCROOT."/images/user".$_SESSION['user_id'].".jpg")){
 	echo "<img class=\"profile_img\" src=\"".URLBASE."/images/user".$_SESSION['user_id'].".jpg\" alt=\"\">";	 	 
 }  else {
	echo "<img class=\"profile_img\" src=\"".URLBASE."/images/user.png\" alt=\"\">"; 
 }
 ?>
          <span class="hidden-xs"><?php echo $_SESSION['nome']; ?></span><span class=" fa fa-angle-down"></span> </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="<?php echo URLBASE;?>/perfil"> <i class="fa fa-user pull-right"></i> O Meu Perfil</a></li>
            <li> <a href="<?php echo URLBASE;?>/settings"> <i class="fa fa-cog pull-right"></i> Definições</a> </li>
            <li><a href="<?php echo URLBASE;?>/logout"><i class="fa fa-sign-out pull-right"></i> Terminar Sessão</a></li>
          </ul>
        </li> <!--
        <li role="presentation" class="dropdown"> <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-envelope-o"></i> <span class="badge bg-green">6</span> </a>
          <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
            <li> <a> <span class="image"><img src="<?php echo URLBASE; ?>/images/user.png" alt="Profile Image" /></span> <span> <span>John Smith</span> <span class="time">3 mins ago</span> </span> <span class="message"> Film festivals used to be do-or-die moments for movie makers. They were where... </span> </a> </li>
            <li> <a> <span class="image"><img src="<?php echo URLBASE; ?>/images/user.png" alt="Profile Image" /></span> <span> <span>John Smith</span> <span class="time">3 mins ago</span> </span> <span class="message"> Film festivals used to be do-or-die moments for movie makers. They were where... </span> </a> </li>
            <li> <a> <span class="image"><img src="<?php echo URLBASE; ?>/images/user.png" alt="Profile Image" /></span> <span> <span>John Smith</span> <span class="time">3 mins ago</span> </span> <span class="message"> Film festivals used to be do-or-die moments for movie makers. They were where... </span> </a> </li>
            <li> <a> <span class="image"><img src="<?php echo URLBASE; ?>/images/user.png" alt="Profile Image" /></span> <span> <span>John Smith</span> <span class="time">3 mins ago</span> </span> <span class="message"> Film festivals used to be do-or-die moments for movie makers. They were where... </span> </a> </li>
            <li>
              <div class="text-center"> <a> <strong>See All Alerts</strong> <i class="fa fa-angle-right"></i> </a> </div>
            </li>
          </ul>
        </li> -->
      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->

<?php  if($tpPag==1){?>
<div class="right_col" role="main">
<div class="">
<div class="row">
<div class="col-md-12">
<div class="x_panel">
<div class="x_title">
  <h2><?php echo $ptitle;?> <small><?php echo $acttitle;?> </small></h2>
  <ul class="nav navbar-right panel_toolbox">
    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
  </ul>
  <div class="clearfix"></div>
</div>
<div class="x_content">
<?php } ?>
<!-- content starts here -->
<?php  } else { header("Location: ".URLBASE."/login"); }    ?>
