<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}


use PrestaShop\PrestaShop\Core\Module\WidgetInterface;


class CustomTextERP extends ObjectModel
{
    /** @var int $idnum - the ID of CustomTextERP */
	public $idnum;

    /** @var String $text - HTML format of CustomTextERP values */
	public $text;

    /** @var string $debug_email - email address used for debug notifications */
    public $debug_email;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'erpsinc',
		'primary' => 'idnum',
		'multilang' => false,
		'multilang_shop' => false,
		'fields' => array(
			'idnum' =>			array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
			'url' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'tokenp' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false),
			'encomendas' =>		array('type' => self::TYPE_INT),
			'stocks' =>		    array('type' => self::TYPE_INT),
            'debug_email' =>    array('type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => false),
		)
	);	
}




//class ERPSinc extends Module  implements WidgetInterface

class ERPSinc extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'ERPSinc';
        $this->tab = 'others';
        $this->version = '1.0.1';
        $this->author = 'Pombaldir.com Serviços Internet Unip. Lda.';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('ERP Sinc');
        $this->description = $this->l('Sincronizador ERP-Prestashop. Mais informações visite: https://www.erpsinc.pt');

        $this->confirmUninstall = $this->l('Deseja remover o módulo ERP-Sinc?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('ERPSINC_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionCartSave') &&
            $this->registerHook('actionOrderStatusUpdate') &&
            $this->registerHook('actionValidateOrder') &&
            $this->registerHook('actionBeforeCartUpdateQty');
    }

    public function uninstall()
    {
        Configuration::deleteByName('ERPSINC_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitERPSincModule')) == true) {
           // $this->postProcess();
            
            $update = $this->processSaveCustomTextERP();

                if (!$update) {
                    $output = '<div class="alert alert-danger conf error">'
                        .$this->trans('Ocorreu um erro ao guardar.', array(), 'Admin.Notifications.Error')
                        .'</div>';
                }
            
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }
    
    
 public function processSaveCustomTextERP()
    {
		$saved = true;
		$info = new CustomTextERP(Tools::getValue('idnum', 1));

		$url = Tools::getValue('url');
		$tokenp = Tools::getValue('tokenp');
		$encomendas = Tools::getValue('encomendas');
		$stocks = Tools::getValue('stocks');
        $debug_email = Tools::getValue('debug_email');
        $ERPSINC_LIVE_MODE = Tools::getValue('ERPSINC_LIVE_MODE');
        
		$info->url = $url;
		$info->tokenp = $tokenp;
		$info->encomendas = $encomendas;
		$info->stocks = $stocks;
        $info->debug_email = $debug_email;
        Configuration::updateValue('ERPSINC_LIVE_MODE', $ERPSINC_LIVE_MODE);  
		
		$saved &= $info->update();
        return $saved;
    }    
    
     
       
    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitERPSincModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        

        return $helper->generateForm(array($this->getConfigForm()));
    }
     
     
    public function getFormValues()
    {
        $fields_value = array();
        $idShop = $this->context->shop->id;
        $idInfo = 1;

        Shop::setContext(Shop::CONTEXT_SHOP, $idShop);
        $info = new CustomTextERP((int)$idInfo);

		$fields_value['url'] = $info->url;
		$fields_value['tokenp'] = $info->tokenp;
		$fields_value['encomendas'] = $info->encomendas;
		$fields_value['stocks'] = $info->stocks;
        $fields_value['debug_email'] = $info->debug_email;
		$fields_value['ERPSINC_LIVE_MODE'] = Configuration::get('ERPSINC_LIVE_MODE', true);
		
        $fields_value['idnum'] = $idInfo; 

        return $fields_value;
    }
    

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'ERPSINC_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Ativar este módulo'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
               
                    
                array(
                    'type' => 'hidden',
					'value' => '1',
                    'name' => 'idnum'
                ),
                array(
                    'type' => 'text',
                    'label' => 'Url do Serviço',
                    'name' => 'url',
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => 'Token',
                    'name' => 'tokenp',
                    'class' => 'rte input fixed-width-xl',
                    'autoload_rte' => true,
                ), 
                    
                array(
                    'type' => 'switch',
                    'label' => 'Criar Encomendas',
                    'name' => 'encomendas',
                    'desc' => 'Lançar Encomendas no ERP após pagamento',
                    'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' =>$this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Email para debug'),
                    'name' => 'debug_email',
                    'desc' => $this->l('Email usado para receber logs de encomendas em modo debug'),
                    'class' => 'rte input fixed-width-xl',
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->getTranslator()->trans('Atualizar Stock', array(), 'Modules.Imageslider.Admin'),
                    'name' => 'stocks',
                    'desc' => $this->getTranslator()->trans('Obter Stock do ERP e atualizar no website', array(), 'Modules.Imageslider.Admin'),
                    'values' => array(
                            array(
                                'id' => 'stock_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'stock_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

  
    
    
    

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'erpsinc` WHERE `idnum`=1 ';	
        return array(
            'cms_infos' => Db::getInstance()->getRow($sql),
        );		
    }    
    
    
    

    
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookActionCartSave($params)
    {
        //$cart = $params["cart"];
        //$arrayAtrib=$cart->getIdProductAttributeByIdAttributes(69, array('10','37'));
        //print_r(arrayAtrib); 
          
    }


    public function hookActionOrderStatusUpdate($params) {		// ENCOMENDAS BACKOFFICE
        $vars=$this->getWidgetVariables("", array());
        if($vars['cms_infos']['encomendas']==1){
            
            if(!empty($params['newOrderStatus'])) {
                if ($params['newOrderStatus']->paid == 1){
                    $orderDetail=OrderDetail::getList((int)$params['id_order']);	
 
                    $orderCab = new Order($params['id_order']); 
                    $customer = new Customer($orderCab->id_customer);
                    $address_invoice = new Address($orderCab->id_address_invoice);
                    $address_delivery = new Address($orderCab->id_address_delivery);
                    
                    
                    
                    //$orderDetail->note = Tools::getValue('note');
                    //$orderCab->note = "zero";
                    //$orderCab->update();  
                    
                    
                    
                    $orderCabF=(object)array_merge((array)$orderCab,array("customer"=>$customer),array("billing"=>$address_invoice),array("shipping"=>$address_delivery),array("details"=>$orderDetail)); 		

                    ERPSINC_OrderProcess(
                        $params['id_order'],
                        json_decode(json_encode($orderCabF), true),
                        $vars['cms_infos']['url'],
                        $vars['cms_infos']['tokenp'],
                        1,
                        $vars['cms_infos']['debug_email']
                    );
                }
            }
        }
    }

    public function hookActionValidateOrder($params) {			// ENCOMENDAS PÚBLICO
        $vars=$this->getWidgetVariables("", array());
        if($vars['cms_infos']['encomendas']==1){
            if(!empty($params['orderStatus'])) {
                if ($params['orderStatus']->paid == 1){
                    $orderDetail=OrderDetail::getList((int)$params['id_order']);	

                    $orderCab = new Order($params['id_order']); 
                    $customer = new Customer($orderCab->id_customer);
                    $address_invoice = new Address($orderCab->id_address_invoice);
                    $address_delivery = new Address($orderCab->id_address_delivery);

                    $orderCabF=(object)array_merge((array)$orderCab,array("customer"=>$customer),array("billing"=>$address_invoice),array("shipping"=>$address_delivery),array("details"=>$orderDetail)); 		

                    ERPSINC_OrderProcess(
                        $params['id_order'],
                        json_decode(json_encode($orderCabF), true),
                        $vars['cms_infos']['url'],
                        $vars['cms_infos']['tokenp'],
                        1,
                        $vars['cms_infos']['debug_email']
                    );
                }
            }
        }
    }    
    

    public function hookActionBeforeCartUpdateQty($params) 
    {
        $vars=$this->getWidgetVariables("", array());
        if($vars['cms_infos']['stocks']==1){
            $product = $params['product'];
            $codigo=$product->reference;
            $id_product=$product->id;
            
            $id_product_attribute=@$_GET['id_product_attribute'];  
            if($id_product_attribute>0){
                $attributes = $product->getAttributeCombinationsById($id_product_attribute, $this->context->language->id);
                $codigo= $attributes[0]['reference'];        
            }
            /*echo "<pre>";
            print_r($codigo);  
            echo "</pre>";*/
            //echo $vars['cms_infos']['url'].'/artigos.php?act_g=sku&auth_userid='.$vars['cms_infos']['tokenp'].'&num='.$codigo;
            // IMPORTANT: the below line is a security risk, read https://paragonie.com/blog/2017/10/certainty-automated-cacert-pem-management-for-php-software
            // in most cases, you should set it to true
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL,  $vars['cms_infos']['url'].'/artigos.php?act_g=sku&auth_userid='.$vars['cms_infos']['tokenp'].'&num='.$codigo.'');
            $result = curl_exec($ch); 
            curl_close($ch);
            $obj = json_decode($result,true);    
            if(is_array($obj) && sizeof($obj)>0 && $obj['found']==1){ 
                StockAvailable::setQuantity($id_product, ''.$id_product_attribute.'', $obj['stock']);
            }
        }       
    }     
}




###########################################################  FUNÇÕES DO PLUGIN  ######################################################
function ERPSINC_OrderProcess($order_id,$orderDetail,$urlws,$chave,$debug,$debugEmail = '') {	

$params = array("act_p" => "order_create","auth_userid" => $chave,"dados" => serialize($orderDetail),"store" => "prestashop","txtlinha" => "","debug" => "".$debug."");
$fields_string="";
 
	foreach($params as $key=>$value){	
	$v=str_replace("&","e",$value);
		$fields_string .= $key.'='.$v.'&'; 
	}
	rtrim($fields_string, '&');
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, ''.$urlws.'/encomendas.php');
	curl_setopt($ch,CURLOPT_POST, count($params));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
	$content = json_decode(trim(curl_exec($ch)), true);
	curl_close($ch);	
	
	$callb_msg=$content['msg'];
	$callb_code=$content['response'];
    
    if($callb_code==1 && $content['ordercreated']==1){ // encomenda criada
        
        $orderid=$content['orderid'];
        $strnumero=$content['strnumero'];
        $customer=$content['customer'];
        
    }
    
     /* 
    $orderMessage = new Message();
    $orderMessage->id_order = $order_id;
    $orderMessage->message = 'Hi I\'m a message.';
    $orderMessage->private = true;
    $orderMessage->save();
    */
    
    
    

	if($debug==1){
        $recipient = !empty($debugEmail) ? $debugEmail : 'webmaster@pombaldir.com';
		Mail::Send((int)(Configuration::get('PS_LANG_DEFAULT')),
						'contact', // email template file to be use
						'Debug: Enc#'.$order_id.' Log', // email subject
						array(
							'{email}' => Configuration::get('PS_SHOP_EMAIL'),
							'{message}' => '<br><hr> '.print_r(json_encode($params,true),true).'<hr>'.print_r(json_encode($callb_msg,true),true).'' // email content
						),$recipient,NULL,NULL,NULL);	
						
	}
 }
