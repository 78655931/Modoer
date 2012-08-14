<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$CO =& $_G['loader']->model(':coupon');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];

$_G['loader']->helper('form', MOD_FLAG);
$_G['loader']->helper('form', 'item');

switch($op) {
    case 'add':
        $tplname = 'coupon_save';
        $detail = array();
        $detail['starttime'] = $_G['timestamp'];
        break;
    case 'edit':
        $couponid = (int) $_GET['couponid'];
        if(!$detail=$CO->read($couponid)) redirect('coupon_empty');
        if($detail['sid']>0) {
            $S =& $_G['loader']->model('item:subject');
            $subject = $S->read($detail['sid'],'*',false);
        }
        $tplname = 'coupon_save';
        break;
    case 'save':
        if($_POST['do']=='edit') {
            $couponid = (int) $_POST['couponid'];
        } else {
            if($MOD['seccode']) check_seccode($_POST['seccode']);
            $couponid = null;
        }
        $post = $CO->get_post($_POST);
        $couponid = $CO->save($post,$couponid,$_POST['role']);
        $next_ac = $_POST['role'] == 'owner' ? 'g_coupon' : 'm_coupon';
        redirect('global_op_succeed', get_forward(url('coupon/member/ac/'.$next_ac),1));
        break;
    case 'delete':
        $CO->delete($_POST['couponids']);
        $next_ac = $_POST['do'] == 'g_coupon' ? 'g_coupon' : 'm_coupon';
        redirect('global_op_succeed_delete', get_forward(url('coupon/member/ac/'.$next_ac)));
        break;
    default:
        redirect('global_op_unkown');
}
?>