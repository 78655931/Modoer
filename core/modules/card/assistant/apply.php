<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(MOD_FLAG.':apply');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];

switch($op) {
case 'detail':
    $applyid = (int) $_GET['applyid'];
    if(!$detail = $A->read($applyid)) redirect('card_apply_empty');
    $status_array = lang('card_status_array');
    $tplname = 'apply_detail';
    break;
case 'apply':
    if($MOD['applyseccode']) check_seccode($_POST['seccode']); //验证码
    $post = $A->get_post($_POST);
    $A->save($post);
    redirect('card_apply_succeed', url('card/member/ac/m_apply'));
    break;
default:
    $user->check_access('card_apply', $A); //检测是否可申请
    $A->check_apply_exists($user->uid); //是否已申请
    $tplname = 'apply';
}
?>