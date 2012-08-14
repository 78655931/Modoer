<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'assistant');
require_once MUDDER_MODULE.'member'.DS.'assistant'.DS.'menu.php';

if(!$user->isLogin) {
    if($in_ajax) {
        $forward = base64_encode($_G['web']['referer']);
        dialog(lang('global_op_title'), '', 'login');
    } else {
        $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : get_url('modoer','','','',1);
        header('Location:' . get_url('member') . 'login.php?forward=' . base64_encode($forward));
        exit;
        //redirect('member_op_not_login', get_url('member').'login.php?forward='.rawurlencode($forward));
    }
}

$allowacs = array('log','pay','finish');

$ac = !in_array($_GET['ac'],$allowacs) ? header("Location:" . url("member/index")) : $_GET['ac'];

$tplname = '';

$scriptname = MOD_ROOT . 'assistant' . DS . $ac . '.php';
if(!is_file($scriptname)) show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, DS, $scriptname)));
require_once MOD_ROOT . 'assistant' . DS . $ac . '.php';

if(empty($tplname)) $tplname = $ac;
require template($tplname, 'member', MOD_FLAG);
?>