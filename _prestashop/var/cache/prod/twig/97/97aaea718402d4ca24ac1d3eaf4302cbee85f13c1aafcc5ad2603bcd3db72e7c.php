<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__a18c87f2260d70f917f0782b0f6b2f68e8f83aab0cae4024a612faa2ec216b3b */
class __TwigTemplate_efa64fc04ddfba3595e5531c1dd063d7c5e6df458f2fc862dfbf2bb040d29138 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'stylesheets' => [$this, 'block_stylesheets'],
            'extra_stylesheets' => [$this, 'block_extra_stylesheets'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
            'content_footer' => [$this, 'block_content_footer'],
            'sidebar_right' => [$this, 'block_sidebar_right'],
            'javascripts' => [$this, 'block_javascripts'],
            'extra_javascripts' => [$this, 'block_extra_javascripts'],
            'translate_javascripts' => [$this, 'block_translate_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"pt\">
<head>
  <meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
<meta name=\"robots\" content=\"NOFOLLOW, NOINDEX\">

<link rel=\"icon\" type=\"image/x-icon\" href=\"/erp-sync.teknisoft.pt/_prestashop/img/favicon.ico\" />
<link rel=\"apple-touch-icon\" href=\"/erp-sync.teknisoft.pt/_prestashop/img/app_icon.png\" />

<title>Module manager • Loja Demo</title>

  <script type=\"text/javascript\">
    var help_class_name = 'AdminModulesManage';
    var iso_user = 'pt';
    var lang_is_rtl = '0';
    var full_language_code = 'pt-pt';
    var full_cldr_language_code = 'pt-PT';
    var country_iso_code = 'PT';
    var _PS_VERSION_ = '1.7.6.7';
    var roundMode = 2;
    var youEditFieldFor = '';
        var new_order_msg = 'Foi feita uma nova encomenda na sua loja.';
    var order_number_msg = 'Número da encomenda: ';
    var total_msg = 'Total: ';
    var from_msg = 'De: ';
    var see_order_msg = 'Ver esta encomenda';
    var new_customer_msg = 'Um novo cliente registou-se na sua loja.';
    var customer_name_msg = 'Nome do cliente: ';
    var new_msg = 'Foi deixada uma nova mensagem na sua loja.';
    var see_msg = 'Ler esta mensagem';
    var token = '20fad729d9d34346d7cebe9caecd6b58';
    var token_admin_orders = 'e449dd13a47e7c8dc11c1fc2e5535d6f';
    var token_admin_customers = '60ab27edfb7f163034774a6cdccf183d';
    var token_admin_customer_threads = '9f290e206245b82516a359fb5882ad7d';
    var currentIndex = 'index.php?controller=AdminModulesManage';
    var employee_token = '20e1844e2857de2937d2063cc7614cb2';
    var choose_language_translate = 'Escolher idioma';
    var default_language = '1';
    var admin_modules_link = '/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/catalog/recommended?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE';
    var admin_notification_get_link = '/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/common/notifications?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE';
    var admin_notification_push_link = '/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/common/notifications/ack?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE';
    var tab_modules_list = '';
    var update_success_msg = 'Atualizado com sucesso';
    var errorLogin = 'O PrestaShop não conseguiu autenticar-se nos Addons. Por favor verifique as suas credenciais e se está ligado à internet.';
    var search_product_msg = 'Pesquisar um produto';
  </script>

      <link href=\"/erp-sync.teknisoft.pt/_prestashop/modules/emarketing/views/css/menuTabIcon.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/public/theme.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/erp-sync.teknisoft.pt/_prestashop/js/jquery/plugins/chosen/jquery.chosen.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/default/css/vendor/nv.d3.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/erp-sync.teknisoft.pt/_prestashop/modules/gamification/views/css/gamification.css\" rel=\"stylesheet\" type=\"text/css\"/>
      <link href=\"/erp-sync.teknisoft.pt/_prestashop/js/jquery/plugins/fancybox/jquery.fancybox.css\" rel=\"stylesheet\" type=\"text/css\"/>
  
  <script type=\"text/javascript\">
var baseAdminDir = \"\\/erp-sync.teknisoft.pt\\/_prestashop\\/admin294awd4er\\/\";
var baseDir = \"\\/erp-sync.teknisoft.pt\\/_prestashop\\/\";
var changeFormLanguageUrl = \"\\/erp-sync.teknisoft.pt\\/_prestashop\\/admin294awd4er\\/index.php\\/configure\\/advanced\\/employees\\/change-form-language?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\";
var currency = {\"iso_code\":\"EUR\",\"sign\":\"\\u20ac\",\"name\":\"Euro\",\"format\":null};
var currency_specifications = {\"symbol\":[\",\",\"\\u00a0\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"currencyCode\":\"EUR\",\"currencySymbol\":\"\\u20ac\",\"positivePattern\":\"#,##0.00\\u00a0\\u00a4\",\"negativePattern\":\"-#,##0.00\\u00a0\\u00a4\",\"maxFractionDigits\":2,\"minFractionDigits\":2,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var host_mode = false;
var number_specifications = {\"symbol\":[\",\",\"\\u00a0\",\";\",\"%\",\"-\",\"+\",\"E\",\"\\u00d7\",\"\\u2030\",\"\\u221e\",\"NaN\"],\"positivePattern\":\"#,##0.###\",\"negativePattern\":\"-#,##0.###\",\"maxFractionDigits\":3,\"minFractionDigits\":0,\"groupingUsed\":true,\"primaryGroupSize\":3,\"secondaryGroupSize\":3};
var show_new_customers = \"1\";
var show_new_messages = false;
var show_new_orders = \"1\";
</script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/modules/ps_faviconnotificationbo/views/js/favico.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/modules/ps_faviconnotificationbo/views/js/ps_faviconnotificationbo.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/public/main.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/js/jquery/plugins/jquery.chosen.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/js/admin.js?v=1.7.6.7\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/public/cldr.bundle.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/js/tools.js?v=1.7.6.7\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/public/bundle.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/js/vendor/d3.v3.min.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/default/js/vendor/nv.d3.min.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/modules/gamification/views/js/gamification_bt.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/js/jquery/plugins/fancybox/jquery.fancybox.js\"></script>
<script type=\"text/javascript\" src=\"/erp-sync.teknisoft.pt/_prestashop/modules/ps_mbo/views/js/recommended-modules.js?v=2.0.1\"></script>

  <script>
  if (undefined !== ps_faviconnotificationbo) {
    ps_faviconnotificationbo.initialize({
      backgroundColor: '#DF0067',
      textColor: '#FFFFFF',
      notificationGetUrl: '/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/common/notifications?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE',
      CHECKBOX_ORDER: 1,
      CHECKBOX_CUSTOMER: 1,
      CHECKBOX_MESSAGE: 1,
      timer: 120000, // Refresh every 2 minutes
    });
  }
</script>
<script>
            var admin_gamification_ajax_url = \"http:\\/\\/localhost:8888\\/erp-sync.teknisoft.pt\\/_prestashop\\/admin294awd4er\\/index.php?controller=AdminGamification&token=7c7917688b60e037db89c3b175661e80\";
            var current_id_tab = 45;
        </script>

";
        // line 101
        $this->displayBlock('stylesheets', $context, $blocks);
        $this->displayBlock('extra_stylesheets', $context, $blocks);
        echo "</head>

<body class=\"lang-pt adminmodulesmanage\">

  <header id=\"header\">

    <nav id=\"header_infos\" class=\"main-header\">
      <button class=\"btn btn-primary-reverse onclick btn-lg unbind ajax-spinner\"></button>

            <i class=\"material-icons js-mobile-menu\">menu</i>
      <a id=\"header_logo\" class=\"logo float-left\" href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminDashboard&amp;token=bcf6b6e100e169f4b602f2fe0a7a9a39\"></a>
      <span id=\"shop_version\">1.7.6.7</span>

      <div class=\"component\" id=\"quick-access-container\">
        <div class=\"dropdown quick-accesses\">
  <button class=\"btn btn-link btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\" id=\"quick_select\">
    Acesso Rápido
  </button>
  <div class=\"dropdown-menu\">
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminStats&amp;module=statscheckup&amp;token=313c5db742a1d70430c283381cc7782d\"
                 data-item=\"Avaliação do catálogo\"
      >Avaliação do catálogo</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminOrders&amp;token=e449dd13a47e7c8dc11c1fc2e5535d6f\"
                 data-item=\"Encomendas\"
      >Encomendas</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?token=d27853d676ed3f19cf9655f806c7c7e1\"
                 data-item=\"Módulos instalados\"
      >Módulos instalados</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/categories/new?token=d27853d676ed3f19cf9655f806c7c7e1\"
                 data-item=\"Nova Categoria\"
      >Nova Categoria</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/products/new?token=d27853d676ed3f19cf9655f806c7c7e1\"
                 data-item=\"Novo produto\"
      >Novo produto</a>
          <a class=\"dropdown-item\"
         href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCartRules&amp;addcart_rule&amp;token=b20f5eafe62061549d14a87517b70a47\"
                 data-item=\"Novo Voucher\"
      >Novo Voucher</a>
        <div class=\"dropdown-divider\"></div>
          <a
        class=\"dropdown-item js-quick-link\"
        href=\"#\"
        data-rand=\"73\"
        data-icon=\"icon-AdminModulesSf\"
        data-method=\"add\"
        data-url=\"index.php/improve/modules/manage?-oaLs-vdhn28ANuY7S2TmgcrBtE\"
        data-post-link=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminQuickAccesses&token=bec8eac5695cf4b067d76ca435cdcc2d\"
        data-prompt-text=\"Atribua um nome a este atalho:\"
        data-link=\"M&oacute;dulos - Lista\"
      >
        <i class=\"material-icons\">add_circle</i>
        Adicionar a página atual ao Acesso Rápido
      </a>
        <a class=\"dropdown-item\" href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminQuickAccesses&token=bec8eac5695cf4b067d76ca435cdcc2d\">
      <i class=\"material-icons\">settings</i>
      Gerir atalhos
    </a>
  </div>
</div>
      </div>
      <div class=\"component\" id=\"header-search-container\">
        <form id=\"header_search\"
      class=\"bo_search_form dropdown-form js-dropdown-form collapsed\"
      method=\"post\"
      action=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminSearch&amp;token=8e1cf5e9ee32d2bb88db8f9b1365fbd4\"
      role=\"search\">
  <input type=\"hidden\" name=\"bo_search_type\" id=\"bo_search_type\" class=\"js-search-type\" />
    <div class=\"input-group\">
    <input type=\"text\" class=\"form-control js-form-search\" id=\"bo_query\" name=\"bo_query\" value=\"\" placeholder=\"Pesquisar (por ex.: referência do produto, nome do cliente…)\">
    <div class=\"input-group-append\">
      <button type=\"button\" class=\"btn btn-outline-secondary dropdown-toggle js-dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
        Em todo o lado
      </button>
      <div class=\"dropdown-menu js-items-list\">
        <a class=\"dropdown-item\" data-item=\"Em todo o lado\" href=\"#\" data-value=\"0\" data-placeholder=\"O que procura?\" data-icon=\"icon-search\"><i class=\"material-icons\">search</i> Em todo o lado</a>
        <div class=\"dropdown-divider\"></div>
        <a class=\"dropdown-item\" data-item=\"Catálogo\" href=\"#\" data-value=\"1\" data-placeholder=\"Nome do produto, SKU, referência...\" data-icon=\"icon-book\"><i class=\"material-icons\">store_mall_directory</i> Catálogo</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por nome\" href=\"#\" data-value=\"2\" data-placeholder=\"E-mail, nome...\" data-icon=\"icon-group\"><i class=\"material-icons\">group</i> Clientes por nome</a>
        <a class=\"dropdown-item\" data-item=\"Clientes por endereço IP\" href=\"#\" data-value=\"6\" data-placeholder=\"123.45.67.89\" data-icon=\"icon-desktop\"><i class=\"material-icons\">desktop_mac</i> Clientes por endereço IP</a>
        <a class=\"dropdown-item\" data-item=\"Encomendas\" href=\"#\" data-value=\"3\" data-placeholder=\"Nº da Encomenda\" data-icon=\"icon-credit-card\"><i class=\"material-icons\">shopping_basket</i> Encomendas</a>
        <a class=\"dropdown-item\" data-item=\"Faturas\" href=\"#\" data-value=\"4\" data-placeholder=\"Número da Fatura\" data-icon=\"icon-book\"><i class=\"material-icons\">book</i> Faturas</a>
        <a class=\"dropdown-item\" data-item=\"Carrinhos\" href=\"#\" data-value=\"5\" data-placeholder=\"ID do Carrinho\" data-icon=\"icon-shopping-cart\"><i class=\"material-icons\">shopping_cart</i> Carrinhos</a>
        <a class=\"dropdown-item\" data-item=\"Módulos\" href=\"#\" data-value=\"7\" data-placeholder=\"Nome do Módulo\" data-icon=\"icon-puzzle-piece\"><i class=\"material-icons\">extension</i> Módulos</a>
      </div>
      <button class=\"btn btn-primary\" type=\"submit\"><span class=\"d-none\">PESQUISAR</span><i class=\"material-icons\">search</i></button>
    </div>
  </div>
</form>

<script type=\"text/javascript\">
 \$(document).ready(function(){
    \$('#bo_query').one('click', function() {
    \$(this).closest('form').removeClass('collapsed');
  });
});
</script>
      </div>

      
      
      <div class=\"component\" id=\"header-shop-list-container\">
          <div id=\"shop-list\" class=\"shop-list dropdown ps-dropdown stores\">
    <button class=\"btn btn-link\" type=\"button\" data-toggle=\"dropdown\">
      <span class=\"selected-item\">
        <i class=\"material-icons visibility\">visibility</i>
                  All shops
                <i class=\"material-icons arrow-down\">arrow_drop_down</i>
      </span>
    </button>
    <div class=\"dropdown-menu dropdown-menu-right ps-dropdown-menu\">
      <ul class=\"items-list\"><li class=\"active\"><a class=\"dropdown-item\" href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE&amp;setShopContext=\">Todas as lojas</a></li><li class=\"group\"><a class=\"dropdown-item\" href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE&amp;setShopContext=g-1\">Grupo de Lojas grupo</a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE&amp;setShopContext=s-1\">Loja Demo</a><a class=\"link-shop\" href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li><li class=\"shop\"><a class=\"dropdown-item \" href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE&amp;setShopContext=s-2\">Loja Demo 2</a><a class=\"link-shop\" href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/loja2/\" target=\"_blank\"><i class=\"material-icons\">&#xE8F4;</i></a></li></ul>

    </div>
  </div>
      </div>

              <div class=\"component header-right-component\" id=\"header-notifications-container\">
          <div id=\"notif\" class=\"notification-center dropdown dropdown-clickable\">
  <button class=\"btn notification js-notification dropdown-toggle\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">notifications_none</i>
    <span id=\"notifications-total\" class=\"count hide\">0</span>
  </button>
  <div class=\"dropdown-menu dropdown-menu-right js-notifs_dropdown\">
    <div class=\"notifications\">
      <ul class=\"nav nav-tabs\" role=\"tablist\">
                          <li class=\"nav-item\">
            <a
              class=\"nav-link active\"
              id=\"orders-tab\"
              data-toggle=\"tab\"
              data-type=\"order\"
              href=\"#orders-notifications\"
              role=\"tab\"
            >
              Encomendas<span id=\"_nb_new_orders_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"customers-tab\"
              data-toggle=\"tab\"
              data-type=\"customer\"
              href=\"#customers-notifications\"
              role=\"tab\"
            >
              Clientes<span id=\"_nb_new_customers_\"></span>
            </a>
          </li>
                                    <li class=\"nav-item\">
            <a
              class=\"nav-link \"
              id=\"messages-tab\"
              data-toggle=\"tab\"
              data-type=\"customer_message\"
              href=\"#messages-notifications\"
              role=\"tab\"
            >
              Mensagens<span id=\"_nb_new_messages_\"></span>
            </a>
          </li>
                        </ul>

      <!-- Tab panes -->
      <div class=\"tab-content\">
                          <div class=\"tab-pane active empty\" id=\"orders-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Nenhuma nova encomenda por enquanto :(<br>
              E que tal alguns descontos de estação?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"customers-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Nenhum novo cliente por enquanto :(<br>
              Tem estado ativo nas redes sociais estes últimos dias?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                                    <div class=\"tab-pane  empty\" id=\"messages-notifications\" role=\"tabpanel\">
            <p class=\"no-notification\">
              Sem nova mensagem por enquanto.<br>
              As más notícias correm depressa, não é?
            </p>
            <div class=\"notification-elements\"></div>
          </div>
                        </div>
    </div>
  </div>
</div>

  <script type=\"text/html\" id=\"order-notification-template\">
    <a class=\"notif\" href='order_url'>
      #_id_order_ -
      de <strong>_customer_name_</strong> (_iso_code_)_carrier_
      <strong class=\"float-sm-right\">_total_paid_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"customer-notification-template\">
    <a class=\"notif\" href='customer_url'>
      #_id_customer_ - <strong>_customer_name_</strong>_company_ - registado <strong>_date_add_</strong>
    </a>
  </script>

  <script type=\"text/html\" id=\"message-notification-template\">
    <a class=\"notif\" href='message_url'>
    <span class=\"message-notification-status _status_\">
      <i class=\"material-icons\">fiber_manual_record</i> _status_
    </span>
      - <strong>_customer_name_</strong> (_company_) - <i class=\"material-icons\">access_time</i> _date_add_
    </a>
  </script>
        </div>
      
      <div class=\"component\" id=\"header-employee-container\">
        <div class=\"dropdown employee-dropdown\">
  <div class=\"rounded-circle person\" data-toggle=\"dropdown\">
    <i class=\"material-icons\">account_circle</i>
  </div>
  <div class=\"dropdown-menu dropdown-menu-right\">
    <div class=\"employee-wrapper-avatar\">
      
      <span class=\"employee_avatar\"><img class=\"avatar rounded-circle\" src=\"http://profile.prestashop.com/nsantos%40pombaldir.com.jpg\" /></span>
      <span class=\"employee_profile\">Welcome back Nelson</span>
      <a class=\"dropdown-item employee-link profile-link\" href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/employees/1/edit?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\">
      <i class=\"material-icons\">settings</i>
      O seu perfil
    </a>
    </div>
    
    <p class=\"divider\"></p>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/resources/documentations?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=resources-en&amp;utm_content=download17\" target=\"_blank\"><i class=\"material-icons\">book</i> Resources</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/training?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=training-en&amp;utm_content=download17\" target=\"_blank\"><i class=\"material-icons\">school</i> Formação</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/experts?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=expert-en&amp;utm_content=download17\" target=\"_blank\"><i class=\"material-icons\">person_pin_circle</i> Find an Expert</a>
    <a class=\"dropdown-item\" href=\"https://addons.prestashop.com?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=addons-en&amp;utm_content=download17\" target=\"_blank\"><i class=\"material-icons\">extension</i> PrestaShop Marketplace</a>
    <a class=\"dropdown-item\" href=\"https://www.prestashop.com/en/contact?utm_source=back-office&amp;utm_medium=profile&amp;utm_campaign=help-center-en&amp;utm_content=download17\" target=\"_blank\"><i class=\"material-icons\">help</i> Help Center</a>
    <p class=\"divider\"></p>
    <a class=\"dropdown-item employee-link text-center\" id=\"header_logout\" href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminLogin&amp;logout=1&amp;token=d238aff9eccfad8d8f360e1d8abe2d42\">
      <i class=\"material-icons d-lg-none\">power_settings_new</i>
      <span>Fechar sessão</span>
    </a>
  </div>
</div>
      </div>
    </nav>

      </header>

  <nav class=\"nav-bar d-none d-md-block\">
  <span class=\"menu-collapse\" data-toggle-url=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/employees/toggle-navigation?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\">
    <i class=\"material-icons\">chevron_left</i>
    <i class=\"material-icons\">chevron_left</i>
  </span>

  <ul class=\"main-menu\">

          
                
                
        
          <li class=\"link-levelone \" data-submenu=\"1\" id=\"tab-AdminDashboard\">
            <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminDashboard&amp;token=bcf6b6e100e169f4b602f2fe0a7a9a39\" class=\"link\" >
              <i class=\"material-icons\">trending_up</i> <span>Painel de controlo</span>
            </a>
          </li>

        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"2\" id=\"tab-SELL\">
              <span class=\"title\">Vender</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"3\" id=\"subtab-AdminParentOrders\">
                  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminOrders&amp;token=e449dd13a47e7c8dc11c1fc2e5535d6f\" class=\"link\">
                    <i class=\"material-icons mi-shopping_basket\">shopping_basket</i>
                    <span>
                    Encomendas
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-3\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"4\" id=\"subtab-AdminOrders\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminOrders&amp;token=e449dd13a47e7c8dc11c1fc2e5535d6f\" class=\"link\"> Encomendas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"5\" id=\"subtab-AdminInvoices\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/orders/invoices/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Faturas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"6\" id=\"subtab-AdminSlip\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminSlip&amp;token=c218a695261e91c1f3c08b3fbf286ae4\" class=\"link\"> Notas de Crédito
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"7\" id=\"subtab-AdminDeliverySlip\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/orders/delivery-slips/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Notas de Entrega
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"8\" id=\"subtab-AdminCarts\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCarts&amp;token=bfba5ffb3ee340e388dd18fa4af24cb1\" class=\"link\"> Carrinhos de Compras
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"9\" id=\"subtab-AdminCatalog\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/products?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-store\">store</i>
                    <span>
                    Catálogo
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-9\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"10\" id=\"subtab-AdminProducts\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/products?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Produtos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"11\" id=\"subtab-AdminCategories\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/categories?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Categorias
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"12\" id=\"subtab-AdminTracking\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminTracking&amp;token=dd38417caa0e35bc72100d0958919a24\" class=\"link\"> Monitorização
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"13\" id=\"subtab-AdminParentAttributesGroups\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminAttributesGroups&amp;token=2c5d48410fc2cb0bd4859aacf0074342\" class=\"link\"> Atributos e Características
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"16\" id=\"subtab-AdminParentManufacturers\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/catalog/brands/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Marcas e Fornecedores
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"19\" id=\"subtab-AdminAttachments\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminAttachments&amp;token=8cf4e283d3170e613927779c7d8fe4dd\" class=\"link\"> Ficheiros
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"20\" id=\"subtab-AdminParentCartRules\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCartRules&amp;token=b20f5eafe62061549d14a87517b70a47\" class=\"link\"> Descontos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"23\" id=\"subtab-AdminStockManagement\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/stocks/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Stocks
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"24\" id=\"subtab-AdminParentCustomer\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/customers/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-account_circle\">account_circle</i>
                    <span>
                    Clientes
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-24\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"25\" id=\"subtab-AdminCustomers\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/sell/customers/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Clientes
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"26\" id=\"subtab-AdminAddresses\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminAddresses&amp;token=6a03f6cd1872670103ae5b5d4fc7215c\" class=\"link\"> Endereços
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"28\" id=\"subtab-AdminParentCustomerThreads\">
                  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCustomerThreads&amp;token=9f290e206245b82516a359fb5882ad7d\" class=\"link\">
                    <i class=\"material-icons mi-chat\">chat</i>
                    <span>
                    Apoio ao Cliente
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-28\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"29\" id=\"subtab-AdminCustomerThreads\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCustomerThreads&amp;token=9f290e206245b82516a359fb5882ad7d\" class=\"link\"> Apoio ao Cliente
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"30\" id=\"subtab-AdminOrderMessage\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminOrderMessage&amp;token=333b7f297c78d99d4e221dab43d82483\" class=\"link\"> Mensagens de Encomendas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"31\" id=\"subtab-AdminReturn\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminReturn&amp;token=d5bce4ed6f29706aa43dc8718d9c8faa\" class=\"link\"> Mercadorias Devolvidas
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone\" data-submenu=\"32\" id=\"subtab-AdminStats\">
                  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminStats&amp;token=313c5db742a1d70430c283381cc7782d\" class=\"link\">
                    <i class=\"material-icons mi-assessment\">assessment</i>
                    <span>
                    Estatísticas
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title -active\" data-submenu=\"42\" id=\"tab-IMPROVE\">
              <span class=\"title\">Melhorar</span>
          </li>

                          
                
                                                
                                                    
                <li class=\"link-levelone has_submenu -active open ul-open\" data-submenu=\"43\" id=\"subtab-AdminParentModulesSf\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-extension\">extension</i>
                    <span>
                    Módulos
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_up
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-43\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo -active\" data-submenu=\"44\" id=\"subtab-AdminModulesSf\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Module Manager
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"48\" id=\"subtab-AdminParentModulesCatalog\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/modules/addons/modules/catalog?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Module Catalog
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"52\" id=\"subtab-AdminParentThemes\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/design/themes/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-desktop_mac\">desktop_mac</i>
                    <span>
                    Design
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-52\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"126\" id=\"subtab-AdminThemesParent\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/design/themes/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Tema Gráfico e Logótipo
                              </a>
                            </li>

                                                                                                                              
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"137\" id=\"subtab-AdminPsMboTheme\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/modules/addons/themes/catalog?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Catálogo do Tema Gráfico
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"55\" id=\"subtab-AdminParentMailTheme\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/design/mail_theme/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Email Theme
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"57\" id=\"subtab-AdminCmsContent\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/design/cms-pages/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Páginas
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"58\" id=\"subtab-AdminModulesPositions\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/design/modules/positions/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Posições
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"59\" id=\"subtab-AdminImages\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminImages&amp;token=7185769c8547f7c437d963024549790a\" class=\"link\"> Definições de Imagem
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"125\" id=\"subtab-AdminLinkWidget\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/modules/link-widget/list?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Link Widget
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"60\" id=\"subtab-AdminParentShipping\">
                  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCarriers&amp;token=15dcc6c433b51abc4de97b7c9268fb2f\" class=\"link\">
                    <i class=\"material-icons mi-local_shipping\">local_shipping</i>
                    <span>
                    Envio
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-60\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"61\" id=\"subtab-AdminCarriers\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminCarriers&amp;token=15dcc6c433b51abc4de97b7c9268fb2f\" class=\"link\"> Transportadoras
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"62\" id=\"subtab-AdminShipping\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/shipping/preferences?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Preferências
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"63\" id=\"subtab-AdminParentPayment\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/payment/payment_methods?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-payment\">payment</i>
                    <span>
                    Pagamento
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-63\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"64\" id=\"subtab-AdminPayment\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/payment/payment_methods?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Métodos de Pagamento
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"65\" id=\"subtab-AdminPaymentPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/payment/preferences?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Preferências
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"66\" id=\"subtab-AdminInternational\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/international/localization/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-language\">language</i>
                    <span>
                    International
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-66\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"67\" id=\"subtab-AdminParentLocalization\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/international/localization/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Localização
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"72\" id=\"subtab-AdminParentCountries\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminZones&amp;token=3c6c76bfb2b751bfe5d1edbb9a5401ec\" class=\"link\"> Localizações
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"76\" id=\"subtab-AdminParentTaxes\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/international/taxes/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> IVA
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"79\" id=\"subtab-AdminTranslations\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/international/translations/settings?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Traduções
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone\" data-submenu=\"131\" id=\"subtab-AdminEmarketing\">
                  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminEmarketing&amp;token=6701cff36f2fcace2115eeeca237f61f\" class=\"link\">
                    <i class=\"material-icons mi-track_changes\">track_changes</i>
                    <span>
                    Advertising
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                    </li>
                          
        
                
                                  
                
        
          <li class=\"category-title \" data-submenu=\"80\" id=\"tab-CONFIGURE\">
              <span class=\"title\">Configurar</span>
          </li>

                          
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"81\" id=\"subtab-ShopParameters\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/preferences/preferences?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-settings\">settings</i>
                    <span>
                    Parâmetros da Loja
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-81\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"82\" id=\"subtab-AdminParentPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/preferences/preferences?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Geral
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"85\" id=\"subtab-AdminParentOrderPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/order-preferences/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Definições da Encomenda
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"88\" id=\"subtab-AdminPPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/product-preferences/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Produtos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"89\" id=\"subtab-AdminParentCustomerPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/customer-preferences/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Definições do cliente
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"93\" id=\"subtab-AdminParentStores\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/contacts/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Contacto
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"96\" id=\"subtab-AdminParentMeta\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/shop/seo-urls/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Tráfego e SEO
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"100\" id=\"subtab-AdminParentSearchConf\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminSearchConf&amp;token=3b4c6ecb7b3207b0c6fb353b2857704e\" class=\"link\"> Pesquisar
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"130\" id=\"subtab-AdminGamification\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminGamification&amp;token=7c7917688b60e037db89c3b175661e80\" class=\"link\"> Merchant Expertise
                              </a>
                            </li>

                                                                        </ul>
                                    </li>
                                        
                
                                                
                
                <li class=\"link-levelone has_submenu\" data-submenu=\"103\" id=\"subtab-AdminAdvancedParameters\">
                  <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/system-information/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\">
                    <i class=\"material-icons mi-settings_applications\">settings_applications</i>
                    <span>
                    Parâmetros Avançados
                    </span>
                                                <i class=\"material-icons sub-tabs-arrow\">
                                                                keyboard_arrow_down
                                                        </i>
                                        </a>
                                          <ul id=\"collapse-103\" class=\"submenu panel-collapse\">
                                                  
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"104\" id=\"subtab-AdminInformation\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/system-information/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Informação
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"105\" id=\"subtab-AdminPerformance\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/performance/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Desempenho
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"106\" id=\"subtab-AdminAdminPreferences\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/administration/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Administração
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"107\" id=\"subtab-AdminEmails\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/emails/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Email
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"108\" id=\"subtab-AdminImport\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/import/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Importar
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"109\" id=\"subtab-AdminParentEmployees\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/employees/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Empregados
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"113\" id=\"subtab-AdminParentRequestSql\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/sql-requests/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Base de dados
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"116\" id=\"subtab-AdminLogs\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/logs/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Registos
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"117\" id=\"subtab-AdminWebservice\">
                              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/configure/advanced/webservice-keys/?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" class=\"link\"> Webservice
                              </a>
                            </li>

                                                                            
                            
                                                        
                            <li class=\"link-leveltwo \" data-submenu=\"118\" id=\"subtab-AdminShopGroup\">
                              <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminShopGroup&amp;token=8ef0ce9cdbaca6cc1da0fdc5670993b2\" class=\"link\"> Multi-Loja
                              </a>
                            </li>

                                                                                                                          </ul>
                                    </li>
                          
        
            </ul>
  
</nav>

<div id=\"main-div\">
          
<div class=\"header-toolbar\">
  <div class=\"container-fluid\">

    
      <nav aria-label=\"Breadcrumb\">
        <ol class=\"breadcrumb\">
                      <li class=\"breadcrumb-item\">Module Manager</li>
          
                      <li class=\"breadcrumb-item active\">
              <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" aria-current=\"page\">Módulos</a>
            </li>
                  </ol>
      </nav>
    

    <div class=\"title-row\">
      
          <h1 class=\"title\">
            Module manager          </h1>
      

      
        <div class=\"toolbar-icons\">
          <div class=\"wrapper\">
            
                                                          <a
                  class=\"btn btn-primary  pointer\"                  id=\"page-header-desc-configuration-add_module\"
                  href=\"#\"                  title=\"Enviar um módulo\"                  data-toggle=\"pstooltip\"
                  data-placement=\"bottom\"                >
                  <i class=\"material-icons\">cloud_upload</i>                  Enviar um módulo
                </a>
                                                                        <a
                  class=\"btn btn-primary  pointer\"                  id=\"page-header-desc-configuration-addons_connect\"
                  href=\"#\"                  title=\"Ligar com o Addons Marketplace\"                  data-toggle=\"pstooltip\"
                  data-placement=\"bottom\"                >
                  <i class=\"material-icons\">vpn_key</i>                  Ligar com o Addons Marketplace
                </a>
                                      
            
                              <a class=\"btn btn-outline-secondary btn-help btn-sidebar\" href=\"#\"
                   title=\"Ajuda\"
                   data-toggle=\"sidebar\"
                   data-target=\"#right-sidebar\"
                   data-url=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/common/sidebar/https%253A%252F%252Fhelp.prestashop.com%252Fpt%252Fdoc%252FAdminModules%253Fversion%253D1.7.6.7%2526country%253Dpt/Ajuda?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\"
                   id=\"product_form_open_help\"
                >
                  Ajuda
                </a>
                                    </div>
        </div>
      
    </div>
  </div>

  
      <div class=\"page-head-tabs\" id=\"head_tabs\">
      <ul class=\"nav nav-pills\">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <li class=\"nav-item\">
                    <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/manage?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" id=\"subtab-AdminModulesManage\" class=\"nav-link tab active current\" data-submenu=\"45\">
                      Módulos
                      <span class=\"notification-container\">
                        <span class=\"notification-counter\"></span>
                      </span>
                    </a>
                  </li>
                                                                <li class=\"nav-item\">
                    <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/alerts?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" id=\"subtab-AdminModulesNotifications\" class=\"nav-link tab \" data-submenu=\"46\">
                      Alerts
                      <span class=\"notification-container\">
                        <span class=\"notification-counter\"></span>
                      </span>
                    </a>
                  </li>
                                                                <li class=\"nav-item\">
                    <a href=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/improve/modules/updates?_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE\" id=\"subtab-AdminModulesUpdates\" class=\"nav-link tab \" data-submenu=\"47\">
                      Updates
                      <span class=\"notification-container\">
                        <span class=\"notification-counter\"></span>
                      </span>
                    </a>
                  </li>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </ul>
    </div>
    <script>
  if (undefined !== mbo) {
    mbo.initialize({
      translations: {
        'Recommended Modules and Services': 'Módulos e Serviços Recomendados',
        'Close': 'Fechar',
      },
      recommendedModulesUrl: '/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php/modules/addons/modules/recommended?tabClassName=AdminModulesManage&_token=rEEPHAnfTE0bWG60-oaLs-vdhn28ANuY7S2TmgcrBtE',
      shouldAttachRecommendedModulesAfterContent: 0,
      shouldAttachRecommendedModulesButton: 0,
      shouldUseLegacyTheme: 0,
    });
  }
</script>

</div>
      
      <div class=\"content-div  with-tabs\">

        

                                                        
        <div class=\"row \">
          <div class=\"col-sm-12\">
            <div id=\"ajax_confirmation\" class=\"alert alert-success\" style=\"display: none;\"></div>


  ";
        // line 1161
        $this->displayBlock('content_header', $context, $blocks);
        // line 1162
        echo "                 ";
        $this->displayBlock('content', $context, $blocks);
        // line 1163
        echo "                 ";
        $this->displayBlock('content_footer', $context, $blocks);
        // line 1164
        echo "                 ";
        $this->displayBlock('sidebar_right', $context, $blocks);
        // line 1165
        echo "
            
          </div>
        </div>

      </div>
    </div>

  <div id=\"non-responsive\" class=\"js-non-responsive\">
  <h1>Oh, não!</h1>
  <p class=\"mt-3\">
    A versão móvel desta página ainda não está disponível.
  </p>
  <p class=\"mt-2\">
    Utilize um computador para obter acesso a esta página, até ser adaptada para um telemóvel.
  </p>
  <p class=\"mt-2\">
    Obrigado.
  </p>
  <a href=\"http://localhost:8888/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/index.php?controller=AdminDashboard&amp;token=bcf6b6e100e169f4b602f2fe0a7a9a39\" class=\"btn btn-primary py-1 mt-3\">
    <i class=\"material-icons\">arrow_back</i>
    Voltar
  </a>
</div>
  <div class=\"mobile-layer\"></div>

      <div id=\"footer\" class=\"bootstrap\">
    
</div>
  

      <div class=\"bootstrap\">
      <div class=\"modal fade\" id=\"modal_addons_connect\" tabindex=\"-1\">
\t<div class=\"modal-dialog modal-md\">
\t\t<div class=\"modal-content\">
\t\t\t\t\t\t<div class=\"modal-header\">
\t\t\t\t<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
\t\t\t\t<h4 class=\"modal-title\"><i class=\"icon-puzzle-piece\"></i> <a target=\"_blank\" href=\"https://addons.prestashop.com/?utm_source=back-office&utm_medium=modules&utm_campaign=back-office-PT&utm_content=download\">PrestaShop Addons</a></h4>
\t\t\t</div>
\t\t\t
\t\t\t<div class=\"modal-body\">
\t\t\t\t\t\t<!--start addons login-->
\t\t\t<form id=\"addons_login_form\" method=\"post\" >
\t\t\t\t<div>
\t\t\t\t\t<a href=\"https://addons.prestashop.com/pt/login?email=nsantos%40pombaldir.com&amp;firstname=Nelson&amp;lastname=Santos&amp;website=http%3A%2F%2Flocalhost%3A8888%2Ferp-sync.teknisoft.pt%2F_prestashop%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-PT&amp;utm_content=download#createnow\"><img class=\"img-responsive center-block\" src=\"/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/default/img/prestashop-addons-logo.png\" alt=\"Logo PrestaShop Addons\"/></a>
\t\t\t\t\t<h3 class=\"text-center\">Ligue a sua loja ao mercado PrestaShop para poder importar automaticamente todas as suas compras Addons.</h3>
\t\t\t\t\t<hr />
\t\t\t\t</div>
\t\t\t\t<div class=\"row\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>Não tem uma conta criada?</h4>
\t\t\t\t\t\t<p class='text-justify'>Descubra as vantagens do PrestaShop Addons! Explore o «marketplace» oficial do PrestaShop e encontre mais de 3500 módulos inovadores e temas-gráficos que o ajudam a otimizar as taxas de conversão de visitas em vendas, aumentar o tráfego na loja, aumentar a fidelidade dos clientes e maximizar o seu nível de produtividade</p>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<h4>Ligue-se ao PrestaShop Addons</h4>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-user\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"username_addons\" name=\"username_addons\" type=\"text\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<div class=\"input-group\">
\t\t\t\t\t\t\t\t<div class=\"input-group-prepend\">
\t\t\t\t\t\t\t\t\t<span class=\"input-group-text\"><i class=\"icon-key\"></i></span>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<input id=\"password_addons\" name=\"password_addons\" type=\"password\" value=\"\" autocomplete=\"off\" class=\"form-control ac_input\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<a class=\"btn btn-link float-right _blank\" href=\"//addons.prestashop.com/pt/forgot-your-password\">Esqueci-me da minha palavra-passe</a>
\t\t\t\t\t\t\t<br>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div class=\"row row-padding-top\">
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<a class=\"btn btn-default btn-block btn-lg _blank\" href=\"https://addons.prestashop.com/pt/login?email=nsantos%40pombaldir.com&amp;firstname=Nelson&amp;lastname=Santos&amp;website=http%3A%2F%2Flocalhost%3A8888%2Ferp-sync.teknisoft.pt%2F_prestashop%2F&amp;utm_source=back-office&amp;utm_medium=connect-to-addons&amp;utm_campaign=back-office-PT&amp;utm_content=download#createnow\">
\t\t\t\t\t\t\t\tCriar uma conta
\t\t\t\t\t\t\t\t<i class=\"icon-external-link\"></i>
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t\t<div class=\"col-md-6\">
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<button id=\"addons_login_button\" class=\"btn btn-primary btn-block btn-lg\" type=\"submit\">
\t\t\t\t\t\t\t\t<i class=\"icon-unlock\"></i> Entrar
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>

\t\t\t\t<div id=\"addons_loading\" class=\"help-block\"></div>

\t\t\t</form>
\t\t\t<!--end addons login-->
\t\t\t</div>


\t\t\t\t\t</div>
\t</div>
</div>

    </div>
  
";
        // line 1272
        $this->displayBlock('javascripts', $context, $blocks);
        $this->displayBlock('extra_javascripts', $context, $blocks);
        $this->displayBlock('translate_javascripts', $context, $blocks);
        echo "</body>
</html>";
    }

    // line 101
    public function block_stylesheets($context, array $blocks = [])
    {
    }

    public function block_extra_stylesheets($context, array $blocks = [])
    {
    }

    // line 1161
    public function block_content_header($context, array $blocks = [])
    {
    }

    // line 1162
    public function block_content($context, array $blocks = [])
    {
    }

    // line 1163
    public function block_content_footer($context, array $blocks = [])
    {
    }

    // line 1164
    public function block_sidebar_right($context, array $blocks = [])
    {
    }

    // line 1272
    public function block_javascripts($context, array $blocks = [])
    {
    }

    public function block_extra_javascripts($context, array $blocks = [])
    {
    }

    public function block_translate_javascripts($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "__string_template__a18c87f2260d70f917f0782b0f6b2f68e8f83aab0cae4024a612faa2ec216b3b";
    }

    public function getDebugInfo()
    {
        return array (  1362 => 1272,  1357 => 1164,  1352 => 1163,  1347 => 1162,  1342 => 1161,  1333 => 101,  1325 => 1272,  1216 => 1165,  1213 => 1164,  1210 => 1163,  1207 => 1162,  1205 => 1161,  141 => 101,  39 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__a18c87f2260d70f917f0782b0f6b2f68e8f83aab0cae4024a612faa2ec216b3b", "");
    }
}
