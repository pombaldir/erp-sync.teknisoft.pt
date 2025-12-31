<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

/**
 * FIX: Shopify só precisa 200. E qualquer warning pode causar reenvio.
 * Responder 200 cedo ajuda a evitar reenvios em alguns ambientes.
 */
http_response_code(200);

$id = $data['id'] ?? null;

$tokenAPI = "w6uYCkqqEWyw";
include("../v2.0/index.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

$mysqli = new mysqli("localhost", "teknisof_crm06", "o_s$81Gh%U2k", "teknisof_c_rm006");

function callAPI($method, $url, $data){
    $curl = curl_init();
    switch (strtoupper($method)){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        break;
        default:
            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'X-Shopify-Access-Token: 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',
        'Content-Type: application/json'
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    $result = curl_exec($curl);
    if (!$result) { die("Connection Failure"); }
    curl_close($curl);
    return $result;
}

function shopVendors(){
    $data_array = array("query" => '{ shop { productVendors(first:100){ edges { node } } } }');
    $get_data = callAPI('POST', 'https://electrominorlda.myshopify.com/admin/api/2024-07/graphql.json', json_encode($data_array));
    $response = json_decode($get_data, true);
    return $response['data']['shop'] ?? [];
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "X-Shopify-Access-Token: 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"
    ));

    $result = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if (!is_array($result) || empty($result['metafields'])) return "";

    foreach ($result['metafields'] as $v){
        if (($v['key'] ?? '') === "erp_code"){
            return $v['value'] ?? "";
        }
    }
    return "";
}

function getVendorCode($vendorName){
    global $mysqli;
    $vendorName = $mysqli->real_escape_string($vendorName);
    $query = $mysqli->query("SELECT codigo from tbl_shop_fornec WHERE nome='$vendorName'") or die($mysqli->errno .' - '. $mysqli->error);
    $qM = $query->fetch_assoc();
    return $qM['codigo'] ?? "";
}

function getArtigoBySKU($sku,$vendor=""){
    global $database;
    $codARtigo = "";

    $count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $sku ]);
    if($count==1){
        $codARtigo=$sku;
    } else {
        $vendorCode=getVendorCode($vendor);
        $artigo=$vendorCode.".".$sku;
        $count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $artigo ]);
        if($count==1){
            $codARtigo=$artigo;
        }
    }
    return $codARtigo;
}

if(!isset($id) || $id === null){
    echo json_encode(["ok" => true, "msg" => "sem id"]);
    exit;
}

error_log(json_encode($data)."\n\n\n", 3, "".$id.".log");

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    echo json_encode(["ok" => true, "msg" => "método não POST"]);
    exit;
}

/* =========================
   MAPS + DEFAULTS
========================= */

$name       = $data['name'] ?? '';
$note       = $data['note'] ?? '';
$tags       = $data['tags'] ?? '';
$created_at = $data['created_at'] ?? null;

$customer   = $data['customer'] ?? [];
$billing_address  = $data['billing_address'] ?? [];
$shipping_address = $data['shipping_address'] ?? [];

$line_items = $data['line_items'] ?? [];

$total_price    = (float)($data['total_price'] ?? 0);
$subtotal_price = (float)($data['subtotal_price'] ?? 0);
$total_tax      = (float)($data['total_tax'] ?? 0);

$tax_lines = $data['tax_lines'] ?? [];

$shipping_lines = $data['shipping_lines'] ?? [];
$total_shipping_price_set = $data['total_shipping_price_set'] ?? ['shop_money' => ['amount' => 0]];

$discount_codes  = $data['discount_codes'] ?? [];
$total_discounts = (float)($data['total_discounts'] ?? 0);

$financial_status = $data['financial_status'] ?? '';
$gateway = $data['payment_gateway_names'][0] ?? '';
$currency = $data['currency'] ?? 'EUR';

$order_number = $data['order_number'] ?? null;
$source_name  = $data['source_name'] ?? 'shopify';

/**
 * FIX: respeitar taxes_included
 */
$taxesIncluded = (bool)($data['taxes_included'] ?? false);

/**
 * FIX: garantir arrays com chaves
 */
$billing_address += [
    'first_name' => '',
    'last_name'  => '',
    'company'    => '',
    'address1'   => '',
    'address2'   => '',
    'city'       => '',
    'province'   => '',
    'zip'        => '',
    'country'    => '',
    'phone'      => ''
];

$shipping_address += [
    'first_name' => '',
    'last_name'  => '',
    'company'    => '',
    'address1'   => '',
    'address2'   => '',
    'city'       => '',
    'province'   => '',
    'zip'        => '',
    'country'    => '',
    'phone'      => ''
];

/* =========================
   DADOS CLIENTE
========================= */

$b_firstname = strtoupper($billing_address['first_name']);
$b_lastname  = strtoupper($billing_address['last_name']);
$b_address_1 = strtoupper($billing_address['address1']);
$b_address_2 = strtoupper($billing_address['address2']);
$b_city      = strtoupper($billing_address['city']);
$b_state     = strtoupper($billing_address['province']);
$b_postcode  = $billing_address['zip'];
$b_country   = strtoupper($billing_address['country']);
$b_email     = $customer['email'] ?? ($data['email'] ?? '');
$b_phone     = $billing_address['phone'];

$s_firstname = strtoupper($shipping_address['first_name']);
$s_last_name = strtoupper($shipping_address['last_name']);
$s_company   = $shipping_address['company'] ?? '';
$s_address_1 = strtoupper($shipping_address['address1']);
$s_address_2 = strtoupper($shipping_address['address2']);
$s_city      = strtoupper($shipping_address['city']);
$s_state     = strtoupper($shipping_address['province']);
$s_postcode  = $shipping_address['zip'];
$s_country   = strtoupper($shipping_address['country']);

/**
 * FIX: shipping_total vem como string "0.00"
 */
$shipping_total = (float)($total_shipping_price_set['shop_money']['amount'] ?? 0);

/**
 * FIX: totalIVA NÃO usar number_format (devolve string)
 * e tax_lines pode não existir
 */
$totalIVA = (float)($tax_lines[0]['price'] ?? $total_tax);

/**
 * FIX: taxa IVA encomenda (percentagem)
 * tax_lines[0]['rate'] = 0.23
 */
$fltIVATaxa1 = (float)($tax_lines[0]['rate'] ?? (($fltIVATaxa1 ?? 23) / 100)) * 100;

/**
 * NIF (no teu caso estás a usar company)
 */
$nif = (string)($shipping_address['company'] ?? '');
$nomeClie = trim($b_firstname." ".$b_lastname);

$nif = substr(preg_replace('/\s+/', '', $nif), -9);
if($nif=="" || strlen($nif)<9){
    $bitConsumidorFinal=1;
    $nif="CONSUMIDOR FINAL";
} else {
    $bitConsumidorFinal=0;
}

/* =========================
   FIX: IDEMPOTÊNCIA REAL
========================= */
$rDocExiste = $database->count("Mov_Encomenda_Cab", ["Id"], [
    "strNumExterno" => (string)$id,
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "strCodSeccao"  => $strCodSeccao
]);

if ($rDocExiste > 0) {
    echo json_encode(["response" => "1", "msg" => "Encomenda $id existe", "ordercreated" => 0, "orderid" => 0]);
    exit;
}

/* =========================
   A PARTIR DAQUI: O TEU FLUXO NORMAL
========================= */

$customerCreated=0;
$strnumeroEnc="";
$cab_id="";
$orderCreated=0;
$errormsg="";
$mensagem="1";
$htmlmsg="";

erpLog("Doc ".$id." não existe, NIF=$nif, CF=$bitConsumidorFinal");

/* Exercício + numerador */
$Query_exerc = $database->query("SELECT TOP (1) strCodigo,YEAR(dtmInicio) as ano FROM Tbl_Exercicios WHERE (GETDATE() BETWEEN dtmInicio AND dtmFim) ORDER BY Id DESC")
    ->fetchAll(PDO::FETCH_ASSOC);

$anoxercicio = $Query_exerc[0]['ano'] ?? date('Y');
$Codexercicio = $Query_exerc[0]['strCodigo'] ?? ($Codexercicio ?? "");

$Q_numerador = $database->select("Tbl_Numeradores", [
    "strFormato","intNum_Mes00","Id","strATValidationCode"
],[
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "strCodSeccao" => $strCodSeccao,
    "strCodExercicio" => $Codexercicio,
    "intTpNumerador" => 1,
    "ORDER" => ["Id" => "DESC"],
    "LIMIT" => 1
]);

$IdNumerador = $Q_numerador[0]['Id'] ?? 0;
$strFormato  = $Q_numerador[0]['strFormato'] ?? "";
$intNum_Mes00 = (int)($Q_numerador[0]['intNum_Mes00'] ?? 0) + 1;
$strATValidationCode = $Q_numerador[0]['strATValidationCode'] ?? "";

$digitos = substr_count($strFormato, '#');
$numeroEnc = str_replace("!AA!", substr($anoxercicio,-2), $strFormato);
$numeroEnc = str_replace("!SECC!", $strCodSeccao.substr($anoxercicio,-2), $numeroEnc);
$numeroEnc = str_replace("!DOC!", $EncstrAbrevTpDoc, $numeroEnc);

$Q_IdDoc = $database->select("Tbl_Tipos_Documentos", ["Id"], ["strAbreviatura" => $EncstrAbrevTpDoc, "LIMIT" => 1]);
$numeroEnc = str_replace("!IDDOC!", $Q_IdDoc[0]['Id'] ?? 0, $numeroEnc);

$intNumeroEnc = $intNum_Mes00;
if($strATValidationCode!=""){ $strATValidationCode=$strATValidationCode."-".$intNumeroEnc; }

$strnumeroEnc = str_replace(str_repeat("#", $digitos), str_pad($intNumeroEnc, $digitos, "0", STR_PAD_LEFT), $numeroEnc);
if($strFormato==""){ $strnumeroEnc=$intNumeroEnc; }

/* Constantes */
$dtmDataVencimento = date('Y-m-d 00:00:00');
$strAbrevMoeda="EUR";
$strLocalCarga="Morada do Remetente";
$strLocalDescarga="$s_postcode $s_city";
$dtmDataCarga=date('Y-m-d 00:00:00');
$dtmDataEstado=$dtmDataCarga;
$dtmDataAbertura=date('Y-m-d H:i:s').".000";
$strHoraCarga=date('H:i');
$strHora=date('H:i:s');

$note = ($note!==NULL && $note!=="NULL" && $note!=="") ? $note : "";
$mensagemExtra="REFERENTE A ENCOMENDA ONLINE: $name \n$note\n";

/* Quantidades */
$quantidTotal=0;
foreach($line_items as $vartigo){
    $quantidTotal += (int)($vartigo['current_quantity'] ?? $vartigo['quantity'] ?? 0);
}

/**
 * FIX: total S/IVA no CAB depende de taxes_included
 * - Se taxes_included=true: SIVA = total_price - totalIVA
 * - Se taxes_included=false: SIVA = total_price / (1+taxa)
 */
if ($taxesIncluded) {
    $CABfltTotalMercadoriaSIVA = truncate($total_price - $totalIVA, 2);
} else {
    $CABfltTotalMercadoriaSIVA = truncate($total_price / (1 + ($fltIVATaxa1/100)), 2);
}

/* Comissões (mantido) */
if($orderDefSeller!=""){
    $Q_comissao=$database->select("Tbl_Gce_Vendedores", [ "fltComissao","intTpComissoes" ],[ "intCodigo" => $orderDefSeller,"LIMIT" => 1]);
    $fltComissao=(float)($Q_comissao[0]['fltComissao'] ?? 0);
    $intTpComissao=$Q_comissao[0]['intTpComissoes'] ?? "";
    $fltComissaoValor=$CABfltTotalMercadoriaSIVA*($fltComissao/100);
} else {
    $fltComissao="";
    $intTpComissao="";
    $fltComissaoValor="";
}

erpLog("Encomenda $id - Inserir CAB $strnumeroEnc");

/* INSERT CAB */
$database->insert("Mov_Encomenda_Cab", [
    "intNumero" => $intNumeroEnc,
    "strNumero" => $strnumeroEnc,
    "strCodSeccao" => $strCodSeccao,
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "strCodExercicio" => $Codexercicio,
    "dtmData" => $dtmDataCarga,
    "intCodEntidade" => 0,
    "intDireccao" => isset($nDirecao) ? $nDirecao : 0,
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
    "fltInfTotalQtd" => $quantidTotal,
    "strHora" => $strHora,
    "strNumExterno" => $id,

    "fltTotalMercadoriaSIVA" => (float)$CABfltTotalMercadoriaSIVA,
    "fltTotalMercadoriaCIVA" => (float)$total_price,

    "intIVACodTaxa1" => $intIVACodTaxa1,
    "intIVACodTaxa2" => -1,
    "intIVACodTaxa3" => -1,
    "intIVACodTaxa4" => -1,
    "intIVACodTaxa5" => -1,
    "intIVACodTaxa6" => -1,

    "fltSubTotal" => (float)$CABfltTotalMercadoriaSIVA,
    "fltTotal" => (float)$total_price,
    "fltTotalToPay" => (float)$total_price,
    "dtmDataEstado" => $dtmDataEstado,

    "intCodVendedor" => $orderDefSeller,
    "intTpComissao" => $intTpComissao,
    "fltComissaoValor" => $fltComissaoValor,
    "fltComissaoPercent" => $fltComissao,
    "fltComissaoValorBase" => (float)$CABfltTotalMercadoriaSIVA,

    "fltIVAValor1" => (float)$totalIVA,
    "fltIVATaxa1" => (float)$fltIVATaxa1,
    "fltIVAIncidencia1" => (float)$CABfltTotalMercadoriaSIVA,
    "bitIvaIncluido" => $taxesIncluded ? 1 : 0,
    "fltTotalIVA" => (float)$totalIVA,

    "bitAddYearIdDocAT" => 1,
    "strMotivoIsencao" => "",
    "str1MotivoIsencao" => "",
    "strATCUD" => isset($strATValidationCode) ? $strATValidationCode : "",
    "intHashControl" => 1,
    "strSaftDocNO" => $strnumeroEnc,
    "bitComissaoDispLiq" => 1,
    "dtmComissaodataDisp" => $dtmDataCarga
]);

$erroCab = $database->error();
$errormsg = $erroCab[2] ?? "";
$cab_id = $database->id();

if($errormsg!=""){
    erpLog("Encomenda $id - ERRO CAB: ".$errormsg);
    echo json_encode(["response"=>"0","msg"=>$errormsg,"ordercreated"=>0,"orderid"=>$cab_id]);
    exit;
}

/* =========================
   LINHAS
========================= */
$x=1;
$intNumLinhaReserva=0;
$fltTotalMercadoriaSIVA=0;
$fltInfTotalArtigos=1;

/* Linha informativa */
$database->insert("Mov_Encomenda_Lin", [
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

foreach($line_items as $vartigo){

    $codigoArtigo = shopMetaValues($vartigo['product_id'] ?? 0, 'erp_code');
    if($codigoArtigo==""){
        $codigoArtigo = getArtigoBySKU(($vartigo['sku'] ?? ''), ($vartigo['vendor'] ?? ''));
    }

    $count = $database->count("Tbl_Gce_Artigos", [ "strCodigo" => $codigoArtigo ]);

    if($count>0){
        $Q_artigo=$database->select("Tbl_Gce_Artigos",
            [ "intCodTaxaIvaVenda","bitNaoMovStk","strDescricao"],
            [ "strCodigo" => $codigoArtigo, "ORDER" => ["Id" => "DESC"], "LIMIT" => 1]
        );

        $CodTaxaIva = $Q_artigo[0]['intCodTaxaIvaVenda'] ?? $intIVACodTaxa1;
        $strDescArtigo = $Q_artigo[0]['strDescricao'] ?? ($vartigo['name'] ?? '');

        $Q_artigoPC=$database->select("Tbl_Gce_ArtigosPrecosCusto",
            ["fltPCMedio"],
            [ "strCodArtigo" => $codigoArtigo, "ORDER" => ["Id" => "DESC"], "LIMIT" => 1]
        );

        /**
         * FIX: não usar number_format (string) em inserts/cálculos
         */
        $fltCustoUnitario = (float)($Q_artigoPC[0]['fltPCMedio'] ?? 0);

        $Q_IVA=$database->select("Tbl_Taxas_Iva", [ "fltTaxa" ],[
            "intCodigo" => $CodTaxaIva, "ORDER" => ["Id" => "DESC"], "LIMIT" => 1
        ]);

        $taxaIVA = (float)($Q_IVA[0]['fltTaxa'] ?? $fltIVATaxa1);
    } else {
        $CodTaxaIva=$intIVACodTaxa1;
        $taxaIVA=(float)$fltIVATaxa1;
        $codigoArtigo=$codDiversos;
        $fltCustoUnitario=0;
        $strDescArtigo=$vartigo['name'] ?? '';
    }

    $amount   = 0.0;
    $quantity = (int) ($vartigo['quantity'] ?? 0);

    if (!empty($vartigo['discount_allocations'][0]['amount'])) {
        $amount = (float)$vartigo['discount_allocations'][0]['amount'];
    }
    $DESCONTOARTIGO = $quantity > 0 ? $amount / $quantity : 0.0;

    /**
     * FIX: rate seguro (pode não existir)
     * e sem number_format
     */
    $rate = (float)($vartigo['tax_lines'][0]['rate'] ?? 0);

    $precoUnitCIVAORIGI = (float)($vartigo['price'] ?? 0) - (float)$DESCONTOARTIGO;

    /**
     * FIX: se taxes_included=true, converter para S/IVA
     * se taxes_included=false, preço já é S/IVA (mantém)
     */
    if ($taxesIncluded) {
        $precoUnitSIVA = $precoUnitCIVAORIGI / (1 + $rate);
    } else {
        $precoUnitSIVA = $precoUnitCIVAORIGI;
    }

    $precoUnitSIVA = truncate($precoUnitSIVA, 3);

    $fltValorMercadoriaSIVA = truncate($quantity * $precoUnitSIVA, 2);

    /**
     * FIX: cálculo C/IVA depende do taxesIncluded
     */
    if ($taxesIncluded) {
        $fltValorAPagar = $fltValorMercadoriaSIVA * (1 + $rate);
    } else {
        $fltValorAPagar = $fltValorMercadoriaSIVA * (1 + $rate);
    }

    $fltValorMercadoriaCIVA = round_up($fltValorAPagar, 2);

    $margemLucro="";
    if($count>0 && $fltCustoUnitario>0 && $precoUnitSIVA>0){
        $margemLucro = number_format((($precoUnitSIVA-$fltCustoUnitario)/$fltCustoUnitario)*100,2);
    }

    $fltTotalMercadoriaSIVA += $fltValorMercadoriaSIVA;

    $arrDados = [];

    if($codDiversos!=$codigoArtigo){
        $arrDados['strCodArmazem']=$armazemDefault;
    }

    $arrDados['fltQuantidadePend']=$quantity;

    $arrDados['strCodSeccao']=$strCodSeccao;
    $arrDados['strAbrevTpDoc']=$EncstrAbrevTpDoc;
    $arrDados['strCodExercicio']=$Codexercicio;
    $arrDados['intNumero']=$intNumeroEnc;
    $arrDados['intNumLinhaReserva']=$intNumLinhaReserva++;
    $arrDados['intNumLinha']=$x++;
    $arrDados['intTpLinha']=5;
    $arrDados['strCodArtigo']=$codigoArtigo;
    $arrDados['strDescArtigo']=$strDescArtigo;
    $arrDados['fltQuantidade']=$quantity;
    $arrDados['dtmDataEntrega']=$dtmDataCarga;
    $arrDados['strHoraEntrega']=$strHoraCarga;
    $arrDados['fltPrecoUnitario']=(float)$precoUnitSIVA;
    $arrDados['fltValorLiquido']=(float)$fltValorMercadoriaSIVA;
    $arrDados['fltTaxaIVA']=(float)$taxaIVA;
    $arrDados['intCodTaxaIVA']=$CodTaxaIva;
    $arrDados['fltValorMercadoriaCIVA']=(float)$fltValorMercadoriaCIVA;
    $arrDados['fltValorMercadoriaSIVA']=(float)$fltValorMercadoriaSIVA;
    $arrDados['fltValorDescontosCIVA']=0;
    $arrDados['fltValorDescontosSIVA']=0;
    $arrDados['fltValorDescontosFinCIVA']=0;
    $arrDados['fltValorDescontosFinSIVA']=0;
    $arrDados['intPriceLine']=0;
    $arrDados['strFormula']=1;
    $arrDados['intTipoAssociacao']=0;
    $arrDados['strAnulFormula']=1;
    $arrDados['fltCustoUnitario']=(float)$fltCustoUnitario;
    $arrDados['fltValorAPagar']=truncate($fltValorAPagar,2);

    if($count>0 && is_numeric($fltCustoUnitario) && $fltCustoUnitario>0){
        /**
         * FIX: não usar number_format para enviar ao ERP como número
         * (se o teu ERP espera string aqui, diz-me e eu volto a formatar)
         */
        $arrDados['CA_Campo02']=round((float)$fltCustoUnitario, 2);
        $arrDados['CA_Campo01']=$margemLucro;
    }

    $database->insert("Mov_Encomenda_Lin", $arrDados);

    $erroLinha=$database->error();
    $errormsg=$erroLinha[2] ?? "";
    $linha_id = $database->id();

    if($errormsg!=""){
        $database->delete("Mov_Encomenda_Lin",["Id" => $linha_id]);
        erpLog("*** ERRO Linha ".$x." Artigo: ".$codigoArtigo." $errormsg");
        echo json_encode(["response"=>"0","msg"=>$errormsg,"ordercreated"=>0,"orderid"=>$cab_id]);
        exit;
    } else {
        erpLog("*** Linha ".$x." Inserida: ".$codigoArtigo."");
    }

    $fltInfTotalArtigos++;
}

/* =========================
   LINHA DE PORTES
========================= */
if($shipping_total==0){
    $codPortes=$codPortesGratis;
    $descrPortes="PORTES GRÁTIS";
} else {
    $descrPortes="PORTES PAGOS";
}

/**
 * FIX: IVA dos portes deve vir dos shipping_lines, não do último artigo
 */
$shippingRate = (float)($shipping_lines[0]['tax_lines'][0]['rate'] ?? (($taxaIVA ?? $fltIVATaxa1)/100));

$fltPortesSIVA = $shipping_total / (1 + $shippingRate);

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

    "fltPrecoUnitario" => (float)round($fltPortesSIVA, 2),
    "fltValorLiquido"  => (float)round($fltPortesSIVA, 2),

    "fltTaxaIVA" => (float)(($shippingRate)*100),
    "intCodTaxaIVA" => $CodTaxaIva,

    "fltValorMercadoriaCIVA" => (float)$shipping_total,
    "fltValorMercadoriaSIVA" => (float)round($fltPortesSIVA, 2),

    "dtmDataEntrega" => $dtmDataCarga,
    "strHoraEntrega" => $strHoraCarga,

    "fltValorDescontosCIVA" => 0,
    "fltValorDescontosSIVA" => 0,
    "fltValorDescontosFinCIVA" => 0,
    "fltValorDescontosFinSIVA" => 0,
    "fltValorAPagar" => (float)$shipping_total
]);

$erroLinha=$database->error();
$errormsg=$erroLinha[2] ?? "";

if($errormsg!=""){
    $database->delete("Mov_Encomenda_Lin",[
        "strAbrevTpDoc" => $EncstrAbrevTpDoc,
        "intNumero" => $intNumeroEnc,
        "strCodSeccao" => $strCodSeccao,
        "strCodExercicio" => $Codexercicio
    ]);
    $database->delete("Mov_Encomenda_Cab",["Id" => $cab_id]);
    erpLog("Encomenda $id - ".$errormsg);
    echo json_encode(["response"=>"0","msg"=>$errormsg,"ordercreated"=>0,"orderid"=>$cab_id]);
    exit;
}

/* Atualiza CAB + Numerador (mantido) */
$database->update("Mov_Encomenda_Cab", [
    "fltInfTotalArtigos" => $fltInfTotalArtigos,
    "fltInfTotalLinhas"  => $x-1,
    "fltInfTotalQtd"     => Medoo::raw("fltInfTotalQtd+1")
],[
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "intNumero" => $intNumeroEnc,
    "strCodSeccao" => $strCodSeccao,
    "strCodExercicio" => $Codexercicio
]);

$database->update("Tbl_Numeradores", [
    "intNum_Mes00" => $intNumeroEnc
],[
    "strAbrevTpDoc" => $EncstrAbrevTpDoc,
    "Id" => $IdNumerador,
    "strCodSeccao" => $strCodSeccao
]);

$htmlmsg .= "\nEncomenda ".$strnumeroEnc." criada no ERP";
$orderCreated=1;

echo json_encode([
    "response" => "1",
    "msg" => $htmlmsg,
    "custcreated" => $customerCreated,
    "ordercreated" => $orderCreated,
    "orderid" => $cab_id,
    "strnumero" => $strnumeroEnc,
    "nif" => $nif,
    "nome" => $nomeClie
]);
exit;