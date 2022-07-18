<?php
include_once DOCROOT.'/include/db_connect.php';

$query = $mysqli->query("select settings from settings where idnum=1") or die($mysqli->errno .' - '. $mysqli->error);
$dados = $query->fetch_array();
$settings=unserialize($dados['settings']);

$store_url=$settings['store_url'];
$ws_api=$settings['ws_api'];


define('DEBUG', false);											// Debug mode
define('PS_SHOP_PATH', ''.$store_url.'');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', ''.$ws_api.'');	// Auth key (Get it in your Back Office)

require_once(DOCROOT."/vendor/PrestaShop-webservice-lib-master/PSWebServiceLibrary.php");
$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);


function get_produto($productID){
try
{
	$webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
	// Here we set the option array for the Webservice : we want customers resources
	$opt['resource'] = 'customers';
	// Call
	$xml = $webService->get($opt);
	// Here we get the elements from children of customers markup "customer"
	$resources = $xml->customers->children();
}
catch (PrestaShopWebserviceException $e)
{
	// Here we are dealing with errors
	$trace = $e->getTrace();
	if ($trace[0]['args'][0] == 404) echo 'Bad ID';
	else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
	else echo 'Other error';
}

return $resources;
}
 
function get_produtosTotal($fields){
global $webService;
$res=array(); 
$opt = array(
    'resource' => 'products',
    'display' => '['.$fields.']'
);

$xml = $webService->get($opt);
$resources = $xml->products->children();
foreach ($resources as $arr){
	$res[]=array("id"=>$arr->id->__toString(),"sku"=>$arr->reference->__toString());
}

return $res;
}


function get_config_value($configurationName,$atrib="id"){
    global $webService;
   
    try {
        $opt = array(
            'resource' => 'configurations',
            'display'  => '[name,value,id]',
            'filter[name]' => '['.$configurationName.']',
        );

        $xml = $webService->get($opt);
    
        $configurationId = null;
        if ($xml->configurations->configuration->count() > 0) {
            $configurationId = $xml->configurations->configuration[0]->$atrib;
        }
        return  $configurationId; 
    } catch (PrestaShopWebserviceException $e) {
            echo 'Error:' . $e->getMessage() . PHP_EOL;
    }
    

}


function add_produto($update, $n_id, $n_id_category_default, $n_id_category, $n_price, $n_active,$n_avail4order,$n_show_price, $n_id_stock_availables ,$n_id_id_product_attribute, $n_l_id, $stock, $n_name, $n_desc, $n_desc_short, $n_link_rewrite, $n_meta_title, $n_meta_description, $n_meta_keywords,$n_available_now,$n_available_later,$idtaglist,$cod,$image_name,$manufacturer,$shipping,$barcode,$weight=0) {
global $webService;

$xml = $webService->get(array('url' => PS_SHOP_PATH.'/api/products?schema=blank'));
$resources = $xml->children()->children();

unset($resources -> id);
unset($resources -> position_in_category);
unset($resources -> id_shop_default);
unset($resources -> date_add);
unset($resources -> date_upd);

unset($resources->associations->combinations);
unset($resources->associations->product_options_values);
unset($resources->associations->product_features);
unset($resources->associations->stock_availables->stock_available->id_product_attribute);


$n_l_id=get_config_value('PS_LANG_DEFAULT','value'); 
//unset($resources->associations->categories->category->id);
//unset($resources-> id_category_default);

//$resources->position_in_category = '0';
//unset($resources->position_in_category);


$Idmarca=GetManufacturerID($manufacturer);
if($Idmarca=="" && $manufacturer!=""){
	$Idmarca=AddManufacturer($manufacturer);	
}

//$resources -> position = '0';
if($update) $resources-> id = $n_id;
if($Idmarca!="") $resources-> id_manufacturer = $Idmarca;
$resources-> id_supplier = '1';
if($n_id_category_default!="")		$resources-> id_category_default = $n_id_category_default;
$resources-> new = '1'; //condition, new is also a php keyword!!
$resources-> cache_default_attribute;
$resources-> id_default_image;
$resources-> id_default_combination = '0';
$resources-> id_tax_rules_group ='1';
//$resources-> id_shop_default='1';
//$resources->id_shop = 1;
//$resources-> quantity = '50';
if($cod!="") { $resources-> reference = $cod; } 
$resources-> supplier_reference;
$resources-> location;
$resources-> width;
$resources-> height;
$resources-> depth;
$resources-> weight = $weight;
$resources-> quantity_discount;
if($n_id_category_default!="")	{	$resources-> ean13="$barcode";	} else {		$resources-> ean13;	}
$resources-> upc;
$resources-> cache_is_pack;
$resources-> cache_has_attachments;
$resources-> is_virtual;
$resources-> on_sale;
$resources-> online_only;
$resources-> ecotax;
//$resources-> minimal_quantity = 10;
if($n_price!="")	$resources-> price = $n_price;
$resources-> wholesale_price;
$resources-> unity;
$resources-> unit_price_ratio;
if($shipping && $shipping>0) $resources-> additional_shipping_cost=$shipping;
$resources-> customizable;
$resources-> text_fields;
$resources-> uploadable_files;
if($n_active!="" && strlen($n_active)>0)	$resources-> active = $n_active;
$resources-> available_for_order = $n_avail4order;
$resources-> available_date;
$resources-> condition;
if($n_show_price!="" && strlen($n_show_price)>0)	$resources-> show_price = $n_show_price;
$resources-> indexed = '1';
$resources-> state = '1';
$resources-> visibility = 'both';
$resources-> advanced_stock_management='0';
$resources-> date_add;
$resources-> date_upd;


/*
    When new product created a new stock available id was created and we can take this id to use.
*/



if($n_id_category!="")	$resources->associations->categories->addChild('category')->addChild('id', $n_id_category);

if($n_name!="" && strlen($n_name)>3){ 
$node = dom_import_simplexml($resources -> name -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_name));
$resources -> name -> language[0][0] = $n_name;
$resources -> name -> language[0][0]['id'] = $n_l_id;
$resources -> name -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
error_log("Lang ID=$n_l_id Titulo: $n_name", 3, "/home/erpsinc/public_html/backoffice/attachments/debug-erp.log");
} else {
error_log("Nome $n_name vazio?", 3, "/home/erpsinc/public_html/backoffice/attachments/debug-erp.log");
}

if($n_desc!="" && strlen($n_desc)>3){
$node = dom_import_simplexml($resources -> description -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_desc));
$resources -> description -> language[0][0] = $n_desc;
$resources -> description -> language[0][0]['id'] = $n_l_id;
$resources -> description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_desc_short!="" && strlen($n_desc_short)>3){
$node = dom_import_simplexml($resources -> description_short -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_desc_short));
$resources -> description_short -> language[0][0] = $n_desc_short;
$resources -> description_short -> language[0][0]['id'] = $n_l_id;
$resources -> description_short -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_link_rewrite!="" && strlen($n_link_rewrite)>3){
$node = dom_import_simplexml($resources -> link_rewrite -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_link_rewrite));
$resources -> link_rewrite -> language[0][0] = $n_link_rewrite;
$resources -> link_rewrite -> language[0][0]['id'] = $n_l_id;
$resources -> link_rewrite -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_meta_title!="" && strlen($n_meta_title)>3){
$node = dom_import_simplexml($resources -> meta_title -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_title));
$resources -> meta_title -> language[0][0] = $n_meta_title;
$resources -> meta_title -> language[0][0]['id'] = $n_l_id;
$resources -> meta_title -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_meta_description!="" && strlen($n_meta_description)>3){
$node = dom_import_simplexml($resources -> meta_description -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_description));
$resources -> meta_description -> language[0][0] = $n_meta_description;
$resources -> meta_description -> language[0][0]['id'] = $n_l_id;
$resources -> meta_description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_meta_keywords!="" && strlen($n_meta_keywords)>3){
$node = dom_import_simplexml($resources -> meta_keywords -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_keywords));
$resources -> meta_keywords -> language[0][0] = $n_meta_keywords;
$resources -> meta_keywords -> language[0][0]['id'] = $n_l_id;
$resources -> meta_keywords -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_available_now!="" && strlen($n_available_now)>0){
$node = dom_import_simplexml($resources -> available_now -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_available_now));
$resources -> available_now -> language[0][0] = $n_available_now;
$resources -> available_now -> language[0][0]['id'] = $n_l_id;
$resources -> available_now -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

if($n_available_later!="" && strlen($n_available_later)>0){
$node = dom_import_simplexml($resources -> available_later -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_available_later));
$resources -> available_later -> language[0][0] = $n_available_later;
$resources -> available_later -> language[0][0]['id'] = $n_l_id;
$resources -> available_later -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
}

//echo '<br/><br/>';
//aggiungo i tag
foreach ($idtaglist as $tag){
    $resources->associations->tags->addChild('tags')->addChild('id', $tag);
}



$id = "";
try {
    $opt = array('resource' => 'products');
    if(!$update){
        $opt['postXml'] = $xml -> asXML();
        $xml = $webService -> add($opt);
        $id = $xml->product->id;	
		/*
		echo"<pre>Debug:<br>";
		print_r($xml);
		echo"</pre>";
		*/
				
    }
    else{
        $opt['putXml'] = $xml -> asXML();
        //echo 'n_id: '.$n_id;
        $opt['id'] = $n_id; 
        $xml = $webService -> edit($opt);
        $id = $n_id;	
    }
	
    getIdStockAvailableAndSet($id,$stock); 
    
    if($update){

    } else {
        addNewImage($id, $image_name);
    }    

} catch (PrestaShopWebserviceException $ex) {
     // echo '<b>Error : '.$ex->getMessage().'</b>';
    	error_log('ERRO: '.$ex->getMessage());   
}

return $id;
}


function set_product_quantity($ProductId, $StokId, $AttributeId, $stock){
global $webService;

$stock=number_format($stock,0,"","");  

$xml = $webService -> get(array('url' => PS_SHOP_PATH . '/api/stock_availables?schema=blank'));
$resources = $xml -> children() -> children();
$resources->id = $StokId;
$resources->id_product  = $ProductId;
$resources->quantity = $stock;
$resources->id_shop = 1;
$resources->out_of_stock=2;
$resources->depends_on_stock = 0;
$resources->id_product_attribute=$AttributeId;
try {
	$opt = array('resource' => 'stock_availables');
	$opt['putXml'] = $xml->asXML();
	$opt['id'] = $StokId ;
	$xml = $webService->edit($opt);
}catch (PrestaShopWebserviceException $ex) {
	echo "<b>Error ao definir stock (".$stock.") ->Error : </b>".$ex->getMessage().'<br>';
}
}

function getIdStockAvailableAndSet($ProductId,$stock){
global $webService;
$opt['resource'] = 'products';
$opt['id'] = $ProductId;
$xml = $webService->get($opt);
foreach ($xml->product->associations->stock_availables->stock_available as $item) {
   //echo "ID: ".$item->id."<br>";
   //echo "Id Attribute: ".$item->id_product_attribute."<br>";
   set_product_quantity($ProductId, $item->id, $item->id_product_attribute, $stock);
}
}



function addNewImage( $product_id, $image_name) {
	if($image_name!=""){
    $url = PS_SHOP_PATH . '/api/images/products/' . $product_id;
    $key = PS_WS_AUTH_KEY;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data','Expect:'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, $key.':');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => new CurlFile($image_name)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (200 == $httpCode) {
      error_log('Product image '.$product_id.' successfully created.');
    }
    error_log('Code '.$httpCode.' '.$image_name.' Art:'.$product_id);     
    return $result;
	}
}


function GetManufacturerID($name) {
    try {
        global $webService;
        $opt = array(
            'resource' =>'manufacturers',
            'display'  => '[id]',
            'filter[name]'  => $name);      
        $xml = $webService->get($opt);  
		if($xml->children()->children()->manufacturer->id !=""){     
        return $xml->children()->children()->manufacturer->id->__toString();
		}
    }   catch (PrestaShopWebserviceException $e)    {       
            $trace = $e->getTrace();
    }   
}

function AddManufacturer($manu_name) {  
    global $webService;
    $xml = $webService->get(array('resource' => 'manufacturers?schema=synopsis'));
    $resources = $xml->children()->children();
    $resources->name = $manu_name;  
    $resources->active = 1; 
    unset($resources -> link_rewrite);

	$opt['resource'] = 'manufacturers';
	$opt['active']   = array();
	
	 $opt['postXml'] = $xml -> asXML();
     $xml = $webService -> add($opt);
     $id = $xml->children()->children()->id->__toString();
	
	return $id;
}



function edit_produto_variation($id,$idvar,$data){
global $woocommerce;
return $woocommerce->put('products/'.$id.'/variations/'.$idvar.'', $data);
}

function remove_produto($productID){
global $webService;	
try {
$opt['resource'] = 'products';
$opt['id'] = getArtigoStoreId($productID); 
$xml = $webService->delete($opt);            
    return 1; 
}
catch (PrestaShopWebserviceException $ex) {
    $trace = $ex->getTrace();                // Retrieve all info on this error
    $errorCode = $trace[0]['args'][0]; // Retrieve error code
    if ($errorCode == 401)
        echo 'Bad auth key';   
    else
        echo 'Error remove_produto: <br />'.$ex->getMessage();
}
}

function list_categorias($catDef=NULL){
global $webService;
	try
	{
			$opt['resource'] = 'categories';
			$opt['display'] = 'full';
			// We set an id if we want to retrieve infos from a customer
			if (isset($catDef))
					$opt['id'] = (int)$catDef; // cast string => int for security measures
	
			// Call
			$xml = $webService->get($opt);
	
			// Here we get the elements from children of customer markup which is children of prestashop root markup
			$resources = $xml->categories->children();
	}
	catch (PrestaShopWebserviceException $e)
	{
			// Here we are dealing with errors
			$trace = $e->getTrace();
			if ($trace[0]['args'][0] == 404) echo 'Bad ID';
			else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
			else echo 'Other error';
	}
	
	foreach($resources->category as $categ){
		$listaCateg[]=array("id"=>$categ->id->__toString(),"name"=>$categ->name->language[0]->__toString(),"parent"=>$categ->id_parent->__toString(),"position"=>$categ->position->__toString());	
	}
	
	array_multisort(array_column($listaCateg, 'parent'), SORT_ASC, array_column($listaCateg, 'position'), SORT_ASC, $listaCateg);
	return $listaCateg;
	
}

function get_categoria($idcat){
    try {
        global $webService;
        $opt = array(
            'resource' =>'categories',
            'display'  => '[id]',
            'filter[id]'  => $idcat);      
        $xml = $webService->get($opt);  
		
        return array("id"=>$xml->children()->children()->category->id->__toString());
		
    }   catch (PrestaShopWebserviceException $e)    {       
            $trace = $e->getTrace();
    }  
}


function add_categoria($data){
global $webService;	

$nomecat=ucwords(strtolower($data['nome']));
$n_id_parent=$data['id_parent'];
$n_is_root_category=$data['is_root_category'];
$n_desc=$data['desc'];
$n_link_rewrite=$data['link_rewrite'];
$n_meta_title=$data['meta_title'];
$n_meta_description=$data['meta_description'];
$n_meta_keywords=$data['meta_keywords'];
$n_l_id=1;
$n_active=1;


$xml = $webService -> get(array('url' => PS_SHOP_PATH . '/api/categories?schema=blank'));
$resources = $xml -> children() -> children();
unset($resources -> id);
unset($resources -> position);
unset($resources -> id_shop_default);
unset($resources -> date_add);
unset($resources -> date_upd);
$resources -> active = $n_active;
$resources -> id_parent = $n_id_parent;
$resources -> id_parent['xlink:href'] = PS_SHOP_PATH . '/api/categories/' . $n_id_parent;
$resources -> is_root_category = $n_is_root_category;

$node = dom_import_simplexml($resources -> name -> language[0][0]);
$no = $node -> ownerDocument;

$node -> appendChild($no -> createCDATASection(''.$nomecat.''));
$resources -> name -> language[0][0] = ''.$nomecat.'';
$resources -> name -> language[0][0]['id'] = $n_l_id;
$resources -> name -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> description -> language[0][0]);
$no = $node -> ownerDocument;

$node -> appendChild($no -> createCDATASection($n_desc));
$resources -> description -> language[0][0] = $n_desc;
$resources -> description -> language[0][0]['id'] = $n_l_id;

$resources -> description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
$node = dom_import_simplexml($resources -> link_rewrite -> language[0][0]);
$no = $node -> ownerDocument;

$node -> appendChild($no -> createCDATASection($n_link_rewrite));
$resources -> link_rewrite -> language[0][0] = $n_link_rewrite;
$resources -> link_rewrite -> language[0][0]['id'] = $n_l_id;

$resources -> link_rewrite -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;
$node = dom_import_simplexml($resources -> meta_title -> language[0][0]);

$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_title));
$resources -> meta_title -> language[0][0] = $n_meta_title;
$resources -> meta_title -> language[0][0]['id'] = $n_l_id;
$resources -> meta_title -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> meta_description -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_description));
$resources -> meta_description -> language[0][0] = $n_meta_description;
$resources -> meta_description -> language[0][0]['id'] = $n_l_id;
$resources -> meta_description -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

$node = dom_import_simplexml($resources -> meta_keywords -> language[0][0]);
$no = $node -> ownerDocument;
$node -> appendChild($no -> createCDATASection($n_meta_keywords));
$resources -> meta_keywords -> language[0][0] = $n_meta_keywords;
$resources -> meta_keywords -> language[0][0]['id'] = $n_l_id;
$resources -> meta_keywords -> language[0][0]['xlink:href'] = PS_SHOP_PATH . '/api/languages/' . $n_l_id;

try {

$opt = array('resource' => 'categories');
$opt['postXml'] = $xml -> asXML();
$xml = $webService -> add($opt);

$idCat=$xml->category->id;
$id_parent=$xml->category->id_parent;
$nomeCat=$xml->category->name->language[0]->__toString();
 
return array("id"=>$idCat,"nome"=>"".$nomeCat."","id_parent"=>$n_id_parent);

} catch (PrestaShopWebserviceException $ex) {
echo 'Erro: <br />' . $ex->getMessage();
}

}



function list_atributos($catDef=NULL){
global $webService;
	try
	{
        $opt['resource'] = 'product_options';
        $opt['display'] = 'full';
			// We set an id if we want to retrieve infos from a customer
			if (isset($catDef))
					$opt['id'] = (int)$catDef; // cast string => int for security measures
			// Call
			$xml = $webService->get($opt);
	
			// Here we get the elements from children of customer markup which is children of prestashop root markup
			$resources = $xml->product_options->children();
	}
	catch (PrestaShopWebserviceException $e)
	{
			// Here we are dealing with errors
			$trace = $e->getTrace();
			if ($trace[0]['args'][0] == 404) echo 'Bad ID';
			else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
			else echo 'Other error';
	}
	
    $listaAtributos=array();
    
    foreach($resources->product_option as $atributo){    
		$listaAtributos[$atributo->id->__toString()]=array("id"=>$atributo->id->__toString(),"name"=>$atributo->name->language[0]->__toString(),"public_name"=>$atributo->public_name->__toString(),"position"=>$atributo->position->__toString());	
	}

	//array_multisort(array_column($listaAtributos, 'position'), SORT_ASC, array_column($listaAtributos, 'position'), SORT_ASC, $listaAtributos);
	return $listaAtributos;
	
}


function atributos_existe($variacao,$termo){
 return  1;  
}

function add_variacao($produto,$data){
 return  1;  
} 

