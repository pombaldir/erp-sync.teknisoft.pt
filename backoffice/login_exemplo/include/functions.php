<?php

/*
 * Copyright (C) 2013 peredur.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once 'psl-config.php';

require(DOCROOT."/vendor/eventviva/php-image-resize/lib/ImageResize.php");
use \Eventviva\ImageResize;

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
); 

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name 
    $secure = SECURE;

    // This stops JavaScript being able to access the session id.
    $httponly = true;

    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
		
		$response = array("mensagem" => "0", "redir" => "0", "htmlmsg" => "Could not initiate a safe session (ini_set)");
		echo json_encode($response);
		
        exit();
    }

    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

    // Sets the session name to the one set above.
    session_name($session_name);

    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password, salt, grupo, empresa  FROM members WHERE username = ? LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $salt, $grupo, $empresa);
        $stmt->fetch();

        // hash the password with the unique salt.
        $password = hash('sha512', $password . $salt); 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked 
                return false;
            } else {
                // Check if the password in the database matches 
                // the password the user submitted.
                if ($db_password == $password) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];

                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;

                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
					$nomecliente=get_user($user_id, $mysqli);
					$_SESSION['nome'] = $nomecliente['nome'];
					$_SESSION['usrGrp'] = $grupo;
					$_SESSION['empresa']=config_val('empresa', $mysqli);
					$_SESSION['empresaID']=$empresa;
					$idrandom = md5(uniqid(mt_rand(), true));
    				$_SESSION["token"] = $idrandom;
					addLog("Login"); 
					
					
					if(!is_file(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php')){
						$query = $mysqli->query("select settingsbd from empresas where idnum='$empresa'");
						$dados = $query->fetch_array();
						$bdSet=unserialize($dados['settingsbd']);
						$cont="<?php  define(\"HOST2\", \"localhost\");\n";
						$cont.="define(\"USER2\", \"".$bdSet['dbusr']."\");\n";
						$cont.="define(\"PASSWORD2\", \"".$bdSet['dbpw']."\");\n";
						$cont.="define(\"DATABASE2\", \"".$bdSet['db']."\");\n\n\n";
						$cont.="?>";
						$fp = fopen(DOCROOT.'/data/emp-'.$_SESSION['empresaID'].'.php', 'w');
						fwrite($fp, $cont);
						fclose($fp);
					}
					
					$query2 = $mysqli->query("select DATE_FORMAT(data_ad,'%d/%m/%Y') as data_ad,DATE_FORMAT(data_ad,'%H:%i') as hora from logs where user='".$user_id."' and accao='Login' order by idnum desc LIMIT 1,1") or die($mysqli->errno .' - '. $mysqli->error);
					$dados2 = $query2->fetch_array();
					$_SESSION['lastLoginD']=$dados2['data_ad'];
					$_SESSION['lastLoginH']=$dados2['hora'];
					
                    // Login successful. 
                    return true;
                } else {
                    // Password is not correct 
                    // We record this attempt in the database 
                    $now = time();
                    if (!$mysqli->query("INSERT INTO login_attempts(user_id, time) 
                                    VALUES ('$user_id', '$now')")) {
						
							$response = array("mensagem" => "0", "redir" => "0", "htmlmsg" => "=Database error: login_attempts");
							echo json_encode($response);
		
						
                        exit();
                    }

                    return false;
                }
            }
        } else {
            // No user exists. 
            return false;
        }
    } else {
        // Could not create a prepared statement
		$response = array("mensagem" => "0", "redir" => "0", "htmlmsg" => "Erro de BD: cannot prepare statement");
		echo json_encode($response);		
        exit();
    }
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();

    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);

        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    } else {
        // Could not create a prepared statement
		$response = array("mensagem" => "0", "redir" => "0", "htmlmsg" => "Database error: cannot prepare statement");
		echo json_encode($response);			
        exit();
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password 
				      FROM members 
				      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if ($login_check == $login_string) {
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Could not prepare statement
							$response = array("mensagem" => "0", "redir" => "0", "htmlmsg" => "Login Check: cannot prepare statement");
							echo json_encode($response);			

            exit();
        }
    } else {
        // Not logged in 
        return false;
    }
}


function change_password($membersID, $password) {
	global $mysqli;
    $membersID = (int)$membersID;
	$mysqli->query("UPDATE members set password='$password' where id='$membersID'") or die($mysqli->errno .' - '. $mysqli->error);
    if (@mysqli_affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}



function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
    
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
    
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
    
    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);
    
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}
   
function get_IdbyUsername($user_id) {
global $mysqli;
$query = $mysqli->query("select id FROM members WHERE username = '$user_id'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_assoc();
	return $dados['id'];
}

function get_user($user_id, $mysqli) {
$query = $mysqli->query("select nome,email,foto FROM members  WHERE id = $user_id") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_assoc();
	return $dados;
}

function get_user_full($user_id, $mysqli) {
$query = $mysqli->query("select * FROM members  WHERE id = $user_id") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_assoc();
	return $dados;
}

function config_val($nmecpo){ // Função de configuração
global $mysqli;
$query = $mysqli->query("select param,valor from tbl_config where param='$nmecpo'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['valor'];
}

function updateConfig($field, $valor){ 
global  $mysqli;
$query = $mysqli->query("UPDATE tbl_config set $field='$valor' where param='$param'") or die($mysqli->errno .' - '. $mysqli->error);
return 1;
}

function updateSettings($param, $valor){ 
global  $mysqli;
$query = $mysqli->query("UPDATE settings set $param='$valor' where idnum='1'") or die($mysqli->errno .' - '. $mysqli->error);
return 1;
}


function settings_val($idempresa,$campo="settings"){ // Função de configuração empresa
global $mysqli;
$query = $mysqli->query("select $campo from settings where idnum='$idempresa'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return @unserialize($dados[''.$campo.'']);
}



function addLog($activity,$user=NULL,$numero=NULL){ 
global $mysqli;
if($user==""){ $user=$_SESSION['user_id']; }
if($numero==""){ $numero=""; }
$query = $mysqli->query("INSERT INTO logs (user,accao,numero) VALUES ('$user','$activity','$numero')") or die($mysqli->errno .' - '. $mysqli->error);
}


function imgFromString_W($img,$width,$out=""){
$image = ImageResize::createFromString($img);
$image->resizeToWidth($width);
$image->crop($width, $width);

if($out==""){
return $image->getImageAsString();	
} else {
$image->save(''.$out.'');
}
}


function imgDisplayFromString($imgData,$width=""){
try {	
$image = ImageResize::createFromString(base64_decode($imgData));
if($width!=""){
$image->resizeToWidth($width);
}
return $image->output();

} catch(HttpClientException $e) {
return $e->getMessage();
}

}




function updtArtigoLocal($erp,$store=NULL,$tpo){ 
global $mysqli;
if($tpo=="cb_artigo_a"){
$query = $mysqli->query("INSERT INTO artigos (id_erp,id_store) VALUES ('$erp','$store')") or die($mysqli->errno .' - '. $mysqli->error);
}
if($tpo=="cb_artigo_d"){
$idPartigoStore=getArtigoStoreId($erp);	
$query = $mysqli->query("delete from artigos where id_erp='$erp'") or die($mysqli->errno .' - '. $mysqli->error);
}

if($tpo=="cb_artigo_u"){
$query = $mysqli->query("select idnum from artigos where  id_erp='$erp' and id_store='$store'") or die($mysqli->errno .' - '. $mysqli->error);
if($query->num_rows==0){
	updtArtigoLocal($erp,$store,"cb_artigo_a");	
}
}

addLog($tpo,"",$erp);
}

function getArtigoStoreId($erpID){ 
global $mysqli;

$query = $mysqli->query("select id_store from artigos where id_erp='$erpID'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['id_store'];

}



function getcategoriaStoreId($erpID){ 
global $mysqli;

$query = $mysqli->query("select id_store from familias where id_erp='$erpID'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['id_store'];
}


function getFamiliaERPId($storeFamId){ 
global $mysqli;
$query = $mysqli->query("select id_erp from familias where id_store='$storeFamId'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['id_erp'];
}

function getFamiliaStoreId($storeFamId){ 
global $mysqli;
$query = $mysqli->query("select id_store from familias where id_erp='$storeFamId'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['id_store'];
}



function getArtigoERPId($storeFamId){ 
global $mysqli;
$query = $mysqli->query("select id_erp from artigos where id_store='$storeFamId'") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['id_erp'];
}

function countArtigosTotal(){ 
global $mysqli;
$query = $mysqli->query("select COUNT(idnum) as total from artigos") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['total'];
}

function countFamiliasTotal(){ 
global $mysqli;
$query = $mysqli->query("select COUNT(idnum) as total from familias") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
return $dados['total'];
}


function buildTree(array $data, $parent = 0) {
    $tree = array();
	
    foreach ($data as $d) {
        if ($d['parent'] == $parent) {
            $children = buildTree($data, $d['id']);
            // set a trivial key
            if (!empty($children)) {
                $d['_children'] = $children;
            }
            $tree[] = $d;
        }
    }
    return $tree;
}


function printTree($tree,$selected, $r = 0, $p = null) {
    foreach ($tree as $i => $t) {
        $dash = ($t['parent'] == 0 || $t['parent'] == $p) ? '' : str_repeat('--', $r) .' ';
		if($t['id']==$selected){ $selectedHTML="selected"; } else {	$selectedHTML=""; }
        printf("\t<option value='%d' ".$selectedHTML.">%s%s</option>\n", $t['id'], $dash, $t['name']);
        if ($t['parent'] == $p) {
            // reset $r
            $r = 0;
        }
        if (isset($t['_children'])) {
            printTree($t['_children'], $selected, ++$r, $t['parent']);
        }
    }
}

function record_sort($records, $field, $reverse=false)
{
    $hash = array();
    
    foreach($records as $key => $record)
    {
        $hash[$record[$field].$key] = $record;
    }
    
    ($reverse)? krsort($hash) : ksort($hash);
    
    $records = array();
    
    foreach($hash as $record)
    {
        $records []= $record;
    }
    
    return $records;
} 


function base64_to_jpeg($base64_string, $part, $output_file) {
    // open the output file for writing
    $ifp = fopen( $output_file, 'wb' ); 
    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode( ',', $base64_string );
    // we could add validation here with ensuring count( $data ) > 1
    fwrite( $ifp, base64_decode( $data[ $part ] ) );
    // clean up the file resource
    fclose( $ifp ); 
    return $output_file; 
}


function normalize ($string) {
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'd'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'R'=>'R', 'r'=>'r',
    );
    return strtr($string, $table);
}

function link_rewrite($url) {
	$url = normalize($url); 
	$url = strtolower($url);   
	$url = str_replace(" ", '-', $url);
    return $url;
}



function niveis_familias($artigo){
	
$prefs=settings_val(1);	
global $arrContextOptions;

$urlws=$prefs['erp_ws']."/artigos.php?act_g=view&num=$artigo&auth_userid=".$prefs['ws_token'];
$json=file_get_contents("$urlws", false, stream_context_create($arrContextOptions));
$obj = json_decode($json, true);
$art_familias=$obj['familias'];

$urlws1=$prefs['erp_ws']."/familias.php?act_g=listaTipos_Familias&auth_userid=".$prefs['ws_token'];
$json1=file_get_contents("$urlws1", false, stream_context_create($arrContextOptions));
$obj1 = json_decode($json1, true);

$urlws2=$prefs['erp_ws']."/familias.php?act_g=lista_FamCompl&auth_userid=".$prefs['ws_token'];
$json2=file_get_contents("$urlws2", false, stream_context_create($arrContextOptions));
$obj2 = json_decode($json2, true);
 
foreach($obj1 as $fam){		

$nvarr=@$obj2[$fam['strCodigo']];
$keyFamilia = array_search(''.$fam['strCodigo'].'', array_column($art_familias, 'strCodTpNivel')); 
$varFam='<select data-legenda="'.$fam['strDescricao'].'" data-nivel="'.$fam['intNivel'].'" class="form-control nivelChange" name="fam['.$fam['strCodigo'].']" id="fam_'.$fam['intNivel'].'"><option value="">Escolha 1 '.$fam['strDescricao'].'</option>';			
if(is_array($nvarr) && sizeof($nvarr)>0){ 
foreach($nvarr as $fval){   
$varFam.= '<option ';
	if(sizeof($art_familias)>0){
	if($art_familias[$keyFamilia]['strCodFamilia']=="".$fval['strCodigo']."") $varFam.= 'selected '; 
	}
	$varFam.= 'value="'.$fval['strCodigo'].'">'.$fval['strDescricao'].'</option>';
	}
	$varFam.='<option value="custom">=- Criar '.$fam['strDescricao'].' no ERP -=</option></select>';
    
	$varFamilias[$fam['intNivel']]=array("label"=>$fam['strDescricao'],"valor"=>$varFam);      
} 
} 


return $varFamilias;  
}

