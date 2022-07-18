<?php include_once 'include/db_connect.php'; include_once 'include/functions.php';

//die(print_r($_GET));
$artigo=$_GET['artigo'];
$idUser=$_GET['usr'];


$query = $mysqli->query("select settings from empresas where idnum=".$idUser."") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();

$settings=unserialize($dados['settings']);

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$json=file_get_contents($settings['erp_ws']."/artigos.php?auth_userid=".$settings['ws_token']."&act_g=getFoto&idartigo=".$artigo."", false, stream_context_create($arrContextOptions));
$imgData = json_decode($json, true);

if($imgData!=""){

echo imgDisplayFromString($imgData);

}