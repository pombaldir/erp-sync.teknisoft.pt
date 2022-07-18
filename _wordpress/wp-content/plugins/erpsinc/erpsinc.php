<?php
/**
 * Plugin Name:       ERP Sinc
 * Plugin URI:        https://erpsinc.pt/plugin-woocommerce/
 * Description:       Sincronizador ERP-Woocommerce. Mais informações visite: https://www.erpsinc.pt
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pombaldir.com Serviços Internet Unip. Lda.
 * Author URI:        https://pombaldir.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 
 */


function activate_erpsinc() {  //update_option( 'erpsinc_status', '1' );  
    if(get_option( 'erpsinc_status')==0){
        update_option( 'erpsinc_status', '1' );   
    } else {
        add_option( 'erpsinc_status', '1' );
    }

  /* activation code here */
}
register_activation_hook( __FILE__, 'activate_erpsinc' );


function deactivate_erpsinc() {
     if ( is_admin() && get_option( 'erpsinc_status' ) == '1' ) {
        update_option( 'erpsinc_status', '0' );
        //delete_option('erpsinc_options'); 
     }
}
register_deactivation_hook( __FILE__, 'deactivate_erpsinc' );





/**
 * custom option and settings
 */
function erpsinc_settings_init() {
 // register a new setting for "wporg" page
 register_setting( 'erpsinc', 'erpsinc_options' );
 
 // register a new section in the "wporg" page
 add_settings_section(
 'erpsinc_section_developers',
 __( 'Sincronizador ERP-Woocommerce.', 'erpsinc' ),
 'erpsinc_section_developers_cb',
 'erpsinc'
 );
 
 // register a new field in the "erpsinc_section_developers" section, inside the "wporg" page
 add_settings_field(
 'erpsinc_field_erp', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'ERP', 'erpsinc' ),
 'erpsinc_field_erp_cb',
 'erpsinc',
 'erpsinc_section_developers',
 [
 'label_for' => 'erpsinc_field_erp',
 'class' => 'erpsinc_row',
 'erpsinc_custom_data' => 'custom',
 ]
 );
    
    
 add_settings_field(
 'erpsinc_field_url', // as of WP 4.6 this value is used only internally
 // use $args' label_for to populate the id inside the callback
 __( 'Url Webservice', 'erpsinc' ),
 'erpsinc_field_text_cb',
 'erpsinc',
 'erpsinc_section_developers',
 [
 'label_for' => 'erpsinc_field_url',
 'class' => 'erpsinc_row',
 'erpsinc_custom_data' => 'custom',
 ]
 );   
    
    
    
 add_settings_field(
 'erpsinc_field_token',
 __( 'Token', 'erpsinc' ),
 'erpsinc_field_text_cb',
 'erpsinc',
 'erpsinc_section_developers',
 [
 'label_for' => 'erpsinc_field_token',
 'class' => 'erpsinc_row',
 'erpsinc_custom_data' => 'custom',
 ]
 ); 
    
    
 add_settings_field(
 'erpsinc_field_encomendas',
 __( 'Criar encomendas', 'erpsinc' ),
 'erpsinc_field_yesno_cb',
 'erpsinc',
 'erpsinc_section_developers',
 [
 'label_for' => 'erpsinc_field_encomendas',
 'class' => 'erpsinc_row',
 'erpsinc_custom_data' => 'custom',
 ]
 );   
    
    
 add_settings_field(
 'erpsinc_field_stock',
 __( 'Atualizar Stock', 'erpsinc' ),
 'erpsinc_field_yesno_cb',
 'erpsinc',
 'erpsinc_section_developers',
 [
 'label_for' => 'erpsinc_field_stock',
 'class' => 'erpsinc_row',
 'erpsinc_custom_data' => 'custom',
 ]
 );     
    
       
    
}
 
/**
 * register our erpsinc_settings_init to the admin_init action hook
 */
add_action( 'admin_init', 'erpsinc_settings_init' );
 
/**
 * custom option and settings:
 * callback functions
 */
 
// developers section cb
 
// section callbacks can accept an $args parameter, which is an array.
// $args have the following keys defined: title, id, callback.
// the values are defined at the add_settings_section() function.
function erpsinc_section_developers_cb( $args ) {
 ?>
 <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Mais informações visite: https://www.erpsinc.pt', 'erpsinc' ); ?></p>
 <?php
}
 
// pill field cb
 
// field callbacks can accept an $args parameter, which is an array.
// $args is defined at the add_settings_field() function.
// wordpress has magic interaction with the following keys: label_for, class.
// the "label_for" key value is used for the "for" attribute of the <label>.
// the "class" key value is used for the "class" attribute of the <tr> containing the field.
// you can add custom key value pairs to be used inside your callbacks.
function erpsinc_field_erp_cb( $args ) {
$options = get_option( 'erpsinc_options' );
echo '<select id="'.esc_attr( $args['label_for'] ).'" data-custom="'.esc_attr( $args['erpsinc_custom_data'] ).'" name="erpsinc_options['.esc_attr( $args['label_for'] ).']">';
echo '<option value="eticadata"'; echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'eticadata', false ) ) : ( '' ); echo'>Eticadata</option>';  
echo '</select>';   
}

function erpsinc_field_text_cb( $args ) {
$options = get_option( 'erpsinc_options' );
$valor=esc_attr( $options[ $args['label_for'] ]);    
echo'<input id="'.esc_attr( $args['label_for'] ).'"  data-custom="'.esc_attr( $args['erpsinc_custom_data'] ).'" name="erpsinc_options['.esc_attr( $args['label_for'] ).']" value="'.$valor.'" class="regular-text form-control">';
}

function erpsinc_field_yesno_cb( $args ) {
$options = get_option( 'erpsinc_options' );
echo '<select id="'.esc_attr( $args['label_for'] ).'" data-custom="'.esc_attr( $args['erpsinc_custom_data'] ).'" name="erpsinc_options['.esc_attr( $args['label_for'] ).']">';
echo '<option value="0"'; echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '0', false ) ) : ( '' ); echo'>Não</option>';  
echo '<option value="1"'; echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '1', false ) ) : ( '' ); echo'>Sim</option>';      
echo '</select>';   
}


 
/**
 * top level menu
 */
function erpsinc_options_page() {
 // add top level menu page
 add_menu_page(
 'ERP Sinc',
 'ERP Sinc',
 'manage_options',
 'erpsinc',
 'erpsinc_options_page_html'
 );
}
 
/**
 * register our erpsinc_options_page to the admin_menu action hook
 */
add_action( 'admin_menu', 'erpsinc_options_page' );
 
/**
 * top level menu:
 * callback functions
 */
function erpsinc_options_page_html() {
 // check user capabilities
 if ( ! current_user_can( 'manage_options' ) ) {
 return;
 }
 
 // add error/update messages
 
 // check if the user have submitted the settings
 // wordpress will add the "settings-updated" $_GET parameter to the url
 if ( isset( $_GET['settings-updated'] ) ) {
 // add settings saved message with the class of "updated"
 add_settings_error( 'erpsinc_messages', 'erpsinc_message', __( 'Definições Guardadas', 'erpsinc' ), 'updated' );
 }
 
 // show error/update messages
 settings_errors( 'erpsinc_messages' );
 ?>
 <div class="wrap">
 <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
 <form action="options.php" method="post">
 <?php
 // output security fields for the registered setting "wporg"
 settings_fields( 'erpsinc' );
 // output setting sections and their fields
 // (sections are registered for "wporg", each field is registered to a specific section)
 do_settings_sections( 'erpsinc' );
 // output save settings button
 submit_button( 'Salvar Definições' );
 ?>
 </form>
 </div>
 <?php
}










# # # # # # # # # # # # # # # # # # # # # # # # # # # # #  POMBALDIR.COM # # # # # # # # # # # # # # # # # # # # # # # # # # # # 
$defERPSinc=get_option( 'erpsinc_options' );
$tokenAPI=$defERPSinc['erpsinc_field_token']; 
$url_websrv=$defERPSinc['erpsinc_field_url']; 
$erp_encomend=$defERPSinc['erpsinc_field_encomendas']; 
$erp_updstock=$defERPSinc['erpsinc_field_stock']; 

$debug="webmaster@pombaldir.com";
$erp_updtTime="1";
  

// atualiza stocks woocommerce_before_single_product callback 
function erpsinc_before_single_product( $wc_print_notices, $int=0 ) { 
   global $product,$url_websrv,$erp_updstock,$erp_updtTime,$tokenAPI;
    $id = $product->get_id();
    $sku=$product->sku;
    $stock_quantity=$product->stock_quantity;
    $manage_stock=$product->manage_stock;   
    
    $date = new DateTime();
    $timestmp=$date->getTimestamp();
    $lastUpdtERP=get_post_meta($id, 'erpsincUpdt', true ); 
    $endTime=strtotime("+$erp_updtTime minutes", $timestmp); 
        
   if($manage_stock=="yes" && $erp_updstock==1 && (($lastUpdtERP!="" && ($timestmp-$lastUpdtERP)>($erp_updtTime*60)) || $lastUpdtERP=="")){  	
       
    if ( $product->is_type( 'variable' ) ) { ## PRODUTO VARIÁVEL
    $variations = $product->get_available_variations();  //echo "<pre>"; print_r($variations); echo "</pre>";  

    foreach ($variations as $key => $value){
       $sku=$value['sku'];
       $stock_quantity=$value['max_qty']; 
       $variation_id=$value['variation_id']; 
        
        $unparsed_json = file_get_contents($url_websrv."/artigos.php?auth_userid=".$tokenAPI."&act_g=sku&num=".$sku.""); 
        $json_object = json_decode($unparsed_json,true);
        $stock=$json_object['stock'];
        $found=$json_object['found'];
         
        if($found==1 && $stock!=$stock_quantity){   //echo "Var $variation_id set stock=$stock<br>";
            wc_update_product_stock( $variation_id, $stock, 'set' );
        }  
    }          
    } else { ## PRODUTO SIMPLES
        $unparsed_json = file_get_contents($url_websrv."/artigos.php?auth_userid=".$tokenAPI."&act_g=sku&num=".$sku.""); 
        $json_object = json_decode($unparsed_json,true);
        $stock=$json_object['stock'];
        $found=$json_object['found'];
         
        if($found==1 && $stock!=$stock_quantity){   
            wc_update_product_stock( $id, $stock);	
            //echo "Current Time: $timestmp Next: $endTime";    
        } 
   }        ## /PRODUTO SIMPLES
       update_post_meta( $id, 'erpsincUpdt', $endTime );
   } else {
       //echo "LAST Query: ".$lastUpdtERP;   
}
    
};     
add_action( 'woocommerce_before_single_product', 'erpsinc_before_single_product', 10, 2 ); 



add_action( 'woocommerce_order_status_changed', 'pombaldir_update_order_status', 99, 3 );
 
function pombaldir_update_order_status(  $order_id, $old_status, $new_status) {
 global $debug,$tokenAPI,$url_websrv,$erp_encomend;
 
$order = new WC_Order( $order_id );
$cabecalho=$order->get_data();

// Iterating through each WC_Order_Item_Product objects
foreach ($order->get_items() as $item_key => $item_values):
    ## Using WC_Order_Item methods ##
    // Item ID is directly accessible from the $item_key in the foreach loop or
    $item_id = $item_values->get_id();
    ## Using WC_Order_Item_Product methods ##
    $item_name = $item_values->get_name(); // Name of the product
    $item_type = $item_values->get_type(); // Type of the order item ("line_item")

    $product_id = $item_values->get_product_id(); // the Product id
    $product = $item_values->get_product(); // the WC_Product object

    ## Access Order Items data properties (in an array of values) ##
    $item_data = $item_values->get_data();

    $product_name = $item_data['name'];
    $product_id = $item_data['product_id'];
    $variation_id = $item_data['variation_id'];
    $quantity = $item_data['quantity'];
    $tax_class = $item_data['tax_class'];
    $line_subtotal = $item_data['subtotal'];
    $line_subtotal_tax = $item_data['subtotal_tax'];
    $line_total = $item_data['total'];
    $line_total_tax = $item_data['total_tax'];
    // Get data from The WC_product object using methods (examples)
    $product_type   = $product->get_type();
    $product_sku    = $product->get_sku();
    $product_price  = $product->get_price();
    $stock_quantity = $product->get_stock_quantity();
	
	$orderdata[]=array_merge($item_data,array('product_sku'=>$product_sku),array('product_type'=>$product_type),array('product_price'=>$product_price),array('stock_quantity'=>$stock_quantity));

endforeach;

     
if( $new_status == "processing" ) {
 
$fields_string="";   
$arrayEnc=array_merge($cabecalho,array('linhas'=>$orderdata));
 
$params = array('act_p' => 'order_create','auth_userid' => ''.$tokenAPI.'','dados' => serialize($arrayEnc), 'store' => 'woocommerce','debug' => ''.$debug.'');
     
    
if($erp_encomend==1){    
foreach($params as $key=>$value){	
$v=str_replace("&","e",$value);
	$fields_string .= $key.'='.$v.'&'; 
}
rtrim($fields_string, '&');
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, ''.$url_websrv.'/encomendas.php');
curl_setopt($ch,CURLOPT_POST, count($params));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
$content = json_decode(trim(curl_exec($ch)), true);
curl_close($ch);
  
 // Add the note 
if($content['response']==1){
if($content['msg']!=""){
$order->add_order_note($content['msg']);
}
$order->update_meta_data( '_wc_acof_2', ''.strtotime(date('Y-m-d')).'' );
$order->update_meta_data( '_wc_acof_3', ''.$content['orderid'].'' );
$order->update_meta_data( '_wc_acof_4', ''.$content['strnumero'].'' );
$order->save(); 
}

}    
    
  
 if($debug!=""){     
    $headers  = 'From: ERP Sinc <webmaster@'.$_SERVER['HTTP_HOST'].'>' . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $payment = "Enc #$order_id ".$order->get_payment_method_title()." <hr>".$arrayEnc['id']."<hr>Detalhes: ".json_encode($arrayEnc)."";
	$payment .= "<hr><b>Resposta Webservice:</b><br> ".$content['msg']."";
    wp_mail($debug, 'Encomenda', $payment, $headers );
 }
 
 //wp_mail("dep.financeiro1@profor.pt", 'Nova Encomenda Web', "Encomenda #$order_id ".$order->get_payment_method_title()."", $headers );
 
 
 }
}












