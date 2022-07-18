<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

//error_reporting(E_ALL);
//ini_set('display_errors', 1); 

if((isset($_GET['auth_userid']) && $_GET['auth_userid']=="$tokenAPI") || (isset($_POST['auth_userid']) && $_POST['auth_userid']=="$tokenAPI")) {
 
if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="order_create" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
$debug=stripslashes($_POST['debug']);
//$EncstrAbrevTpDoc=$_POST['tpdoc'];
$dados=unserialize($_POST['dados']);   
extract($dados, EXTR_PREFIX_SAME, "dados");
 
//parse_str($dados);  
//$message=serialize($dados);    
//enviaMail("nsantos@pombaldir.com","LOG ".$_POST['store']."",$message);
    
# # # # # # # # # # # # # # # # # # # # # # # # # # # // PRESTASHOP // # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_POST['store']=="prestashop"){
     

$b_firstname=stripslashes($billing['firstname']);
$b_lastname=stripslashes($billing['lastname']);
$b_company=stripslashes($billing['company']);
$b_address_1=stripslashes($billing['address1']);
$b_address_2=stripslashes($billing['address2']);
$b_city=stripslashes($billing['city']);
$b_state=stripslashes($billing['city']);
$b_postcode=stripslashes($billing['postcode']);
$b_country=stripslashes($billing['country']);
$b_email=stripslashes($customer['email']);
$b_phone=stripslashes($billing['phone']);

$s_firstname=stripslashes($shipping['firstname']);
$s_last_name=stripslashes($shipping['lastname']);
$s_company=stripslashes($shipping['company']);
$s_address_1=stripslashes($shipping['address1']);
$s_address_2=stripslashes($shipping['address2']);
$s_city=stripslashes($shipping['city']);
$s_state=stripslashes($shipping['city']);
$s_postcode=stripslashes($shipping['postcode']);
$s_country=stripslashes($shipping['country']);

$shipping_total=stripslashes($total_shipping_tax_incl); 
$shipping_tax=stripslashes($carrier_tax_rate); 
$nif=stripslashes($billing['vat_number']);  
$total=stripslashes($total_paid_tax_incl);  
$total_paid_tax_excl=stripslashes($total_paid_tax_excl); // VRS 2.0      
$total_tax=$total_paid_tax_incl-$total_paid_tax_excl;    
    
$linhas=$details; 
$customer_note="";  
    

foreach($details as $kartigo=>$vartigo){	
$linhas[$kartigo]=array("quantity"=>$vartigo['product_quantity'],"product_sku"=>$vartigo['product_reference'],"punit_civa"=>$vartigo['unit_price_tax_incl'],"punit_siva"=>$vartigo['unit_price_tax_excl'],"total"=>$vartigo['total_price_tax_incl'],"totalsiva"=>$vartigo['total_price_tax_excl'],"name"=>$vartigo['product_name'],"product_price"=>$vartigo['unit_price_tax_incl']);
}    
   
  //die(enviaMail("nsantos@pombaldir.com","LOG ".$_POST['store']."",$_POST['dados']));  
    
//die();  
    
    
    
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # // CS-CART // # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_POST['store']=="cscart"){
 
}
# # # # # # # # # # # # # # # # # # # # # # # # #  // WOOCOMMERCE // # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_POST['store']=="oscommerce"){
$b_firstname=stripslashes($billing['first_name']);
$b_lastname=stripslashes($billing['last_name']);
$b_company=stripslashes($billing['company']);
$b_address_1=stripslashes($billing['address_1']);
$b_address_2=stripslashes($billing['address_2']);
$b_city=stripslashes($billing['city']);
$b_state=stripslashes($billing['state']);
$b_postcode=stripslashes($billing['postcode']);
$b_country=stripslashes($billing['country']);
$b_email=stripslashes($billing['email']);
$b_phone=stripslashes($billing['phone']);

$s_firstname=stripslashes($shipping['first_name']);
$s_last_name=stripslashes($shipping['last_name']);
$s_company=stripslashes($shipping['company']);
$s_address_1=stripslashes($shipping['address_1']);
$s_address_2=stripslashes($shipping['address_2']);
$s_city=stripslashes($shipping['city']);
$s_state=stripslashes($shipping['state']);
$s_postcode=stripslashes($shipping['postcode']);
$s_country=stripslashes($shipping['country']);

$shipping_total=stripslashes($shipping_total); 
$shipping_tax=stripslashes($shipping_tax); 

$mdata=array();
foreach($meta_data as $v1){
	foreach($v1 as $k2=>$v2){
	$mdata[$v2['key']]=$v2['value'];
	}
}

$nif=$mdata['billing_nif'];

} 
#################################################################	INSERE NO ERP  ######################################################		
$nomeClie=strtoupper($b_firstname." ".$b_lastname);
if($b_company!="" && strlen($b_company)>3){
	$nomeClie=strtoupper($b_company);
	}
 
 
$nif=preg_replace('/\s+/', '', $nif);
$nif=substr($nif, -9);
if($nif=="" || strlen($nif)<9){ $bitConsumidorFinal=1; $nif="CONSUMIDOR FINAL"; } else { $bitConsumidorFinal=0; }
 
$rDocExiste = $database->count("Mov_Encomenda_Cab", [
	"strNumExterno" => "$id",
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "strCodSeccao" => $strCodSeccao
]);

$customerCreated=0;
$strnumeroEnc="";
$cab_id="";
$orderCreated=0;
$errormsg="";
$mensagem="1";
$htmlmsg="";

if($rDocExiste==0){
#################################################################	ADICIONA CLIENTE  ######################################################		
	

	$strObs="Cliente criado via Loja Online";
	
	$rq2=$database->count("Tbl_Clientes", ["strNumContrib[!]" => NULL,"strNumContrib[!]" => "CF","strNumContrib" => $nif]);
	//$rq3=$database->count("Tbl_Clientes", ['intCodigo' => $nCliente]);

	if(strlen($nif)>=9 && $rq2>0){
	$qCliente=$database->select('Tbl_Clientes', ['intCodigo'], ['strNumContrib' => $nif], ["LIMIT" => 1]);
	$nCliente=$qCliente[0]['intCodigo'];
	$htmlmsg="Já existe cliente com NIF $nif (Cód: $nCliente)"; 		
	} 
	/*else if($rq3>0){
	$htmlmsg="Já existe cliente com código $nCliente";	
	} */
	 
	else {
		
	if(!isset($tpnumeracao) || $tpnumeracao=="sequencial"){
	$ListCliente=$database->select("Tbl_Clientes", ["intCodigo"], ["ORDER" => ["Tbl_Clientes.Id" => "DESC"],"LIMIT" => 1]);
	$nCliente=$ListCliente[0]['intCodigo']+1; // Nº DE CLIENTE
	} 
	if(isset($tpnumeracao) && $tpnumeracao=="tlm"){
	$nCliente=preg_replace('/\s+/', '', $b_phone);
	$nCliente=substr($nCliente, -9);	
	}
		
	$dtmAbertura=date('Y-m-d H:i:s');	
	if($nCliente!="" && strlen($b_postcode)>2 && $nCliente!="0"){
		
		$database->insert("Tbl_Clientes", [
			"strTelefone" => "$b_phone",
			"strTelemovel" => "$b_phone",
			"strAbrevSubZona" => "$subzona",
			"strEmail" => "$b_email",
			"strMorada_lin1" => "$b_address_1",
			"strMorada_lin2" => "$b_address_2",
			"strLocalidade" => "$b_city",
			"strPostal" => "$b_postcode",
			"strNome" => "$nomeClie",
			"strNumContrib" => "$nif",
			"intCodigo" => "$nCliente",
			"bitUseElectronicDocument" => $faturaEletronica,
			"strObs" => "$strObs",
			"bitConsumidorFinal" => $bitConsumidorFinal,
			"dtmAbertura" => $dtmAbertura,
			"dtmAlteracao" => $dtmAbertura,
			"strCodCondPag" => $strCodCondPag,
			"bitPortalWeb" => 1,
			"intSinalTp" => 0,
			"bitAviso_vencimento" => 1,
			"intCodCatEntidade" => 1,
			"intCodVendedor" => $custmDefSeller
		]);
			$erro=$database->error();
			$errormsg=$erro[2];
		
			if($errormsg==""){
			$customerCreated=1;
			$htmlmsg="Cliente $nCliente criado no ERP";	 
			}
		}
	}
	
	// Direção

		if(($b_address_1!=$s_address_1) || ($b_address_2!=$s_address_2) && $errormsg==""){
			$direcao=$database->select("Tbl_Direccoes", [
				"intNumero"
			],[
				"intCodigo" => $nCliente,
				"ORDER" => ["intNumero" => "DESC"],
				"LIMIT" => 1
			]);
			
			if($direcao==""){
				$nDirecao=1;	
			} else {
				$nDirecao=$direcao[0]['intNumero']+1;		
			}
 			$nomeDirecao=$s_firstname." ".$s_last_name; 
			
			if($s_company!="" && strlen($s_company)>3){
			$nomeDirecao.= " - $s_company";
			}
			
			$database->insert("Tbl_Direccoes", [
			"intCodigo" => "$nCliente",
			"intNumero" => "$nDirecao",
			"intTp_Entidade" => 0,
			"strNome" => "$nomeDirecao",
			"strMorada_lin1" => "$s_address_1",
			"strMorada_lin2" => "$s_address_2",
			"strLocalidade" => "$s_city",
			"strpostal" => "$s_postcode",
			"strTelefone" => "$b_phone",
			"strFax" => "",
			"strEmail" => "$b_email",
			"strObs" => "",
			"intCodVendedor" => $custmDefSeller,
			"strAbrevSubZona" => $subzona,
			"dtmAbertura" => $dtmAbertura,
			"bitUsarDirFact" => 0
			]);
			
			$erroDirecao=$database->error();
			$errormsg=$erroDirecao[2];
			
			if($errormsg==""){
			$htmlmsg.="\nDireção #".$nDirecao." criada no ERP";	 
			}	
	}
	
	//  /Direção
	
	// Fatura eletrónica
	if($faturaEletronica=="1"){
	$contacto_existe=$database->count("Tbl_Contactos", ["strChaveEntidade" => $nCliente,"strCodTpContacto" => "$depFinTpContacto","intTipoEntidade"=>1]);
	if($contacto_existe==0){
	
		$dtmAbertura=date('Y-m-d H:i:s');	
		$qContacto=$database->select("Tbl_Contactos", [
				"intCodContacto"
			],[
				"ORDER" => ["intCodContacto" => "DESC"],
				"LIMIT" => 1
			]);
		$nContacto=$qContacto[0]['intCodContacto']+1;
		
		$database->insert("Tbl_Contactos", [
			"intCodContacto" => "$nContacto",
			"strNome" => "$b_firstname $b_lastname",
			"strEmail1" => $b_email,
			"intNumLinha" => "1",
			"dtmAbertura" => $dtmAbertura,
			"dtmAlteracao" => $dtmAbertura,
			"strCodTpContacto" => "$depFinTpContacto",
			"strChaveEntidade" => "$nCliente",
			"bitIsSincronizado" => 0,
			"bitInactivo" => 0,
			"intTipoEntidade" => "1"
		]);
			$erroContacto=$database->error();
			$errormsg=$erroContacto[2];
	}
	}
	// /Fatura eletrónica
	

###########################################################  /ADICIONA CLIENTE  ######################################################	
###########################################################    CRIA ENCOMENDA   ######################################################	
$Query_exerc=$database->query("SELECT TOP (1) strCodigo,YEAR(dtmInicio) as ano FROM Tbl_Exercicios WHERE (GETDATE() BETWEEN dtmInicio AND dtmFim) ORDER BY Id DESC")->fetchAll(PDO::FETCH_ASSOC);	
$anoxercicio=$Query_exerc[0]['ano'];
$Codexercicio=$Query_exerc[0]['strCodigo'];

$Q_numerador=$database->select("Tbl_Numeradores", [
				"strFormato","intNum_Mes00","Id"
			],[
				"strAbrevTpDoc" => $EncstrAbrevTpDoc,
				"strCodSeccao" => $strCodSeccao,
				"strCodExercicio" => $Codexercicio,
                "intTpNumerador" => 1,
				"ORDER" => ["Id" => "DESC"],
				"LIMIT" => 1
			]);	
	
$IdNumerador=$Q_numerador[0]['Id'];	 
$strFormato=$Q_numerador[0]['strFormato'];
$intNum_Mes00=$Q_numerador[0]['intNum_Mes00']+1;	
$digitos=substr_count($strFormato, '#');
$numeroEnc=str_replace("!AA!", substr($anoxercicio,-2),$strFormato);	
$numeroEnc=str_replace("!SECC!", $strCodSeccao.substr($anoxercicio,-2),$numeroEnc);
$numeroEnc=str_replace("!DOC!", $EncstrAbrevTpDoc,$numeroEnc);
$Q_IdDoc=$database->select("Tbl_Tipos_Documentos", ["Id"],["strAbreviatura" => $EncstrAbrevTpDoc,"LIMIT" => 1]); // Numeração do documento
$numeroEnc=str_replace("!IDDOC!", $Q_IdDoc[0]['Id'],$numeroEnc);  



$intNumeroEnc=$intNum_Mes00; 		// intNumero
$strnumeroEnc=str_replace(str_repeat("#", $digitos), str_pad($intNumeroEnc, $digitos, "0", STR_PAD_LEFT),$numeroEnc);	
if($strFormato==""){		$strnumeroEnc=$intNumeroEnc;	}

## Constantes 
	$strCodCondPag="1";
	$dtmDataVencimento=date('Y-m-d 00:00:00'); 
	$strAbrevMoeda="EUR";
	$strMeioExpedicao=1;
	$strLocalCarga="Morada do Remetente";
	$strLocalDescarga="$s_postcode $s_city";
	$dtmDataCarga=date('Y-m-d 00:00:00');
	$dtmDataEstado=$dtmDataCarga;
	$dtmDataAbertura=date('Y-m-d H:i:s').".000";
	$strHoraCarga=date('H:i'); 
	$strHora=date('H:i:s'); 
	$mensagemExtra="$s_company\n$s_firstname $s_last_name\n$s_address_1 $s_address_2\n$s_city \n$s_postcode $s_state\n$s_country\n\n\nCriado via encomendas web: #$id \n$customer_note\n";
    
## /Constantes 

$quantidTotal=0;
	foreach($linhas as $kartigo=>$vartigo){	
	$quantidTotal=$vartigo['quantity']+$quantidTotal;
	}

	if($total_tax>0){
	$fltTotalMercadoriaSIVA=$total-$total_tax;	
	} else {
	$fltTotalMercadoriaSIVA=$total/(($fltIVATaxa1/100)+1);  	
	} 
	
	## Comissões 
	if($orderDefSeller!=""){
		$Q_comissao=$database->select("Tbl_Gce_Vendedores", [
				"fltComissao","intTpComissoes"
			],[
				"intCodigo" => $orderDefSeller,
				"LIMIT" => 1
			]);	
			
	$fltComissao=$Q_comissao[0]['fltComissao'];		
	$intTpComissao=$Q_comissao[0]['intTpComissoes'];	
	$fltComissaoValor=$fltTotalMercadoriaSIVA*($fltComissao/100);
	} else {
	$fltComissao="";		
	$intTpComissao="";	
	$fltComissaoValor="";
	}
	## /Comissões

	$database->insert("Mov_Encomenda_Cab", [
		"intNumero" => $intNumeroEnc,
		"strNumero" => $strnumeroEnc,
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"dtmData" => $dtmDataCarga,
		"intCodEntidade" => $nCliente,
		"intDireccao" => isset($nDirecao)  ?: 0,
		"strCodCondPag" => $strCodCondPag,
		"dtmDataVencimento" => $dtmDataVencimento,
		"dtmDataRequisicao" => $dtmDataCarga,
		"strNumRequisicao" => $id,
		"dtmDataAbertura" => $dtmDataCarga,
		"dtmDataAlteracao" => $dtmDataAbertura,
		"dtmDataAlteracaoEstado" => $dtmDataAbertura,
		"dtmDataEntregaCab" => $dtmDataCarga,
		"strHoraEntregaCab" => $strHoraCarga,
		"strAbrevMoeda" => $strAbrevMoeda,
		"fltCambio" => 1,
		"fltCambioPagTroco" => 1,
		"intLinhaPreco" => 1,
		"strAbrevSubZona" => $subzona,
		"strAplicacaoOrigem" => "ENCP",
		"strObs" => $mensagemExtra,
		"strMeioExpedicao" => $strMeioExpedicao,
		"strLocalCarga" => $strLocalCarga,
		"strLocalDescarga" => $strLocalDescarga,
		"strECVDNumContrib" => "$nif",
		"strECVDNome" => "$nomeClie",
		"strECVDMorada" => "$b_address_1 $b_address_2",
		"strECVDCodPostal" => "$b_postcode",
		"strECVDTelefone" => "$b_phone",
		"strECVDLocalidade" => "$b_city",
		"fltInfTotalArtigos" => $quantidTotal,
		"fltInfTotalLinhas" => sizeof($linhas),
		"fltInfTotalQtd" => $quantidTotal,
		"strLogin" => "$strLogin",
		"strHora" => $strHora,
		"strNumExterno" => $id,
		"fltTotalMercadoriaSIVA" => $fltTotalMercadoriaSIVA,
		"fltTotalMercadoriaCIVA" => $total,
		"intIVACodTaxa1" => $intIVACodTaxa1,
		"intIVACodTaxa2" => -1,
		"intIVACodTaxa3" => -1,
		"intIVACodTaxa4" => -1,
		"intIVACodTaxa5" => -1,
		"intIVACodTaxa6" => -1,
		"fltIVATaxa1" => $fltIVATaxa1,
		"fltIVAIncidencia1" => $total,
		"bitIvaIncluido" => 0,
		"fltIVAValor1" => $total_tax,
		"fltTotalIVA" => $total_tax,
		"fltSubTotal" => $total,
		"fltTotal" => $total,
		"fltTotalToPay" => $total,
		"dtmDataEstado" => $dtmDataEstado,
		"intCodVendedor" => $orderDefSeller,
		"intTpComissao" => $intTpComissao,
		"fltComissaoValor" => $fltComissaoValor,
		"fltComissaoPercent" => $fltComissao,
		"fltComissaoValorBase" => number_format($fltTotalMercadoriaSIVA,2),
		"strMotivoIsencao" => "",
		"str1MotivoIsencao" => "",
        "CA_NaoSincronizar" => 1,
		"bitComissaoDispLiq" => 1,
		"dtmComissaodataDisp" => $dtmDataCarga
	]);
			
	$erroCab=$database->error();
	$errormsg=$erroCab[2];
	$cab_id = $database->id();
	
	if($errormsg==""){
	  
	
	$x=1;
	$totalIVA=0;
	 
	foreach($linhas as $kartigo=>$vartigo){	
	
	$count = $database->count("Tbl_Gce_Artigos", [
	"strCodigo" => $vartigo['product_sku']
	]);
	
	if($count>0){
	$Q_artigo=$database->select("Tbl_Gce_Artigos", [
				"intCodTaxaIvaVenda","bitNaoMovStk"
			],[
				"strCodigo" => $vartigo['product_sku'],
				"ORDER" => ["Id" => "DESC"],
				"LIMIT" => 1
			]);	
	$CodTaxaIva=$Q_artigo[0]['intCodTaxaIvaVenda'];	 
	$bitNaoMovStk=$Q_artigo[0]['bitNaoMovStk'];	
	
	
	$Q_IVA=$database->select("Tbl_Taxas_Iva", [
				"fltTaxa"
			],[
				"intCodigo" => $CodTaxaIva,
				"ORDER" => ["Id" => "DESC"],
				"LIMIT" => 1
			]);	
	$taxaIVA=$Q_IVA[0]['fltTaxa'];	 
	$codigoArtigo=$vartigo['product_sku'];
		
	} else {
	$CodTaxaIva=$intIVACodTaxa1;
	$taxaIVA=$fltIVATaxa1;	
	$codigoArtigo=$codDiversos;
	}
	   
	$fltValorMercadoriaSIVA=$vartigo['total']/(($taxaIVA/100)+1);
	
	$totalIVA=($vartigo['total']-$fltValorMercadoriaSIVA)+$totalIVA;
	 	
		
	if($codDiversos==$codigoArtigo){	  
	$database->insert("Mov_Encomenda_Lin", [
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
		"intNumLinha" => $x++,
		"intTpLinha" => 5,
		"strCodArtigo" => "$codigoArtigo",
		"strDescArtigo" => $vartigo['name'],
		"fltQuantidade" => $vartigo['quantity'],
        "fltQuantidadePend" => $vartigo['quantity'],
		"dtmDataEntrega" => "".$dtmDataCarga."",
		"strHoraEntrega" => "".$strHoraCarga."",
		"fltPrecoUnitario" => $vartigo['punit_siva'], 	      // S IVA   
		"fltValorLiquido" => $vartigo['totalsiva'],           // S IVA   
		"fltTaxaIVA" => $taxaIVA,
		"intCodTaxaIVA" => $CodTaxaIva,
		"fltValorMercadoriaCIVA" => $vartigo['total'],
		"fltValorMercadoriaSIVA" => $vartigo['totalsiva'],    // S IVA   
		"fltValorDescontosCIVA" => 0,
		"fltValorDescontosSIVA" => 0,
		"fltValorDescontosFinCIVA" => 0,
		"fltValorDescontosFinSIVA" => 0,
		"fltValorAPagar" => $vartigo['total']
		]);
	} else {
	$database->insert("Mov_Encomenda_Lin", [
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
		"strCodArmazem" => $armazemDefault,
		"intNumLinha" => $x++,
		"intTpLinha" => 5,
		"strCodArtigo" => $codigoArtigo,
		"strDescArtigo" => $vartigo['name'],
		"fltQuantidade" => $vartigo['quantity'],
		"intNumLinhaReserva" => $x,							// RESERVA STOCK
		"fltQuantidadePend" => $vartigo['quantity'],		// RESERVA STOCK
		"fltQtdReservarStk" => $vartigo['quantity'],		// RESERVA STOCK
		"fltQtdReservarStkOri" => $vartigo['quantity'],		// RESERVA STOCK
		"strCodClassMovStk" => 5,							// RESERVA STOCK 
		"dtmDataEntrega" => "".$dtmDataCarga."",
		"strHoraEntrega" => "".$strHoraCarga."",
		"fltPrecoUnitario" => $vartigo['punit_siva'],         // S IVA   
		"fltValorLiquido" => $vartigo['totalsiva'],           // S IVA    
		"fltTaxaIVA" => $taxaIVA,
		"intCodTaxaIVA" => $CodTaxaIva,
		"fltValorMercadoriaCIVA" => $vartigo['total'],
		"fltValorMercadoriaSIVA" => $vartigo['totalsiva'],    // S IVA  
		"fltValorDescontosCIVA" => 0,
		"fltValorDescontosSIVA" => 0,
		"fltValorDescontosFinCIVA" => 0,
		"fltValorDescontosFinSIVA" => 0,
		"fltValorAPagar" => $vartigo['total']
		]);		
	}
	
		
	$erroLinha=$database->error();
	$errormsg=$erroLinha[2];
	
	$linha_id = $database->id();
	if($bitNaoMovStk==0 && $linha_id>0){
		$database->update("Mov_Encomenda_Lin", [
		"strCodArmazem" => $armazemDefault
		],[
			"Id" => $linha_id
		]);
	}
	
}



	if($shipping_total>0){
		
	$fltPortesSIVA=$shipping_total/(($taxaIVA/100)+1);
	$totalIVA=($shipping_total-$fltPortesSIVA)+$totalIVA;
		
	$database->insert("Mov_Encomenda_Lin", [
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
        "strCodArmazem" => $armazemDefault,
		"intNumLinha" => $x++,
		"intTpLinha" => 5,
		"strCodArtigo" => "$codPortes",
		"strDescArtigo" => "Despesas de envio",
		"fltQuantidade" => 1,
		"fltQuantidadePend" => 1,
		"fltPrecoUnitario" => $fltPortesSIVA, // SEM IVA 
		"fltValorLiquido" => $shipping_total,
		"fltTaxaIVA" => $taxaIVA,
		"intCodTaxaIVA" => $CodTaxaIva,
		"fltValorMercadoriaCIVA" => $shipping_total,
		"fltValorMercadoriaSIVA" => $fltPortesSIVA,
		"fltValorDescontosCIVA" => 0,
		"fltValorDescontosSIVA" => 0,
		"fltValorDescontosFinCIVA" => 0,
		"fltValorDescontosFinSIVA" => 0,
		"fltValorAPagar" => $shipping_total
		]);
	}

	$database->insert("Mov_Encomenda_Lin",
	[
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
		"intNumLinha" => $x,
		"intTpLinha" => 0,
		"strDescArtigo" => "Encomenda web #$id",
	]);
 
  
 $database->update("Mov_Encomenda_Cab", [
	"fltIVAValor1" => $totalIVA,
	"fltTotalIVA" => $totalIVA,
	"fltIVAIncidencia1" => $total-$totalIVA,
	"fltSubTotal" => $total-$totalIVA,
	"fltTotalMercadoriaSIVA" => $total-$totalIVA,
	],[
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"intNumero" => $intNumeroEnc,
		"strCodSeccao" => $strCodSeccao,
		"strCodExercicio" => $Codexercicio
	]);

	}
	
if($errormsg==""){	
$database->update("Tbl_Numeradores", [
	"intNum_Mes00" => $intNumeroEnc
	],[
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"Id" => $IdNumerador,
		"strCodSeccao" => $strCodSeccao
	]);	
$erroNumerador=$database->error();
$errormsg=$erroNumerador[2];
}
	

if($errormsg==""){
$htmlmsg.="\nEncomenda $EncstrAbrevTpDoc ".$strnumeroEnc." criada no ERP";	
$orderCreated=1;
} 
	
###########################################################   /CRIA ENCOMENDA   ######################################################	
}
$mensagemHtml="";  
if($errormsg!=""){
$htmlmsg=$errormsg;	
$mensagem="0";	
}

if($debug!=""){	
$loghtmlBD="";
foreach($database->log() as $logBD){
$loghtmlBD.="<hr>$logBD";	
}

$mensagemHtml.="<b>$htmlmsg</b>".$loghtmlBD."<hr><b>id:</b> $id <b>Nome:</b>".$b_firstname." ".$b_lastname." <b>Morada:</b>".$b_address_1." ".$b_address_2."<hr><b>Linhas:</b> ".json_encode($linhas)."";  
enviaMail("nsantos@pombaldir.com",$_SERVER['HTTP_HOST']." webservice",$mensagemHtml);
} 

$output = array("response" => $mensagem, "msg" =>"$htmlmsg","custcreated"=>$customerCreated,"ordercreated"=>$orderCreated,"orderid"=>$cab_id,"strnumero"=>$strnumeroEnc,"customer"=>"$nCliente","nif"=>$nif,"nome"=>$nomeClie); 

}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }
}
