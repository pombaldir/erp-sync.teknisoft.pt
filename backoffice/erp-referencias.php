<?php $ptitle="Referências";	include("header.php");  

if($act==""){
?>

<table id="table<?php echo $p;?>" class="table table-striped table-condensed table-bordered display">
  <thead>
    <tr>
      <th></th>
      <th></th>    
    <th></th>    
      <th></th>      
      <th scope="col" width="12%">Código</th>
      <th scope="col" width="30%">Descrição</th>
        
      <th width="6%"></th>    
       <th width="6%"></th>  
       <th width="4%"><i class="fa fa-cubes"></i></th>
      <th scope="col" width="4%"><i class="fa fa-globe"></i></th>
        <th></th>     
      <th scope="col" width="6%">Ação</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>
<?php } if($act=="edit"){  

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$webserviceERP=$prefs['erp_ws'];
$webserviceTKN=$prefs['ws_token'];
$platform=$prefs['store'];

$urlws=$webserviceERP."/referencias.php?act_g=view&num=$num&auth_userid=".$webserviceTKN;
$json=file_get_contents("$urlws", false, stream_context_create($arrContextOptions));
$obj = json_decode($json, true);
$art_familias=$obj['familias'];

$imagemdef=settings_val(1,"imagem");  
$imagemdef=$imagemdef['filename'];
    
 //echo $urlws;
?>
<div class="x_content">
  <form class="form-horizontal form-label-left" id="erp-artigos" data-parsley-validate>
    <div class="" role="tabpanel" data-example-id="togglable-tabs">
      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content1" id="tab-1" role="tab" data-toggle="tab" aria-expanded="true">Dados Gerais</a> </li>
        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="tab-2" data-toggle="tab" aria-expanded="false">Imagens</a> </li>
       <!--  <li role="presentation" class=""><a href="#tab_price" role="tab" id="tab-price" data-toggle="tab" aria-expanded="false">Preços</a> </li>
        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="tab-3" data-toggle="tab" aria-expanded="false">Outros Dados</a> </li> -->
      </ul>
      <div id="myTabContent" class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="tab-1">
          <input type="hidden" name="erp_familia" id="erp_familia" value="<?php echo $num;?>">
          <div class="form-group">
            <label class="control-label col-sm-2 col-xs-12">Código</label>
            <div class="col-sm-3 col-xs-12">
              <input value="<?php echo $obj['strCodigo'];?>" name="strCodigo" id="strCodigo" type="text" class="form-control" placeholder="">
            </div>
            <!-- <label class="control-label col-sm-2 col-xs-12">Cód Interno</label>
            <div class="col-sm-2 col-xs-12">
              <input value="<?php echo $obj['intCodInterno'];?>" name="intCodInterno" id="intCodInterno" type="text" class="form-control" placeholder="">
            </div>-->
            <div class="col-sm-3 col-xs-12">
              <div class="">
                <label>
                  <input name="bitInactivo" id="bitInactivo" type="checkbox" class="js-switch" value="1" <?php if ($obj['bitInactivo']==1){ echo "checked"; } ?> />
                  Inativo </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2 col-sm-2 col-xs-12">Descrição</label>
            <div class="col-sm-7 col-xs-12">
              <input value="<?php echo $obj['strDescricao'];?>" name="strDescricao" id="strDescricao" type="text" class="form-control" placeholder="">
            </div><!--
            <label class="control-label col-sm-1 col-xs-12">Stock</label>
            <div class="col-sm-1 col-xs-12">
              <input value="<?php echo number_format($obj['QuantStock'],0);?>" name="QuantStock" id="QuantStock" type="text" class="form-control" placeholder="">
            </div> -->
          </div>
          <div class="form-group">
            <div class="col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h4>Detalhes</h4>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div id="alerts"></div>
                  <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                    <div class="btn-group"> <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li><a data-edit="fontName Arial">
                          <p style="font-family:Arial">Arial</p>
                          </a></li>
                        <li><a data-edit="fontName Courier New">
                          <p style="font-family:Courier New">Courier New</p>
                          </a></li>
                        <li><a data-edit="fontName Impact">
                          <p style="font-family:Impact">Impact</p>
                          </a></li>
                        <li><a data-edit="fontName Lucida Console">
                          <p style="font-family:Lucida Console">Lucida Console</p>
                          </a></li>
                      </ul>
                    </div>
                    <div class="btn-group"> <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                      <ul class="dropdown-menu">
                        <li> <a data-edit="fontSize 5">
                          <p style="font-size:17px">Título</p>
                          </a> </li>
                        <li> <a data-edit="fontSize 2">
                          <p style="font-size:12px">Normal</p>
                          </a> </li>
                        <li> <a data-edit="fontSize 1">
                          <p style="font-size:11px">Pequeno</p>
                          </a> </li>
                      </ul>
                    </div>
                    <div class="btn-group"> <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a> <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a> <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a> <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a> </div>
                    <div class="btn-group"> <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a> <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a> <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a> <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a> </div>
                    <div class="btn-group"> <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a> <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a> <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a> <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a> </div>
                    <div class="btn-group"> <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                      <div class="dropdown-menu input-append">
                        <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                        <button class="btn" type="button">Add</button>
                      </div>
                      <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a> </div>
                    <div class="btn-group"> <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a> <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a> </div>
                  </div>
                  <div id="editor-one" class="editor-wrapper"><?php echo $obj['strDescricaoCompl'];?></div>
                  <textarea name="descr" id="descr" style="display:none;"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="tab-2"><?php //echo '<img src="data:image/jpg;base64,' . $obj['imgImagem'] . '">';?>
          <div class="form-group">
            <div class="col-md-55">
              <div class="thumbnail">
                <div class="image view view-first"> <img class="fotoPrincipal" id="imgpredef" style="height: 100%; display: block; " src="<?php if($obj['imgImagem']!="") { echo 'data:image/jpg;base64,' . $obj['imgImagem'] . ''; } else { echo URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef"; } ?>" alt="imagem predefinida" />
                  <div class="mask">
                    <p><?php echo $obj['strCodigo'];?></p>
                    <div class="tools tools-bottom"> <a href="#"><i class="fa fa-link"></i></a> <a href="#" class="elimFoto" id="foto<?php echo $_SESSION['empresaID']; ?>"  data-tipo="mainfotoRef"><i class="fa fa-times"></i></a> </div>
                  </div>
                </div>
                <div class="caption">
                  <p><?php echo $obj['strCodigo'];?> <a href="#" role="button" class="fileinput-button"> <i class="fa fa-pencil"></i> editar
                    <input id="fileupload" type="file" name="files[]" data-form-data='{"num": "<?php echo $num;?>","tipo": "mainfotoRef"}'>
                    </a> </p>
                </div>
              </div>
            </div>
            <span id="extra" class="colextra"></span> <a data-idart="<?php echo $num;?>" data-descricao="<?php echo $obj['strDescricao'];?>" class="btn btn-primary gimages" href="#"><i class="fa fa-google"></i> Google</a> </div>
        </div>
      
          <!--
        <div role="tabpanel" class="tab-pane fade" id="tab_price" aria-labelledby="tab-price">
          <div class="form-group">
            <label class="control-label col-sm-1 col-xs-12">P.C Refª</label>
            <div class="col-sm-2 col-xs-12">
              <input value="<?php echo number_format($obj['fltPCReferencia'],2);?>" name="fltPCReferencia" id="fltPCReferencia" type="number" class="form-control" placeholder="">
            </div>
           

            
          </div>
          <div class="row">
            <div class="col-xs-6 Tblprecos">
              <?php // echo "<pre>"; print_r($obj['precos']); echo "</pre>"; ?>
              <table id="tableC<?php echo $p;?>" class="table table-striped table-condensed display" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Preço s/ Iva</th>
                    <th scope="col">Desc 1</th>
                    <th scope="col">Desc 2</th>
                    <th scope="col">Iva %</th>
                    <th scope="col">Preço c/ Iva</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
				  if(is_array($obj['precos']) && sizeof($obj['precos'])>0){
				  foreach ($obj['precos'] as $pricev){
				  echo "<tr><td>".$pricev['intNumero']."</td><td>".$pricev['fltPreco']."</td><td>".number_format($pricev['fltDesconto1'],0)."</td><td>".number_format($pricev['fltDesconto2'],0)."</td><td>".$pricev['fltTaxa']."</td><td>".$pricev['PrecoFinCiva']."</td></tr>";
			  }   
                  } ?>
                </tbody>
              </table>
            </div>
            <div class="col-xs-1"> </div>
            <div class="col-xs-5">
              <?php if($obj['strCodBarras']!=""){
			$codbar=trim($obj['strCodBarras']);	
			} else {
			$codbar="";		
			}
			
			?>
              <div style="display: inline-block;height: 200px;width: 50%;overflow: hidden;"> <img style="margin-top: -10px;" width="100%" src="<?php echo URLBASE;?>/vendors/barcode/barcode.php?code=<?php echo $codbar; ?>&encoding=ANY&scale=4&mode=png"> </div>
            </div>
          </div>
        </div> 

-->
          
       <!-- <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="tab-3">
          <?php 
$urlws1=$settings['erp_ws']."/familias.php?act_g=listaTipos_Familias&auth_userid=".$settings['ws_token'];
$json1=file_get_contents("$urlws1", false, stream_context_create($arrContextOptions));
$obj1 = json_decode($json1, true);

foreach($obj1 as $k=>$valorFamilia){	
	echo "<div class=\"form-group\"><label class=\"control-label col-sm-4 col-xs-12\">".$valorFamilia['legenda']."</label><div class=\"col-md-3 col-sm-3 col-xs-12\"> <select data-legenda=\"".$valorFamilia['legenda']."\" data-nivel=\"".$k."\" class=\"form-control nivelChange\" name=\"fam[".$k."]\" id=\"fam_".$k."\"><option value=\"\">Escolha 1 ".$valorFamilia['legenda']."</option>";
	foreach($valorFamilia['lista'] as $valF){
	$keyFamilia = array_search(''.$k.'', array_column($art_familias, 'strCodTpNivel')); 
	echo"<option value=\"".$valF['strCodigo']."\"";
	
	if($keyFamilia>=0 && ($valF['strCodigo']==@$art_familias[$keyFamilia]['strCodFamilia'])){	echo " selected";	}
	echo">".$valF['strDescricao']."</option>";
	}
	echo"<option value=\"custom\">=- Criar ".$valorFamilia['legenda']." no ERP -=</option></select></div></div>";
}
?>
        </div> -->
        <div class="ln_solid"></div>
      </div>
      <div class="form-group">
        <div class="col-md-9"> <a href="#" onClick="window.history.back();" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Voltar</a>
          <?php if(isset($prefs) && $prefs['store']=="prestashop" && getArtigoStoreId($num)>0) { ?>
          <a href="#" class="btn btn-info" id="btnsinc"><i class="fa fa-exchange"></i> Sincronizar</a>
          <?php } ?>
        </div>
        <div class="col-md-3 col-xs-12">
          <button class="btn btn-primary" type="reset"><i class="fa fa-reset"></i> Cancelar</button>
          <button type="submit" class="btn btn-success" id="submitbtn"><i class="fa fa-edit"></i> Editar</button>
        </div>
      </div>
    </div>
    <input type="hidden" name="idnum" id="idnum" value="<?php echo $num;?>">
    <input type="hidden" name="act_p" id="act_p" value="edit">
    <input type="hidden" name="auth_userid" id="auth_userid" value="<?php echo $prefs['ws_token'];?>">
  </form>
</div>
<?php } if($act=="scan"){   ?>

<!--
<div class="row">
	<div class="col-lg-6">
		<div class="input-group">
			<input id="scanner_input" class="form-control" placeholder="Click the button to scan an EAN..." type="text" /> 
			<span class="input-group-btn"> 
				<button class="btn btn-default" type="button" data-toggle="modal" data-target="#livestream_scanner">
					<i class="fa fa-barcode"></i>
				</button> 
			</span>
		</div>
	</div>
</div> -->

<div class="modal" id="livestream_scanner">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title">Digitalizar Código</h4>
      </div>
      <div class="modal-body" style="position: static">
        <div id="interactive" class="viewport"></div>
        <div class="error"></div>
      </div>
      <div class="modal-footer">
        <label class="btn btn-default pull-left"> <i class="fa fa-camera"></i> Utilizar câmera
          <input type="file" accept="image/*;capture=camera" capture="camera" class="hidden" />
        </label>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal -->

<div align="center" id="barcInit"><a href="#" class="btnScan" data-role="button" data-toggle="modal" data-target="#livestream_scanner"><img src="<?php echo URLBASE;?>/images/bar-code-scan.gif" width="369" height="369" alt=""/></a></div>
<div align="center"> <a class="btnScan" href="#" data-role="button" data-toggle="modal" data-target="#livestream_scanner">
  <svg id="barcode"></svg>
  </a> </div>
<form class="form-horizontal form-label-left" id="erp-artigos-scan">
  <div class="row"><span id="detalhes"></span></div>
  <input type="hidden" name="idnum" id="idnum" value="">
  <input type="hidden" name="strCodigo" id="strCodigo" value="">
  <input type="hidden" name="act_p" id="act_p" value="quickedit">
  <input type="hidden" name="auth_userid" id="auth_userid" value="<?php echo $prefs['ws_token'];?>">
</form>
<br>
<div class="row colextra">
  <div class="col-md-12"><span id="extra"></span></div>
</div>
<?php } include("footer.php"); ?>
