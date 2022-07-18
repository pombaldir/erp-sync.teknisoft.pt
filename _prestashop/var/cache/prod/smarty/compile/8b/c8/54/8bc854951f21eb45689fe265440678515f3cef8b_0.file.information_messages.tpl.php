<?php
/* Smarty version 3.1.33, created on 2020-08-20 11:07:39
  from '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/template/components/layout/information_messages.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3e4b6b51df97_67974591',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8bc854951f21eb45689fe265440678515f3cef8b' => 
    array (
      0 => '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/template/components/layout/information_messages.tpl',
      1 => 1597917087,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3e4b6b51df97_67974591 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['informations']->value) && count($_smarty_tpl->tpl_vars['informations']->value) && $_smarty_tpl->tpl_vars['informations']->value) {?>
  <div class="bootstrap">
    <div class="alert alert-info">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <ul id="infos_block" class="list-unstyled">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['informations']->value, 'info');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['info']->value) {
?>
          <li><?php echo $_smarty_tpl->tpl_vars['info']->value;?>
</li>
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
      </ul>
    </div>
  </div>
<?php }
}
}
