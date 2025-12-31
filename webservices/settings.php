<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}
//ini_set('memory_limit','512M');
ini_set('display_errors', 1);
error_reporting(E_ALL);




if(isset($_GET['auth_userid']) || isset($_POST['auth_userid'])){

if(isset($_GET['auth_userid']))  $tokenAPI=$_GET['auth_userid']; 
if(isset($_POST['auth_userid'])) $tokenAPI=$_POST['auth_userid']; 

switch ($tokenAPI) {
        
   case "D0Al7gSITm3hnNm":			// PLUMEX
        $bdServer="192.168.1.93";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_TMC";
        $EncstrAbrevTpDoc="ENCWB";
		$Medlog=false;
		$orderDefSeller="1";
		$custmDefSeller=$orderDefSeller; 
		$subzona="PT";
		$strCodCondPag="1";
		$strCodSeccao="1";
		$intIVACodTaxa1="7";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="DIV";
		$codPortes=$codDiversos;
		$armazemDefault="1"; 
		$faturaEletronica="0"; 
		$depFinTpContacto="002"; 
        break;        
        
    case "DzAl9gSITm3hnbq":			// LIVE SOUND
        $bdServer="192.168.1.93";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_LIVE";
		$Medlog=false;
		$orderDefSeller="1";
		$custmDefSeller=$orderDefSeller; 
		$subzona="ON";
		$strCodCondPag="PP";
		$strCodSeccao="1";
		$intIVACodTaxa1="3";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="1";
		$codPortes=$codDiversos;
		$armazemDefault="1"; 
		$faturaEletronica="0"; 
		$depFinTpContacto="002"; 
        break;
    case "SKrhuSplYSM0IOQ":			// LOJAS MARIAS
        $bdServer="192.168.1.96";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_SOFLT";
		$Medlog=false;
        break;
    case "pmm5eWVnrhxf5wn":			// MOMENTO ZEN
        $bdServer="192.168.1.93";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_LMIST";
		$Medlog=false;
        break;
    case "24PbJ7xKCM3zutcC":		// MALAS VITESSE 
        $bdServer="192.168.1.96";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_MVITS"; 
		$Medlog=true;		
		$orderDefSeller="1";
		$custmDefSeller="1"; 
		$subzona="ON";
		$strCodCondPag="PP";
		$strCodSeccao="1";
		$intIVACodTaxa1="3";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="1";	        // Tem de movimentar stocks para reservar encomendas
		$codPortes="9001"; 
		$armazemDefault="1"; 
		$faturaEletronica="0"; 
		$depFinTpContacto="002"; 
		$tpnumeracao="tlm";
        break;
    case "6jfSzqQgxLAer2Q":			// CIDADE 100 IDADE
        $bdServer="192.168.1.97";
		$bdUser="sa";
		$bdPswd="platinum";
		$bdName="Emp_CD100"; 
		$Medlog=true;		
		$orderDefSeller="1";
		$custmDefSeller="1"; 
		$subzona="PT";
		$strCodCondPag="1";
		$strCodSeccao="1";
		$intIVACodTaxa1="3";
		$fltIVATaxa1="23"; 
		$strLogin="";
		$codDiversos="1";
		$codPortes=$codDiversos;
		$armazemDefault="1"; 
		$faturaEletronica="0"; 
		$depFinTpContacto="002"; 
        break;
    case "qeyjp93ek5y6t47r":		// DESAFIO SALUTAR
        $bdServer="192.168.1.96";
		$bdUser="sa";
		$bdPswd="17#PblDataETI";
		$bdName="Emp_DESAF";
		$Medlog=false;
        break;
	}
}


##########################################################################################################
require_once dirname(__DIR__) . '/webservices/vendor/autoload.php';
 
// Using Medoo namespace



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;

/*
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

*/


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
        die(json_encode(array("success"=>0, "msg"=>$e->getMessage())));
}


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






function titleCase($string, $delimiters = array(" ", "-", ".", "'", "O'", "Mc"), $exceptions = array("de", "das", "dos",  "drª", "Lda", "II", "III", "IV", "V", "VI"))
{
    /*
     * Exceptions in lower case are words you don't want converted
     * Exceptions all in upper case are any words you don't want converted to title case
     *   but should be converted to upper case, e.g.:
     *   king henry viii or king henry Viii should be King Henry VIII
     */ 
    $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
    foreach ($delimiters as $dlnr => $delimiter) {
        $words = explode($delimiter, $string);
        $newwords = array();
        foreach ($words as $wordnr => $word) {
            if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtoupper($word, "UTF-8");
            } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                // check exceptions list for any words that should be in upper case
                $word = mb_strtolower($word, "UTF-8");
            } elseif (!in_array($word, $exceptions)) {
                // convert to uppercase (non-utf8 only)
                $word = ucfirst($word);
            }
            array_push($newwords, $word);
        }
        $string = join($delimiter, $newwords);
   }//foreach
   return $string;
}


?>