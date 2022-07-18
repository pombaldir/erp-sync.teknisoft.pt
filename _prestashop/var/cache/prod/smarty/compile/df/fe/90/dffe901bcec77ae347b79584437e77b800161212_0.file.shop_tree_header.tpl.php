<?php
/* Smarty version 3.1.33, created on 2020-08-20 11:07:32
  from '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/default/template/controllers/shop/helpers/tree/shop_tree_header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3e4b64244b55_18736406',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dffe901bcec77ae347b79584437e77b800161212' => 
    array (
      0 => '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/default/template/controllers/shop/helpers/tree/shop_tree_header.tpl',
      1 => 1597917090,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3e4b64244b55_18736406 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="panel-heading">
	<?php if (isset($_smarty_tpl->tpl_vars['title']->value)) {?><i class="icon-sitemap"></i>&nbsp;<?php echo call_user_func_array( $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['l'][0], array( array('s'=>$_smarty_tpl->tpl_vars['title']->value),$_smarty_tpl ) );
}?>
	<div class="pull-right">
		<?php if (isset($_smarty_tpl->tpl_vars['toolbar']->value)) {
echo $_smarty_tpl->tpl_vars['toolbar']->value;
}?>
	</div>
</div>
<?php }
}
