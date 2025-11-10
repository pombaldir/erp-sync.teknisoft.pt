<?php session_start(); if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
    }
    // Access-Control headers are received during OPTIONS requests
    if ((isset($_SERVER['REQUEST_METHOD'])) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
    }

    if(basename($_SERVER['SCRIPT_FILENAME'])!="config.php" && !isset($_GET['date'])){
    header('Content-type: application/json; charset=utf-8');
    }      

if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
   header('HTTP/1.0 403 Forbidden');
   exit('Forbidden');
} 
  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('max_execution_time', 900); //300 seconds = 5 minutes
ini_set('memory_limit', '4096M');


require_once dirname(__DIR__) . '/v2.0/vendor/autoload.php';
require_once dirname(__DIR__) . '/_techrest/functions.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;



   
$act=$tp="";
if(isset($_GET['act'])) {  $act=$_GET['act']; } 
if(isset($_POST['act'])) {  $act=$_POST['act']; } 



if(isset($argv) && array_key_exists(1,$argv)) {  $tp=$argv[1]; } else {
    if(isset($_GET['tp']))  {  $tp=$_GET['tp']; }  else { $tp=""; } 
}  
if(isset($argv) && array_key_exists(2,$argv)) {  $tokenAPI=$argv[2]; } else {
    if(isset($_GET['auth_userid']))  { $tokenAPI=$_GET['auth_userid'];   }
    if(isset($_POST['auth_userid'])) { $tokenAPI=$_POST['auth_userid'];  } 
}  
    


if(isset($tokenAPI)){
  
switch ($tokenAPI) {  

	case "r87anjpcj63pjb9D":		   // GROWING WISHES
		$bdServer="192.168.1.96";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_GROW";
		$Medlog=true;	

        $TECHAPI_vat_number="514449756";
        $TECHAPI_username="pombaldata";
        $TECHAPI_password_web="pdi1608";
        
	break; 


    
	case "05sLr6nYZE9ZWme":		   // TECH DEMO
		$bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_TECH";
		$Medlog=true;	

        $TECHAPI_vat_number="504128582J";
        $TECHAPI_username="eticadata";
        $TECHAPI_password_web="Eti*23";
	break; 


	case "nRlhBpaRusKFkPc":		   // PERFECT PROCESS
		$bdServer="192.168.1.91";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_SB779";
		$Medlog=true;	

        $TECHAPI_vat_number="510270425";
        $TECHAPI_username="API";
        $TECHAPI_password_web="Perfect*23";
	break; 


	case "3hoCW3ZVJpz6isX":		   // THAI - RESTAURANTE TAILANDÊS DE VILAMOURA, LDA.
		$bdServer="192.168.1.91";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_SB290";
		$Medlog=true;	

        $TECHAPI_vat_number="506496619";
        $TECHAPI_username="API";
        $TECHAPI_password_web="Thai*23";
	break; 

    case "e69uwGTNRwAtWmEB":		   // cidade 100 idade
		$bdServer="192.168.1.90";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_CD100";
		$Medlog=true;	

        $TECHAPI_vat_number="513445307";
        $TECHAPI_username="Api";
        $TECHAPI_password_web="Cidade*23";
	break; 
}
    
// ALTER TABLE (yourTable) ADD NewColumn INT IDENTITY(1,1)

if(isset($_GET['loja']) && $_GET['loja']!=""){
    $TECHAPI_shop_number=$_GET['loja'];
    $_SESSION['loja']=$TECHAPI_shop_number;  
} else {
    if(isset($_SESSION['loja']) &&  $_SESSION['loja']!=""){
        $TECHAPI_shop_number=$_SESSION['loja']; 
    } else {
        $TECHAPI_shop_number=1; 
    }
}  

##########################################################################################################
##########################################################################################################
try { 
    $database = new Medoo([
        'database_type' => 'mssql',
        'database_name' => ''.$bdName.'',
        'server' => ''.$bdServer.'',
        'username' => ''.$bdUser.'',
        'password' => ''.$bdPswd.'',
        'driver' => 'php_pdo_sqlsrv',
        "charset" => "utf8",
        "error" => PDO::ERRMODE_SILENT,
        "port" => "1433",
        "logging" => $Medlog
        ]);
    } catch (PDOException $e) {
        die(json_encode(array("success"=>0, "message"=>$e->getMessage(), "db"=>$bdName)));
}
##########################################################################################################
##########################################################################################################
 
$strLogin=TechAPI_Login($TECHAPI_shop_number); 

$strHash=$strLogin['hash']; 
$TECHAPI_ApiVersion="v".$strLogin['api_version']; 
 
$param=ERP_Entities("USR_sync_config",array('params','seccao'),['shop_number' => $TECHAPI_shop_number]);  


if(is_array($param) && sizeof($param)==0){$database->insert("USR_sync_config", ['shop_number' => $TECHAPI_shop_number,"params"=>serialize(array())]);
    $erro=$database->error();  $errormsg=$erro[2];   if($errormsg!=""){ die("$errormsg");  }  
}    
$paramDB=unserialize($param[0]['params']); 

if($paramDB!="" && is_array($paramDB) && sizeof($paramDB)>0){
$ERP_OPT_criaAutoFamilia=$paramDB['criaAutoFamilia'];
$ERP_OPT_criaAutoSFamilia=$paramDB['criaAutoSFamilia'];
$ERP_strCodTpFamilia=$paramDB['nivFamilia'];      // Tbl_Gce_Tipos_Familias (strCodigo)
$ERP_strCodTpSFamilia=$paramDB['nivSFamilia'];    // Tbl_Gce_Tipos_Familias (strCodigo)
$ERP_strCodSubZona=$paramDB['codSubZona'];
$ERP_ConfLojas=$paramDB['loja'];
$ERP_scodArtigos=@$paramDB['codArtigos']; if($ERP_scodArtigos=="")$ERP_scodArtigos="product_number";
//$ERP_strCodSeccao=$paramDB['seccao']; 
$ERP_dataIniSync=$paramDB['dataIniSync'];
if($ERP_strCodTpFamilia=="" || $ERP_strCodTpSFamilia==""){  echo "<h1>Tem de criar o nível de Familia e Sub-família no ERP</h1>";  }
} else {
    $ERP_OPT_criaAutoFamilia=$ERP_OPT_criaAutoSFamilia=$ERP_strCodTpFamilia=$ERP_strCodTpSFamilia=$ERP_strCodSubZona=$ERP_ConfLojas=$ERP_dataIniSync="";
}
 
function enviaeMail($destinatario,$subject,$message,$fromemail="",$fromname="",$cc="",$bcc=""){
	global $bdName;
    $mail = new PHPMailer(true);                              	// Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 	// Enable verbose debug output
        $mail->isSMTP();                                      	// Set mailer to use SMTP
        $mail->Host = 'smtp.sendgrid.net';  					// Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               	// Enable SMTP authentication
        $mail->Username = 'pombaldir';                 			// SMTP username
        $mail->Password = 'duckmans3';                         // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('webmaster@'.$_SERVER['HTTP_HOST'].'', ''.$bdName.' '.$_SERVER['HTTP_HOST'].'');
        $mail->addAddress(''.$destinatario.'');              

        //Content
        $mail->isHTML(true);                                
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
    return $mail->ErrorInfo;
    }
}






function erpLog($text){
	global $bdName,$wsLog; 
	if(isset($wsLog) && $wsLog==1){
	if(is_array($text)){ $text=json_encode($text);}
	error_log(PHP_EOL."$text", 3, "/home/erpsinc/public_html/webservices/v2.0/logs/log-".$bdName."".date('ymd').".log");  
	}  
} 


function checkDBError($databaseQ){
    $err=$databaseQ->errorInfo;
    $msg="";
    if(is_array($err) && array_key_exists(2,$err) && $err[2]!=""){
        $msg=$err[2];
        print_r(array("msg"=>$msg));
        die("");
    }
    //return array("msg"=>$msg);  
}




}


?> 