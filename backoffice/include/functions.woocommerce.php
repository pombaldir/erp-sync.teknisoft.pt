<?php
include_once DOCROOT.'/include/db_connect.php';


$query = $mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
$settings=unserialize($dados['settings']);

$store_url=$settings['store_url'];
$ws_api=$settings['ws_api'];
$ws_secret=$settings['ws_secret'];


require(DOCROOT."/vendor/autoload.php");

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

$woocommerce = new Client(
    ''.$store_url.'', 
    ''.$ws_api.'', 
    ''.$ws_secret.'',
    [
    'wp_api' => true,
		'query_string_auth' => true,
    //'follow_redirects' => true,
		'verify_ssl' => false,		
        'version' => 'wc/v2',
    ]
);


  
function get_produto($productID,$retorna=0){
global $woocommerce;
  try {
    $product=$woocommerce->get('products/'.$productID.'');
    return $product;
  } catch(HttpClientException $e){
    if($retorna==0){
      return 0;
    } else {
      return trataMsgErro($e);   
    }
  }
}

 
function get_produtosTotal($fields){
global $woocommerce;
$page = 1;
$products = [];
$all_products = [];
do{
  try {
    $products = $woocommerce->get('products',array('per_page' => 100, 'page' => $page, 'status'=>'publish', '_fields'=>''.$fields.''));
  }catch(HttpClientException $e){
    die("Can't get products: $e");
  }
  $all_products = array_merge($all_products,$products);
  $page++;
} while (count($products) > 0);

return $all_products;
}


function add_produto($data,$retorna=0){
global $woocommerce;
  try {
    return $woocommerce->post('products', $data);
  } catch(HttpClientException $e){
    if($retorna==0){  return 0;  } else {
      return trataMsgErro($e);   
    } 
  }
}

 
function edit_produto($id,$data){
global $woocommerce;
return $woocommerce->put('products/'.$id.'', $data);
}

function edit_produto_variation($id,$idvar,$data){
global $woocommerce;
return $woocommerce->put('products/'.$id.'/variations/'.$idvar.'', $data);
}

function remove_produto($productID,$retorna=0){
global $woocommerce;
$artigoStore=getArtigoStoreId($productID);
try {
  return $woocommerce->delete('products/'.$artigoStore.'', ['force' => true]);
} catch(HttpClientException $e){
  if($retorna==0){
    return 0;
  } else {
    return trataMsgErro($e);   
  }
}
}
 




/**/
function list_categorias($parent=""){
global $woocommerce; 
try{
  //$p1=$woocommerce->get('products/categories',array('per_page' => 100, 'page' => 1, 'status'=>'publish', 'parent'=>$parent, '_fields'=>array('id','name','parent','menu_order')));
  $i=1;
  $arrRes=array();
  $arrAtrib=array('per_page' => 100, 'status'=>'publish','_fields'=>array('id','name','parent','menu_order'));
  if($parent!=""){
    $arrAtrib['parent']=$parent;
  }
  while ($i<=10){
    $arrAtrib['page']=$i;
    $arrFin=$woocommerce->get('products/categories',$arrAtrib);
    $arrRes=array_merge( $arrFin,$arrRes);

    if(sizeof($arrFin)/100==0){
      return array_values($arrRes);
    }
  $i++;
  }

  return array_values($arrRes); 
}
catch (HttpClientException $e) {
    
  //echo "<!--";
    print_r($e->getMessage());
    print_r($e->getRequest());
    print_r($e->getResponse());
  //  echo "-->";
}
}  





function get_categoria($idcat){
global $woocommerce;
try {
	return $woocommerce->get('products/categories/'.$idcat.'');	
} catch(HttpClientException $e) {
     //print_r($e->getMessage());
	 //print_r($e->getRequest());
	 //print_r($e->getResponse());
	 return array("id"=>0);
}
}

function add_categoria($data){
global $woocommerce;
return $woocommerce->post('products/categories', $data);
}


function getIdStockAvailableAndSet($ProductId,$stock,$variable=""){
global $woocommerce;
try {
	$data = [
    'stock_quantity' => ''.$stock.'',
	'_fields'=>array('id')
	];
  if($variable==""){
    return $woocommerce->put('products/'.$ProductId.'',$data);
  } else {  
    return $woocommerce->put('products/'.$variable.'/variations/'.$ProductId.'',$data);   
  }
} catch(HttpClientException $e) {
	 return array("id"=>0);
}
}



function list_atributos(){
global $woocommerce;
$artribt=$woocommerce->get('products/attributes',array('per_page' => 100, 'page' => 1, 'status'=>'publish', '_fields'=>array('id','name','slug','type')));
foreach($artribt as $valor){
    $res[$valor['id']]=$valor; 
}    
  return  $res;  
}

function atributos_termo_existe($variacao,$termo){
global $woocommerce;
$artribt=$woocommerce->get('products/attributes/'.$variacao.'/terms',array('per_page' => 100, 'page' => 1, '_fields'=>array('id','name')));
$pesqAtrib = array_search($termo, array_column($artribt, 'name'));
 return  $pesqAtrib;  
}

function atributos_add_termo($id,$data){
global $woocommerce;
return $woocommerce->post('products/attributes/'.$id.'/terms', $data);
} 

function add_variacao($produto,$data,$retorna=0){
global $woocommerce;
  try {
    return $woocommerce->post('products/'.$produto.'/variations', $data);
  } catch(HttpClientException $e){
    if($retorna==0){  return 0; } else {  
      return trataMsgErro($e); 
    }
  }
} 


function remove_variacao($produto,$id,$retorna=0){
global $woocommerce;
try {
  return $woocommerce->delete('products/'.$produto.'/variations/'.$id.'',array("force"=>1));
} catch(HttpClientException $e){
  if($retorna==0){
    return 0;
  } else {
    return trataMsgErro($e);    
  }
}

} 


function trataMsgErro($string){ 
  $msg=get_string_between((string)$string, ': Error: ', 'in /home/erpsinc'); 
  return $msg;
}   

function get_string_between($string, $start, $end){
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}

