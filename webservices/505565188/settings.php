<?php
if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}
//ini_set('memory_limit','512M');



$tokenAPI="DzAl9gSITm3hnbq"; 



##########################################################################################################
require_once dirname(__DIR__) . '/vendor/autoload.php';
 
// Using Medoo namespace
use Medoo\Medoo;


$database = new Medoo([
	'database_type' => 'mssql',
	'database_name' => 'Emp_LIVE',
	'server' => '192.168.1.96',
	'username' => 'sa',
	'password' => 'platinum',
	'driver' => 'php_pdo_sqlsrv',
	"charset" => "utf8",
	"port" => "1433",
	"logging" => false
	]);
  
?> 