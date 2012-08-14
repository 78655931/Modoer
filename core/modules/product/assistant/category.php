<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$C =& $_G['loader']->model(MOD_FLAG.':category');

switch($op) {
case 'create':
    $catid = $C->create((int)$_POST['sid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo $catid;
        output();
    } else {
        redirect('global_op_succeed', url('product/member/ac/g_product'));
    }
    break;
case 'rename':
	$catid = $C->rename((int)$_POST['catid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo 'OK';
        output();
    } else {
        redirect('global_op_succeed', url('product/member/ac/g_product'));
    }
	break;
case 'delete':
    $catid = (int) $_GET['catid'];
    $C->delete($catid);
    $forward = url('product/member/ac/g_product');
    redirect('global_op_succeed_delete', $forward);
    break;
default:
    $op = 'list';
    $content = '';
    if($list = $C->get_list((int)$_POST['sid'])) {
        foreach($list as $k => $v) {
            $content .= '<option value="'.$k.'">'.$v['name'].'</option>';
        }
        echo $content;
    } else {
        echo 'null';
    }
    output();
}
?>