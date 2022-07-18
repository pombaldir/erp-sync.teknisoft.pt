<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");
use Medoo\Medoo;



$database->query("SET TEXTSIZE 2147483647;"); 
ini_set( 'mssql.textlimit' , '2147483647' );
ini_set( 'mssql.textsize' , '2147483647' );



if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="$tokenAPI") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="$tokenAPI")) {
	 
	if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
	if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }
	if(isset($_GET['token']))	{	$token=stripslashes($_GET['token']);	}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if(isset($_POST['act_p']) && $act_pst=="resetArtigos" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	$database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 0], ["bitInactivo"=>0]);
	$output = array("success" => 1);	
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if(isset($_POST['act_p']) && $act_pst=="updtArtigos" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	//$sku=$_POST['sku'];	
	$extra=$_POST['extra'];
	$arrtotal=array(); 
	
	if(is_array($extra) && sizeof($extra)>0){
	$database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 0], ["bitInactivo"=>0]);
	
	foreach($extra as $v){
	$database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 1], ["strCodigo"=>$v['sku'],"bitInactivo"=>0]);	
	
	$post = $database->select("Tbl_Gce_Artigos", [
	"Id",
	"strObs(idws)" => Medoo::raw("".$v['idws']."")
	], [
		"strCodigo" => $v['sku']
	]);
	
	if(sizeof($post)==1){
		$arrtotal[]=$post;	
	}
	}
	} else {		$arrtotal[]=array(); }
	
	$success=1;	
	$html_msg="Artigos sincronizados";			 
	    
	$output = array("success" => $success,"message"=> "$html_msg","dados"=> $arrtotal);	
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && ($act_pst=="edit" || $act_pst=="quickedit") && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idnum']);
$erp_familia=stripslashes($_POST['erp_familia']);
$strCodigo=stripslashes($_POST['strCodigo']);
$strDescricao=stripslashes($_POST['strDescricao']);
$descr=stripslashes($_POST['descr']);

if($idartigo>0){
if($act_pst=="quickedit"){
	
	
} else {
if(isset($_POST['bitInactivo']) &&  $_POST['bitInactivo']==1){
	$inativo=1;
} else {
	$inativo=0;	
}
$dataUpdt = $database->update("Tbl_Gce_Artigos", ["strDescricaoCompl" => $descr,"bitInactivo" => $inativo], ["Id" => $idartigo]);
}


if(isset($_POST['fam']) && is_array($_POST['fam'])){
foreach($_POST['fam']  as $k=>$v){	
$dataFam = $database->update("Tbl_Gce_ArtigosFamilias", ["strCodFamilia" => $v], ["strcodArtigo" => $strCodigo,"strCodTpNivel" => $k]);
if($dataFam->rowCount()==0 && $v!=""){
	$database->insert("Tbl_Gce_ArtigosFamilias", ["strCodFamilia" => $v,"strcodArtigo" => $strCodigo,"strCodTpNivel" => $k]);
} 
if($v=="") {
	$database->delete("Tbl_Gce_ArtigosFamilias", ["AND" => ["strcodArtigo" => $strCodigo,"strCodTpNivel" => $k]]);
} 
}
}


$sucesso=1;
$html_msg="Artigo editado";

} else {
$sucesso=0; 	
$html_msg="Artigo não editado";	
}


$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log());
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="editFoto" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idnum']);
$tipo=stripslashes($_POST['tipo']);
$imgContent=$_POST['file'];
 
$sucesso=0;	  
$html_msg="Foto editada";  
 
if($tipo=="mainfoto"){
	$datafile_0=base64_decode($imgContent); 
	$datafile_1= unpack("H*hex", base64_decode($imgContent)); 
	$datafile_2="0x".$datafile_1."";	 
  
  $dataUpdt = $database->update("Tbl_Gce_Artigos", 
  ["imgImagem" => Medoo::raw('CONVERT(varbinary(MAX), '.$imgContent.')')], 
  ["Id" => $idartigo]);
  
	$sucesso=$dataUpdt->rowCount();  
}
$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log());
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="addFotobyUrl" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idartigo=stripslashes($_POST['idart']);  
$url=stripslashes($_POST['url']); 
$tipo=stripslashes($_POST['tipo']);
 
$sucesso=0;	  
$html_msg="Foto editada";  
 
if($tipo=="mainfoto"){
	
	$ficheiro=unpack("H*hex", file_get_contents($url,TRUE)); 
	$imgContent="0x".$ficheiro['hex'];	
  
  $dataUpdt = $database->update("Tbl_Gce_Artigos", 
  ["imgImagem" => Medoo::raw('CONVERT(varbinary(MAX), '.$imgContent.')')], 
  ["Id" => $idartigo]);
  
$sucesso=$dataUpdt->rowCount();  
	
}
$output = array("success" => "$sucesso", "type" => "info", "message" => $html_msg, "log" => $database->log());
}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="delFoto" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$idAtt=stripslashes($_POST['idAtt']);
$tipo=stripslashes($_POST['tipo']);	
$idartigo=stripslashes($_POST['idartigo']);
$sucesso=1;	 
$html_msg="Foto removida";  

if($tipo=="mainfoto"){
	$dataUpdt = $database->update("Tbl_Gce_Artigos", ["imgImagem" => Medoo::raw("NULL")], ["Id" => $idartigo]);
	$sucesso=$dataUpdt->rowCount();
	$html_msg="Foto principal removida";  
}

$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg");
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="checkweb" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$id_artigo=stripslashes($_POST['id_artigo']);	

	$dataUpdt = $database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 1], ["Id" => $id_artigo]);
	
	if($dataUpdt==1){
	$post = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	],[
	"strCodigo",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"strCodBarras",
	"QuantStock",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => $id_artigo,
		"LIMIT" => 1
	]);
		 
	# # IMAGEM
	if($post[0]['imgImagem']==""){
	$hasImage=0;
	$imgData="";	
	} else {
	$hasImage=1;	
	$imgData=base64_encode($post[0]['imgImagem']);		 
	} 
	# # STOCK
	$stock=number_format($post[0]['QuantStock'],2,".",""); 
	# # PREÇOS
	$qPrecos = getPrecos($post[0]['strCodigo']);
	 
	foreach($qPrecos as $postprecos){
		$precos[$postprecos['intNumero']]=number_format($postprecos['fltPreco'],2,".","");
	}	 											

	# # FAMILIA
	$qFamilias = $database->select("Tbl_Gce_ArtigosFamilias", [
	"[>]Tbl_Gce_Familias" => ["strCodFamilia" => "strCodigo"],
	],[
	"Tbl_Gce_Familias.Id(idfamilia)",
	"Tbl_Gce_Familias.strDescricao",
	"strCodTpNivel"
	], [
		"strcodArtigo" => $post[0]['strCodigo']
	]);	
	$arrfam=array();
	foreach($qFamilias as $fam){
	$arrfam[$fam['strCodTpNivel']]=array("codigo"=>$fam['idfamilia'],"descricao"=>$fam['strDescricao']);  	
	}
	//print_r($database->log()); 
	$detalhes=array("strCodigo"=>$post[0]['strCodigo'],"strCodCategoria"=>$post[0]['strCodCategoria'],"strCodBarras"=>$post[0]['strCodBarras'],"strDescricao"=>$post[0]['strDescricao'],"strDescricaoCompl"=>$post[0]['strDescricaoCompl'],"strObs"=>$post[0]['strObs'],"strCodFamilia"=>$post[0]['strCodFamilia'],"hasImage"=>$hasImage,"imgData"=>$imgData,"stock"=>$stock,"precos"=>$precos,"familias"=>$arrfam); 
	}

	if($html_msg==""){
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	} else {		$sucesso=0;		}
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo", "artigo" => $detalhes);

}

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="uncheckweb" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	$id_artigo=$_POST['id_artigo'];
	$dataUpdt = $database->update("Tbl_Gce_Artigos", ["bitPortalWeb" => 0], ["Id" => $id_artigo]);
	if($dataUpdt==1){
	$sucesso=1;	$html_msg="Artigo atualizado no ERP";  
	} else {
	$sucesso=0;	$html_msg="ERRO SQL ".$database->log();  	
	}
	$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg", "idartigo" => "$id_artigo");

}


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="view" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$num=stripslashes($_GET['num']);
	$Tbl_Gce_Artigos = array();
	$Tbl_Gce_ArtigosPrecos = array();
	$Gce_stk_real = array();
	$Tbl_Gce_ArtigosFamilias = array();
	$Tbl_Gce_ArtigosIdiomas = array();
	
    
	$Tbl_Gce_Artigos = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	],[
	"strCodigo",
	"intCodInterno",
	"strCodBarras",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
	"bitInactivo",
	"fltPCReferencia",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => $num,
		"LIMIT" => 1
	]);
	
	//print_r($database->error());
	# # PREÇOS	
	$Precos = getPrecos($Tbl_Gce_Artigos[0]['strCodigo']);
	
	//print_r($Tbl_Gce_ArtigosPrecos);
	//print_r($database->error()); 
	//print_r($database->log()); 

	# # STOCKS
	$Gce_stk_real = $database->select("Gce_stk_real", [
	"QuantStock",
	"ReservaStock"
	], [
		"strcodArtigo" => $Tbl_Gce_Artigos[0]['strCodigo']
	]);	
			
	# # FAMILIA
	$Tbl_Gce_ArtigosFamilias = $database->select("Tbl_Gce_ArtigosFamilias", [
	"strCodFamilia",
	"strCodTpNivel"
	], [
		"strcodArtigo" => $Tbl_Gce_Artigos[0]['strCodigo']
	]);		
		
	# # IDIOMAS
	$Tbl_Gce_ArtigosIdiomas = $database->select("Tbl_Gce_ArtigosIdiomas", [
	"strDescricao",
	"strDescricaoCompl",
	"strExcerto",
	"strCodIdioma",
	""
	], [
		"strcodArtigo" => $Tbl_Gce_Artigos[0]['strCodigo']
	]);					

 	$output= array("strCodigo"=>$Tbl_Gce_Artigos[0]['strCodigo'],"strCodBarras"=>$Tbl_Gce_Artigos[0]['strCodBarras'],"intCodInterno"=>$Tbl_Gce_Artigos[0]['intCodInterno'],"strDescricao"=>htmlentities($Tbl_Gce_Artigos[0]['strDescricao']),"strDescricaoCompl"=>$Tbl_Gce_Artigos[0]['strDescricaoCompl'],"bitInactivo"=>$Tbl_Gce_Artigos[0]['bitInactivo'],"strObs"=>$Tbl_Gce_Artigos[0]['strObs'],"fltPCReferencia"=>$Tbl_Gce_Artigos[0]['fltPCReferencia'],"QuantStock"=>number_format($Tbl_Gce_Artigos[0]['QuantStock'],2,".",""),"precos"=>$Precos,"stocks"=>$Gce_stk_real,"familias"=>$Tbl_Gce_ArtigosFamilias,"idiomas"=>$Tbl_Gce_ArtigosIdiomas,"imgImagem"=>base64_encode($Tbl_Gce_Artigos[0]['imgImagem']));
	 
}


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="searchBarcode" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$code=stripslashes($_GET['code']);
	$sucesso=0;
		
	$Tbl_Gce_Artigos = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	],[
	"strCodigo",
	"Id",
	"bitPortalWeb",
	"intCodInterno",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
	"bitInactivo",
	"fltPCReferencia",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.strCodBarras" => $code,
		"LIMIT" => 1
	]);
	
	if(sizeof($Tbl_Gce_Artigos)>0){
		
	$Id=$Tbl_Gce_Artigos[0]['Id'];	
	$codArtigo=$Tbl_Gce_Artigos[0]['strCodigo'];	
	$intCodInterno=$Tbl_Gce_Artigos[0]['intCodInterno'];
	$imgImagem=base64_encode($Tbl_Gce_Artigos[0]['imgImagem']);
	$strDescricao=htmlentities($Tbl_Gce_Artigos[0]['strDescricao']);
	$strDescricaoCompl=$Tbl_Gce_Artigos[0]['strDescricaoCompl'];
	$bitInactivo=$Tbl_Gce_Artigos[0]['bitInactivo'];
	$strObs=$Tbl_Gce_Artigos[0]['strObs'];
	$fltPCReferencia=number_format($Tbl_Gce_Artigos[0]['fltPCReferencia'],2);
	$QuantStock=number_format($Tbl_Gce_Artigos[0]['QuantStock'],2,".","");
	$bitPortalWeb=$Tbl_Gce_Artigos[0]['bitPortalWeb'];
	
	# # PREÇOS	
	$Precos = getPrecos($codArtigo);
	
	# # STOCKS
	$Gce_stk_real = $database->select("Gce_stk_real", [
	"QuantStock",
	"ReservaStock"
	], [
		"strcodArtigo" => $codArtigo
	]);	
			
	# # FAMILIA
	$Tbl_Gce_ArtigosFamilias = $database->select("Tbl_Gce_ArtigosFamilias", [
	"strCodFamilia",
	"strCodTpNivel"
	], [
		"strcodArtigo" => $codArtigo
	]);		
	
	
	### SELECT OPÇOES
	
	$Tbl_Gce_Tipos_Familias = $database->select("Tbl_Gce_Tipos_Familias", [
	"strCodigo",
	"strDescricao",
	"intNivel",
	"Id"
	],[
		"ORDER" => ["intNivel"=>"ASC"]
	]);	
	
	$Tbl_Gce_Familias = $database->select("Tbl_Gce_Familias", [
	"strCodigo",
	"strDescricao",
	"strCodTpFamilia",
	"Id"
	],[
		"ORDER" => ["strDescricao"=>"ASC"]
	]);	
	
	
	foreach($Tbl_Gce_Familias  as $val){
	$famarray[$val['strCodTpFamilia']][]=array("strCodigo"=>$val['strCodigo'],"strDescricao"=>$val['strDescricao']);	
	}
	foreach($Tbl_Gce_ArtigosFamilias as $vls){
	$famPredef[$vls['strCodTpNivel']]=$vls['strCodFamilia'];  	
	}
	foreach($Tbl_Gce_Tipos_Familias as $fam){		
	$nvarr=@$famarray[$fam['strCodigo']];
	$varFam='<select data-legenda="'.$fam['strDescricao'].'" data-nivel="'.$fam['intNivel'].'" class="form-control nivelChange" name="fam['.$fam['strCodigo'].']" id="fam_'.$fam['intNivel'].'"><option value="">Escolha 1 '.$fam['strDescricao'].'</option>';			
	if(is_array($nvarr) && sizeof($nvarr)>0){
	foreach($nvarr as $fval){  
	$varFam.= '<option ';
		if($famPredef[$fam['intNivel']]=="".$fval['strCodigo']."") $varFam.= 'selected '; 
		$varFam.= 'value="'.$fval['strCodigo'].'">'.$fval['strDescricao'].'</option>';
		}
		$varFam.='<option value="custom">=- Criar '.$fam['strDescricao'].' no ERP -=</option></select>';
		$varFamilias[]=array("label"=>$fam['strDescricao'],"valor"=>$varFam,"nivel"=>$fam['intNivel']);      
	}  
	}
	
	### /SELECT OPÇOES
	
	
	
	$sucesso=1;
	}
				
 	$output= array("success" => "$sucesso", "Id"=>$Id, "bitPortalWeb"=>$bitPortalWeb, "strCodigo"=>$codArtigo,"intCodInterno"=>$intCodInterno,"strDescricao"=>$strDescricao,"strDescricaoCompl"=>$strDescricaoCompl,"bitInactivo"=>$bitInactivo,"strObs"=>$strObs,"fltPCReferencia"=>$fltPCReferencia,"QuantStock"=>$QuantStock,"precos"=>$Precos,"stocks"=>$Gce_stk_real,"familias"=>$Tbl_Gce_ArtigosFamilias,"listaFam"=>$varFamilias,"idiomas"=>$Tbl_Gce_ArtigosIdiomas,"imgImagem"=>$imgImagem);
	 
}
	
	
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
	
if(isset($_GET['act_g']) && $act_get=="sku" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
		
	$codartigo=$_GET['num'];   

	$Tbl_Gce=$database->select("Tbl_Gce_ArtigosArmLocalLote", [
		"[>]Tbl_Gce_Armazens" => ["strCodArmazem" => "strCodigo"]
	], [		
    "QuantStock" => Medoo::raw('SUM(Tbl_Gce_ArtigosArmLocalLote.fltStockQtd - Tbl_Gce_ArtigosArmLocalLote.fltStockReservado)') 
	], [
		"strCodArtigo" => $codartigo,
		"CA_ONLINE" => 1
	]);	
		

    if(sizeof($Tbl_Gce)>0){
	$stock=number_format($Tbl_Gce[0]['QuantStock']);
    } else {
    $stock=0;    
    }
								
$output = array("stock"=>$stock,"found"=>sizeof($Tbl_Gce));

}  	
	
	
	
	
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="getStocks" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	
	if(isset($_POST['dados']) && is_array($_POST['dados']) && sizeof($_POST['dados'])>0){
		
	$artigos=$_POST['dados'];  	

	foreach($artigos as $v){
	$Tbl_Gce=$database->select("Gce_stk_real", [
	"QuantStock" => Medoo::raw('QuantStock-ReservaStock') 
	], [
		"strCodArtigo" => $v['sku']
	]);	
	
	$stock=number_format($Tbl_Gce[0]['QuantStock']);
	$output[$v['idws']]=$stock;
	}
	} else {		$output=array(); }
}	
	
	
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="list" && ($_SERVER['REQUEST_METHOD'] === 'GET')){

    $sIndexColumn = "strCodigo"; 
    /* DB table to use */ 
    $sTable = "Tbl_Gce_Artigos"; 
    /*
    * Columns
    */ 
	$aColumns = array('Id','strCodigo', 'strDescricao','QuantStock', 'bitPortalWeb', 'strCodCategoria');      
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */     
    /*
     * Ordering
    */
    $sOrder = "";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".addslashes( $_GET['sSortDir_'.$i] ) .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
    }
     
	
	
    $sWhere = "WHERE Tbl_Gce_Artigos.bitInactivo=0 ";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere .= "AND (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true")
        	{
            $sWhere .= "".$aColumns[$i]." LIKE '%".addslashes( $_GET['sSearch'] )."%' OR ";		
			}
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }	
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";  
            }
           $sWhere .= " ".$aColumns[$i]." LIKE '%".addslashes($_GET['sSearch_'.$i])."%' ";
        }
    }
     			
	
	
	if(isset($_GET['Familia']) && $_GET['Familia']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Familia']."'";  
	}
	
	if(isset($_GET['Marca']) && $_GET['Marca']!=""){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_ArtigosFamilias.strCodFamilia='".$_GET['Marca']."'";  
	}
	
	if(isset($_GET['bitDispWeb']) && $_GET['bitDispWeb']!="" && $_GET['bitDispWeb']!="0"){
		if($sWhere==""){ $sWhere .= " WHERE "; } else { $sWhere .= " AND "; } 
		$sWhere .= "Tbl_Gce_Artigos.bitPortalWeb='".$_GET['bitDispWeb']."'";  
	}
	
	

	/*
	$sQuery = $database->select("Tbl_Gce_Artigos", [
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"]
	],[
	"strCodigo",
	"strCodCategoria",
	"strDescricao",
	"strDescricaoCompl",
	"strObs",
	"QuantStock",
	"strCodFamilia",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => 3537,
		"Tbl_Gce_ArtigosFamilias.strCodTpNivel" => "Niv 1"
	]);
	*/
	
	/*
	$sQuery = $database->select("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	"[>]Gce_stk_real" => ["strCodigo" => "strCodArtigo"],
	], [
		"strCodigo" => Medoo::raw( "DISTINCT Tbl_Gce_Artigos.strCodigo"),
		"Tbl_Gce_Artigos.strDescricao",
		"Tbl_Gce_Artigos.bitPortalWeb",
		"Tbl_Gce_Artigos.Id",
		"QuantStock[Int]", 
		"strCodFamilia"
	],
	Medoo::raw("".$sWhere." ".$sOrder." OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY"));
	*/
	
	$sQuery = $database->query("SELECT * FROM (SELECT Tbl_Gce_Artigos.strCodigo,Tbl_Gce_Artigos.strDescricao,Tbl_Gce_Artigos.bitPortalWeb,
	Tbl_Gce_Artigos.Id,CAST(QuantStock AS INT) AS QuantStock,strCodFamilia,
	ROW_NUMBER() OVER (PARTITION BY Tbl_Gce_Artigos.Id ORDER BY Tbl_Gce_Artigos.Id ASC) AS RowNumber
	FROM Tbl_Gce_Artigos 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	LEFT JOIN [Gce_stk_real] ON [Tbl_Gce_Artigos].[strCodigo] = [Gce_stk_real].[strCodArtigo]
	".$sWhere." ".$sOrder."  OFFSET 0 ROWS) AS T where T.RowNumber = 1 ".$sOrder."
	OFFSET ".$_GET['iDisplayStart']." ROWS FETCH NEXT ".$_GET['iDisplayLength']." ROWS ONLY")->fetchAll();
	
	 
	//var_dump( $database->error() );	
	//die(print_r($database->log()));
 
 	/*
	SELECT [Tbl_Gce_Artigos].[strCodigo],[Tbl_Gce_Artigos].[strDescricao],[Tbl_Gce_Artigos].[bitPortalWeb],[Tbl_Gce_Artigos].[Id],
	[Tbl_Gce_Artigos].[strTpArtigo],[Tbl_Gce_Artigos].[strCodCategoria] FROM [Tbl_Gce_Artigos] 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	WHERE [Tbl_Gce_Artigos].[strDescricao] != '' AND [Tbl_Gce_Artigos].[bitInactivo] = 0 
	ORDER BY [Tbl_Gce_Artigos].[Id] DESC OFFSET 0 ROWS FETCH NEXT 5 ROWS ONLY
	*/ 
 
 /*
    $iTotal = $database->count("Tbl_Gce_Artigos", [
	"[>]Tbl_Gce_ArtigosFamilias" => ["strCodigo" => "strCodArtigo"],
	], ["Tbl_Gce_Artigos.Id"],
	Medoo::raw("".$sWhere.""));
	*/
	
	$data = $database->query("SELECT COUNT(strCodigo) as total FROM (SELECT [Tbl_Gce_Artigos].[strCodigo],
	ROW_NUMBER() OVER (PARTITION BY Tbl_Gce_Artigos.Id ORDER BY Tbl_Gce_Artigos.Id DESC) AS RowNumber FROM [Tbl_Gce_Artigos] 
	LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	LEFT JOIN [Gce_stk_real] ON [Tbl_Gce_Artigos].[strCodigo] = [Gce_stk_real].[strCodArtigo]
	".$sWhere.") T WHERE  T.RowNumber=1")->fetchAll();
	
	$iTotal=$data[0]['total'];

	
	
//	die(var_dump( $database->error() ));
//	die(var_dump($database->log()));

	
	 /* 
	 SELECT COUNT([Tbl_Gce_Artigos].[strCodigo]) FROM [Tbl_Gce_Artigos] 
	 LEFT JOIN [Tbl_Gce_ArtigosFamilias] ON [Tbl_Gce_Artigos].[strCodigo] = [Tbl_Gce_ArtigosFamilias].[strCodArtigo] 
	 WHERE [Tbl_Gce_Artigos].[strDescricao] != '' AND [Tbl_Gce_Artigos].[bitInactivo] = 0
	 */

       
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iTotal,
        "aaData" => array()
    );
     

	foreach($sQuery as $aRow)
	{	
        $row = array();
		// Add the row ID and class to the object
		//$row['DT_RowId'] = 'row_'.$aRow['Id'];
		//$row['DT_RowClass'] = 'grade'.$aRow['strCodigo'];
		
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
             if ( $aColumns[$i] != ' ' )
            {
                /* General output */
                $row[] = $aRow[ $aColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }
   }  
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }
}
