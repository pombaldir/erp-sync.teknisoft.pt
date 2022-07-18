<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');
include("settings.php");
use Medoo\Medoo;
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #





$q = $database->select("Tbl_Gce_Artigos",[
	"strCodigo",
	"imgImagem"
	], [
		"Tbl_Gce_Artigos.Id" => "2614",
		"LIMIT" => 1
	]);
	

$output=base64_encode($q[0]['imgImagem']);	

	
//print_r($database->log());
//print_r( $database->error() );	


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }

