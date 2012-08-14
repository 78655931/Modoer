<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$RO =& $_G['loader']->model(MOD_FLAG.':opt_group');

$op = _input('op');
switch($op) {
case 'add':
    $admin->tplname = cptpl('opt_group', MOD_FLAG);
    break;
case 'edit':
    $gid = (int) $_GET['gid'];
    if(!$detail = $RO->read($gid)) redirect('global_op_empty');
    $admin->tplname = cptpl('opt_group', MOD_FLAG);
    break;
case 'save':
    $post = $RO->get_post($_POST);
    $RO->save($post, $_POST['gid']);
    redirect('global_op_succeed', cpurl($module,$act));
    break;
case 'delete':
    $RO->delete(_get('gid'));
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    list(,$list) = $RO->read_all();
    $admin->tplname = cptpl('opt_group', MOD_FLAG);
}
?>