<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');

include_once '../backoffice/include/db_connect.php';	include_once '../backoffice/include/functions.php';


if(isset($_GET['auth_userid'])) {
if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	}
if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); }

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_GET['act_g']) && $act_get=="getEmp" && ($_SERVER['REQUEST_METHOD'] === 'GET')){
	$token=stripslashes($_GET['auth_userid']);
	
	$query = $mysqli->query("select settings from empresas where api='$token'") or die($mysqli->errno .' - '. $mysqli->error);
	$dados = $query->fetch_array();
	$output=@unserialize($dados['settings']);	
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
   if(isset($output)){
    echo json_encode($output );
   }
}
