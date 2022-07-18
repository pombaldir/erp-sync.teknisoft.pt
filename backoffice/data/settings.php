<?php include_once '../include/db_connect.php';	include_once '../include/functions.php'; if(isset($_POST['accaoP']) && $_POST['accaoP']!= "editafotodef")  header('Content-Type: application/json'); 

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
/* ############################################## EDITAR PERFIL #################################################### */

if (isset($_POST['accaoP']) && $accao == "edit" && $_SERVER['REQUEST_METHOD'] == "POST") {

$importar=array();
$erp = filter_input(INPUT_POST, 'erp', FILTER_SANITIZE_STRING);	
$store = filter_input(INPUT_POST, 'store', FILTER_SANITIZE_STRING);	
$erp_ws = filter_input(INPUT_POST, 'erp_ws', FILTER_SANITIZE_STRING);	
$store_url=filter_input(INPUT_POST, 'store_url', FILTER_SANITIZE_URL);
$ws_token=filter_input(INPUT_POST, 'ws_token', FILTER_SANITIZE_STRING);
$ws_api=filter_input(INPUT_POST, 'ws_api', FILTER_SANITIZE_STRING);
$ws_secret=filter_input(INPUT_POST, 'ws_secret', FILTER_SANITIZE_STRING);
$preco_linha=filter_input(INPUT_POST, 'preco_linha', FILTER_SANITIZE_STRING);
$catdefault=filter_input(INPUT_POST, 'catdefault', FILTER_SANITIZE_STRING);
$catTreeTop=filter_input(INPUT_POST, 'catTreeTop', FILTER_SANITIZE_STRING);
$tpfamilia=filter_input(INPUT_POST, 'tpfamilia', FILTER_SANITIZE_STRING);
$tpSfamilia=filter_input(INPUT_POST, 'tpsubfamilia', FILTER_SANITIZE_STRING);
$tpmarcas=filter_input(INPUT_POST, 'tpmarcas', FILTER_SANITIZE_STRING);
$portes = isset($_POST['portes']) && $_POST['portes']!="" ? $_POST['portes'] : 0;

$importar=$_POST['importar'];
$grelhas=isset($_POST['grelha']) && $_POST['grelha']!="" ? $_POST['grelha'] : array();  

$settings=serialize(array("erp"=>$erp,"store"=>$store,"erp_ws"=>$erp_ws,"store_url"=>$store_url,"ws_token"=>$ws_token,"ws_api"=>$ws_api,"ws_secret"=>$ws_secret,"importar"=>serialize($importar),"preco_linha"=>$preco_linha,"portes"=>$portes,"catdefault"=>$catdefault,"catTreeTop"=>$catTreeTop,"tpfamilia"=>$tpfamilia,"tpSfamilia"=>$tpSfamilia,"tpmarcas"=>$tpmarcas,"grelhas"=>serialize($grelhas)));

$mysqli->query("UPDATE settings set settings='$settings',api='$ws_token' where idnum='1'") or die($mysqli->errno .' - '. $mysqli->error);

$sucesso=1;	$html_msg="Definições editadas com êxito!";
$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg");

}



/* ############################################## EDITAR FOTO #################################################### */

if (isset($_POST['accaoP']) && $accao == "editafotodef" && $_SERVER['REQUEST_METHOD'] == "POST") {
$imgContent=addslashes(file_get_contents($_FILES['uploadfile']['tmp_name']));
$filename = basename($_FILES["uploadfile"]["name"]);

$htmlmsg="";
if(!is_dir(DOCROOT."/attachments/".$_SESSION['empresaID']."")){
	mkdir(DOCROOT."/attachments/".$_SESSION['empresaID'].""); 	
}
  
$imagemdef=settings_val($_SESSION['empresaID'],"imagem");  
$imagemdef=$imagemdef['filename'];  
 
if(is_file(DOCROOT."/attachments/".$_SESSION['empresaID']."/$imagemdef")){
	unlink(DOCROOT."/attachments/".$_SESSION['empresaID']."/$imagemdef"); 
}
 
move_uploaded_file($_FILES['uploadfile']['tmp_name'], DOCROOT."/attachments/".$_SESSION['empresaID']."/$filename");
 
updateSettings("imagem", serialize(array("filename"=>$filename)));   
  
$mensagem="1"; 
if($htmlmsg=="")	{ $htmlmsg="Foto editada com sucesso!"; } 
$output = array("success" => "$mensagem","type" => "info", "message" => "$htmlmsg", "filename" => "$filename");    
}


/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}