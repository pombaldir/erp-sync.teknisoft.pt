<?php if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
} 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$serverName = "192.168.1.100"; //serverName\instanceName
$database="Emp_SHAP";
$dbuser="sa";
$dbpass="17#PblDataETI";

$tokenAPI="9f[X?z!*jkmgJ[R*"; 
 
##########################################################################################################
require_once dirname(__DIR__) . '/vendor/autoload.php';
// Using Medoo namespace
use Medoo\Medoo;

$database = new Medoo([
	'database_type' => 'mssql',
	'database_name' => ''.$database.'',
	'server' => ''.$serverName.'',
	'username' => ''.$dbuser.'',
	'password' => ''.$dbpass.'',
	'driver' => 'php_pdo_sqlsrv',
	"charset" => "utf8",
	"port" => "1433",
	"logging" => true
	]);
  
?> 