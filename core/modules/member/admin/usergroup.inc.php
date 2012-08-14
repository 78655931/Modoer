<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$U =& $_G['loader']->model('member:usergroup');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

switch($op) {
case 'post':
    if(is_array($_POST['groups'])) {
        $U->update($_POST['groups']);
    }
    if($_POST['newgroup']['groupname']) {
        $U->save($_POST['newgroup']);
    }
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'delete':
    if(!is_array($_POST['groupids'])||!$_POST['groupids']) {
        redirect('global_op_unselect');
    }
    $U->delete($_POST['groupids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'edit':
    if(check_submit('dosubmit')) {
        $post = $U->get_post($_POST);
        $U->save($post, $_POST['groupid']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
    } else {
        $detail = $U->read($_GET['groupid']);
        $access =& $detail['access'];
        $admin->tplname = cptpl('usergroup_save', MOD_FLAG);
    }
    break;
default:
    $op = $_GET['op'] = 'list';
    $_GET['type'] = $_GET['type'] ? $_GET['type'] : 'member';
    $list = $U->read_all($_GET['type']);
    $admin->tplname = cptpl('usergroup', MOD_FLAG);
}