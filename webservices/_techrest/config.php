<?php include('index.php');  
$urlPATH="config.php?auth_userid=".$_GET['auth_userid'];


switch ($tp) {  
	case "families":		   
		$titPsingular="família";
    $titPPlural=$titPsingular."s";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("families");
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_Familias",$ERPFIelds,array("strCodTpFamilia"=>$ERP_strCodTpFamilia,"ORDER"=>array("strDescricao"=>"ASC")));
  break; 
	case "subfamilies":		   
		$titPsingular="sub-família";
    $titPPlural=$titPsingular."s";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("subfamilies");
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_Familias",$ERPFIelds,array("strCodTpFamilia"=>$ERP_strCodTpSFamilia,"ORDER"=>array("strDescricao"=>"ASC")));
  break;   
	case "paymentsconditions":		   
		$titPsingular="condição de pagamento";
    $titPPlural="condições de pagamento";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("paymentsconditions");
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_CondPagamento",$ERPFIelds);
    break; 
  case "paymentsmethods":		   
      $titPsingular="método de pagamento";
      $titPPlural="método de pagamento";
      $txtDef="Sincronizar ".$titPPlural." com o ERP";  
      $wsData=TechAPI_Entities("paymentsmethods");
      $ERPFIelds=array('Id','strAbreviatura','strDescricao');
      $ERPData=ERP_Entities("Tbl_Gce_Tipos_MovPag",$ERPFIelds);
  break;     
  case "typesdocsclients":		   
      $titPsingular="tipo de documento de clientes";
      $titPPlural="tipos de documentos de clientes";
      $txtDef="Sincronizar ".$titPPlural." com o ERP";  
      $wsData=TechAPI_Entities("typesdocsclients","where active=1");
      $ERPFIelds=array('Id','strAbreviatura','strDescricao');
      $ERPData=ERP_Entities("Tbl_Tipos_Documentos",$ERPFIelds,array("bitDispVendas"=>1,"intTpEntidade"=>0,"ORDER"=>array("strDescricao"=>"ASC")));
  break;  
  case "typesdocsproviders":		   
    $titPsingular="tipo de documento de compra";
    $titPPlural="tipos de documentos de compra";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("typesdocsproviders","where active=1");
    $ERPFIelds=array('Id','strAbreviatura','strDescricao');
    $ERPData=ERP_Entities("Tbl_Tipos_Documentos",$ERPFIelds,array("bitDispCompras"=>1,"intTpEntidade"=>2,"ORDER"=>array("strDescricao"=>"ASC")));
  break;  
  case "typesdocsreceiptsclients":		   
    $titPsingular="tipo de documento de liquidação clientes";
    $titPPlural="tipos de documentos de liquidação clientes";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("typesdocsreceiptsclients","where active=1");
    $ERPFIelds=array('Id','strAbreviatura','strDescricao');
    $ERPData=ERP_Entities("Tbl_Tipos_Documentos",$ERPFIelds,array("bitDispLiquidacoes"=>1,"intTpEntidade"=>0,"ORDER"=>array("strDescricao"=>"ASC")));
  break; 
  case "typesdocsreceiptsproviders":		   
    $titPsingular="tipo de documento de liquidação a fornecedores";
    $titPPlural="tipos de documentos de liquidação fornecedores";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("typesdocsreceiptsproviders","where active=1");
    $ERPFIelds=array('Id','strAbreviatura','strDescricao');
    $ERPData=ERP_Entities("Tbl_Tipos_Documentos",$ERPFIelds,array("bitDispLiquidacoes"=>1,"intTpEntidade"=>2,"ORDER"=>array("strDescricao"=>"ASC")));
  break; 

  case "orderscenters":		   
    $titPsingular="centro de custo";
    $titPPlural="centros de custo";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("orderscenters");
    $ERPFIelds=array('Id','strAbreviatura','strDescricao');
    $ERPData=ERP_Entities("Tbl_Centros_Custo",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break;   
  
  case "warehouses":		   
    $titPsingular="armazém";
    $titPPlural="armazéns";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("warehouses");
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_Armazens",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break; 
  case "vatstaxs":		   
    $titPsingular="taxa de IVA";
    $titPPlural="taxas de IVA";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities("vatstaxs","where active=1");
    $ERPFIelds=array('Id','intCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Taxas_Iva",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break;  
  case "exceptionsreasons":		   
    $titPsingular="motivo de isenção IVA";
    $titPPlural="motivos de isenção IVA";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities($tp,"where active=1");
    $ERPFIelds=array('Id','strCodigo','strMotivoIsencao');
    $ERPData=ERP_Entities("Tbl_MotivosIsencaoIva",$ERPFIelds,array("ORDER"=>array("strMotivoIsencao"=>"ASC")));
  break;  
  case "employees":		   
    $titPsingular="funcionário";
    $titPPlural=$titPsingular."s";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities($tp,"where active=1","","ORDER BY name ASC");
    $ERPFIelds=array('Id','intCodigo','strNome');
    $ERPData=ERP_Entities("Tbl_Gce_Vendedores",$ERPFIelds,array("ORDER"=>array("strNome"=>"ASC")));

  break;  
  case "units":		   
    $titPsingular="unidade";
    $titPPlural=$titPsingular."s";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=TechAPI_Entities($tp);
    $ERPFIelds=array('Id','strAbreviatura','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_UnidadesMedida",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break;  
  case "inventoryTypes":		   
    $titPsingular="Tipo de Inventário";
    $titPPlural="Tipos de Inventário";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=array(array("id"=>"P","description"=>"Matérias primas, subsidiárias e de consumo"),array("id"=>"M","description"=>"Mercadorias"),array("id"=>"A","description"=>"Produtos acabados e de consumo"),array("id"=>"S","description"=>"Subprodutos, desperdícios e refugos"),array("id"=>"T","description"=>"Trabalhos em curso"),array("id"=>"B","description"=>"Ativos biológicos"));
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_Categorias",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break;  
  case "productTypes":		   
    $titPsingular="Tipo de produtos";
    $titPPlural="Tipos de produtos";
    $txtDef="Sincronizar ".$titPPlural." com o ERP";  
    $wsData=array(array("id"=>"P","description"=>"Produtos"),array("id"=>"S","description"=>"Serviços"),array("id"=>"O","description"=>"Outros"),array("id"=>"I","description"=>"Impostos"));
    $ERPFIelds=array('Id','strCodigo','strDescricao');
    $ERPData=ERP_Entities("Tbl_Gce_Tipos_Artigos",$ERPFIelds,array("ORDER"=>array("strDescricao"=>"ASC")));
  break;
  default:
  $titPPlural=$titPsingular=$txtDef=$titAct="";
  
  break;  

} 
 
switch ($act) {  
	case "param":		   
		$titAct="parametrização";
    break; 
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Configuração</title>


    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
   
    <!-- bootstrap-daterangepicker -->
    <link href="https://www.erpsinc.pt/backoffice/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="https://www.erpsinc.pt/backoffice/build/css/custom.min.css" rel="stylesheet">

    <!-- PNotify -->
<link href="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
<link href="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
<link href="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">


<meta name="robots" content="noindex">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo $urlPATH;?>" class="site_title"><i class="fa fa-paw"></i> <span>ERP-SINC</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
            
            <!-- <div class="profile_pic">
                <img src="/backoffice/build/images/img.jpg" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
              </div>-->
            </div>
            <!-- /menu profile quick info -->
            <br /><br />
            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>Geral</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home</a></li>
                  
                  <li class="<?php if($act=="param"){ echo "active";}?>"><a><i class="fa fa-refresh"></i> Sincronização <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" <?php if($act=="param"){ echo "style=\"display: block;\"";}  ?>>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=families">Famílias</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=subfamilies">Sub-Famílias</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=paymentsconditions">Condições de Pagamento</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=paymentsmethods">Métodos de Pagamento</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=typesdocsclients">Tipos de Docs Venda</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=typesdocsproviders">Tipos de Docs Compra</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=typesdocsreceiptsclients">Docs Liq. a Clientes</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=typesdocsreceiptsproviders">Docs Liq. a Fornecedores</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=warehouses">Armazéns</a></li> 
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=vatstaxs">Taxas de IVA</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=exceptionsreasons">Motivos de Isenção IVA</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=employees">Funcionários</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=units">Unidades de Medida</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=inventoryTypes">Categorias de artigos</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=param&tp=productTypes">Tipos de artigos</a></li>
                              
                      
                    </ul>
                  </li>

                  <li class="<?php if($act=="syncParam"){ echo "active";}?>"><a><i class="fa fa-cog"></i> Preferências <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" <?php if($act=="syncParam"){ echo "style=\"display: block;\"";}  ?>>
                      <li><a href="<?php echo $urlPATH;?>&act=syncParam">Parâmetros Gerais</a></li>
                      <!--<li><a href="<?php echo $urlPATH;?>&act=utils">Utilitários</a></li> -->
                      </ul>
                  </li>
                  
                  <li class="<?php if($act=="erpproducts"){ echo "active";}?>"><a><i class="fa fa-bank"></i> ERP <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" <?php if($act=="erpproducts"){ echo "style=\"display: block;\"";}  ?>>
                      <li><a href="<?php echo $urlPATH;?>&act=erpproducts">Listagem de Artigos</a></li>
                      <li><a href="<?php echo $urlPATH;?>&act=erpsalesdocs">Listagem de Documentos</a></li>
                      </ul>
                  </li>
                  
                  <li class="<?php if($act=="logs"){ echo "active";}?>"><a><i class="fa fa-filter"></i> Logs <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu" <?php if($act=="logs"){ echo "style=\"display: block;\"";}  ?>>
                      <li><a href="<?php echo $urlPATH;?>&act=logs">Logs de sincronização</a></li>
                      </ul>
                  </li>

                  <!--
                  <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="general_elements.html">General Elements</a></li>
                      <li><a href="media_gallery.html">Media Gallery</a></li>
                      <li><a href="typography.html">Typography</a></li>
                      <li><a href="icons.html">Icons</a></li>
                      <li><a href="glyphicons.html">Glyphicons</a></li>
                      <li><a href="widgets.html">Widgets</a></li>
                      <li><a href="invoice.html">Invoice</a></li>
                      <li><a href="inbox.html">Inbox</a></li>
                      <li><a href="calendar.html">Calendar</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="tables.html">Tables</a></li>
                      <li><a href="tables_dynamic.html">Table Dynamic</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="chartjs.html">Chart JS</a></li>
                      <li><a href="chartjs2.html">Chart JS2</a></li>
                      <li><a href="morisjs.html">Moris JS</a></li>
                      <li><a href="echarts.html">ECharts</a></li>
                      <li><a href="other_charts.html">Other Charts</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-clone"></i>Layouts <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="fixed_sidebar.html">Fixed Sidebar</a></li>
                      <li><a href="fixed_footer.html">Fixed Footer</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
              <div class="menu_section">
                <h3>Live On</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="e_commerce.html">E-commerce</a></li>
                      <li><a href="projects.html">Projects</a></li>
                      <li><a href="project_detail.html">Project Detail</a></li>
                      <li><a href="contacts.html">Contacts</a></li>
                      <li><a href="profile.html">Profile</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="page_403.html">403 Error</a></li>
                      <li><a href="page_404.html">404 Error</a></li>
                      <li><a href="page_500.html">500 Error</a></li>
                      <li><a href="plain_page.html">Plain Page</a></li>
                      <li><a href="login.html">Login Page</a></li>
                      <li><a href="pricing_tables.html">Pricing Tables</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="#level1_1">Level One</a>
                        <li><a>Level One<span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                            <li class="sub_menu"><a href="level2.html">Level Two</a>
                            </li>
                            <li><a href="#level2_1">Level Two</a>
                            </li>
                            <li><a href="#level2_2">Level Two</a>
                            </li>
                          </ul>
                        </li>
                        <li><a href="#level1_2">Level One</a>
                        </li>
                    </ul>
                  </li>                  
                  <li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>
                </ul>-->
              </div>

            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
              <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
              <li class="">
          <select name="loja" id="loja" class="form-control">  
            <?php
            $lojas=ERP_Entities('USR_sync_config',array('shop_number'),array("sync"=>true));
            foreach($lojas as $v)
            {
              echo "<option value=\"".$v['shop_number']."\""; if($TECHAPI_shop_number==$v['shop_number']){  echo " selected"; }
              echo ">Loja ".$v['shop_number']."</option>"; 
            }
            ?>
          </select>
            </li>
            </ul>
<!--
              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="/images/img.jpg" alt="">John Doe
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul> -->
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
         
        <?php if($act==""){ ?>
        <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Users</span>
              <div class="count">2500</div>
              <span class="count_bottom"><i class="green">4% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> Average Time</span>
              <div class="count">123.50</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Males</span>
              <div class="count green">2,500</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Females</span>
              <div class="count">4,567</div>
              <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>12% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
              <div class="count">2,315</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Total Connections</span>
              <div class="count">7,325</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>
            </div>
          </div>
          <!-- /top tiles -->
          <?php } ?>
         
          


          <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2><?php echo ucfirst($titPPlural);?> <small><?php echo ucfirst($titAct);?></small></h2>
                      <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a> </li>
                      </ul>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <div class="dashboard-widget-content">
                        <div class="col-sm-12 hidden-small">
                          <h2 class="line_30"><?php echo $txtDef;?></h2>

<?php if($act=="param"){ 
$arrParamsSync=array('typesdocsclients','typesdocsproviders');  
/*
echo "<pre>";
print_r(USR_sync_Param("typesdocsclients"));
echo "</pre>";
*/
?>

<div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action" id="tableConfigParam">
                        <thead>
                          <tr class="headings">      

    <?php if($tp=="families" || $tp=="subfamilies" || $tp=="paymentsconditions" || $tp=="typesdocsclients" || $tp=="typesdocsproviders" || $tp=="typesdocsreceiptsclients" || $tp=="typesdocsreceiptsproviders" || $tp=="vatstaxs" || $tp=="exceptionsreasons" || $tp=="employees" || $tp=="units" || $tp=="paymentsmethods" || $tp=="warehouses" || $tp=="inventoryTypes" || $tp=="productTypes"){
   
   if(in_array($tp,$arrParamsSync)){ echo '<th>Sinc</th>'; } 
   
   echo'<th class="column-title" style="display: table-cell;">Código </th>
    <th class="column-title" style="display: table-cell;">Descrição </th>
    <th class="column-title" style="display: table-cell;">Eticadata </th>
    </tr></thead><tbody>';

    $i=0;
    if(is_array($wsData)&& sizeof($wsData)>0){
foreach($wsData as $val){ 

    $valorFieldTechS=$val['id']; 

    if($tp=="employees"){
      $descriFieldTechS=$val['name']; 
    } else {
      $descriFieldTechS=$val['description']; 
    }

    if(in_array($tp,$arrParamsSync) && USR_sync_Val($tp,$valorFieldTechS,'sync')==1){
    $valsel1=" selected";
    $valsel2=" checked";
    } else { $valsel1=$valsel2=""; }

    echo '<tr class="pointer'.$valsel1.'" id="row_'.$valorFieldTechS.'">';
    if(in_array($tp,$arrParamsSync)){
    echo '<td class="a-center"><input type="checkbox" name="webprodts" class="flat" id="webprodts_'.$valorFieldTechS.'" value="'.$valorFieldTechS.'" '.$valsel2.'></td>';
    }
    echo '<td class=" ">'.$valorFieldTechS.'</td>';
    echo '<td class="">'.$descriFieldTechS.'</td>';
    echo '<td class=" last">'.selOptionERP($ERPData,"erpOption",$i,$valorFieldTechS,ERP_Val($tp,$valorFieldTechS),$ERPFIelds[2],$ERPFIelds[1]).'</td>';
    $i++;
} 
echo '</tr></tbody></table></div>';
  }

/*
   echo "<pre>";
   print_r($ERPData);
   echo "</pre>";
*/

    }  ?>




<?php } if($act=="logs"){ 
  
//$condition=['LIMIT' => 10]; 

//$logsD=ERP_Entities("USR_sync_log",array('tipo','act','entity','msg','recnum','dta'),$condition);  
  
//print_r($logsD);
?>

<div class="table-responsive">
                      <table id="table_logs" class="table table-striped jambo_table">
                        <thead>
                          <tr class="headings">
                            <th></th><th>Data</th><th>Entidade</th><th>Ação</th><th>Mensagem</th>
                          </tr>
                        </thead><tbody>

<tr><td></td><td></td><td></td><td></td><td></td></tr>
</tbody></table>



<?php } if($act=="erpproducts"){  ?>
  
  <div class="table-responsive">
                        <table id="table_erpproducts" class="table table-striped jambo_table">
                          <thead>
                            <tr class="headings">
                              <th></th><th>Código</th><th>Descrição</th><th>Criação</th><th>Modificação</th><th>Unidade</th>
                            </tr>
                          </thead><tbody></tbody></table>
  
  

                          <?php } if($act=="erpsalesdocs"){  ?>
  
  <div class="table-responsive">
                        <table id="table_erpsalesdocs" class="table table-striped jambo_table">
                          <thead>
                            <tr class="headings">
                              <th></th><th>Tp Doc</th><th>Nº Doc</th><th>Data Doc</th><th>Cliente</th><th>NIF</th>
                            </tr>
                          </thead><tbody></tbody></table>
  
  


<?php } if($act=="syncParam"){ 
  
 
  $ERPfam=ERP_Entities("Tbl_Gce_Tipos_Familias",array('strCodigo','strDescricao'));  
  $ERPszo=ERP_Entities("Tbl_Subzonas",array('strAbreviatura','strDescricao'));  
  $ERPsecc=ERP_Entities("Tbl_Gce_Seccoes",array('strCodigo','strDescricao'));  


  $Tech_Lojas=TechAPI_Entities("shops");
 //print_r($Tech_Lojas);

  ?>


<form class="form-horizontal form-label-left" id="erp-settings" data-parsley-validate>
    <div class="" role="tabpanel" data-example-id="togglable-tabs">
      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content1" id="tab-1" role="tab" data-toggle="tab" aria-expanded="true">Preferências</a> </li>
<?php foreach($Tech_Lojas as $kLoja=>$vLoja){ ?>
  <li role="presentation"><a href="#tab_<?php echo $kLoja;?>" id="tab-<?php echo $kLoja;?>" role="tab" data-toggle="tab" aria-expanded="false"><?php echo $vLoja['id'];?></a> </li>
<?php } ?>

      </ul>
      <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="tab-1">
          <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Nível de Famílias</label>
            <div class="col-md-3 col-xs-12"><select name="param[nivFamilia]" id="param_nivFamilia" class="form-control">
            <?php foreach($ERPfam as $v1){ echo "<option value=\"".$v1['strCodigo']."\""; if($ERP_strCodTpFamilia==$v1['strCodigo']) echo " selected"; echo ">".$v1['strDescricao']."</option>"; } ?>
            </select></div>
            
            <label class="control-label col-sm-2 col-xs-12">Nível de Sub-Famílias</label>
            <div class="col-md-3 col-xs-12"><select name="param[nivSFamilia]" id="param_nivSFamilia" class="form-control">
            <?php foreach($ERPfam as $v1){ echo "<option value=\"".$v1['strCodigo']."\""; if($ERP_strCodTpSFamilia==$v1['strCodigo']) echo " selected"; echo ">".$v1['strDescricao']."</option>"; } ?>
            </select></div>

          </div>


          <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Criação auto Famílias</label>
            <div class="col-md-3 col-xs-12"><select name="param[criaAutoFamilia]" id="param_criaAutoFamilia" class="form-control">
            <?php echo "<option value=\"0\""; if($ERP_OPT_criaAutoFamilia==0) echo " selected"; echo ">Não</option>";  ?>
            <?php echo "<option value=\"1\""; if($ERP_OPT_criaAutoFamilia==1) echo " selected"; echo ">Sim</option>";  ?>
            </select></div>

            <label class="control-label col-sm-2 col-xs-12">Criação auto Sub-Famílias</label>
            <div class="col-md-3 col-xs-12"><select name="param[criaAutoSFamilia]" id="param_criaAutoSFamilia" class="form-control">
            <?php echo "<option value=\"0\""; if($ERP_OPT_criaAutoSFamilia==0) echo " selected"; echo ">Não</option>";  ?>
            <?php echo "<option value=\"1\""; if($ERP_OPT_criaAutoSFamilia==1) echo " selected"; echo ">Sim</option>";  ?>
            </select></div>
          </div>

          <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Subzona predefinida</label>
            <div class="col-md-3 col-xs-12"><select name="param[codSubZona]" id="param_codSubZona" class="form-control">
            <?php foreach($ERPszo as $v2){ echo "<option value=\"".$v2['strAbreviatura']."\""; if($ERP_strCodSubZona==$v2['strAbreviatura']) echo " selected"; echo ">".$v2['strDescricao']."</option>"; } ?>
            </select></div>

            <label class="control-label col-sm-2 col-xs-12">Data Inicial Sincronização</label>
            <div class="col-md-3 col-xs-12"><input type="date" name="param[dataIniSync]" id="param_dataIniSync" class="form-control" value="<?php echo date('Y-m-d',strtotime($ERP_dataIniSync)) ?>"></div>

          </div>

          <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Código dos Artigos</label>
            <div class="col-md-3 col-xs-12"><select name="param[codArtigos]" id="param_codartigos" class="form-control">
            <option value="product_number" <?php if($ERP_scodArtigos=="product_number"){ echo "selected";} ?>>product_number</option>
            <option value="main_bar_code" <?php if($ERP_scodArtigos=="main_bar_code"){ echo "selected";} ?>>main_bar_code</option>
            </select></div>

          </div>

         

        </div>

        <?php foreach($Tech_Lojas as $kLoja=>$vLoja){ 
        
          $ERP_strCodseccao=$ERP_ConfLojas[''.$vLoja['id'].'']['seccao'];  
          
        ?>
        <div role="tabpanel" class="tab-pane fade" id="tab_<?php echo $kLoja;?>" aria-labelledby="tab-<?php echo $kLoja;?>">

      <div class="pull-right">
        <div class="col-md-12 col-xs-12">
        <strong>Empresa:</strong> <?php echo $vLoja['company_name'];?> <strong>NIF:</strong> <?php echo $vLoja['company_vat_number'];?> <strong>Loja Nº:</strong> <?php echo $vLoja['shop_number'];?> <strong>Software:</strong> <?php echo $vLoja['software_name'];?> <strong>Versão BD:</strong> <?php echo $vLoja['db_version'];?>
        </div> 
      </div>

          <br><br>
        <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Sincronizar Loja</label>
            <div class="col-md-1 col-xs-12"><select name="param[loja][<?php echo $vLoja['id'];?>][sync]" id="loja_<?php echo $vLoja['id'];?>_sync" class="form-control">
            <?php echo "<option value=\"0\""; if($ERP_ConfLojas[''.$vLoja['id'].'']['sync']==0) echo " selected"; echo ">Não</option>";  ?>
            <?php echo "<option value=\"1\""; if($ERP_ConfLojas[''.$vLoja['id'].'']['sync']==1) echo " selected"; echo ">Sim</option>";  ?>
            </select></div>

            <input type="hidden" name="param[loja][<?php echo $vLoja['id'];?>][shop_number]" value="<?php echo $vLoja['shop_number'];?>">

            <label class="control-label col-sm-2 col-xs-12">Secção ERP</label>
            <div class="col-md-2 col-xs-12"><select name="param[loja][<?php echo $vLoja['id'];?>][seccao]" id="loja_<?php echo $vLoja['id'];?>_seccao" class="form-control">
            <?php foreach($ERPsecc as $v1){ echo "<option value=\"".$v1['strCodigo']."\""; if($ERP_strCodseccao==$v1['strCodigo']) echo " selected"; echo ">".$v1['strDescricao']."</option>"; } ?>
            </select></div>

        </div>



        </div>
        <?php  } ?>
        <div class="ln_solid"></div>

      <div class="form-group">
        <div class="col-md-9"> <a href="#" onClick="window.history.back();" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Voltar</a></div>
        <div class="col-md-3 col-xs-12">
          <button class="btn btn-primary" type="reset"><i class="fa fa-trash"></i> Cancelar</button>
          <button type="submit" class="btn btn-success" id="submitbtn"><i class="fa fa-save"></i> Guardar</button>
        </div>
      </div>


          </div>
          </div>
          </div>


 <input type="hidden" name="act" id="act" value="updatesettings">         
 <input type="hidden" name="auth_userid" id="auth_userid" value="<?php echo $tokenAPI;?>">         

 

</form>



<?php } if($act=="utils"){
  $entSync=getEntitiesTosync();
  
  ?>
  
  <form class="form-horizontal form-label-left" id="form-typesdocsclients" data-parsley-validate>

  <div class="form-group">
  <div class="col-md-2 col-xs-12">
  <button class="btn btn-danger" type="submit" id="typesdocsclients"><i class="fa fa-trash"></i> Reiniciar Docs Venda</button>
   </div> 
  
<?php foreach($entSync as $val){
 echo ' <div class="col-md-1"><input name="entity[]" class="form-control2" type="checkbox" value="'.$val['erpval'].'"> <label>'.$val['erpval'].'</label></div>';
}
?>
   </div>



  <div class="form-group">
  <div class="col-md-2 col-xs-12">
  <button class="btn btn-danger btnGeral" name="entity" id="typesdocsclients" type="submit" value="families"><i class="fa fa-trash"></i> Reiniciar Famílias</button>
   </div> 
   <div class="col-md-2 col-xs-12">
  <button class="btn btn-danger btnGeral" name="entity" id="typesdocsclients" type="submit" value="subfamilies"><i class="fa fa-trash"></i> Reiniciar Sub-famílias</button>
   </div> 

   <div class="col-md-2 col-xs-12">
  <button class="btn btn-danger btnGeral" name="entity" id="typesdocsclients" type="submit" value="products"><i class="fa fa-trash"></i> Reiniciar Produtos</button>
   </div> 

   <div class="col-md-2 col-xs-12">
  <button class="btn btn-danger btnGeral" name="entity" id="typesdocsclients" type="submit" value="clients"><i class="fa fa-trash"></i> Reiniciar Clientes</button>
   </div>
</div> 
 


   </div>


<input type="hidden" name="act" id="act" value="resettypesdocsclients">         
<input type="hidden" name="auth_userid" id="auth_userid" value="<?php echo $tokenAPI;?>">         


<input type="hidden" name="hiddenEntity" id="hiddenEntity" value="">         




<?php // echo $ERP_dataIniSync; 

//print_r($ERP_ConfLojas); //
?>

<div class="form-group">
<br><br><br><br><br>
<div class="col-md-2"><a href="/sync.php?tp=documentsclients&act=all&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>" target="_blank" class="btn btn-success"><i class="fa fa-refresh"></i> Sincronizar Docs Venda</a></div>
<div class="col-md-2"><a href="/sync.php?tp=clients&act=all&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>" target="_blank" class="btn btn-success"><i class="fa fa-refresh"></i> Sincronizar Clientes</a></div>
<div class="col-md-2"><a href="/sync.php?tp=products&act=all&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>" target="_blank" class="btn btn-success"><i class="fa fa-refresh"></i> Sincronizar Produtos</a></div>
<div class="col-md-2"><a href="/sync.php?tp=robot&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>" target="_blank" class="btn btn-success"><i class="fa fa-refresh"></i> Sincronizar Hoje</a></div>
<div class="col-md-2"><a href="/sync.php?tp=documentsclients&act=date&date=<?php echo $ERP_dataIniSync;?>&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>" target="_blank" class="btn btn-success"><i class="fa fa-refresh"></i> Sincronizar à Data</a></div>
</div>

</form>

<?php } ?>






                        
                        </div>
                    </div></div></div></div></div></div></div>


        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
           by <a href="https://erpsinc.pt">ERP-SINC</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/skycons/skycons.js"></script>
   
    
    <!-- DateJS -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/DateJS/build/date.js"></script>
   
    <!-- bootstrap-daterangepicker -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/moment/min/moment.min.js"></script>
    <script src="https://www.erpsinc.pt/backoffice/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


      <!-- Datatables -->
    <script src="https://www.erpsinc.pt/backoffice/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="https://www.erpsinc.pt/backoffice/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  

	<!-- PNotify -->
  <script src="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.js"></script>
    <script src="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="https://www.erpsinc.pt/backoffice/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    

    <script src="https://www.erpsinc.pt/backoffice/vendors/bootbox/bootbox.min.js"></script>


    <!-- Custom Theme Scripts -->
    <script src="https://www.erpsinc.pt/backoffice/build/js/custom.min.js"></script>

<script>  
$(document).ready(function() {  
 
  <?php if($act=="param"){  ?>
          $('.configSelERP').change(function() {
            var techVal =$(this).attr("data-vtech");
                $.ajax({
                  type: "POST",
                  url: "utils.php",
                  data: {"auth_userid":"<?php echo $_GET['auth_userid'];?>", "act":"updateSync","tp":"<?php echo $_GET['tp'];?>","techval":techVal,"val":$(this).val()},
                  success: function(data, textStatus, jqXHR) {
                       
                      console.log(data); 


                  }, error: function(e) {

                  }
                });
               
            });	



$('input').on('ifChecked', function(event){
  var techVal = this.value;
  upDateSync('<?php echo $_GET['tp'];?>',techVal,1);
});
  

$('input').on('ifUnchecked', function(event){
  var techVal = this.value;
  upDateSync('<?php echo $_GET['tp'];?>',techVal,0);
});

function upDateSync(tp,techval,val){
  $.ajax({
    type: "POST",
    url: "utils.php",
    data: {"auth_userid":"<?php echo $_GET['auth_userid'];?>", "act":"updateSyncTax","tp":""+tp+"","techval":""+techval+"","val":""+val+""},
    success: function(data, textStatus, jqXHR) {  console.log(data);  }, 
    error: function(e) { }
  });
}




  <?php }  if($act=="syncParam" || $act=="utils"){  ?>

$( "form" ).on( "submit", function( event ) {
  event.preventDefault();
  
  $.ajax({
  type: "POST",
  url: "utils.php",
  data: $( this ).serialize(),
  dataType: "json",
  success: function(data){
    console.log(data); 
    $('#hiddenEntity').val('');  

      new PNotify({
            title: "Formulário submetido",
            type: ""+data.type+"",
            text: ""+data.message+"",
            nonblock: {
            nonblock: true
        },
        addclass: 'dark',
        styling: 'bootstrap3',
        hide: true,
        before_close: function(PNotify) { PNotify.queueRemove(); return false; }
      }); 
  },
 	error: function (request, status, error) {
    alert("Erro: "+request.responseText);
    console.log(error);
    $('#hiddenEntity').val('');  
  }
  });
});


<?php   if($act=="utils"){  ?>
$(".btn-danger").click(function(e){
    e.preventDefault();
    var idForm = this.id;   
    $('#hiddenEntity').val('');  
  
   var  hvalue=this.value;  
    if ($(this).hasClass("btnGeral")) {
      $('#hiddenEntity').val(hvalue);       
    } else { 
      $('#hiddenEntity').val(''); 
    }   


      if (confirm("Deseja prosseguir?")){
         $('form#form-'+idForm).submit();
      }


      
   });  

   



   <?php }  }  if($act=="logs"){  ?>
  
   var oTable = $('#table_logs').DataTable( {
			"aaSorting": [[ 0, "desc" ]],
			"bProcessing": true,
			"bServerSide": true,
			"aoColumnDefs": [ 
		 	{ "bVisible":  false, "bSearchable": false, "aTargets": [0]}
	 		],
			"sAjaxSource": "/utils.php", 
          "lengthMenu": [[20, 50, 100, 200, 4000], [20, 50, 100, 200, "Todos"]],
	        "bPaginate": true,
	        "bSort": true,
          "sDom": "<'row'<'dataTables_header clearfix'<'col-md-1'l><'col-xs-2 selFamilia'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
			"fnServerParams": function ( aoData ) {
		    	aoData.push({ "name": "auth_userid", "value": "<?php echo $tokenAPI;?>" },{ "name": "act", "value": "listLog" });
		    },
			"oLanguage": {
				"sInfo": "_START_ a _END_ de _TOTAL_ registos",
				"sLengthMenu": "_MENU_",
				"sInfoEmpty": "Não existem registos",	
				"sEmptyTable": "Não existem registos",	
				"sZeroRecords": "Não existem registos a exibir",
				"sSearch": "Pesquisar: ",
				"oPaginate": {
				  "sPrevious": "Anterior",
				  "sNext": "Seguinte",
				  "sFirst": "Início",
				  "sLast": "Última"
				},
				"sInfoFiltered": ""
			}
	    }); 
<?php } if($act=="erpproducts"){  ?>
    
  var oTable = $('#table_erpproducts').DataTable( {
     "aaSorting": [[ 0, "desc" ]],
     "bProcessing": true,  
     "bServerSide": true,
     "aoColumnDefs": [ 
      { "bVisible":  false, "bSearchable": false, "aTargets": [0]}
      ],
     "sAjaxSource": "/utils.php", 
         "lengthMenu": [[20, 50, 100, 200, 4000], [20, 50, 100, 200, "Todos"]],
         "bPaginate": true,
         "bSort": true,
         "sDom": "<'row'<'dataTables_header clearfix'<'col-md-1'l><'col-xs-2 table_htmlBtn'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
     "fnServerParams": function ( aoData ) {
         aoData.push({ "name": "auth_userid", "value": "<?php echo $tokenAPI;?>" },{ "name": "act", "value": "listErpProducts" });
       },
     "oLanguage": {
       "sInfo": "_START_ a _END_ de _TOTAL_ registos",
       "sLengthMenu": "_MENU_",
       "sInfoEmpty": "Não existem registos",	
       "sEmptyTable": "Não existem registos",	
       "sZeroRecords": "Não existem registos a exibir",
       "sSearch": "Pesquisar: ",
       "oPaginate": {
         "sPrevious": "Anterior",
         "sNext": "Seguinte",
         "sFirst": "Início",
         "sLast": "Última"
       },
       "sInfoFiltered": ""
     }
     }); 


     
var openModalIframe = function openModalIframe (url) {
  var dialog = bootbox.dialog({
    message: '<iframe src="' + url + '&rnd='+Math.floor(Math.random() * 10000)+'"  class="border-0 h-100 w-100" frameBorder="0" style="width:100%;height:100%;"></iframe>',
    size: 'lg'
  });

dialog.on("shown.bs.modal", function() {
   
});

  // Remove whitespace inside dialog which is provided by the iframe content.
  dialog.find('.modal-body').addClass('p-0');

  // Maximize usable screen area inside modal.
  dialog.find('.modal-content').addClass('h-100');
  dialog.find('.bootbox-body').addClass('h-100');

  // Hide the close button instead of using `closeButton: false` in the bootbox config,
  // so we have a way to close the modal from within the iframe.
  dialog.find('.bootbox-close-button').addClass('d-none');
};

$('.table_htmlBtn').html('<button id="btproductsync"  class="btn btn-md btn-info" ><i class="fa fa-cloud"></i> Sincronizar</button>');

$("#btproductsync").click(function(){ 
 openModalIframe('sync.php?tp=products&act=date&date=<?php echo date('Y-m-d');?>&auth_userid=<?php echo $tokenAPI;?>&browser=1&loja=<?php echo $TECHAPI_shop_number;?>');
});     


  <?php } if($act=="erpsalesdocs"){  ?>
   
   var oTable = $('#table_erpsalesdocs').DataTable( {
      "aaSorting": [[ 0, "desc" ]],
      "bProcessing": true,  
      "bServerSide": true,
      "aoColumnDefs": [ 
       { "bVisible":  false, "bSearchable": false, "aTargets": [0]}
       ],
      "sAjaxSource": "/utils.php", 
          "lengthMenu": [[20, 50, 100, 200, 4000], [20, 50, 100, 200, "Todos"]],
          "bPaginate": true,
          "bSort": true,
          "sDom": "<'row'<'dataTables_header clearfix'<'col-md-1'l><'col-xs-2 selFamilia'><'col-md-9'f>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
      "fnServerParams": function ( aoData ) {
          aoData.push({ "name": "auth_userid", "value": "<?php echo $tokenAPI;?>" },{ "name": "act", "value": "listErpSalesDocs" });
        },
      "oLanguage": {
        "sInfo": "_START_ a _END_ de _TOTAL_ registos",
        "sLengthMenu": "_MENU_",
        "sInfoEmpty": "Não existem registos",	
        "sEmptyTable": "Não existem registos",	
        "sZeroRecords": "Não existem registos a exibir",
        "sSearch": "Pesquisar: ",
        "oPaginate": {
          "sPrevious": "Anterior",
          "sNext": "Seguinte",
          "sFirst": "Início",
          "sLast": "Última"
        },
        "sInfoFiltered": ""
      }
      }); 
 

     <?php }  ?>

     $('#loja').change(function() {
          <?php $qstring=$_SERVER['QUERY_STRING']; $qstring=explode('&loja=',$qstring); ?>
              window.location = 'config.php?<?php echo $qstring[0];?>&loja='+this.value; // redirect
          return false;
      });
            
});
</script> 
	
  </body>
</html>
