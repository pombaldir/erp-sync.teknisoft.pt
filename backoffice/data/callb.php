<?php include_once '../include/db_connect.php';	include_once '../include/functions.php'; header('Content-Type: application/json'); 

sec_session_start();


if (login_check($mysqli) == true) {
	
if(is_file(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php')){
    $mysqli->close();	
    require(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php');
    $mysqli = new mysqli(HOST2, USER2, PASSWORD2, DATABASE2);
    $mysqli->set_charset("utf8");
}	

	
if (isset($_POST['accaoP'])) {
    $accao = mysqli_real_escape_string($mysqli,$_POST['accaoP']);
}
if (isset($_GET['accaoG'])) {
    $accao = mysqli_real_escape_string($mysqli,$_GET['accaoG']);
}
/* ############################################## EDITAR ARTIGO #################################################### */

if (isset($_POST['accaoP']) && ($accao == "cb_artigo_a" || $accao == "cb_artigo_d") && $_SERVER['REQUEST_METHOD'] == "POST"){   
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #     
$id_artigo = filter_input(INPUT_POST, 'id_artigo', FILTER_SANITIZE_STRING);	
$platform = filter_input(INPUT_POST, 'platform', FILTER_SANITIZE_STRING);	
$id_ref=isset($_POST['id_ref']) && $_POST['id_ref']!="" ? $_POST['id_ref'] : "NULL";     
    
if($platform=="woocommerce"){
include_once DOCROOT.'/include/functions.woocommerce.php';	
}
if($platform=="prestashop"){
include_once DOCROOT.'/include/functions.prestashop.php';	
}    
    
    
$idPartigoStore="";
$sucesso=1;	
$diplayout=1; 
$categStore="";
$alerta="";
$strCodBarras="";
$mgstype="error"; 
$html_msg="Ocorreu um erro";
    

$prefs=settings_val(1);
$prefs_importar=unserialize($prefs['importar']);
$preco_linha=$prefs['preco_linha'];
$nivel_familia=$prefs['tpfamilia'];
if(isset($prefs['tpSfamilia'])){ $nivel_Sfamilia=$prefs['tpSfamilia']; } else {	$nivel_Sfamilia=""; }
$nivel_marca=$prefs['tpmarcas'];
$portesPredf=$prefs['portes'];
$urlFoto="";
$imagemdef="";
$SubcategStore="";
    
$attributes=$variacao=$Listvariacoes=$listaGrelhas=array();    

if($accao == "cb_artigo_a"){
	$cat=array();
	$images=array();
	$precos=array();
	$descr=array();
	$imgImagem=$_POST['detail']['hasImage'];
	$strCodCategoria=$_POST['detail']['strCodCategoria'];
	$strCodigo=$_POST['detail']['strCodigo'];
    $strCodigoARTIGO=$strCodigo;
     
	$strCodBarras=$_POST['detail']['strCodBarras'];
	$strDescricaoCompl=$_POST['detail']['strDescricaoCompl'];
	$strDescricao=$_POST['detail']['strDescricao'];
	$strObs=$_POST['detail']['strObs'];
	$Preco=@$_POST['detail']['precos'][''.$preco_linha.'']; if($Preco==""){ $Preco=0; } 
	$stock=$_POST['detail']['stock'];
	$fltPesoLiquido=@$_POST['detail']['fltPesoLiquido'];
    ### REFERÊNCIAS ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### ### 
    if(array_key_exists('referencias',$_POST['detail'])){ 
    $ERPReferencia=$_POST['detail']['referencias'];
    if(array_key_exists('var',$ERPReferencia) && sizeof($ERPReferencia['var'])>0){  
                
        $strCodReferencia=$ERPReferencia['strCodReferencia'];   # Referência-Pai        
        
        if(array_key_exists('grelhas',$prefs))  { $listaGrelhas=unserialize($prefs['grelhas']); }  
		                             
        foreach($ERPReferencia['var'] as $k=>$v){
        $pesqAtrib = array_search($k, array_column($listaGrelhas, 'grelha'));  ## Pesquisa o atributo/variação na configuração da grelha   
		//die(json_encode($pesqAtrib));
 
        if($pesqAtrib !="" || $pesqAtrib>=0){      # O atributo está configurado e assignado   
            $IDAtributo=$listaGrelhas[$pesqAtrib+1]['atrib'];    
            $attributes[]=array("id"=>$IDAtributo,"name"=>$v,"visible"=>1,"variation"=>1,"options"=>array($v)); 
            $variacao[]=array("id"=>$IDAtributo,"option"=>$v); 
            $varID[$IDAtributo]=$v;

			error_log("Atrib: $IDAtributo ", 3, "/home/erpsinc/public_html/backoffice/my-errors.log");


        }           
        }
    }
    }
    
    ### /REFERÊNCIAS
    
	if(@is_array($_POST['detail']['familias']) && sizeof($_POST['detail']['familias'])>0){
	$strCodFamilia=@$_POST['detail']['familias'][''.$nivel_familia.'']['codigo'];  
	$strNomFamilia=@$_POST['detail']['familias'][''.$nivel_familia.'']['descricao']; 
	$categStore=getcategoriaStoreId($strCodFamilia);  if($categStore==""){ $categStore=$prefs['catdefault']; }
	
	if(isset($nivel_Sfamilia) && $nivel_Sfamilia!=""){
	$Subcateg=@$_POST['detail']['familias'][''.$nivel_Sfamilia.'']['codigo'];  
	$SubcategStore=getcategoriaStoreId($Subcateg);  if($SubcategStore==""){ $SubcategStore=$categStore; }
	}
	
	} else { 
	$strCodFamilia="";
	$strNomFamilia="";
	$categStore=$prefs['catdefault']; 
	$SubcategStore=$categStore;
	}
	
	if(isset($SubcategStore) && $SubcategStore==""){ $SubcategStore=$categStore; } 
	 
	
	if(@is_array($_POST['detail']['familias']) && sizeof($_POST['detail']['familias'])>0){
	$strMarca=@$_POST['detail']['familias'][''.$nivel_marca.'']['descricao'];   
	} else { $strMarca=""; }	
	
	if(getcategoriaStoreId($strCodFamilia)==""){
	$alerta.="<br><br><span class=\"label label-danger\">Nota:</span> A família $strNomFamilia não está sincronizada. Artigo inserido na categoria predefinida</span>"; 	
	}
	
		
	if($imgImagem!=0 && in_array("imagens",$prefs_importar)){

	$urlFoto=URLBASE.'/attachments/tmp/art'.$_SESSION['empresaID'].''.$id_artigo.'.jpg?v='.rand(1,99);
    //$urlFoto=URLBASE.'/attachments/tmp/art'.$_SESSION['empresaID'].''.$id_artigo.'.jpg';    
	$imgData=$_POST['detail']['imgData'];
	$localFileImg=DOCROOT.'/attachments/tmp/art'.$_SESSION['empresaID'].''.$id_artigo.'.jpg';
	if(is_file($localFileImg)) unlink ($localFileImg);
	
	if($imgData!=""){
	base64_to_jpeg($imgData,0,$localFileImg); 
	} 
	
	} else {
	$urlFoto="https://erpsinc.pt/backoffice/images/sem-foto.png";  
	$imagemdef=settings_val(1,"imagem");  
	$imagemdef=$imagemdef['filename'];
	 
	$localFileImg=DOCROOT."/attachments/".$_SESSION['empresaID']."/$imagemdef";	
		
	if(is_file($localFileImg)){ 
	$urlFoto=URLBASE."/attachments/".$_SESSION['empresaID']."/$imagemdef";	;	
	} 	
	}
}
//die($urlFoto);
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #     
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # #  WOOCOMMERCE  # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
    
if($accao == "cb_artigo_a" && $platform=="woocommerce"){

	
    
if(in_array("stock",$prefs_importar)) 	{ $manage_stock=true;	} else { $manage_stock=false;	}
if($stock>0) { $in_stock=true;	} else { $in_stock=false;	}
      
    
if(ReferenciaExiste($id_ref)==0 || $id_ref=='NULL' || !array_key_exists('referencias',$_POST['detail'])){     ## é para criar o artigo?....  
    
if($id_ref>0){ 
    $prod_type="variable";
    //$strCodigo=$strCodReferencia;
    $strCodigo=$strCodReferencia;
}    else {
    $prod_type="simple";
    
    //$attributes=array(); 
}
    
$data = [
    'name' => ''.$strDescricao.'',
    'type' => $prod_type,
	'sku'  => ''.$strCodigo.'',
    
	'meta_data' => [
        [
            'key' => 'erpID',
			'value' => $id_artigo
        ]
    ],
	'manage_stock' => $manage_stock,
	'stock_quantity' => ''.$stock.'',
	'in_stock' => $in_stock,
];


if($categStore!=""){
$cat=array('categories' => [
        [
            'id' => $categStore
        ]
    ]);
}
if(in_array("imagens",$prefs_importar)){ 
	$images=array('images' => [
        [	
            'src' => ''.$urlFoto.'',
            'position' => 1
        ]
    ]);
}
if(in_array("precos",$prefs_importar)){
	$precos=array('regular_price' => ''.$Preco.'');
}
if(in_array("descr",$prefs_importar)){
	$descr=array('description' => ''.$strDescricaoCompl.'','short_description' => ''.$strDescricao.'');
}

$data=array_merge($data,$cat,$images,$precos,$descr,array("attributes"=>$attributes)); 

$produto=add_produto($data,1);    // Cria o Produto

if(is_array($produto) && array_key_exists('id',$produto)){
	$idPartigoStore=$produto['id'];
	$mgstype="success";
	if($id_ref!='NULL' && $data['type']=="variable" && ($strCodReferencia!=$strCodigoARTIGO)){ 
	$Listvariacoes=array("regular_price"=>$Preco,"sku"=>$strCodigoARTIGO,"manage_stock"=>$manage_stock,"stock_quantity"=>$stock,"attributes"=>$variacao);    
	add_variacao($idPartigoStore,$Listvariacoes);  
	}	
	$html_msg="Artigo importado com êxito para a loja online!<br><small><strong>$strDescricao</strong><br><strong>ERP:</strong> $strCodigo&nbsp;&nbsp;&nbsp;&nbsp;<strong>Loja:</strong> #$idPartigoStore </small>".$alerta."";
	} else { 
		$html_msg=$produto; $mgstype="error"; 
	}  


 
  
}    ## então atualizamos a referência....  
  
if($id_ref!=NULL && array_key_exists('referencias',$_POST['detail']) && sizeof($_POST['detail']['referencias'])>0 && $idPartigoStore==""){
    if(ReferenciaExiste($id_ref)>0){
        
        $idArtigoReferencia=getRefArtigoId($id_ref);
         
        ### ATUALIZAR OS ATRIBUTOS DO ARTIGO, PARA FICARIEM DISPONIVEIS COMO VARIAÇÃO....
        $arrF=array(); 
        $getAtribExistentes=get_produto($idArtigoReferencia);
		if($getAtribExistentes==0){
			$mysqli->query("DELETE FROM artigos where id_store='$idArtigoReferencia' and id_erp_ref='$id_ref'");
			$mgstype="error";
			$html_msg="ocorreu um erro!";
		} else {
        $AtribExistentes=$getAtribExistentes['attributes'];        
        foreach($AtribExistentes as $arrexist){
            $arrF[]=array("id"=>$arrexist['id'],"name"=>$arrexist['name'],"visible"=>1,"variation"=>1,"options"=>array_merge($arrexist['options'],array($varID[$arrexist['id']]))); 
        }
        edit_produto($idArtigoReferencia,array("attributes"=>$arrF));
        ###########################################################################################################################################################################
        
        $dataAtributos=array("regular_price"=>$Preco,"sku"=>$strCodigo,"manage_stock"=>$manage_stock,"stock_quantity"=>$stock,'in_stock' => $in_stock,"attributes"=>$variacao,'image' => array('src' => $urlFoto)); 
          
        $variacao=add_variacao($idArtigoReferencia,$dataAtributos,1);
		if(is_array($variacao) && array_key_exists('id',$variacao)){
        $idPartigoStore=$variacao['id'];
        $mgstype="success";
        $html_msg="Variação importada com êxito para a loja online!Loja:</strong> #$idPartigoStore </small>";
		} else {
			$mgstype="error";
			$html_msg=$variacao;  
			}
		}
    }   
}   
    
    
}

# # # # # # # # # # # # # # # # # # # # # # # #  REMOVE PRODUTO OU VARIAÇÃO  # # # # # # # # # # # # # # # # # # # # # # # 
if($accao == "cb_artigo_d" && $platform=="woocommerce"){
$mgstype="error";    
$idStoreDesteArtigo=getRefArtigoERP($id_artigo);    
if(ReferenciaExiste($id_ref)>0 || $id_ref!=NULL ){   
$idRArtPrincipal=getRefArtigoId($id_ref);   
}


if($idStoreDesteArtigo==$idRArtPrincipal || $id_ref=='NULL'){           
remove_produto($id_artigo);
$html_msg="Artigo removido com êxito da loja online!"; 
if($id_ref>0){
    $query = $mysqli->query("select id_erp from artigos where id_erp_ref='$id_ref'");
    $arrElim=array();
    while($dados = $query->fetch_assoc()){
        $arrElim[]=$dados['id_erp'];
    }
    if(sizeof($arrElim)>0){
        ERP_Uncheck_Serial($arrElim,$id_ref); 
    }  
}    
    
} else {
$rmvar=remove_variacao($idRArtPrincipal,getArtigoStoreId($id_artigo),1);   
if(is_array($rmvar) && array_key_exists('id',$rmvar)){
$html_msg="Variação removida com êxito da loja online!"; 
} else { $html_msg=$rmvar; }  
}
    
}


# # # # # # # # # # # # # # # # # # # # # # # #  PRESTASHOP  # # # # # # # # # # # # # # # # # # # # # # # 
if($platform=="prestashop"){
	if($accao == "cb_artigo_a"){
	
	

	$idPartigoStore=add_produto(0, "", $categStore, $SubcategStore, "$Preco", 1, 1, 1, 12,"", 1, "$stock","$strDescricao", "$strDescricaoCompl", "$strDescricaoCompl", "", "", "", "","","",array(),"$strCodigo","$localFileImg","$strMarca",$portesPredf,$strCodBarras,$fltPesoLiquido);
	$mgstype="success"; 
	$html_msg="Artigo importado com êxito para a loja online! <br><small><strong>$strDescricao</strong><br><strong>ERP:</strong> $strCodigo&nbsp;&nbsp;&nbsp;&nbsp;<strong>Loja:</strong> #$idPartigoStore </small>".$alerta."";
	
	} 
	if($accao == "cb_artigo_d"){ 
	$idrem=remove_produto($id_artigo);
	$mgstype="error"; 
	if($idrem==1){
	$html_msg="Artigo removido com êxito da loja online!";
	} else {  $diplayout=0;  }
	}
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
//if($id_artigo>0 && $idPartigoStore>0){    
updtArtigoLocal($id_artigo,$idPartigoStore,$accao,$id_ref);
//}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
if($diplayout==1){
$output = array("success" => $sucesso, "type" => $mgstype, "message" => $html_msg,"extra"=>"Cat Web: $categStore Foto: $urlFoto ($imagemdef)","atrib"=>$variacao);
}

}


# # # # # # # # # # # # # # # # # # # # # # # #  UPDATE  # # # # # # # # # # # # # # # # # # # # # # # 
if($accao == "cb_artigo_u"){

$Idws = filter_input(INPUT_POST, 'Idws', FILTER_SANITIZE_STRING);	
$Id = filter_input(INPUT_POST, 'Id', FILTER_SANITIZE_STRING);	

updtArtigoLocal($Id,$Idws,"cb_artigo_u");


$sucesso=1;	
$mgstype="success";
$output = array("success" => "$sucesso", "type" => "$mgstype");

}

# # # # # # # # # # # # # # # # # # # # # # # #  UPDATE  # # # # # # # # # # # # # # # # # # # # # # # 
if($accao == "cb_artigo_upserial"){
$idSync=array();
$idERP=array();

$i=0;
if(isset($_POST['dados']) && sizeof($_POST['dados'])>0){
foreach($_POST['dados'] as $kv){
	foreach($kv as $v){
	updtArtigoLocal($v['Id'],$v['strObs'],"cb_artigo_u");	
	$i++;
	$idERP[]=$v['Id'];
	}
}
/**/
$query = $mysqli->query("select id_erp from artigos");
while($dados = $query->fetch_array()){
	$idSync[]=$dados['id_erp'];	
}
}
$result = array_diff($idERP,$idSync);
$mysqli->query("delete from artigos where id_erp IN ('".implode("','",$result)."')");

$sucesso=1;	
$mgstype="success";
$output = array("success" => "$sucesso", "type" => "$mgstype", "message" =>"$i artigos sincronizados. ".sizeof($result)." removidos");
}

/* ############################################## OUTPUT ######################################################### */
if(isset($output)){
echo json_encode($output);
}
}