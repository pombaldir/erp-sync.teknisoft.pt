<?php $ptitle="Dashboard"; $tpPag=2;	include("header.php"); 

$artigosImpo=countArtigosTotal(); 
$countFamiliasTotal=countFamiliasTotal();
$query = $mysqli->query("select maxprod from settings where idnum='1'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();


$percent=number_format((($artigosImpo/$dados['maxprod'])*100),0);



?>

<div class="right_col" role="main">
  <div class="">
    <div class="row">
      <div class="col-md-12">
        <div class="row tile_count">
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"> <span class="count_top"><i class="fa fa-exchange"></i> Artigos Importados</span>
            <div class="count"><?php echo $artigosImpo;?></div>
            <span class="count_bottom"><i class="green"><?php echo  $percent;?>% </i></span> </div>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"> <span class="count_top"><i class="fa fa-exclamation"></i> Máximo Produtos</span>
            <div class="count"><?php echo $dados['maxprod'];?></div>
            <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i></i> Upgrade Ilimitado</span> </div>
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"> <span class="count_top"><i class="fa fa-exchange"></i> Famílias Sinc.</span>
            <div class="count"><?php echo $countFamiliasTotal;?></div>
            <span class="count_bottom"><!--<i class="green"><i class="fa fa-sort-asc"></i>34% </i>--> </span> </div>
          <div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count"> <span class="count_top"><i class="fa fa-sign-in"></i> Último acesso</span>
            <div class="count">
              <h3><?php  echo $_SESSION['lastLoginD'];?></h3>
            </div>
            <span class="count_bottom"><i class="green"><i class="fa fa-clock-o"></i></i><?php echo $_SESSION['lastLoginH'];?> </span> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include("footer.php"); ?>