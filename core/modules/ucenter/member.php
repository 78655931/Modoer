<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'assistant');
require MUDDER_MODULE.'member'.DS.'assistant'.DS.'menu.php';

$allowacs = array('credits','pm');

$ac = !in_array($_GET['ac'], $allowacs) ? header("Location:" . url("member/index")) : $_GET['ac'];
if(!$user->isLogin && !in_array($ac, $guestacs)) {
    if(defined('IN_AJAX')) {
        $forward = base64_encode($_G['web']['referer']);
        dialog(lang('global_op_title'), '', 'login');
    } else {
        $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : url('modoer','',1);
        header('Location:' . url('member/login/forward/'.base64_encode($forward)));
        exit;
    }
}

$tplname = '';

$scriptname = MOD_ROOT . 'assistant' . DS . $ac . '.php';
if(!is_file($scriptname)) show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, DS, $scriptname)));
require_once MOD_ROOT . 'assistant' . DS . $ac . '.php';

if(empty($tplname)) $tplname = $ac;
$_HEAD['title'] = lang('member_operation_title');
require_once template($tplname, 'member', MOD_FLAG);
?>