<?php include("index.php");

if($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['act']))	{	$accao="list";	}
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['act']))	    {	$accao=$_GET['act'];	}
if($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['act']))	{	$accao="list";	}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['act']))	    {	$accao=$_POST['act'];	}

$limit=isset($_GET['limit']) && $_GET['limit']!="" ? $_GET['limit'] : 1000;
$offset=isset($_GET['offset']) && $_GET['offset']!="" ? $_GET['offset'] : 0;

# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if($accao=="list"){
    $token = erpLoginATENA();
    $output = array();
    $idCliente = $_POST['idCliente'];
    $page = 1;
    $auth = "Authorization: Bearer ".$token;

    $detailERP = array("idCliente" => $idCliente, "page" => $page);

    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,            "https://atenasync.com/api/getStock");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST,           1);    
	curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($detailERP));
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
        $auth,
		"Content-Type: application/json",
	));
	$result = json_decode(curl_exec ($ch), true);
    $_SESSION['tkerpcook']=$cookie_jar;   
    if(is_array($result) && array_key_exists('status',$result)){
        $output = $result;  
        /*$last_page= $result['stock']['last_page'];
        for ($i = 2; $i <= $last_page; $i++){
            $detailERP = array("idCliente" => $idCliente, "page" => $i);

            curl_setopt($ch, CURLOPT_URL,            "https://atenasync.com/api/getStock");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST,           1);    
            curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($detailERP));
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
                $auth,
                "Content-Type: application/json",
            ));
            $result = json_decode(curl_exec ($ch), true);
            if(is_array($result) && array_key_exists('status',$result)){
                $output['stock']['data'] = array_merge($output['stock']['data'],$result['stock']['data']);
            }
        }*/
        $output = $output['stock']['data'];
    } else {
        $output = "Erro a obter os dados";
    }
    
    //print_r($output);
   }
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
if(isset($output)){
    echo json_encode($output );
}
