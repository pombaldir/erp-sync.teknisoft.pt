<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}

//https://app.swaggerhub.com/apis/Pombaldir.com/ERPSinc/1.0.0
 
$headers = getRequestHeaders();
$tokenAPI=$headers['X-Api-Key'];
//$tokenAPI="CByJ4EUXw9TQuxag";

if(isset($_GET['debug'])) { $tokenAPI="demo"; }
 

## DEFINIÇÃO DE VARIÁVEIS
$Custom_StockList="";

## SELEÇÃO DA EMPRESA AUTENTICADA
switch ($tokenAPI) {
    case "9CbSLDc39TcSA3SP":        // CRISTALVIDA			
        $bdServer="192.168.1.100";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_CRISV";
		$Medlog=false;
        break;
        
    case "CByJ4EUXw9TQuxag":        // STEP FREEDOM			
        $bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_STEP";
		$Medlog=false;
        $Custom_StockList=array("strCodArmazem[!]"=>["5","99"]);
        break;
         
    case "demo":			
        $bdServer="192.168.1.100";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_DEMO";
		$Medlog=false;
        break;
    default:
    header('HTTP/1.0 403 Forbidden');
    die(json_encode(array("success"=>0,"errormsg"=>"Acesso inválido")));
    break;
}


  
##########################################################################################################
require_once dirname(__DIR__) . '/api.erpsinc.pt/vendor/autoload.php';
 
// Using Medoo namespace

use Medoo\Medoo;

$database = new Medoo([
	'database_type' => 'mssql',
	'database_name' => ''.$bdName.'',
	'server' => ''.$bdServer.'',
	'username' => ''.$bdUser.'',
	'password' => ''.$bdPswd.'',
	'driver' => 'php_pdo_sqlsrv',
	"charset" => "utf8",
	"port" => "1433",
	"logging" => $Medlog
	]);


$database->query("SET TEXTSIZE 2147483647;"); 
ini_set( 'mssql.textlimit' , '2147483647' );
ini_set( 'mssql.textsize' , '2147483647' );


function getRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

function recursive_unset(&$array, $unwanted_key) {
    unset($array[$unwanted_key]);
    foreach ($array as &$value) {
        if (is_array($value)) {
            recursive_unset($value, $unwanted_key);
        }
    }
}
  
function getStockByCod($codigo, $tp=0) {
    global $database, $Custom_StockList;
    $filtro=array("strCodArtigo"=>$codigo);
    if($Custom_StockList!="" && is_array($Custom_StockList)){
        $filtro=array_merge($filtro,$Custom_StockList);
    }
      
    if($tp==1){
        $totalQtd = $database->sum("Tbl_Gce_ArtigosArmLocalLote", "fltStockQtd", $filtro);
        $totalRes = $database->sum("Tbl_Gce_ArtigosArmLocalLote", "fltStockReservado", $filtro);
        $outP=$totalQtd-$totalRes;
    } else {
    $ArrayStock=$database->select("Tbl_Gce_ArtigosArmLocalLote",["strCodArmazem","fltStockQtd","fltStockReservado"], $filtro);  
    }
    
    return $outP;
}

?>