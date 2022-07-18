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


/* ############################################## EDITAR Artigo #################################################### */
if (isset($_POST['accaoP']) && $accao == "updtStk" && $_SERVER['REQUEST_METHOD'] == "POST") {

$dados = $_POST['dados'];

foreach($dados as $artigo){
	
	
}

$output = array("success" => 1);	

}



/* ############################################## EDITAR Artigo #################################################### */
if (isset($_POST['accaoP']) && $accao == "updtArtigos" && $_SERVER['REQUEST_METHOD'] == "POST") {
/*
$idArtErp = filter_input(INPUT_POST, 'Id', FILTER_SANITIZE_STRING);	
$sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);	


$idArtStore = "";	

updtArtigoLocal($idArtErp,$idArtStore,"cb_artigo_u");

$htmlmsg="ok";
$success=1;	

$output = array("success" => $success,"message"=> "$htmlmsg");	
*/
}



/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}