<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$W =& $_G['loader']->model('word');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

switch($op) {
case 'delete':
    if(empty($_POST['ids'])) redirect('global_op_unselect');
    $W->delete($_POST['ids']);
    redirect('global_op_succeed_delete',cpurl($module,$act,"list"));
    break;
case 'update':
    $W->update($_POST['words']);
    if($_POST['newword']['keyword']) {
        $W->save($_POST['newword']);
        //$_POST['newword']['admin'] = 'admin';
    }
    redirect('global_op_succeed',cpurl($module,$act,"list"));
    break;
default:
    $op = 'list';
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $W->find($start, $offset);    
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, $op));
    $admin->tplname = cptpl('word_list');
}
?>