<?php header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: : x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('Content-type: application/json; charset=utf-8');


require_once dirname(__DIR__) . '/ws.erpsinc.pt/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1); 

use Shopify\Context;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;
use Shopify\Rest\Admin2024_07\Product;
use Shopify\Utils;

 
Shopify\Context::initialize(
    '4f9e38e66da270e3b478593b24a7f9c5',
    '9f3f5dd8da7072f2bd85de0b6d46ab05',
    'read_products',
    'https://electrominor.pt/admin/api/2022-01/products.json',
    new FileSessionStorage('/tmp/php_sessions'),
    '2024-07',
    false,
    true,
);


/*
$requestHeaders = array('api_version'=>'2024-07', 'X-Shopify-Access-Token'=>''.base64_encode('4f9e38e66da270e3b478593b24a7f9c5' . ':' . '9f3f5dd8da7072f2bd85de0b6d46ab05'));
$requestCookies = array();
$isOnline = true;

$session = Utils::loadCurrentSession(
    $requestHeaders,
    $requestCookies,
    $isOnline
);
*/
//curl -X GET https://electrominorlda.myshopify.com/admin/api/2024-07/products/9099629035828/metafields/38353444208948.json -H 'Content-Type: application/json' -H 'X-Shopify-Access-Token: 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'




function shopMetaValbyID($endpId,$metafId,$end="products"){
    $ch = curl_init();  
    $url ='https://electrominorlda.myshopify.com/admin/api/2024-07/products/'.$endpId.'/metafields/'.$metafId.'.json';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $streamVerboseHandle = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "X-Shopify-Access-Token: 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"
    ));
    
    $result = json_decode(curl_exec ($ch), true);  
    if ($result === FALSE) {
        printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),  htmlspecialchars(curl_error($ch)));
    } 
    curl_close($ch);  
    if($result==""){ rewind($streamVerboseHandle);  $verboseLog = stream_get_contents($streamVerboseHandle);  echo "cUrl verbose information:\n", "<pre>", htmlspecialchars($verboseLog), "</pre>\n";
    } else {
    return $result['metafield']['value']; 
    }
}
 


function shopMetaValues($endpId,$retKey="",$endp="products"){
    $ch = curl_init();  
    $url ='https://electrominorlda.myshopify.com/admin/api/2024-07/'.$endp.'/'.$endpId.'/metafields.json';

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    $streamVerboseHandle = fopen('php://temp', 'w+');
    curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array(
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json",
        "X-Shopify-Access-Token: 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"
    ));
    
    $result = json_decode(curl_exec ($ch), true);  
    if ($result === FALSE) {
        printf("cUrl error (#%d): %s<br>\n", curl_errno($ch),  htmlspecialchars(curl_error($ch)));
    } 
    curl_close($ch);  
    if($result==""){ rewind($streamVerboseHandle);  $verboseLog = stream_get_contents($streamVerboseHandle);  echo "cUrl verbose information:\n", "<pre>", htmlspecialchars($verboseLog), "</pre>\n";
    } else {
        $vals=$result['metafields']; 
       
        
        foreach($vals as $k=>$v){ //print_r($v); 
            if($v['key']=="erp_code"){
                return $v['value'];
            } 
        
    }
    }
}
 
echo shopMetaValues('8503209132340','erp_code'); 


// 4f9e38e66da270e3b478593b24a7f9c5 => api key
// 9f3f5dd8da7072f2bd85de0b6d46ab05 => api secret 
//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   => admin api token
/*
$requestHeaders = array('api_version'=>'2024-07', 'X-Shopify-Access-Token'=>''.base64_encode('4f9e38e66da270e3b478593b24a7f9c5' . ':' . '9f3f5dd8da7072f2bd85de0b6d46ab05'));
$requestCookies = array();
$isOnline = true;

$session = Utils::loadCurrentSession(
    $requestHeaders,
    $requestCookies,
    $isOnline
);

$client = new Rest(
    $session->getShop(),
    $session->getAccessToken()
);
//$response = $client->get('shop');


$response = $client->get(path: 'products');
*/
//print_r($response);

/*
*/
/**/    

