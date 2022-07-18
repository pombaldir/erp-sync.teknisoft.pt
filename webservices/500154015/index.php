<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 */
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
   # header('HTTP/1.0 403 Forbidden');
#    exit('Forbidden');
}
//ini_set('memory_limit','512M');
ini_set('mssql.charset', 'UTF-8');


require_once dirname(__DIR__) . '/erpsinc/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

if(isset($_GET['auth_userid']) || isset($_POST['auth_userid'])){
if(isset($_GET['auth_userid']))  $tokenAPI=$_GET['auth_userid']; 
if(isset($_POST['auth_userid'])) $tokenAPI=$_POST['auth_userid']; 
   
switch ($tokenAPI) {  
    case "PjYef9tGTJLGkP8u":            // 
		$bdServer="192.168.148.2";
		$bdUser="sa2";
		$bdPswd="Jmendes2022,";
		$bdName="Emp_JML";
		$Medlog=true;	
        $EncstrAbrevTpDoc="ENCWB";
		$orderDefSeller="2";
		$custmDefSeller="2"; 
        $billingNIF="_billing_nif";     // WOOCOMMERCE METADATA
        $ClientesLower=199999;          // Limite superior de novo cliente (qdo numeração descontínua)
		$subzona="9";
		$strCodCondPag="1";
		$strCodSeccao="c1";
		$intIVACodTaxa1="3";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="C9";
		$codPortes="C19";
		$armazemDefault="2"; 
        $strMeioExpedicao="4";
		$faturaEletronica="0"; 
		$depFinTpContacto="1"; 
        break;  
}
    

##########################################################################################################

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
##########################################################################################################

function getPrecos($codigo){
	global $database;
	 
	$data = $database->query(
	"SELECT intNumero, 
	CONVERT(varchar(10), COALESCE(CAST(fltPreco AS NUMERIC(18,2)),0)) AS fltPreco,	
	CAST(fltDesconto1 AS DECIMAL(19,2)) AS fltDesconto1,CAST(fltDesconto2 AS DECIMAL(19,2)) AS fltDesconto2,
	intCodTaxaIvaVenda,CAST(fltTaxa AS INT) AS fltTaxa,
	
	CONVERT(varchar(10), (CASE WHEN fltDesconto2 = 0 
				THEN CAST(subt1 AS DECIMAL(19,2))
				ELSE
				CAST(subt1 - ((subt1 *(fltDesconto2 / 100))) AS DECIMAL(19,2))
	END) ) AS PrecoFinCiva
	
		
			FROM
				(SELECT
						Tbl_Gce_ArtigosPrecos.fltPreco,
						Tbl_Gce_ArtigosPrecos.intTpIva,
						Tbl_Taxas_Iva.fltTaxa,
						Tbl_Gce_ArtigosPrecos.intNumero,
						Tbl_Gce_ArtigosPrecos.fltDesconto1,
						Tbl_Gce_ArtigosPrecos.fltDesconto2,
						intCodTaxaIvaVenda,
						 (
							CASE
							WHEN fltDesconto1 = 0 THEN
								(CASE WHEN intTpIva=0 THEN (((Tbl_Taxas_Iva.fltTaxa/100)+1)*fltPreco) ELSE fltPreco END) 
							ELSE
								(CASE WHEN intTpIva=0 THEN (((Tbl_Taxas_Iva.fltTaxa/100)+1)*(fltPreco - ((fltPreco *(fltDesconto1 / 100))))) ELSE fltPreco - ((fltPreco *(fltDesconto1 / 100))) END) 
							END
						) AS subt1
					FROM
						Tbl_Gce_ArtigosPrecos
						LEFT JOIN Tbl_Gce_Artigos ON Tbl_Gce_ArtigosPrecos.strCodArtigo=Tbl_Gce_Artigos.strCodigo
						LEFT JOIN Tbl_Taxas_Iva ON Tbl_Gce_Artigos.intCodTaxaIvaVenda=Tbl_Taxas_Iva.intCodigo
					WHERE
						Tbl_Gce_ArtigosPrecos.strCodArtigo = '".$codigo."' AND fltPreco>0 
				) T", [])->fetchAll(PDO::FETCH_ASSOC);	
				
	return $data;			
}
    
    
function getPrecosRef($codigo){
	global $database;
	$post = $database->select("Tbl_Gce_ReferenciasPrecos", [
	"intNumero",
	"fltPreco"
	], [
		"strCodReferencia" => $codigo,
		"LIMIT" => 1
	]);
	return $post;    
}

function getQuantStock($codigo){
	global $database;
	$post = $database->select("Gce_stk_real", [
	"QuantStock",
	"ReservaStock"
	], [
		"strCodArtigo" => $codigo,
		"LIMIT" => 1
	]);
	return $post;
}


  
function enviaMail($destinatario,$subject,$message,$fromemail="",$fromname="",$cc="",$bcc=""){
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



}


?> 