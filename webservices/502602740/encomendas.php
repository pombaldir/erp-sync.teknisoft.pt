<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);     
if(is_array($data)) { extract($data); }  
$tokenAPI="w6uYCkqqEWyw";
include("../v2.0/index.php");  

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

$mysqli = new mysqli("localhost",  "teknisof_crm06", "o_s$81Gh%U2k", "teknisof_c_rm006");

function callAPI($method, $url, $data){   
	// https://weichie.com/blog/curl-api-calls-with-php/
	$curl = curl_init();   
	switch (strtoupper($method)){      
		case "POST":         
			curl_setopt($curl, CURLOPT_POST, 1);         
			if ($data)	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);         
		break;      
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT"); if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					         
		break;      
		default:
			if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));   
	}   
		// OPTIONS:   
		curl_setopt($curl, CURLOPT_URL, $url);   
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-Shopify-Access-Token: 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','Content-Type: application/json',   ));   
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);   
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);   
		// EXECUTE:   
		$result = curl_exec($curl);   if(!$result){die("Connection Failure");}   
		curl_close($curl);   
		return $result;
}


function shopVendors(){
    $data_array =  array( "query" => '{ shop { productVendors(first:100){ edges { node } } } }');
	$get_data = callAPI('POST', 'https://electrominorlda.myshopify.com/admin/api/2024-07/graphql.json', json_encode($data_array));
	$response = json_decode($get_data, true);
	$errors = $response['response']['errors'];
	$data = $response['response']['data'][0];
	return $response['data']['shop'];
}


function shopMetaValues($endpId,$retKey="",$endp="products"){
    $ch = curl_init();  
    $url ='https://electrominorlda.myshopify.com/admin/api/2024-07/'.$endp.'/'.$endpId.'/metafields.json';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $streamVerboseHandle = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "X-Shopify-Access-Token: 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"
    ));
    
    $result = json_decode(curl_exec ($ch), true);  
    if ($result === FALSE) {
        printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),  htmlspecialchars(curl_error($ch)));
    } 
    curl_close($ch);  
    if($result==""){ rewind($streamVerboseHandle);  $verboseLog = stream_get_contents($streamVerboseHandle);  echo "cUrl verbose information:\n", "<pre>", htmlspecialchars($verboseLog), "</pre>\n";
    } else {
        $vals=$result['metafields']; 
        foreach($vals as $k=>$v){ //print_r($v); 
            if($v['key']=="erp_code"){
                return $v['value'];
            } 
        
    }
    }
}


function getVendorCode($vendorName){
	global $mysqli;

	$query = $mysqli->query("SELECT codigo from tbl_shop_fornec WHERE nome='$vendorName'") or die($mysqli->errno .' - '. $mysqli->error);
	$qM = $query->fetch_assoc();
	$valDB=$qM['codigo'];
	return $valDB;
}


function getArtigoBySKU($sku,$vendor=""){
	global $database;
	$codARtigo="";

	$count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $sku]);
	if($count==1){
		$codARtigo=$sku;
	} else {
		$vendorCode=getVendorCode($vendor);
		$artigo=$vendorCode.".".$sku;
		$count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $artigo]);
		if($count==1){
			$codARtigo=$artigo;
		} else {
			//return  $vendorCode;
		}
	}
	return $codARtigo;
}
 

//getArtigoBySKU($sku,$vendor="") 

if(!isset($id)){ /* */ die(json_encode(shopVendors())); die(json_encode(getArtigoBySKU("0002771","Osram")));  }

//error_log($data."\n\n\n", 3, "encomendas.log");
//error_log(var_dump($data)."\n\n\n", 3, "encomendas2.log");
error_log(json_encode($data)."\n\n\n", 3, "".$id.".log");


//die(); 
//die(json_encode("OK"));
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($_SERVER['REQUEST_METHOD'] === 'POST'){
# # # # # # # # # # # # # # # # # # # # # # # # #  // SHOPIFY // # # # # # # # # # # # # # # # # # # # # # # # # # #
$b_firstname=strtoupper($billing_address['first_name']);
$b_lastname=strtoupper($billing_address['last_name']); 
$b_company=strtoupper($billing_address['company']); 
$b_address_1=strtoupper($billing_address['address1']); 
$b_address_2=strtoupper($billing_address['address2']); 
$b_city=strtoupper($billing_address['city']); 
$b_state=strtoupper($billing_address['province']); 
$b_postcode=$billing_address['zip']; 
$b_country=strtoupper($billing_address['country']); 
$b_email=$customer['email']; 
$b_phone=$billing_address['phone']; 

$s_firstname=strtoupper($shipping_address['first_name']);
$s_last_name=strtoupper($shipping_address['last_name']);
$s_company=$shipping_address['company'];
$s_address_1=strtoupper($shipping_address['address1']);
$s_address_2=strtoupper($shipping_address['address2']);
$s_city=strtoupper($shipping_address['city']);
$s_state=strtoupper($shipping_address['province']);
$s_postcode=$shipping_address['zip'];
$s_country=strtoupper($shipping_address['country']);
 
//$shipping_total=$shipping_lines[0]['price']-$shipping_lines[0]['discounted_price']; 
$shipping_total=$total_shipping_price_set['shop_money']['amount'];
//$shipping_tax=$total_tax_set['shop_money']['amount']; 
$nif=$shipping_address['company']; 
$totalIVA=number_format($tax_lines[0]['price'],1); 

#################################################################	INSERE NO ERP  ######################################################		
$nomeClie="".$b_firstname." ".$b_lastname.""; 
//if($s_company!="" && strlen($s_company)>3){ $nomeClie=$s_company; } 

//die(json_encode("$nomeClie"));

$nif=substr(preg_replace('/\s+/', '', $nif), -9);
if($nif=="" || strlen($nif)<9){ $bitConsumidorFinal=1; $nif="CONSUMIDOR FINAL"; 
## APAGAMOS AS VARIÁVEIS DE DADOS DO CLIENTE ##
//$nomeClie=$b_address_1=$b_address_2=$b_postcode=$b_phone=$b_city=$b_email="";
} else { $bitConsumidorFinal=0; }

$rDocExiste = $database->count("Mov_Encomenda_Cab",["Id"],["strNumExterno" => "$id","strAbrevTpDoc" => $EncstrAbrevTpDoc,"strCodSeccao" => $strCodSeccao]);
$customerCreated=0;
$strnumeroEnc="";
$cab_id="";
$orderCreated=0;
$errormsg="";
$mensagem="1";
$htmlmsg="";
 
if($rDocExiste==0){ 
	erpLog("Doc ".$id." não existe, NIF=$nif, CF=$bitConsumidorFinal");  
###########################################################    CRIA ENCOMENDA   ######################################################	
$Query_exerc=$database->query("SELECT TOP (1) strCodigo,YEAR(dtmInicio) as ano FROM Tbl_Exercicios WHERE (GETDATE() BETWEEN dtmInicio AND dtmFim) ORDER BY Id DESC")->fetchAll(PDO::FETCH_ASSOC);	
$anoxercicio=$Query_exerc[0]['ano'];
$Codexercicio=$Query_exerc[0]['strCodigo'];

$Q_numerador=$database->select("Tbl_Numeradores", [
				"strFormato","intNum_Mes00","Id","strATValidationCode"
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
$strATValidationCode=$Q_numerador[0]['strATValidationCode'];
$digitos=substr_count($strFormato, '#');
$numeroEnc=str_replace("!AA!", substr($anoxercicio,-2),$strFormato);	
$numeroEnc=str_replace("!SECC!", $strCodSeccao.substr($anoxercicio,-2),$numeroEnc);
$numeroEnc=str_replace("!DOC!", $EncstrAbrevTpDoc,$numeroEnc);
$Q_IdDoc=$database->select("Tbl_Tipos_Documentos", ["Id"],["strAbreviatura" => $EncstrAbrevTpDoc,"LIMIT" => 1]); // Numeração do documento
$numeroEnc=str_replace("!IDDOC!", $Q_IdDoc[0]['Id'],$numeroEnc);  
 
$intNumeroEnc=$intNum_Mes00; 		// intNumero
if($strATValidationCode!=""){ $strATValidationCode=$strATValidationCode."-".$intNumeroEnc;}	// ATCUD 
$strnumeroEnc=str_replace(str_repeat("#", $digitos), str_pad($intNumeroEnc, $digitos, "0", STR_PAD_LEFT),$numeroEnc);	
if($strFormato==""){		$strnumeroEnc=$intNumeroEnc;	}

## Constantes 
	$dtmDataVencimento=date('Y-m-d 00:00:00'); 
	$strAbrevMoeda="EUR";
	$strLocalCarga="Morada do Remetente";
	$strLocalDescarga="$s_postcode $s_city";
	$dtmDataCarga=date('Y-m-d 00:00:00');
	$dtmDataEstado=$dtmDataCarga;
	$dtmDataAbertura=date('Y-m-d H:i:s').".000";
	$strHoraCarga=date('H:i'); 
	$strHora=date('H:i:s'); 
	$note=($note!=NULL && $note!="NULL" && $note!="") ? $note : "";
	$mensagemExtra="REFERENTE A ENCOMENDA ONLINE: $name \n$note\n"; 
## /Constantes 
 
$quantidTotal=0;
	foreach($line_items as $kartigo=>$vartigo){	
	$quantidTotal=$vartigo['current_quantity']+$quantidTotal;
	}

	/*
	if($total_tax>0){
		$CABfltTotalMercadoriaSIVA=$total_price-$total_tax;	
	} else {
		$CABfltTotalMercadoriaSIVA=$total_price/(($fltIVATaxa1/100)+1);  	
	} 
	*/


	//$CABfltTotalMercadoriaSIVA=$total_price-$total_tax;	
	$CABfltTotalMercadoriaSIVA=truncate($total_price/(($fltIVATaxa1/100)+1),2);  	
	
	## Comissões 
	if($orderDefSeller!=""){
		$Q_comissao=$database->select("Tbl_Gce_Vendedores", [ "fltComissao","intTpComissoes" ],[ "intCodigo" => $orderDefSeller,"LIMIT" => 1]);		
		$fltComissao=$Q_comissao[0]['fltComissao'];		
		$intTpComissao=$Q_comissao[0]['intTpComissoes'];	
		$fltComissaoValor=$CABfltTotalMercadoriaSIVA*($fltComissao/100);
	} else {
		$fltComissao="";		
		$intTpComissao="";	
		$fltComissaoValor="";
	}
	## /Comissões
	//die(json_encode("$nomeClie"));
	erpLog("Encomenda $id - Inserir CAB $strnumeroEnc");
  
	$database->insert("Mov_Encomenda_Cab", [
		"intNumero" => $intNumeroEnc,
		"strNumero" => $strnumeroEnc,
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"dtmData" => $dtmDataCarga,
		"intCodEntidade" => 0,
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
        "strECVDEmail" => "$b_email",
		//"fltInfTotalArtigos" => $quantidTotal,
		//"fltInfTotalLinhas" => sizeof($line_items),
		"fltInfTotalQtd" => $quantidTotal,
		//"strLogin" => "$strLogin",
		"strHora" => $strHora,
		"strNumExterno" => $id,
		"fltTotalMercadoriaSIVA" => $CABfltTotalMercadoriaSIVA,
		"fltTotalMercadoriaCIVA" => $total_price,
		"intIVACodTaxa1" => $intIVACodTaxa1,
		"intIVACodTaxa2" => -1,
		"intIVACodTaxa3" => -1,
		"intIVACodTaxa4" => -1,
		"intIVACodTaxa5" => -1,
		"intIVACodTaxa6" => -1,


		"fltSubTotal" => $CABfltTotalMercadoriaSIVA,
		"fltTotal" => $total_price,
		"fltTotalToPay" => $total_price,
		"dtmDataEstado" => $dtmDataEstado,
		"intCodVendedor" => $orderDefSeller,
		"intTpComissao" => $intTpComissao,
		"fltComissaoValor" => $fltComissaoValor,
		"fltComissaoPercent" => $fltComissao,
		"fltComissaoValorBase" => $CABfltTotalMercadoriaSIVA,

		"fltIVAValor1" => $totalIVA,
		"fltIVATaxa1" => $fltIVATaxa1,
		"fltIVAIncidencia1" => $CABfltTotalMercadoriaSIVA,
		"bitIvaIncluido" => 0,
		"fltTotalIVA" => $totalIVA,


		
		"bitAddYearIdDocAT" => 1,
		"strMotivoIsencao" => "",
		"str1MotivoIsencao" => "",
		"strATCUD" => isset($strATValidationCode) ? $strATValidationCode : "",
		"intHashControl" => 1,
		"strSaftDocNO" => $strnumeroEnc,
		"bitComissaoDispLiq" => 1,
		"dtmComissaodataDisp" => $dtmDataCarga
	]);

		  	
	$erroCab=$database->error();
	$errormsg=$erroCab[2];
	$cab_id = $database->id();
	
	if($errormsg==""){
	  
	
	$x=1;
	$intNumLinhaReserva=0;
	
	$fltTotalMercadoriaSIVA=0;
	$fltInfTotalArtigos=1;

	$database->insert("Mov_Encomenda_Lin",
	[
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
		"intNumLinha" => $x++,
		"intNumLinhaReserva" => $intNumLinhaReserva++,
		"intTpLinha" => 0,
		"intTipoAssociacao" => 0,
		"strDescArtigo" => "REFERENTE A ENCOMENDA ONLINE: $name",
	]);  

	foreach($line_items as $kartigo=>$vartigo){	
		//$codigoArtigo=$vartigo['sku'];
		$codigoArtigo=shopMetaValues($vartigo['product_id'],'erp_code');  
		
		if($codigoArtigo==""){
			$codigoArtigo=getArtigoBySKU($vartigo['sku'],$vartigo['vendor']);
		} 
  
		//$codigoArtigo=str_replace(" ","",$codigoArtigo);
		//$count = $database->count("Tbl_Gce_Artigos", [ "strCodigo[~]" => $codigoArtigo]);
		$count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $codigoArtigo]);
		//error_log(json_encode($database->log())."\n\n\n", 3, "".$id."teste.log");
		 
		if($count>0){
		$Q_artigo=$database->select("Tbl_Gce_Artigos", [ "intCodTaxaIvaVenda","bitNaoMovStk","strDescricao"],[ "strCodigo" => $codigoArtigo,"ORDER" => ["Id" => "DESC"],"LIMIT" => 1]);	
		$CodTaxaIva=$Q_artigo[0]['intCodTaxaIvaVenda'];	 
		$bitNaoMovStk=$Q_artigo[0]['bitNaoMovStk'];	
		$strDescArtigo=$Q_artigo[0]['strDescricao'];	
		## PREÇO DE CUSTO MÉDIO
		$Q_artigoPC=$database->select("Tbl_Gce_ArtigosPrecosCusto", ["fltPCMedio"],[ "strCodArtigo" => $codigoArtigo,"ORDER" => ["Id" => "DESC"],"LIMIT" => 1]);	
		if(is_array($Q_artigoPC) && sizeof($Q_artigoPC)>0){ $fltCustoUnitario=number_format($Q_artigoPC[0]['fltPCMedio'],3);  } else {$fltCustoUnitario=0; }
		
		$Q_IVA=$database->select("Tbl_Taxas_Iva", [ "fltTaxa" ],[ "intCodigo" => $CodTaxaIva, "ORDER" => ["Id" => "DESC"],"LIMIT" => 1]);	
		$taxaIVA=$Q_IVA[0]['fltTaxa'];	 
		

		} else {
		$CodTaxaIva=$intIVACodTaxa1;
		$taxaIVA=$fltIVATaxa1;	
		$codigoArtigo=$codDiversos;
		$fltCustoUnitario=0;
		$strDescArtigo=$vartigo['name'];
		}
		
		//$precoUnitSIVA=$vartigo['price']; 
		//$precoUnitCIVA=$vartigo['price']*((number_format($vartigo['tax_lines'][0]['rate'],2)+1));
 
		$DESCONTOARTIGO=$vartigo['discount_allocations']['amount']/$vartigo['quantity'];
		$precoUnitCIVAORIGI=$vartigo['price']-$DESCONTOARTIGO;  
		$precoUnitSIVA=$precoUnitCIVAORIGI/((number_format($vartigo['tax_lines'][0]['rate'],2)+1));
		$precoUnitSIVA=truncate($precoUnitSIVA, 3);
 
		$fltValorMercadoriaSIVA=truncate($vartigo['quantity']*$precoUnitSIVA,2);
		$fltValorAPagar=$fltValorMercadoriaSIVA*((number_format($vartigo['tax_lines'][0]['rate'],2)+1)); 
		$fltValorMercadoriaCIVA=round_up($fltValorAPagar,2);  
		
		$margemLucro ="";  
		if($count>0 && $fltCustoUnitario>0 && $precoUnitSIVA>0){
		$margemLucro=number_format((($precoUnitSIVA-$fltCustoUnitario)/$fltCustoUnitario)*100,2);  
		}    

		$fltTotalMercadoriaSIVA=$fltValorMercadoriaSIVA+$fltTotalMercadoriaSIVA;
 
		//$totalIVA=($vartigo['total']-$fltValorMercadoriaSIVA)+$totalIVA;
		$arrDados=array(); 
		if($codDiversos!=$codigoArtigo){	
			//$arrDados['fltQtdReservarStk']=$vartigo['quantity'];
			//$arrDados['fltQtdReservarStkOri']=$vartigo['quantity'];
			//$arrDados['strCodClassMovStk']=5;
			$arrDados['strCodArmazem']=$armazemDefault;
		}  
		
		$arrDados['fltQuantidadePend']=$vartigo['quantity'];
		
		
		$arrDados['strCodSeccao']=$strCodSeccao;
		$arrDados['strAbrevTpDoc']=$EncstrAbrevTpDoc;
		$arrDados['strCodExercicio']=$Codexercicio;
		$arrDados['intNumero']=$intNumeroEnc;
		$arrDados['intNumLinhaReserva']=$intNumLinhaReserva++;
		$arrDados['intNumLinha']=$x++;
		$arrDados['intTpLinha']=5;
		$arrDados['strCodArtigo']=$codigoArtigo;
		$arrDados['strDescArtigo']=$strDescArtigo;
		$arrDados['fltQuantidade']=$vartigo['quantity'];
		$arrDados['dtmDataEntrega']=$dtmDataCarga;
		$arrDados['strHoraEntrega']=$strHoraCarga;
		$arrDados['fltPrecoUnitario']=$precoUnitSIVA; 	// SEM IVA   
		$arrDados['fltValorLiquido']=$fltValorMercadoriaSIVA;
		$arrDados['fltTaxaIVA']=$taxaIVA;
		$arrDados['intCodTaxaIVA']=$CodTaxaIva;
		$arrDados['fltValorMercadoriaCIVA']=$fltValorMercadoriaCIVA;
		$arrDados['fltValorMercadoriaSIVA']=$fltValorMercadoriaSIVA;
		$arrDados['fltValorDescontosCIVA']=0;
		$arrDados['fltValorDescontosSIVA']=0;
		$arrDados['fltValorDescontosFinCIVA']=0;
		$arrDados['fltValorDescontosFinSIVA']=0;
		$arrDados['intPriceLine']=0;
		$arrDados['strFormula']=1;
		$arrDados['intTipoAssociacao']=0;
		$arrDados['strAnulFormula']=1;
		$arrDados['fltCustoUnitario']=$fltCustoUnitario;
		$arrDados['fltValorAPagar']=truncate($fltValorAPagar,2);
		if($count>0 && is_numeric($fltCustoUnitario) && $fltCustoUnitario>0){
		$arrDados['CA_Campo02']=number_format($fltCustoUnitario,2);
		$arrDados['CA_Campo01']=$margemLucro; 
		} 
		

		$database->insert("Mov_Encomenda_Lin", $arrDados);	
			
		$fltInfTotalArtigos++;
		$erroLinha=$database->error();
		$errormsg=$erroLinha[2];
		$linha_id = $database->id();

		if($errormsg!=""){	
			$database->delete("Mov_Encomenda_Lin",["Id" => $linha_id]);
			die(json_encode($errormsg));
		} else {
			erpLog("*** Linha ".$x." Inserida: ".$codigoArtigo.""); 
		}	
	}
	
	###### LINHA DE PORTES ###################################################################################################
	if($shipping_total==0){ $codPortes=$codPortesGratis; $descrPortes="PORTES GRÁTIS"; } else { $descrPortes="PORTES PAGOS"; }
		
	$fltPortesCIVA=$shipping_total/(($taxaIVA/100)+1);
		
	$database->insert("Mov_Encomenda_Lin", [
		"strCodSeccao" => $strCodSeccao,
		"strAbrevTpDoc" => $EncstrAbrevTpDoc,
		"strCodExercicio" => $Codexercicio,
		"intNumero" => $intNumeroEnc,
		"intNumLinha" => $x,
		"intNumLinhaReserva" => $intNumLinhaReserva++,
		"intTpLinha" => 5,
		"strCodArtigo" => $codPortes,
		"strDescArtigo" => $descrPortes,
		"fltQuantidade" => 1,
		"strFormula" => 1,
		"strAnulFormula" => 1,
		"fltQuantidadePend" => 1,
		"fltQuantidadeSatisf" => 0,
		"intTipoAssociacao" => 0,
		"fltPrecoUnitario" => $fltPortesCIVA, // SEM IVA 
		"fltValorLiquido" => number_format($fltPortesCIVA,2), 
		"fltTaxaIVA" => $taxaIVA,
		"intCodTaxaIVA" => $CodTaxaIva,
		"fltValorMercadoriaCIVA" => $shipping_total,
		"fltValorMercadoriaSIVA" => number_format($fltPortesCIVA,2),
		"dtmDataEntrega"=>$dtmDataCarga,
		"strHoraEntrega" => $strHoraCarga,
		"fltValorDescontosCIVA" => 0,
		"fltValorDescontosSIVA" => 0,
		"fltValorDescontosFinCIVA" => 0,
		"fltValorDescontosFinSIVA" => 0,
		"fltValorAPagar" => $shipping_total
		]);
	###### /LINHA DE PORTES ###################################################################################################
		$erroLinha=$database->error();	$errormsg=$erroLinha[2];
		if($errormsg!=""){ 	## OCORREU ERRO NA LINHA DE PORTES....	
			$database->delete("Mov_Encomenda_Lin",[
				"strAbrevTpDoc" => $EncstrAbrevTpDoc,
				"intNumero" => $intNumeroEnc,
				"strCodSeccao" => $strCodSeccao,
				"strCodExercicio" => $Codexercicio]);
			$database->delete("Mov_Encomenda_Cab",["Id" => $cab_id]);
			erpLog("Encomenda $id - ".$errormsg."");
			} else {		## NÃO OCORREU ERRO NA LINHA DE PORTES....
				
		$database->update("Mov_Encomenda_Cab", [
		//"fltSubTotal" => $fltTotalMercadoriaSIVA,
		//"fltTotalMercadoriaSIVA" => $fltTotalMercadoriaSIVA,
		"fltInfTotalArtigos" => $fltInfTotalArtigos++,
		"fltInfTotalLinhas" => $x-1,
		"fltInfTotalQtd" => Medoo::raw("fltInfTotalQtd+1")
		],[
			"strAbrevTpDoc" => $EncstrAbrevTpDoc,
			"intNumero" => $intNumeroEnc,
			"strCodSeccao" => $strCodSeccao,
			"strCodExercicio" => $Codexercicio
		]);

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

		if($errormsg==""){
			$htmlmsg.="\nEncomenda ".$strnumeroEnc." criada no ERP";	
			$orderCreated=1;
		} else {
			$htmlmsg.=$errormsg;	
		} 
	}
	else {
		erpLog("Encomenda $id - ERRO CAB: ".$errormsg."");   
		erpLog("Encomenda $id - ".json_encode($database->log()[array_key_last($database->log())] )."");
		$database->delete("Mov_Encomenda_Cab",["Id" => $cab_id]);
	} 
	erpLog("*** Linha ".$x." Inserida (Portes): ".$codPortes.""); 
}	

} else {
	$htmlmsg="Encomenda $id existe";
	erpLog($htmlmsg);    
}

}
} 

###########################################################   /CRIA ENCOMENDA   ######################################################	

$mensagemHtml="";  
if($errormsg!=""){
$htmlmsg=$errormsg;	
$mensagem="0";	
}

if(isset($debug) && $debug!=""){	
$loghtmlBD="";
foreach($database->log() as $logBD){
$loghtmlBD.="<hr>$logBD";	
}
 
$mensagemHtml.="<b>$htmlmsg</b>".$loghtmlBD."<hr><b>id:</b> $id <b>Nome:</b>".$b_firstname." ".$b_lastname." <b>Morada:</b>".$b_address_1." ".$b_address_2."<hr><b>Linhas:</b> ".json_encode($linhas)."";  
enviaMail("nsantos@pombaldir.com",$_SERVER['HTTP_HOST']." webservice",$mensagemHtml);
} 

$output = array("response" => $mensagem, "msg" =>"$htmlmsg","custcreated"=>$customerCreated,"ordercreated"=>$orderCreated,"orderid"=>$cab_id,"strnumero"=>$strnumeroEnc,"nif"=>$nif,"nome"=>$nomeClie); 


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }
