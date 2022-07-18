<?php $ptitle="Grelhas/Atributos";	include("header.php"); 

if($act==""){
	
/*    
$query = $mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();

$settings=unserialize($dados['settings']); 
if(array_key_exists('grelhas',$settings)){
$listaGrelhas=unserialize($settings['grelhas']);  
} else { $listaGrelhas=array(); }
                    

$ListaAtributos=list_atributos();   

 */   
    
$key=atributos_termo_existe(3,"Vermelho2s");
    
    
echo "<pre>"; var_dump($key); echo "</pre>"; 
    
?>

<table id="table<?php echo $p;?>" class="table table-striped table-condensed table-bordered">
  <thead>
    <tr>
      <th></th>
      <th width="15%">Código</th>
      <th>Descrição</th>
      <th width="7%">Sinc</th>
      <th width="10%">Ação</th>
    </tr>
  </thead>
  <tbody>
<?php
    
 ?>     
      
  </tbody>
</table>
<?php } if($act=="edit"){  


?>
<div class="x_content"> <br />
  <form class="form-horizontal form-label-left" id="demo-form" data-parsley-validate>
    <input type="hidden" name="erp_familia" id="erp_familia" value="<?php echo $num;?>">
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">Família ERP</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input value="<?php echo $obj['strDescricao'];?>" name="erp_familiaD" id="erp_familiaD" type="text" class="form-control" placeholder="">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">Categoria E-commerce</label>
      <div class="col-md-3 col-sm-3 col-xs-12">
        <select class="form-control" name="store" id="store">
          <option value="">Escolha 1 opção</option>
          <?php printTree($tree,$catwebID,0,$catTreeTop); ?>
          <option value="custom">Criar categoria no website</option>
        </select>
      </div>
    </div>
    <div class="ln_solid"></div>
    <div class="form-group">
      <div class="col-md-9"> <a href="#" onClick="window.history.back();" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Voltar</a> </div>
      <div class="col-md-3 col-xs-12">
        <button id="<?php echo $num;?>" class="btn btn-primary btnunlink" type="reset"><i class="fa fa-unlink"></i> Apagar</button>
        <button type="submit" class="btn btn-success" id="submitbtn"><i class="fa fa-exchange"></i> Sincronizar</button>
      </div>
    </div>
    <input type="hidden" name="accaoP" id="accaoP" value="edit">
  </form>
</div>








<?php  } include("footer.php"); ?>
