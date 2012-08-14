<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$P =& $_G['loader']->model(MOD_FLAG.':picture');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
switch ($op) {
case 'checkup':
    $P->checkup($_POST['picids']);
    redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
    break;
case 'delete':
    $P->delete($_POST['picids']);
    redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['p.city_id'] = $_CITY['aid'];
    $where['p.status'] = 0;
    $select = 'p.*,s.city_id,s.pid,s.catid,s.name,s.subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $P->find($select, $where, array('addtime'=>'DESC'), $start, $offset, TRUE, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('pactid' => $pactid)));

    $admin->tplname = cptpl('picture_check', MOD_FLAG);
 }