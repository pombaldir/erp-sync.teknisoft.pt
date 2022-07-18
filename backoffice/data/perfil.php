<?php include_once '../include/db_connect.php';	include_once '../include/functions.php'; if(isset($_POST['accaoP']) && $_POST['accaoP'] != "editafoto")  header('Content-Type: application/json'); 

sec_session_start();


if (login_check($mysqli) == true) {
	
if (isset($_POST['accaoP'])) {
    $accao = mysqli_real_escape_string($mysqli,$_POST['accaoP']);
}
if (isset($_GET['accaoG'])) {
    $accao = mysqli_real_escape_string($mysqli,$_GET['accaoG']);
}
/* ############################################## EDITAR PERFIL #################################################### */

if (isset($_POST['accaoP']) && $accao == "edit" && $_SERVER['REQUEST_METHOD'] == "POST") {


$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);	
$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);	
$password = filter_input(INPUT_POST, 'p1', FILTER_SANITIZE_STRING);	
$password2 = filter_input(INPUT_POST, 'p2', FILTER_SANITIZE_STRING);	
$tlm = filter_input(INPUT_POST, 'tlm', FILTER_SANITIZE_STRING);	
$html_msg="";
$errPass=array();

if($password !="" && ($password == $password2)){ 
	
	if ($stmt = $mysqli->prepare("SELECT password,salt FROM members  WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $_SESSION['user_id']); 
        $stmt->execute();    
        $stmt->store_result();
        $stmt->bind_result($db_password, $salt);
        $stmt->fetch();
	}
	
	$nova_pass=hash('sha512', $password2 . $salt);
	if($nova_pass==$db_password){
		$errPass[]="A Password não pode ser igual à antiga";	
	} 
	if(sizeof($errPass)==0){
	change_password($_SESSION['user_id'], $nova_pass);
	$html_msg.="A password foi alterada.<br>";
	addLog("Password alterada");
	} else {
		foreach($errPass as $errop){
		$html_msg.="$errop<br>";	
		}
	}
	
}

$mysqli->query("UPDATE members set nome='$nome',email='$email',tlm='$tlm' where id='".$_SESSION['user_id']."'") or die($mysqli->errno .' - '. $mysqli->error);

$sucesso=1;	$html_msg.="Perfil editado com exito!";
$output = array("success" => "$sucesso", "type" => "info", "message" => "$html_msg");

}

/* ############################################## EDITAR FOTO #################################################### */

if (isset($_POST['accaoP']) && $accao == "editafoto" && $_SERVER['REQUEST_METHOD'] == "POST") {
$imgContent=addslashes(file_get_contents($_FILES['uploadfile']['tmp_name']));
$idusredit = filter_input(INPUT_POST, 'idusredit', FILTER_SANITIZE_STRING);	
$htmlmsg="";
$mysqli->query("UPDATE members set foto='$imgContent' where id='$idusredit'") or $htmlmsg=$mysqli->errno .' - '. $mysqli->error;
 
 if(is_file(DOCROOT."/images/user".$_SESSION['user_id'].".jpg")){
	unlink(DOCROOT."/images/user".$idusredit.".jpg"); 
 }
 
error_log(imgFromString_W("".stripslashes($imgContent)."",200,DOCROOT."/images/user".$idusredit.".jpg"));
 
$mensagem="1";
if($htmlmsg=="")	{ $htmlmsg="Foto editada com sucesso ok !"; } 
$output = array("success" => "$mensagem","type" => "info", "message" => "$htmlmsg", "src" => "data:image/png;base64,$imgContent");    
}

/* ############################################## OBTER FOTO #################################################### */
if (isset($_GET['accaoG']) && $accao == "getUserPic" && $_SERVER['REQUEST_METHOD'] == "GET") {
$user_id = mysqli_real_escape_string($mysqli,$_GET['user_id']);
$query = $mysqli->query("select foto FROM members  WHERE id = $user_id") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_assoc();
$output = array("success" => "1","type" => "info","fotodata" => base64_encode($dados['foto']));	
}
/* ############################################## EDITAR NOTAS #################################################### */
if (isset($_POST['accaoP']) && $accao == "editNotes" && $_SERVER['REQUEST_METHOD'] == "POST") {
$notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);	

$mysqli->query("UPDATE members set notes='$notes' where id='".$_SESSION['user_id']."'") or die($mysqli->errno .' - '. $mysqli->error);

$htmlmsg="Notas editadas";
$output = array("success" => "1","type" => "info","message" => "$htmlmsg");	
}

/* ############################################## OUTPUT ######################################################### */
echo json_encode($output);
}