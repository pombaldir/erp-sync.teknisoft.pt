<?php 
$ptitle="Definições";
$tpPag=2;
include("header.php"); 
?>
<?php if($act==""){ 
$obj1=array();

$query = $mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();

$settings=unserialize($dados['settings']); 
$check1=unserialize($settings['importar']);
$platform=$settings['store'];

if($platform!=""){
include_once DOCROOT.'/include/functions.'.$platform.'.php';	
}

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

if($settings['ws_token']!=""){
$urlws1=$settings['erp_ws']."/familias.php?act_g=listaTipos_Familias&auth_userid=".$settings['ws_token'];
$json1=file_get_contents("$urlws1", false, stream_context_create($arrContextOptions));
$obj1 = json_decode(remove_utf8_bom($json1), true);

$urlws2=$settings['erp_ws']."/referencias.php?act_g=grelhasCab&auth_userid=".$settings['ws_token'];
$json2=file_get_contents($urlws2, false, stream_context_create($arrContextOptions));
$grelhas = json_decode(remove_utf8_bom($json2), true);
    


}

?>

<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content"> <br />
            <form class="form-horizontal form-label-left" id="form-settings" data-parsley-validate>
              <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#tab_content1" id="tab-1" role="tab" data-toggle="tab" aria-expanded="true">Definições Gerais</a> </li>
                  <li role="presentation" class=""><a href="#tab_content2" role="tab" id="tab-2" data-toggle="tab" aria-expanded="false">Importação</a> </li>
                  <li role="presentation" class=""><a href="#tab_content3" role="tab" id="tab-3" data-toggle="tab" aria-expanded="false">Avançadas</a> </li>
                </ul>
                <div id="myTabContent" class="tab-content">
                   <?php  // echo "<pre>"; print_r(list_atributos()); echo "</pre>"; ?>
                    
                    
                  <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="tab-1">
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Software ERP</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="erp" id="erp">
                          <option value="">Escolha 1 opção</option>
                          <option <?php if($settings['erp']=="eticadata") echo "selected";?> value="eticadata">Eticadata</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Software E-commerce</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="store" id="store">
                          <option value="">Escolha 1 opção</option>
                          <option <?php if($settings['store']=="magento") echo "selected";?> value="magento">Magento</option>
                          <option <?php if($settings['store']=="opencart") echo "selected";?> value="opencart">Opencart</option>
                          <option <?php if($settings['store']=="woocommerce") echo "selected";?> value="woocommerce">Woocommerce</option>
                          <option <?php if($settings['store']=="prestashop") echo "selected";?> value="prestashop">Prestashop</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Webservice ERP</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="<?php echo $settings['erp_ws'];?>" name="erp_ws" id="erp_ws" type="text" class="form-control" placeholder="Url do webservice">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Website E-commerce</label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input value="<?php echo $settings['store_url'];?>" name="store_url" id="store_url" type="text" class="form-control" placeholder="Url do website">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Token ERP WS</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <input value="<?php echo $settings['ws_token'];?>" name="ws_token" id="ws_token" type="text" class="form-control" placeholder="Chave de autenticação">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">API Website</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <input value="<?php echo $settings['ws_api'];?>" name="ws_api" id="ws_api" type="text" class="form-control" placeholder="API do seu website">
                      </div>
                      <label class="control-label col-md-1 col-sm-1 col-xs-12 lbws_secret">Secret</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <input value="<?php echo $settings['ws_secret'];?>" name="ws_secret" id="ws_secret" type="text" class="form-control" placeholder="Secret API do seu website">
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="tab-2">
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Importar</label>
                      <div class="col-md-8 col-sm-8 col-xs-12">
                        <p style="padding: 5px;">
                          <input  type="checkbox" name="importar[]" id="stock" value="stock" class="flat2" <?php if(is_array($check1) && in_array("stock",$check1))  echo "checked"; ?> />
                          Stock
                          <input  type="checkbox" name="importar[]" id="precos" value="precos" class="flat2" <?php if(is_array($check1) && in_array("precos",$check1))  echo "checked"; ?> />
                          Preços
                          <input  type="checkbox" name="importar[]" id="imagens" value="imagens" class="flat2" <?php if(is_array($check1) && in_array("imagens",$check1))  echo "checked"; ?> />
                          Imagens
                          <input  type="checkbox" name="importar[]" id="imagens" value="descr" class="descr" <?php if(is_array($check1) && in_array("descr",$check1))  echo "checked"; ?> />
                          Descrição </p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Linha de Preço</label>
                      <div class="col-md-1 col-sm-1 col-xs-6">
                        <input value="<?php echo $settings['preco_linha'];?>" name="preco_linha" id="preco_linha" type="text" class="form-control">
                      </div>
                      <label class="control-label col-md-2 col-sm-4 col-xs-12">Categoria Predef</label>
                      <div class="col-md-3 col-sm-1 col-xs-3">
                        <select class="form-control" name="catdefault" id="catdefault">
                          <option value="">Escolha 1 opção</option>
                          <?php 
if($settings['ws_token']!=""){

#######################################################   woocommerce  ##################################################
if($platform=="woocommerce"){
$categoriasweb=list_categorias();  
///   OBTÉM A CATEGORIA TOPO ////////////////////////////////////////////////
$topoArrayCat = array_column($categoriasweb, 'parent');
array_multisort($topoArrayCat, SORT_ASC, $categoriasweb);  
$catTreeTop=$topoArrayCat[0];

if(is_array($categoriasweb)){
$tree = @buildTree($categoriasweb,$catTreeTop);  
} else { $tree = array();   }		
}	
#######################################################   /woocommerce  ##################################################




#######################################################   PRESTASHOP  ##################################################
if($platform=="prestashop"){
$tree=$tree[0]['_children'][0]['_children'];
$catTreeTop=2;
}
printTree($tree,$settings['catdefault'],0,$catTreeTop);
}
#######################################################   /PRESTASHOP  ##################################################




?>
                        </select>
                      </div>
                    </div>
<?php  
//  echo "<pre>"; print_r($categoriasweb);  echo "</pre>";
//  echo "<pre>"; print_r($catTopo);        echo "</pre>";
?>   

<input value="<?php echo $catTreeTop;?>" name="catTreeTop" id="catTreeTop" type="hidden">


                    <div class="form-group divportes">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Portes Adicionais</label>
                      <div class="col-sm-2 col-xs-6">
                        <input value="<?php echo $settings['portes'];?>" name="portes" id="portes" type="number" step="any" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-xs-12">Imagem Predefinida</label>
                      <div class="col-md-3 col-sm-1 col-xs-3">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                          <div class="fileinput-new thumbnail" style="width: 100%">
                            <?php 

$imagemdef=settings_val(1,"imagem");  
$imagemdef=$imagemdef['filename'];

if(is_file(DOCROOT."/attachments/".$_SESSION['empresaID']."/$imagemdef")){
 	echo "<img id=\"fotouser\" src=\"".URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef\" alt=\"\">";	 	 
 }  else {
	echo "<img id=\"fotouser\" src=\"".URLBASE."/images/user.png\" alt=\"\">"; 
 }
?>
                          </div>
                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                          <div> <span class="btn btn-default btn-file"><span class="fileinput-new">Alterar imagem</span><span class="fileinput-exists">Alterar</span>
                            <input type="file" id="uploadfile" name="uploadfile" />
                            </span> <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a> </div>
                        </div>
                      </div>
                    </div>
                    <div class="ln_solid"></div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="tab-3">
                   
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Famílias</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="tpfamilia" id="tpfamilia">
                          <option value="">Escolha 1 opção</option>
                          <?php foreach($obj1 as $k=>$valF){
                          echo "<option ";
						  if(@$settings['tpfamilia']==$k) echo "selected "; 
						  echo "value=\"".$k."\">".$valF['legenda']."</option>";
                        } 
						?>
                        </select>
                      </div>
                    </div>
                    
                    
 <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Sub-Famílias</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="tpsubfamilia" id="tpsubfamilia">
                          <option value="">Escolha 1 opção</option>
                          <?php foreach($obj1 as $k=>$valF){
                          echo "<option ";
						  if(@$settings['tpSfamilia']==$k) echo "selected "; 
						  echo "value=\"".$k."\">".$valF['legenda']."</option>";
                        } 
						?>
                        </select>
                      </div>
                    </div>                    
                    
                                        
                    
                    <div class="form-group">
                      <label class="control-label col-md-4 col-sm-4 col-xs-12">Marcas</label>
                      <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="tpmarcas" id="tpmarcas">
                          <option value="">Escolha 1 opção</option>
                          <?php foreach($obj1 as $k=>$valF){
                          echo "<option ";
						  if(@$settings['tpmarcas']==$k) echo "selected "; 
						  echo "value=\"".$k."\">".$valF['legenda']."</option>";
                        } 
						?>
                        </select>
                      </div>
                    </div>
                      
                    <?php 
                    if(array_key_exists('grelhas',$settings)){
                    $listaGrelhas=unserialize($settings['grelhas']);  
                    } else { $listaGrelhas=array(); }
                    
                         
                    
                    if(sizeof($grelhas)>0){
                        
                    $ListaAtributos=list_atributos();      
                    //echo "<pre>"; print_r($grelhas); echo "</pre>"; 
                      
                    ?>                       
                    <?php for($i=1; $i<=sizeof($grelhas); $i++){ ?>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Grelha <?php echo $i;?></label>
                      <div class="col-md-2 col-sm-2 col-xs-12">
                        <select class="form-control" name="grelha[<?php echo $i;?>][grelha]" id="grelha<?php echo $i;?>">
                          <option value="">Escolha 1 opção</option>
                          <?php foreach($grelhas as $k=>$valF){
                          echo "<option ";
						  if(@$listaGrelhas[$i]['grelha']==$valF['strCodigo']) echo "selected "; 
						  echo "value=\"".$valF['strCodigo']."\">".$valF['strDescricao']."</option>";
                        } 
				    ?> 
                        </select>
                      </div>
                        <div class="col-md-1 col-sm-1 col-xs-12" align="center"><h3>=</h3></div>  
                         <div class="col-md-2 col-sm-2 col-xs-12">
                        <select class="form-control" name="grelha[<?php echo $i;?>][atrib]" id="atrib<?php echo $i;?>">
                          <option value="">Escolha 1 opção</option>
                    <?php foreach($ListaAtributos as $k=>$valF){
                          echo "<option ";
						  if(@$listaGrelhas[$i]['atrib']==$k) echo "selected ";  
						  echo "value=\"".$k."\">".$valF['name']."</option>";
                        } 
				    ?>
                        </select>
                      </div> 
                          
                          
                    </div>
                    <?php } }   ?>
                      
                      
                      
                      
                      
                      
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-9">
                    <button class="btn btn-primary" type="reset">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="submitbtn">Submeter</button>
                  </div>
                </div>
              </div>
              <input type="hidden" name="accaoP" id="accaoP" value="edit">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?php include("footer.php"); ?>