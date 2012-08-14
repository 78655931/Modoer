<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'manage');
require_once MOD_ROOT . 'manage' . DS . 'menu.php';

if(!$user->isLogin) {
    if($_G['in_ajax']) {
        $forward = base64_encode($_G['web']['referer']);
        dialog(lang('global_op_title'), '', 'login');
    } else {
        $forward = $_G['web']['reuri'] ? $_G['web']['url'] . $_G['web']['reuri'] : url('member/index', 0, 1);
        location(url('member/login/forward/'.base64_encode($forward)));
    }
}

$allowacs = array('subject','review','album','guestbook');
$ac = !in_array($_GET['ac'], $allowacs) ? 'subject' : $_GET['ac'];
$tplname = $ac;
$_HEAD['title'] = lang('member_operation_title');
require_once(MOD_ROOT . 'manage' . DS . $ac . '.php');
require_once(template($tplname, 'manage', MOD_FLAG));
?>