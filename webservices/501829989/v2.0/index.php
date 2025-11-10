<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
   # header('HTTP/1.0 403 Forbidden');
#    exit('Forbidden');
}
//ini_set('memory_limit','512M');
require_once dirname(__DIR__) . '/v2.0/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

if(isset($_GET['auth_userid']) || isset($_POST['auth_userid'])){
if(isset($_GET['auth_userid']))  $tokenAPI=$_GET['auth_userid']; 
if(isset($_POST['auth_userid'])) $tokenAPI=$_POST['auth_userid']; 
 
    
switch ($tokenAPI) {  
    case "hR7kN0pK9jX7qN3k":        // SUPER LIVRO
        $bdServer="192.168.2.1";
		$bdUser="sa";
		$bdPswd="Platinum17";
		$bdName="Emp_002";
		$Medlog=true;	
        $EncstrAbrevTpDoc="ENCWB";
		$orderDefSeller="14";
		$custmDefSeller="14"; 
		$subzona="4";
		$strCodCondPag="1";
		$strCodSeccao="1";
		$intIVACodTaxa1="3";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="DIV";
		$codPortes="PO";
		$armazemDefault="1"; 
        $strMeioExpedicao="3";
		$faturaEletronica="0"; 
		$depFinTpContacto="004"; 
		$tpnumeracao="tlm";
		$wsLog=1;  
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
       
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'email-smtp.eu-west-1.amazonaws.com';   // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'AKIA5YQOQR5V233IYFCY';                 // SMTP username
    $mail->Password   = 'BFKntDYKEmQemBFruqfRhQE2O4ky1JJZfd5zDpEjjH1O';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
   
  $mail->CharSet = 'UTF-8'; 

    $mail->setFrom('webmaster@pombaldir.com', ''.$bdName.' '.$_SERVER['HTTP_HOST'].'');
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
	error_log(PHP_EOL."$text", 3, "C:\/inetpub\/501829989.erpsinc.pt\/v2.0\/logs\/log-".$bdName."".date('ymd').".log");  
	}  
} 

}

?>