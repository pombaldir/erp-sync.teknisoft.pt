<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("index.php");  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Medoo\Medoo;


$a=15171;
$rDocExiste = $database->count("Mov_Encomenda_Cab", ["strNumExterno" => "$a","strAbrevTpDoc" => 'ENCON',"strCodSeccao" => 'ONL']);





var_dump($rDocExiste);