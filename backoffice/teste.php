<?php $ptitle="Teste";	include("header.php"); 
//include_once DOCROOT.'/include/functions.prestashop.php';	
//include_once DOCROOT.'/include/functions.woocommerce.php';	

//$data=array("price"=>999,"new"=>1,"reference"=>"0909","active"=>1,"name"=>"teste","description_short"=>"lingua","id_category_default"=>1);
/*
try {
    // creating webservice access
$webService = new PrestaShopWebservice('https://naturata.pt', 'KUEENHCLY6J8C8U8BMU9JQQ9JEXW2Q3V	', true);
 
    // call to retrieve all customers
    $xml = $webService->get(['resource' => 'customers']);
} catch (PrestaShopWebserviceException $ex) {
    // Shows a message related to the error
    echo 'Other error: <br />' . $ex->getMessage();
}
*/
/*

<id/>
<id_manufacturer/>
<id_supplier/>
<id_category_default/>
<new/>
<cache_default_attribute/>
<id_default_image/>
<id_default_combination/>
<id_tax_rules_group/>
<position_in_category/>
<type/>
<id_shop_default/>
<reference/>
<supplier_reference/>
<location/>
<width/>
<height/>
<depth/>
<weight/>
<quantity_discount/>
<ean13/>
<isbn/>
<upc/>
<cache_is_pack/>
<cache_has_attachments/>
<is_virtual/>
<state/>
<additional_delivery_times/>
<delivery_in_stock>
<language id="1"/>
</delivery_in_stock>
<delivery_out_stock>
<language id="1"/>
</delivery_out_stock>
<on_sale/>
<online_only/>
<ecotax/>
<minimal_quantity/>
<low_stock_threshold/>
<low_stock_alert/>
<price/>
<wholesale_price/>
<unity/>
<unit_price_ratio/>
<additional_shipping_cost/>
<customizable/>
<text_fields/>
<uploadable_files/>
<active/>
<redirect_type/>
<id_type_redirected/>
<available_for_order/>
<available_date/>
<show_condition/>
<condition/>
<show_price/>
<indexed/>
<visibility/>
<advanced_stock_management/>
<date_add/>
<date_upd/>
<pack_stock_type/>
<meta_description>
<language id="1"/>
</meta_description>
<meta_keywords>
<language id="1"/>
</meta_keywords>
<meta_title>
<language id="1"/>
</meta_title>
<link_rewrite>
<language id="1"/>
</link_rewrite>
<name>
<language id="1"/>
</name>
<description>
<language id="1"/>
</description>
<description_short>
<language id="1"/>
</description_short>
<available_now>
<language id="1"/>
</available_now>
<available_later>
<language id="1"/>
</available_later>
<associations>
<categories>
<category>
<id/>
</category>
</categories>
<images>
<image>
<id/>
</image>
</images>
<combinations>
<combination>
<id/>
</combination>
</combinations>
<product_option_values>
<product_option_value>
<id/>
</product_option_value>
</product_option_values>
<product_features>
<product_feature>
<id/>
<id_feature_value/>
</product_feature>
</product_features>
<tags>
<tag>
<id/>
</tag>
</tags>
<stock_availables>
<stock_available>
<id/>
<id_product_attribute/>
</stock_available>
</stock_availables>
<accessories>
<product>
<id/>
</product>
</accessories>
<product_bundle>
<product>
<id/>
<quantity/>
</product>
</product_bundle>
</associations>
</product>
*/

//add_produto($data);

//add_produto(0, "", 3, 3, "1999", 1, 1, 1, 12,"", 1, "28","Produto teste", "descricao longa", "descricao curta", "", "", "", "","","",array(),"369-HA50151V41UA99","","","66");

//print_r($_SERVER);


//echo"OK";

//function add_produto($update, $n_id, $n_id_category_default, $n_id_category, $n_price, $n_active,$n_avail4order,$n_show_price, $n_id_stock_availables ,$n_id_id_product_attribute, $n_l_id, $stock, $n_name, $n_desc, $n_desc_short, $n_link_rewrite, $n_meta_title, $n_meta_description, $n_meta_keywords,$n_available_now,$n_available_later,$idtaglist,$cod,$image_name,$manufacturer,$shipping,$barcode)


//$prod=add_produto(0, "", 0, 0, $preco, 1, 1, 1, 12,"", 1, "$stock","teste", "teste 2", "teste 4", "", "", "", "","","",array(),"xptoteste","$localFileImg","Marca",0,"");

//print_r(get_produto(69));


/*
$url = PS_SHOP_PATH . '/api/images/products/940';
    $key = PS_WS_AUTH_KEY;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:multipart/form-data','Expect:'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_USERPWD, $key.':');
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => new CurlFile('attachments/tmp/art-6-176959.jpg')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (200 == $httpCode) {
      echo 'Product image '.$product_id.' successfully created.';
    }   
*/  
	
echo "<pre>";

//echo get_produto(1266); 

//edit_produto_variation($id,$idvar,$data);

//echo get_config_value('PS_LANG_DEFAULT','value');  

//echo getcategoriaStoreId('22'); // 22 => 45


print_r( list_categorias());

/*
try {
  // creating webservice access
  // call to retrieve all customers
  $xml = $webService->get(['resource' => 'languages']);

  $resources = $xml->languages->children();
  foreach ($resources as $resource) {
      $attributes = $resource->attributes();
      $resourceId = $attributes['id'];

      echo "$resourceId<br>";
      // From there you could, for example, use th resource ID to call the webservice to get its details
  }

} catch (PrestaShopWebserviceException $ex) {
  // Shows a message related to the error
  echo 'Other error: <br />' . $ex->getMessage();
}
*/

echo "</pre>";

?>


 

<?php include("footer.php"); ?>