<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');

if($_POST['dosubmit']) {

    if($_POST['modcfg']['passport_weibo_appsecret'] == '*********') unset($_POST['modcfg']['passport_weibo_appsecret']);
    if($_POST['modcfg']['passport_qq_appkey'] == '*********') unset($_POST['modcfg']['passport_qq_appkey']);
    if($_POST['modcfg']['passport_taobao_appsecret'] == '*********') unset($_POST['modcfg']['passport_taobao_appsecret']);

    $_POST['modcfg']['passport_list'] = $_POST['modcfg']['passport_list'] ? implode(',',$_POST['modcfg']['passport_list']) : '';
	$C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, $act, 'config'));

} else {

	$_G['loader']->helper('form','member');
    $modcfg = $C->read_all(MOD_FLAG);
    $modcfg['passport_list'] = $modcfg['passport_list'] ? explode(',', $modcfg['passport_list']) : array();

    if($modcfg['passport_weibo_appsecret']) $modcfg['passport_weibo_appsecret'] = '*********';
    if($modcfg['passport_qq_appkey']) $modcfg['passport_qq_appkey'] = '*********';
    if($modcfg['passport_taobao_appsecret']) $modcfg['passport_taobao_appsecret'] = '*********';

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>