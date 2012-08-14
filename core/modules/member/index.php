<?php
/**
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'assistant');
require_once MOD_ROOT . 'assistant' . DS . 'menu.php';

if($_G['menu_mapping'] && !$_G['in_ajax']) {
    $src_menu_url = "$MOD[flag]/index/ac/$_GET[ac]";
    $dst_menu_url = '';
    foreach($_G['menu_mapping'] as $re) if($re['src']==$src_menu_url) $dst_menu_url = $re['dst'];
    if($dst_menu_url) location(url($dst_menu_url));
}

if(!$user->isLogin) {
    if($_G['in_ajax']) {
        $forward = base64_encode($_G['web']['referer']);
        dialog(lang('global_op_title'), '', 'login');
    } else {
        $forward = $_G['web']['reuri'] ? $_G['web']['url'] . $_G['web']['reuri'] : url('member/index', 0, 1);
        location(url('member/login/forward/'.base64_encode($forward)));
    }
}

$allowacs = array('main', 'pm', 'friend', 'myset', 'face', 'point', 'task', 'group', 'notice', 'passport', 'feed', 'address', 'follow');
$ac = !in_array($_GET['ac'], $allowacs) ? 'main' : $_GET['ac'];
$tplname = $ac;
$_HEAD['title'] = lang('member_operation_title');
require_once(MOD_ROOT . DS . 'assistant' . DS . $ac . '.php');
require_once(template($tplname, 'member', MOD_FLAG));
?>