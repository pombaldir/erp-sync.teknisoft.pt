<?php include_once '../include/db_connect.php';	include_once '../include/functions.php'; header('Content-Type: application/json'); 

sec_session_start();


if (login_check($mysqli) == true) {
	
if(is_file(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php')){
$mysqli->close();	
require(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php');
$mysqli = new mysqli(HOST2, USER2, PASSWORD2, DATABASE2);
$mysqli->set_charset("utf8");
}
	
if (isset($_POST['accaoP'])) {
    $accao = mysqli_real_escape_string($mysqli,$_POST['accaoP']);
}
if (isset($_GET['accaoG'])) {
    $accao = mysqli_real_escape_string($mysqli,$_GET['accaoG']);
}

/* ############################################## EDITAR FAMILIA #################################################### */
if (isset($_POST['accaoP']) && $accao == "updtFamilias" && $_SERVER['REQUEST_METHOD'] == "POST") {
$idnum = filter_input(INPUT_POST, 'idnum', FILTER_SANITIZE_STRING);	
$prefs=settings_val(1);
$webserviceERP=$prefs['erp_ws'];
$webserviceTKN=$prefs['ws_token'];
$idERPFam=getFamiliaERPId($idnum);

include_once DOCROOT.'/include/functions.'.$prefs['store'].'.php';	

$categ=get_categoria($idnum);

$existeFam=$categ['id'];

if($existeFam !="" && $existeFam>=0){
$htmlmsg="$idnum Existe";
$success=1;	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$webserviceERP."/familias.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"act_p=checkweb&num=$idERPFam&auth_userid=".$webserviceTKN."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$server_output = curl_exec ($ch);
curl_close ($ch);
} else {
$htmlmsg="$idnum Não existe";
$success=0;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$webserviceERP."/familias.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"act_p=uncheckweb&num=$idERPFam&auth_userid=".$webserviceTKN."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$server_output = curl_exec ($ch);
curl_close ($ch);

$mysqli->query("delete from familias where empresa='".$_SESSION['empresaID']."' and id_store='$idnum' and id_erp='$idERPFam'") or die($mysqli->errno .' - '. $mysqli->error);

}  

$output = array("success" => $success,"message"=> "$htmlmsg");	
}



/* ############################################## EDITAR FAMILIA #################################################### */
if (isset($_POST['accaoP']) && $accao == "FamErpReset" && $_SERVER['REQUEST_METHOD'] == "POST") {
$prefs=settings_val($_SESSION['empresaID']);
$webserviceERP=$prefs['erp_ws'];
$webserviceTKN=$prefs['ws_token'];

if($prefs['store']=="woocommerce"){
include_once DOCROOT.'/include/functions.woocommerce.php';	
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$webserviceERP."/familias.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"act_p=uncheckallweb&auth_userid=".$webserviceTKN."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$server_output = curl_exec ($ch);
curl_close ($ch);

$mysqli->query("delete from familias where empresa='".$_SESSION['empresaID']."'") or die($mysqli->errno .' - '. $mysqli->error);
 

$output = array("success" => 1,"message"=> $server_output);	
}



/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}