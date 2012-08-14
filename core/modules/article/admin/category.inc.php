<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('article:category');
$op = _input('op');
$_G['loader']->helper('form',MOD_FLAG);

switch($op) {
case 'move':
    $C->move($_POST['catids'],$_POST['move_pid']);
    redirect('global_op_succeed', cpurl($module,$act,'list',array('pid'=>$_POST['move_pid'])));
    break;
case 'delete':
    $catids = isset($_POST['catids']) ? $_POST['catids'] : $_GET['catids'];
    $C->delete($catids);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'update':
    if($_POST['category']) {
        $C->update($_POST['category']);
    }
    if($_POST['newcategory']['name']) {
        $C->save($_POST['newcategory']);
    } else {
        $C->write_cache();
    }
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'rebuild':
    $C->rebuild($_POST['catids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
default:
    $op = 'list';
    $pid = (int) $_GET['pid'];
    $where = array();
    $where['pid'] = $pid;
    $list = $C->find($where);
    if($pid) if(!$cate = $C->read($pid)) redirect('article_category_empty');
    $admin->tplname = cptpl('category_list', MOD_FLAG);
}
?>