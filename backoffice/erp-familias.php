<?php $ptitle="Famílias";	include("header.php"); 

if($act==""){
	
$urlws1=$prefs['erp_ws']."/familias.php?act_g=listaTipos_Familias&auth_userid=".$prefs['ws_token'];
$json1=file_get_contents("$urlws1", false, stream_context_create($arrContextOptions));
$obj1 = json_decode(remove_utf8_bom($json1), true);	


$famdefault=$prefs['tpfamilia'];

$htmlsel="";
foreach($obj1 as $k=>$v){
	if($k!=$prefs['tpmarcas']){
	$arrFam[$k]=$v['legenda'];
	$htmlsel.="<option value=\"".$k."\">".$v['legenda']."</option>";
	}
}
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
  </tbody>
</table>
<?php } if($act=="edit"){  



$prefs=settings_val(1);
$webserviceERP=$prefs['erp_ws'];
$webserviceTKN=$prefs['ws_token'];
$platform=$prefs['store'];
if(array_key_exists('catTreeTop',$prefs)){
  $catTreeTop=$prefs['catTreeTop'];  
} else { $catTreeTop=""; }

$urlws=$webserviceERP."/familias.php?act_g=view&num=$num&auth_userid=".$webserviceTKN;
$json=file_get_contents("$urlws", false, stream_context_create($arrContextOptions));
$obj = json_decode(remove_utf8_bom($json), true);



#######################################################   woocommerce  ##################################################
if($platform=="woocommerce"){
include_once DOCROOT.'/include/functions.woocommerce.php';	
  if(!isset($_SESSION['catlist'])){
    $categoriasweb=list_categorias();
    $_SESSION['catlist']=$categoriasweb;
  } else {
    $categoriasweb=$_SESSION['catlist'];     
  }
} 
#######################################################   PRESTASHOP  ##################################################
if($platform=="prestashop"){
include_once DOCROOT.'/include/functions.prestashop.php';	
$categoriasweb=list_categorias();
}
########################################################################################################################


//echo "<pre>"; print_r($categoriasweb); echo "</pre>";
/*
Array
        (
            [id] => 831
            [name] => 1 Ano
            [parent] => 1167
            [menu_order] => 0
        )
 */
// die();

$catwebID=getcategoriaStoreId($num);
$catTreeTop="";

$tree = buildTree($categoriasweb,$catTreeTop);
if($platform=="prestashop"){
$tree=$tree[0]['_children'][0]['_children'];
$catTreeTop=2;
}


//echo "<pre>"; print_r($tree); echo "</pre>";

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









<!-- Modal -->
<div class="modal fade" id="familiaModal" tabindex="-1" role="dialog" aria-labelledby="FamiliaModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="FamiliaModalLabel">Criar Categoria no website</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
       
       
  <form class="form-horizontal form-label-left" id="demo-form" data-parsley-validate>
    <input type="hidden" name="erp_familia" id="erp_familia" value="<?php echo $num;?>">
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">Família ERP</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input value="<?php echo $obj['strDescricao'];?>" name="Newerp_familiaD" id="Newerp_familiaD" type="text" class="form-control" placeholder="">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-4 col-sm-4 col-xs-12">Categoria-mãe</label>
      <div class="col-sm-4 col-xs-12">
        <select class="form-control" name="parentstore" id="parentstore">
          <option value="<?php echo $catTreeTop;?>">Escolha 1 opção</option>
          <?php printTree($tree,"",0,$catTreeTop); ?>
          
        </select>
      </div>
    </div>  
  </form>
       
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary btnCreateCateg">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php  } include("footer.php"); ?>
