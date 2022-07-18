<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
   # header('HTTP/1.0 403 Forbidden');
#    exit('Forbidden');
}
//ini_set('memory_limit','512M');



require_once dirname(__DIR__) . '/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;


if(isset($_GET['auth_userid']) || isset($_POST['auth_userid'])){
if(isset($_GET['auth_userid']))  $tokenAPI=$_GET['auth_userid']; 
if(isset($_POST['auth_userid'])) $tokenAPI=$_POST['auth_userid']; 
   
switch ($tokenAPI) {  
    case "zE3xS8vX7zW3cO0y":            // CH KIDS	
		$bdServer="192.168.1.97";
		$bdUser="sa";
		$bdPswd="platinum";
		$bdName="Emp_CHARA";
		$Medlog=true;	
        $EncstrAbrevTpDoc="ENC";
		$orderDefSeller="1";
		$custmDefSeller="1"; 
        $billingNIF="_billing_nif";     // WOOCOMMERCE METADATA
        $ClientesLower=199999;          // Limite superior de novo cliente (qdo numeração descontínua)
		$subzona="PT";
		$strCodCondPag="1";
		$strCodSeccao="SEC1";
		$intIVACodTaxa1="1";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="DIVERSOS";
		$codPortes="PORTES";
		$armazemDefault="1"; 
        $strMeioExpedicao="2";
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