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

$settingsRegisto=$mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dadosAtuais=$settingsRegisto->fetch_array();
$settingsAtuais=@unserialize($dadosAtuais['settings']);
if(!is_array($settingsAtuais)){
	$settingsAtuais=array();
}

$importarGuardado=isset($settingsAtuais['importar']) ? @unserialize($settingsAtuais['importar']) : array();
$grelhasGuardadas=isset($settingsAtuais['grelhas']) ? @unserialize($settingsAtuais['grelhas']) : array();

$settingsNovos=$settingsAtuais;

$settingsNovos['erp'] = array_key_exists('erp', $_POST) ? filter_input(INPUT_POST, 'erp', FILTER_SANITIZE_STRING) : (isset($settingsNovos['erp']) ? $settingsNovos['erp'] : "");
$settingsNovos['store'] = array_key_exists('store', $_POST) ? filter_input(INPUT_POST, 'store', FILTER_SANITIZE_STRING) : (isset($settingsNovos['store']) ? $settingsNovos['store'] : "");
$settingsNovos['erp_ws'] = array_key_exists('erp_ws', $_POST) ? filter_input(INPUT_POST, 'erp_ws', FILTER_SANITIZE_STRING) : (isset($settingsNovos['erp_ws']) ? $settingsNovos['erp_ws'] : "");
$settingsNovos['store_url'] = array_key_exists('store_url', $_POST) ? filter_input(INPUT_POST, 'store_url', FILTER_SANITIZE_URL) : (isset($settingsNovos['store_url']) ? $settingsNovos['store_url'] : "");
$settingsNovos['ws_token'] = array_key_exists('ws_token', $_POST) ? filter_input(INPUT_POST, 'ws_token', FILTER_SANITIZE_STRING) : (isset($settingsNovos['ws_token']) ? $settingsNovos['ws_token'] : "");
$settingsNovos['ws_api'] = array_key_exists('ws_api', $_POST) ? filter_input(INPUT_POST, 'ws_api', FILTER_SANITIZE_STRING) : (isset($settingsNovos['ws_api']) ? $settingsNovos['ws_api'] : "");
$settingsNovos['ws_secret'] = array_key_exists('ws_secret', $_POST) ? filter_input(INPUT_POST, 'ws_secret', FILTER_SANITIZE_STRING) : (isset($settingsNovos['ws_secret']) ? $settingsNovos['ws_secret'] : "");
$settingsNovos['preco_linha'] = array_key_exists('preco_linha', $_POST) ? filter_input(INPUT_POST, 'preco_linha', FILTER_SANITIZE_STRING) : (isset($settingsNovos['preco_linha']) ? $settingsNovos['preco_linha'] : "");
$settingsNovos['catdefault'] = array_key_exists('catdefault', $_POST) ? filter_input(INPUT_POST, 'catdefault', FILTER_SANITIZE_STRING) : (isset($settingsNovos['catdefault']) ? $settingsNovos['catdefault'] : "");
$settingsNovos['catTreeTop'] = array_key_exists('catTreeTop', $_POST) ? filter_input(INPUT_POST, 'catTreeTop', FILTER_SANITIZE_STRING) : (isset($settingsNovos['catTreeTop']) ? $settingsNovos['catTreeTop'] : "");
$settingsNovos['tpfamilia'] = array_key_exists('tpfamilia', $_POST) ? filter_input(INPUT_POST, 'tpfamilia', FILTER_SANITIZE_STRING) : (isset($settingsNovos['tpfamilia']) ? $settingsNovos['tpfamilia'] : "");
$settingsNovos['tpSfamilia'] = array_key_exists('tpsubfamilia', $_POST) ? filter_input(INPUT_POST, 'tpsubfamilia', FILTER_SANITIZE_STRING) : (isset($settingsNovos['tpSfamilia']) ? $settingsNovos['tpSfamilia'] : "");
$settingsNovos['tpmarcas'] = array_key_exists('tpmarcas', $_POST) ? filter_input(INPUT_POST, 'tpmarcas', FILTER_SANITIZE_STRING) : (isset($settingsNovos['tpmarcas']) ? $settingsNovos['tpmarcas'] : "");
$settingsNovos['portes'] = array_key_exists('portes', $_POST) && $_POST['portes']!="" ? $_POST['portes'] : (isset($settingsNovos['portes']) ? $settingsNovos['portes'] : 0);

$importar=array_key_exists('importar', $_POST) ? $_POST['importar'] : $importarGuardado;
$grelhas=array_key_exists('grelha', $_POST) && $_POST['grelha']!="" ? $_POST['grelha'] : $grelhasGuardadas;

$settingsNovos['importar']=serialize(is_array($importar) ? $importar : array());
$settingsNovos['grelhas']=serialize(is_array($grelhas) ? $grelhas : array());

$settings=serialize($settingsNovos);
$ws_token=$settingsNovos['ws_token'];

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
