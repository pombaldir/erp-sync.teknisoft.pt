<?php if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        }
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
        exit(0);
    }

    header('Content-type: application/json; charset=utf-8');
    
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

//https://app.swaggerhub.com/apis/Pombaldir.com/ERPSinc/1.0.0
 
$headers = getRequestHeaders();
$tokenAPI=$headers['X-Api-Key'];
//$tokenAPI="9CbSLDc39TcSA3SP";
//print_r($tokenAPI);
if(isset($_GET['debug'])) { $tokenAPI="demo"; }
 
## DEFINIÇÃO DE VARIÁVEIS
$Custom_StockList="";

$CA_Enc_CAB=array();

## SELEÇÃO DA EMPRESA AUTENTICADA
switch ($tokenAPI) {
    /*
    case "9CbSLDc39TcSA3SP":        // CRISTALVIDA			
        $bdServer="192.168.1.24";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_CRISV";
		$Medlog=false;
    break;
    */
 
    case "w6uYCkqqEWyw":            // ELECTROMINOR		
        $bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_ELCT";
		$Medlog=false;
    break;
    

    case "7ojOChyEEUF3":            // DIKAMAR		
        $bdServer="192.168.1.91";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="emp_dik";
		$Medlog=false;
    break;


    case "dikamar_demo":            // DIKAMAR		
        $bdServer="192.168.1.91";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="emp_zdik";
		$Medlog=false;
    break;
    
    case "iY1zB9nH0sI6jG4m":            // MIRANDA & MIRANDA		
        $bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_MIRMI";
		$Medlog=false;
    break;
    


    
    
    case "CByJ4EUXw9TQuxag":        // STEP FREEDOM			
        $bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_IFERR";
        $Custom_StockList=array("strCodArmazem[!]"=>["5","99"]); 
        $wsLog=1;
		$Medlog=false;   
    break;
    
    case "9f[X?z!*jkmgJ[R*":        // SHAPETEK		
        $bdServer="192.168.1.24";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_SHAP";
        $CA_Enc_CAB=array("CA_YourRef","CA_PrazoEntrega");
        $CA_Artigos=array("CA_Densidade");
		$Medlog=false;
    break;
 
    case "5f[Y?z?*LkmgJ[R*":        // NELSON FERREIRA		
        $bdServer="192.168.1.96";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_NFER";
		$Medlog=false;
    break;

    case "4nBUXnbHdGwj8H2G":        // TREVOS e CASTELOS	
        $bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_TREVO";
		$Medlog=false;
    break;


    case "SmA13z?xU(hxR4e":        // ZCONTAS
        $bdServer="192.168.1.24";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
        if(isset($_GET['db']) && $_GET['db']!=""){ 
            $bdName=$_GET['db']; 
        } else { 
            $bdName="emp_125";  
        }
		$Medlog=false;
    break;

    
    case "demo":			
        $bdServer="192.168.1.24";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_DEMO";
		$Medlog=false;
        break;
    default:
        header('HTTP/1.0 403 Forbidden');
        die(json_encode(array("success"=>0,"errormsg"=>"Acesso inválido","d"=>$tokenAPI)));
        break; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($input['database']) && $input['database'] !== '') {
        $bdName = $input['database'];
    } elseif (isset($_POST['database']) && $_POST['database'] !== '') {
        $bdName = $_POST['database'];
    }
}



##########################################################################################################
require_once dirname(__DIR__) . '/api.erpsinc.pt/vendor/autoload.php';
 
// Using Medoo namespace

use Medoo\Medoo;

try {
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
    } catch (PDOException $e) {
        die(json_encode(array("success"=>0, "message"=>$e->getMessage())));
}

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
  
function getIvaCodeByTax($taxValue) {
    static $cache = array();
    if ($taxValue === null || $taxValue === '') {
        return null;
    }

    $normalized = round((float) $taxValue, 4);
    $cacheKey = (string) $normalized;

    if (array_key_exists($cacheKey, $cache)) {
        return $cache[$cacheKey];
    }

    global $database;
 
    try {
        $stmt = $database->pdo->prepare("
            SELECT TOP 1 intCodigo
            FROM Tbl_Taxas_Iva
            WHERE ABS(fltTaxa - :tax) < 0.0001
            ORDER BY intCodigo
        ");
        $stmt->bindValue(':tax', $normalized);
        $stmt->execute();
        $code = $stmt->fetchColumn();
        $stmt->closeCursor();
        $cache[$cacheKey] = ($code !== false) ? (int) $code : null;
    } catch (Exception $e) {
        $cache[$cacheKey] = null;
    }

    return $cache[$cacheKey];
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
   // return $database->log();
}


function getExercicioByDate($dte) {
    global $database;

    try {
        // Verifica se $database está definido
        if (!isset($database)) {
            throw new Exception('Conexão com a base de dados não está definida.');
        }
        // Garante formato de data válido
        $data = date('Y-m-d', strtotime($dte));

        // Tenta buscar o exercício
        $exercicio = $database->get("Tbl_Exercicios", "strCodigo", [
            "dtmInicio[<=]" => $data,
            "dtmFim[>=]"    => $data
        ]);

        // Caso não encontre nada
        if (!$exercicio) {
            // Usa o ano da data como fallback
            $exercicio = date('Y', strtotime($dte));
        }
        // Retorna 
        return $exercicio;
    } catch (Exception $e) {
        error_log("Erro em getExercicioByDate: " . $e->getMessage());
        return null;
    }
}

/**
 * Retorna informação do próximo numerador sequencial para um documento.
 *
 * @param string|int $exercicio Código do exercício (strCodExercicio).
 * @param string     $seccao    Código da secção (strCodSeccao).
 * @param string     $tipoDoc   Abreviatura do tipo de documento (strAbrevTpDoc).
 *
 * @return array{intNumero:int,strNumero:string} Número sequencial e formato aplicado.
 */
function getNextDocumentNumerador($exercicio, $seccao, $tipoDoc) {
    global $database;

    if (!isset($database)) {
        throw new RuntimeException('Ligação à base de dados indisponível.');
    }

    $exercicio = trim((string) $exercicio);
    $seccao = trim((string) $seccao);
    $tipoDoc = trim((string) $tipoDoc);

    if ($exercicio === '' || $seccao === '' || $tipoDoc === '') {
        throw new InvalidArgumentException('Exercício, secção e tipo de documento são obrigatórios.');
    }
 
    $record = $database->select("Tbl_Numeradores", [
        "intNum_Mes00",
        "strFormato"
    ], [
        "strCodExercicio" => $exercicio,
        "strCodSeccao"    => $seccao,
        "strAbrevTpDoc"   => $tipoDoc,
        "intTpNumerador"  => 1
    ]);

    $current = 0;
    $format = '';
    
    if (is_array($record)) {
        $current = isset($record[0]['intNum_Mes00']) ? (int) $record[0]['intNum_Mes00'] : 0;
        $format = isset($record[0]['strFormato']) ? (string) $record[0]['strFormato'] : '';
    } elseif ($record !== null) {
        $current = (int) $record;
    }

    $next = $current + 1;
    $formatted = formatDocumentNumberByPattern($format, $exercicio, $next, $seccao, $tipoDoc);

    return [
        'intNumero' => $next,
        'strNumero' => $formatted
    ];
}

/**
 * Aplica o formato configurado ao numerador.
 *
 * Suporta:
 *  - !AAAA! → ano completo do exercício (ou ano corrente se inválido)
 *  - !AA!   → últimos dois dígitos do ano
 *  - grupos de # → numerador com padding à esquerda
 */
function formatDocumentNumberByPattern($format, $exercicio, $numero, $seccao = '', $tipoDoc = '', $docId = null) {
    $format = $format ?? '';
    if (trim($format) === '') {
        return (string) $numero;
    }

    $formatted = $format;
    $ano = extractYearFromExercicio($exercicio);
    $anoCurto = substr($ano, -2);

    // Substituições case-insensitive para suportar !aaaa!, !AAAA!, etc.
    $formatted = str_ireplace('!AAAA!', $ano, $formatted);
    $formatted = str_ireplace('!AAAA', $ano, $formatted);
    $formatted = str_ireplace('!AA!', $anoCurto, $formatted);
    $formatted = str_ireplace('!AA', $anoCurto, $formatted);

    if ($seccao !== '') {
        $formatted = str_ireplace('!SECC!', $seccao . $anoCurto, $formatted);
    } else {
        $formatted = str_ireplace('!SECC!', $anoCurto, $formatted);
    }

    if ($tipoDoc !== '') {
        $formatted = str_ireplace('!DOC!', $tipoDoc, $formatted);
    } else {
        $formatted = str_ireplace('!DOC!', '', $formatted);
    }

    if (stripos($formatted, '!IDDOC!') !== false) {
        if ($docId === null && $tipoDoc !== '' && isset($GLOBALS['database'])) {
            try {
                $docId = $GLOBALS['database']->get("Tbl_Tipos_Documentos", "Id", [
                    "strAbreviatura" => $tipoDoc
                ]);
            } catch (Exception $e) {
                $docId = null;
            }
        }
        $formatted = str_ireplace('!IDDOC!', ($docId !== null ? $docId : ''), $formatted);
    }

    $formatted = preg_replace_callback('/#+/', function ($matches) use ($numero) {
        $length = strlen($matches[0]);
        return str_pad((string) $numero, $length, '0', STR_PAD_LEFT);
    }, $formatted);

    if (strpos($formatted, '#') !== false) {
        $formatted = str_replace('#', '0', $formatted);
    }

    // Remove tokens não reconhecidos (!ALGUMA_COISA!) que tenham ficado pendentes.
    $formatted = preg_replace('/![^!]+!/', '', $formatted);

    return $formatted;
}

/**
 * Tenta extrair um ano válido a partir do código de exercício.
 */
function extractYearFromExercicio($exercicio) {
    $exercicio = (string) $exercicio;

    if (preg_match('/\d{4}/', $exercicio, $matches)) {
        return $matches[0];
    }

    if (preg_match('/\d{2}/', $exercicio, $matches)) {
        $twoDigits = (int) $matches[0];
        $currentCentury = (int) substr(date('Y'), 0, 2);
        $century = $twoDigits >= 50 ? $currentCentury - 1 : $currentCentury;
        return sprintf('%d%02d', $century, $twoDigits);
    }

    return date('Y');
}

function erpLog($text){
	global $bdName,$wsLog; 
	if(isset($wsLog) && $wsLog==1){
	if(is_array($text)){ $text=json_encode($text);}
	error_log(PHP_EOL."$text", 3, "/home/erpsinc/public_html/api/logs/log-".$bdName."".date('ymd').".log");  
	}  
} 

function utf8ize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = utf8ize($value);
        }
    } elseif (is_string($data)) {
        // Converte qualquer string não UTF-8 para UTF-8 válida
        return mb_convert_encoding($data, 'UTF-8', 'UTF-8, ISO-8859-1, CP1252');
    }
    return $data;
}
?>