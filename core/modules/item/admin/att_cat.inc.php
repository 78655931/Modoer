<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$AT =& $_G['loader']->model(MOD_FLAG.':att_cat');

switch($_GET['op']) {
case 'add':
    $admin->tplname = cptpl('att_cat', MOD_FLAG);
    break;
case 'edit':
    $catid = (int) $_GET['catid'];
    if(!$detail = $AT->read($catid)) redirect('global_op_empty');
    $admin->tplname = cptpl('att_cat', MOD_FLAG);
    break;
case 'save':
    $post = $AT->get_post($_POST);
    $AT->save($post, $_POST['catid']);
    redirect('global_op_succeed', cpurl($module,$act));
    break;
case 'delete':
    $AT->delete(_get('catid'));
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    list(,$list) = $AT->read_all();
    $admin->tplname = cptpl('att_cat', MOD_FLAG);
    break;
}
?>