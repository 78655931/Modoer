<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$offset = 20;
$start = get_start($_GET['page'], $offset);

$op = _input('op','pay');
if($op == 'card') {
    $PC =& $_G['loader']->model('pay:card');
    $where = array();
    $where['uid'] = $user->uid;
    $orderby = array('dateline'=>'DESC');
    list($total, $list) = $PC->find('*', $where, $orderby, $start, $offset, TRUE);
} else {
    $op = 'pay';
    $PL =& $_G['loader']->model('pay:log');
    $PL->update_status($user->uid);
    $where = array();
    $where['uid'] = $user->uid;
    $orderby = array('dateline'=>'DESC');
    list($total, $list) = $PL->find('*', $where, $orderby, $start, $offset, TRUE);
}
if($total) $multipage = multi($total, $offset, $_GET['page'], url("pay/member/ac/$ac/op/$op/page/_PAGE_"));
?>