<?php header('Access-Control-Allow-Origin: *'); header('Content-Type: application/json;');  //error_log("".serialize($_REQUEST)."", 1, "webmaster@pombaldir.com"); 
include_once 'include/db_connect.php';	include_once 'include/functions.php';
  

if((isset($_GET['auth_userid']) &&  $_GET['auth_userid']=="".tokenAPI."") || (isset($_POST['auth_userid']) &&  $_POST['auth_userid']=="".tokenAPI."")) {
if(isset($_GET['act_g']))	{	$act_get=stripslashes($_GET['act_g']);	} else {$act_get="";}
if(isset($_POST['act_p']))	{	$act_pst=stripslashes($_POST['act_p']); } else {$act_pst="";}
/* #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #   */
/* #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #   */
 
 /**/
require ("../backoffice/include/cpaneluapi.class.php");
$cPanel = new cpanelAPI('erpsinc', 'vV6cZ6pW1pR5tM3d', 'localhost', 'ANM9T59X7165IJT4NIDDCW8TEZ31S60X');


// clonetes_pombaldir   ANM9T59X7165IJT4NIDDCW8TEZ31S60X

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($_POST['act_p']) && $act_pst=="create" && ($_SERVER['REQUEST_METHOD'] === 'POST')){
	
$userid=mysqli_real_escape_string($mysqli,$_POST['userid']); 
$usernam=mysqli_real_escape_string($mysqli,$_POST['usernam']); 
$passwd=base64_decode(mysqli_real_escape_string($mysqli,$_POST['passwd'])); 
$nome=mysqli_real_escape_string($mysqli,$_POST['nome']); 
$company=mysqli_real_escape_string($mysqli,$_POST['company']); 
$serviceid=mysqli_real_escape_string($mysqli,$_POST['serviceid']); 
$email=mysqli_real_escape_string($mysqli,$_POST['email']); 
$nif=mysqli_real_escape_string($mysqli,$_POST['nif']); 
$codigo=mysqli_real_escape_string($mysqli,$_POST['codigo']); 
$domain=mysqli_real_escape_string($mysqli,$_POST['domain']); 
$ecommerce=mysqli_real_escape_string($mysqli,$_POST['ecommerce']); 
$erp=mysqli_real_escape_string($mysqli,$_POST['erp']); 
$empr=""; 
$erroBD=array();

$query = $mysqli->query("select idnum from empresas where nif='$nif'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
if($query->num_rows==0){

if($company==""){	$company=$nome;	}
$setts=serialize(array('erp' => ''.$erp.'','store' => ''.$ecommerce.'','erp_ws' => 'https://','store_url' => 'https://'.$domain.'','ws_token' => '','ws_api' => '','ws_secret' => '','importar' => 'a:4:{i:0;s:5:"stock";i:1;s:6:"precos";i:2;s:7:"imagens";i:3;s:5:"descr";}','portes'=>0,'preco_linha' => '1','catdefault' => '','tpfamilia' => '','tpmarcas' => ''));

$mysqli->query("insert into empresas (nif,nome,serviceid,maxprod) values ('$nif','$company','$serviceid','1000')") or die("ERRO Conn1 : ".mysql_error());   
$empr=$mysqli->insert_id;

/* 
$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
$password = hash('sha512', $passwd . $random_salt);	

$mysqli->query("insert into members (`username`, `email`, `password`, `salt`, `nome`, `grupo`, `empresa`) values ('$usernam', '$email', '$password', '$random_salt', '$nome',1,'$empr')");   
*/
$passwd=hash('sha512', $passwd);
 
if (strlen($passwd) != 128) {
	$erroBD[]="Password inválida";
}
if(sizeof($erroBD)==0){
$random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
$password = hash('sha512', $passwd . $random_salt);
 
$mysqli->query("insert into members (`username`, `email`, `password`, `salt`, `nome`, `grupo`, `empresa`) values ('$usernam', '$email', '$password', '$random_salt', '$nome',1,'$empr')");   


if(sizeof($erroBD)==0){
$cpaneluser="teknisoftcp";
$databasename="erpsinc_e_".str_pad(substr($serviceid,-9), 9, '0', STR_PAD_LEFT);
$databaseuser="erpsinc_u".str_pad($empr, 6, '0', STR_PAD_LEFT);
$databasepass="12%4Gh6pt";

//create database    
$response =  $cPanel->uapi->Mysql->create_database(['name' => $databasename]);
if(is_array($response->errors)){
$erroBD[]=$response->errors[0];
}
//create user 
if(sizeof($erroBD)==0){
$response =  $cPanel->uapi->Mysql->create_user(['name' => $databaseuser, 'password' => $databasepass]);
if(is_array($response->errors)){
$erroBD[]=$response->errors[0];
}}
//add user  
if(sizeof($erroBD)==0){
$response =  $cPanel->uapi->Mysql->set_privileges_on_database(['user' => $databaseuser, 'database' => $databasename, 'privileges' => 'ALL PRIVILEGES']);
if(is_array($response->errors)){
$erroBD[]=$response->errors[0];
}}

}
}

if(sizeof($erroBD)==0){
	
$settsemp=serialize(array('db' => $databasename,'dbusr' => $databaseuser,'dbpw' => $databasepass,'dbh' => 'localhost'));
$mysqli->query("UPDATE empresas SET settingsbd='$settsemp' where idnum='$empr'");   
 	

$myquery='SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
USE '.$databasename.';

CREATE TABLE artigos (
  idnum int(11) NOT NULL,
  id_erp int(11) NOT NULL DEFAULT 0,
  id_erp_ref int(11) DEFAULT NULL,
  id_store int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  idnum int(11) NOT NULL,
  nome varchar(128) DEFAULT NULL,
  nif varchar(16) DEFAULT NULL,
  serviceid int(12) DEFAULT NULL,
  imagem varchar(128) DEFAULT NULL,
  maxprod int(20) NOT NULL DEFAULT \'20\',
  api varchar(255) DEFAULT NULL,
  settings text
);

DROP TABLE IF EXISTS familias;
CREATE TABLE familias (
  idnum int(11) NOT NULL,
  id_erp int(11) NOT NULL DEFAULT \'0\',
  id_store int(11) DEFAULT NULL
);

DROP TABLE IF EXISTS logs;
CREATE TABLE `logs` (
  idnum int(11) NOT NULL,
  user int(10) DEFAULT NULL,
  accao text,
  numero int(40) DEFAULT NULL,
  data_ad timestamp NOT NULL
);


ALTER TABLE artigos
  ADD PRIMARY KEY (idnum);

ALTER TABLE settings
  ADD PRIMARY KEY (idnum);

ALTER TABLE familias
  ADD PRIMARY KEY (idnum);

ALTER TABLE `logs`
  ADD PRIMARY KEY (idnum);

ALTER TABLE artigos
  MODIFY idnum int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE settings
  MODIFY idnum int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE familias
  MODIFY idnum int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `logs`
  MODIFY idnum int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO settings (settings,nif,nome,serviceid,maxprod) VALUES (\''.$setts.'\',\''.$nif.'\',\''.$company.'\',\''.$serviceid.'\',\'1000\');

COMMIT;';
}

if(sizeof($erroBD)==0){
$mysqli = new mysqli('localhost', $databaseuser, $databasepass, $databasename);
$mysqli->set_charset("utf8");
mysqli_multi_query($mysqli,"$myquery") or $erroBD=$mysqli->errno .' - '. $mysqli->error;
} 


}  

$output = array("result" => "success", "emp"=>$password, "msg"=>$erroBD);  	 
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($act_pst=="suspend"){
$serviceid=mysqli_real_escape_string($mysqli,$_POST['serviceid']); 
$mysqli->query("UPDATE empresas SET status=0 where serviceid='$serviceid'");   
$output = array("result" => "success");
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($act_pst=="unsuspend"){
$serviceid=mysqli_real_escape_string($mysqli,$_POST['serviceid']); 
$mysqli->query("UPDATE empresas SET status=1 where serviceid='$serviceid'");   
$output = array("result" => "success"); 
}
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

if($act_pst=="terminate"){
$serviceid=mysqli_real_escape_string($mysqli,$_POST['serviceid']); 

$query = $mysqli->query("select idnum,settingsbd from `empresas` WHERE serviceid='$serviceid'");
$dados = $query->fetch_array();
$empresaId=$dados['idnum'];
$dadosBD=unserialize($dados['settingsbd']);

$cPanel->uapi->Mysql->delete_user(['name' => $dadosBD['dbusr']]);
$cPanel->uapi->Mysql->delete_database(['name' => $dadosBD['db']]);
  
$mysqli->query("DELETE FROM `empresas` WHERE idnum='$empresaId' AND serviceid='$serviceid'");
$mysqli->query("DELETE FROM `members` WHERE empresa='$empresaId'");

$output = array("result" => "success","dados" => $_POST);  
}

/* #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #  #   */
echo json_encode($output);	}