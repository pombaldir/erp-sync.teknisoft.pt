<?php
/*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
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
			'text' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
			'tokenp' =>			array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false),
			'encomendas' =>		array('type' => self::TYPE_INT),
			'stocks' =>		array('type' => self::TYPE_INT),
		)
	);	
}





class ERPSinc extends Module  implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'erpsinc';
        $this->author = 'Pombaldir.com';
        $this->version = '1.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('ERP SINC', array(), 'Modules.ERPSinc.Admin');
        $this->description = $this->trans('Sincronizador ERP-Prestashop. Mais informações visite: https://www.erpsinc.pt', array(), 'Modules.ERPSinc.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.4.0', 'max' => _PS_VERSION_);
 
        //$this->templateFile = $this->fetch(_PS_MODULE_DIR_ . 'erpsinc/views/templates/hook/erpsinc.tpl');
		$this->templateFile = 'views/templates/hook/erpsinc.tpl';
		
		
    }

    public function install()
    {
        return $this->runInstallSteps();
    }

    public function runInstallSteps()
    {
        return parent::install()
            && $this->installDB()
            && $this->registerHook('actionCartUpdateQuantityBefore') 	// Stocks
			&& $this->registerHook('displayShoppingCart')
			&& $this->registerHook('actionOrderStatusUpdate')			// encomendas
			&& $this->registerHook('actionValidateOrder')				// encomendas
            && $this->registerHook('actionCartSave');
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDB();
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'erpsinc` (
                `idnum` INT(10) UNSIGNED NOT NULL,
				`text` text NOT NULL,
				`tokenp` varchar(255),
				`encomendas` int(1),
				`stocks` int(1),
                PRIMARY KEY (`idnum`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;'
        );
        $return &= Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'erpsinc` (`idnum`,`text`,`tokenp`,`encomendas`,`stocks`) VALUES (1,\'https://erpsinc.pt/webservices\',\'\',0,0);');
        return $return;
    }
 
    public function uninstallDB($drop_table = true)
    {
        $ret = true;
        if ($drop_table) {
            $ret &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'erpsinc`');
        }
        return $ret;
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('saveerpsinc')) {
           
                $update = $this->processSaveCustomTextERP();

                if (!$update) {
                    $output = '<div class="alert alert-danger conf error">'
                        .$this->trans('Ocorreu um erro ao guardar.', array(), 'Admin.Notifications.Error')
                        .'</div>';
                }

                $this->_clearCache($this->templateFile);
        }

        return $output.$this->renderForm();
    }

    public function processSaveCustomTextERP()
    {
		$saved = true;
		$info = new CustomTextERP(Tools::getValue('idnum', 1));

		$text = Tools::getValue('text');
		$tokenp = Tools::getValue('tokenp');
		$encomendas = Tools::getValue('encomendas');
		$stocks = Tools::getValue('stocks');
        
		$info->text = $text;
		$info->tokenp = $tokenp;
		$info->encomendas = $encomendas;
		$info->stocks = $stocks;
		
		$saved &= $info->update();
        return $saved;
    }


    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->trans('ERP SINC - Configurar Módulo', array(), 'Modules.ERPSinc.Admin'),
            ),
            'input' => array(
                'idnum' => array(
                    'type' => 'hidden',
					'value' => '1',
                    'name' => 'idnum'
                ),
                'content' => array(
                    'type' => 'text',
                    'label' => $this->trans('Url do Serviço', array(), 'Modules.ERPSinc.Admin'),
                    'name' => 'text',
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
                'tokenp' => array(
                    'type' => 'text',
                    'label' => $this->trans('Token', array(), 'Modules.ERPSinc.Admin'),
                    'name' => 'tokenp',
                    'class' => 'rte input fixed-width-xl',
                    'autoload_rte' => true,
                ),
                array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Criar Encomendas', array(), 'Modules.Imageslider.Admin'),
                        'name' => 'encomendas',
                        'desc' => $this->getTranslator()->trans('Lançar Encomendas no ERP após pagamento', array(), 'Modules.Imageslider.Admin'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
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
                                'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'stock_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('Disabled', array(), 'Admin.Global')
                            )
                        ),
                    )/*,
                'doc_enc' => array(
                    'type' => 'text',
                    'label' => $this->trans('Doc. Encomendas', array(), 'Modules.ERPSinc.Admin'),
                    'name' => 'doc_enc',
                    'class' => 'rte input fixed-width-md',
                    'autoload_rte' => true,
                ),*/
				
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->trans('Back to list', array(), 'Admin.Actions'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        if (Shop::isFeatureActive() && Tools::getValue('idnum') == false) {
            $fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso_theme'
            );
        }


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'erpsinc';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'saveerpsinc';

        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm(array(array('form' => $fields_form)));
    }

    public function getFormValues()
    {
        $fields_value = array();
        $idShop = $this->context->shop->id;
        $idInfo = 1;

        Shop::setContext(Shop::CONTEXT_SHOP, $idShop);
        $info = new CustomTextERP((int)$idInfo);

        $fields_value['text'] = $info->text;
		$fields_value['tokenp'] = $info->tokenp;
		$fields_value['encomendas'] = $info->encomendas;
		$fields_value['stocks'] = $info->stocks;
		
		
        $fields_value['idnum'] = $idInfo;

        return $fields_value;
    }


    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->active) {
            return;
        }
		
		/*
		if (!$this->isCached($this->templateFile, $this->getCacheId('erpsinc'))) {
			$vars=$this->getWidgetVariables($hookName, $configuration);
			foreach($vars['cms_infos'] as $k=>$v){
			$this->smarty->assign($k, $v);	
			}		
        }		
		return $this->fetch(_PS_MODULE_DIR_ . 'erpsinc/views/templates/hook/erpsinc.tpl');
		*/
		
		/*
		$vars=$this->getWidgetVariables($hookName, $configuration);
		$products = Context::getContext()->cart->getProducts();
		$artigosArr=array();
		foreach($products  as $prod){
			$artigosArr[]=array("sku"=>$prod['reference'],"idws"=>$prod['id_product']);
		}
		 		 
		if($vars['cms_infos']['stocks']==1 && ($hookName=="displayShoppingCart" || $hookName=="actionCartSave")) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $vars['cms_infos']['text'].'/artigos.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('auth_userid' => $vars['cms_infos']['tokenp'],'act_p' => 'getStocks','dados' => $artigosArr)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch), true);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		if (200 == $httpCode) {
			if(is_array($result) && sizeof($result)>0){
			  foreach($result as $k=>$v){
				StockAvailable::setQuantity($k, '', $v);
			  }
			}
		}
		}
		*/
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'erpsinc` WHERE `idnum`=1 ';	
        return array(
            'cms_infos' => Db::getInstance()->getRow($sql),
        );		
    }
	
  
	public function displayShoppingCart($params){
		return $this->fetch(_PS_MODULE_DIR_ . 'erpsinc/views/templates/hook/erpsinc.tpl');
		
	}
	


public function hookActionOrderStatusUpdate($params) {		// ENCOMENDAS BACKOFFICE
        if(!empty($params['newOrderStatus'])) {
			if ($params['newOrderStatus']->paid == 1){
				$orderDetail=OrderDetail::getList((int)$params['id_order']);	
				
				$orderCab = new Order($params['id_order']); 
				$customer = new Customer($orderCab->id_customer);
				$address_invoice = new Address($orderCab->id_address_invoice);
  				$address_delivery = new Address($orderCab->id_address_delivery);
				
				$orderCabF=(object)array_merge((array)$orderCab,array("customer"=>$customer),array("billing"=>$address_invoice),array("shipping"=>$address_delivery),array("details"=>$orderDetail)); 		
				
				$vars=$this->getWidgetVariables(NULL,array());
				if($vars['cms_infos']['encomendas']==1){ 
				ERPSINC_OrderProcess($params['id_order'],$orderCabF,$vars['cms_infos']['text'],$vars['cms_infos']['tokenp'],1);
				}
    		}
		}
}

public function hookActionValidateOrder($params) {			// ENCOMENDAS PÚBLICO
        if(!empty($params['orderStatus'])) {
			if ($params['orderStatus']->paid == 1){
				$orderDetail=OrderDetail::getList((int)$params['id_order']);	
				
				$orderCab = new Order($params['id_order']); 
				$customer = new Customer($orderCab->id_customer);
				$address_invoice = new Address($orderCab->id_address_invoice);
  				$address_delivery = new Address($orderCab->id_address_delivery);
				
				$orderCabF=(object)array_merge((array)$orderCab,array("customer"=>$customer),array("billing"=>$address_invoice),array("shipping"=>$address_delivery),array("details"=>$orderDetail)); 		
						
				$vars=$this->getWidgetVariables(NULL,array());
				if($vars['cms_infos']['encomendas']==1){ 
				ERPSINC_OrderProcess($params['id_order'],$orderCabF,$vars['cms_infos']['text'],$vars['cms_infos']['tokenp'],1);
				}
    		}
		}
}


public function hookactionCartUpdateQuantityBefore($params) {	  // STOCKS  actionBeforeCartUpdateQty
	
	$vars=$this->getWidgetVariables("", array());
	if($vars['cms_infos']['stocks']==1){
		$products = Context::getContext()->cart->getProducts();
		$artigosArr=array();
		foreach($products  as $prod){
			$artigosArr[]=array("sku"=>$prod['reference'],"idws"=>$prod['id_product']);
		}
		 		  
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $vars['cms_infos']['text'].'/artigos.php');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('auth_userid' => $vars['cms_infos']['tokenp'],'act_p' => 'getStocks','dados' => $artigosArr)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = json_decode(curl_exec($ch), true);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch); 
		if (200 == $httpCode) {
			if(is_array($result) && sizeof($result)>0){
			  foreach($result as $k=>$v){
				StockAvailable::setQuantity($k, '', $v);
			  }
			}
		}
		}	
	}
}


###########################################################  FUNÇÕES DO PLUGIN  ######################################################
function ERPSINC_OrderProcess($order_id,$orderDetail,$urlws,$chave,$debug) {	

$params = array("act_p" => "order_create","auth_userid" => $chave,"dados" => serialize($orderDetail),"store" => "prestashop","txtlinha" => "","tpdoc" =>"".$order_tpdoc."","debug" => "".$debug."");
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

	if($debug==1){
		Mail::Send((int)(Configuration::get('PS_LANG_DEFAULT')),
						'contact', // email template file to be use
						'Debug: Enc#'.$order_id.' Log', // email subject
						array(
							'{email}' => Configuration::get('PS_SHOP_EMAIL'),
							'{message}' => '<br><hr> '.print_r(json_encode($params,true),true).'<hr>'.print_r(json_encode($callb_msg,true),true).'' // email content
						),'webmaster@pombaldir.com',NULL,NULL,NULL);	
						
	}
 }