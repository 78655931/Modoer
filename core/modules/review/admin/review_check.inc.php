<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(MOD_FLAG.':review');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
$forward = get_forward(cpurl($module,$act));

switch ($op) {
case 'checkup':
    $R->checkup($_POST['rids']);
    redirect('global_op_succeed', $forward);
    break;
case 'delete':
    $R->delete($_POST['rids']);
    redirect('global_op_succeed', $forward);
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
    $where['r.status'] = 0;
    $select = 'r.rid,r.sid,r.uid,r.username,r.posttime,r.flowers,r.responds,r.status,r.content,s.city_id,s.pid,s.catid,s.name,s.subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $R->find($select, $where, array('addtime'=>'DESC'), $start, $offset, TRUE, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));

    $admin->tplname = cptpl('review_check', MOD_FLAG);
 }