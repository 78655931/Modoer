<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');

if($_POST['dosubmit']) {

	if($_POST['modcfg']['taoke_appsecret'] == '*********') unset($_POST['modcfg']['taoke_appsecret']);
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

	$_G['loader']->helper('form', MOD_FLAG);
	$_G['loader']->helper('form', 'member');
    $modcfg = $C->read_all(MOD_FLAG);
	if($modcfg['taoke_appsecret']) $modcfg['taoke_appsecret'] = '*********';

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>