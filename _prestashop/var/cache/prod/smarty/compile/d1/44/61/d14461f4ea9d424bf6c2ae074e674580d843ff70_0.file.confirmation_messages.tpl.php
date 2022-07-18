<?php
/* Smarty version 3.1.33, created on 2020-08-20 11:07:39
  from '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/template/components/layout/confirmation_messages.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f3e4b6b52b821_85981395',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd14461f4ea9d424bf6c2ae074e674580d843ff70' => 
    array (
      0 => '/Users/nelsonsantos/Sites/erp-sync.teknisoft.pt/_prestashop/admin294awd4er/themes/new-theme/template/components/layout/confirmation_messages.tpl',
      1 => 1597917087,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f3e4b6b52b821_85981395 (Smarty_Internal_Template $_smarty_tpl) {
if (isset($_smarty_tpl->tpl_vars['confirmations']->value) && count($_smarty_tpl->tpl_vars['confirmations']->value) && $_smarty_tpl->tpl_vars['confirmations']->value) {?>
  <div class="bootstrap">
    <div class="alert alert-success" style="display:block;">
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['confirmations']->value, 'conf');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['conf']->value) {
?>
        <?php echo $_smarty_tpl->tpl_vars['conf']->value;?>

      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
  </div>
<?php }
}
}
