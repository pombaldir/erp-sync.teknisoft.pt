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

$prefs=settings_val(1);
include_once DOCROOT.'/include/functions.'.$prefs['store'].'.php';	


/* ############################################## STOCKS UPDATE #################################################### */
if (isset($_POST['accaoP']) && $accao == "updtStk" && $_SERVER['REQUEST_METHOD'] == "POST") {
$stock = $_POST['stk'];
$art = $_POST['art'];
$ref = $_POST['ref'];

$output = getIdStockAvailableAndSet($art,$stock,$ref);

}

/* ############################################## EDITAR ARTIGO #################################################### */
if (isset($_POST['accaoP']) && $accao == "sincArtigo" && $_SERVER['REQUEST_METHOD'] == "POST") {
$id = mysqli_real_escape_string($mysqli,$_POST['id']);
$preco = mysqli_real_escape_string($mysqli,$_POST['preco']);
$stock=mysqli_real_escape_string($mysqli,$_POST['stock']);
$sku=mysqli_real_escape_string($mysqli,$_POST['sku']);
$nome=mysqli_real_escape_string($mysqli,$_POST['nome']);
$n_active=1;
$n_desc="yyyy";
$n_desc_short="zzzzz";
 
add_produto(1, $id, "", "", $preco, $n_active,$n_active,1, "","", 1, $stock, "$nome", $n_desc, $n_desc_short, "", "", "", "","","",array(),"$sku","","","","");

$htmlmsg="Artigo sincronizado";
$output = array("success" => "1","type" => "info","message" => "$htmlmsg");	
}

/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}