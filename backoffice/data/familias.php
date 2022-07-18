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
if (isset($_POST['accaoP']) && $accao == "edit" && $_SERVER['REQUEST_METHOD'] == "POST") {
$erp_familia = filter_input(INPUT_POST, 'erp_familia', FILTER_SANITIZE_STRING);	
$store_familia = filter_input(INPUT_POST, 'store', FILTER_SANITIZE_STRING);	
$server_output="";

$prefs=settings_val(1);
$webserviceERP=$prefs['erp_ws'];
$webserviceTKN=$prefs['ws_token']; 

if($store_familia==""){
$mysqli->query("DELETE FROM familias where id_erp='$erp_familia'") or die($mysqli->errno .' - '. $mysqli->error);	

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$webserviceERP."/familias.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"act_p=uncheckweb&num=$erp_familia&auth_userid=".$webserviceTKN."");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$server_output = curl_exec ($ch);
curl_close ($ch);

}  else {
$query = $mysqli->query("select id_erp from familias where id_erp='$erp_familia'") or die($mysqli->errno .' - '. $mysqli->error);
$rowcount=mysqli_num_rows($query);
if($rowcount==0){
	
	$mysqli->query("insert into familias (id_erp,id_store) values ('$erp_familia','$store_familia')") or die($mysqli->errno .' - '. $mysqli->error);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$webserviceERP."/familias.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,"act_p=checkweb&num=$erp_familia&auth_userid=".$webserviceTKN."");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$server_output = curl_exec ($ch);
	curl_close ($ch);

} else {
	$mysqli->query("UPDATE familias SET id_store='$store_familia' WHERE id_erp='$erp_familia'") or die($mysqli->errno .' - '. $mysqli->error);	 
}
}

$htmlmsg="Família editada";
$output = array("success" => "1","type" => "info","message" => "$htmlmsg","erp" => "$server_output");	
}

/* ############################################## CRIAR CAT WEB #################################################### */
if (isset($_POST['accaoP']) && $accao == "createWeb" && $_SERVER['REQUEST_METHOD'] == "POST") {
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);	
$platform = filter_input(INPUT_POST, 'platform', FILTER_SANITIZE_STRING);	
$catERP = filter_input(INPUT_POST, 'catERP', FILTER_SANITIZE_STRING);	
$catParent = filter_input(INPUT_POST, 'catParent', FILTER_SANITIZE_STRING);	

include_once DOCROOT.'/include/functions.'.$platform.'.php';	

if($platform=="woocommerce"){
$data = [
    'name' => ''.$nome.''
];
}

if($platform=="prestashop"){
if($catParent==""){
	$is_root_category=1;
	//$catParent=0;
} else {
	$is_root_category=0;	
}
$data=array("nome"=> normalize($nome),"id_parent"=> "$catParent","is_root_category"=> $is_root_category,"desc"=>normalize($nome),"link_rewrite"=> link_rewrite($nome),"meta_title"=> "","meta_description"=> "","meta_keywords"=> "");
}

$cat=add_categoria($data);

$catID=$cat['id'];
if($catID>0){
$success=1;	
} else {
$success=0;		
}


$htmlmsg="Família criada";
$output = array("success" => "$success","type" => "info","message" => "$htmlmsg","catID" => "$catID");	
}

/* ############################################## EDITAR FAMILIA #################################################### */
if (isset($_POST['accaoP']) && $accao == "remsinc" && $_SERVER['REQUEST_METHOD'] == "POST") {
$idreGisto = mysqli_real_escape_string($mysqli,$_POST['num']);	
$mysqli->query("delete from familias where id_erp='$idreGisto'") or die($mysqli->errno .' - '. $mysqli->error);
$success=1;
$htmlmsg="Ligação removida";
$output = array("success" => "$success","type" => "info","message" => "$htmlmsg");	
}

/* ############################################## ARTIGO FAMILIA #################################################### */
if (isset($_GET['accaoG']) && $accao == "listFamSelect" && $_SERVER['REQUEST_METHOD'] == "GET") {
$artigo = mysqli_real_escape_string($mysqli,$_GET['artigo']);	
$output = niveis_familias($artigo);	
}
/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}