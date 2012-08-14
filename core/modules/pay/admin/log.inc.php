<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$LOG =& $_G['loader']->model('pay:log');

if(check_submit('dosubmit')) {
    $LOG->delete(_post('orderids'));
    redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
} else {
    $LOG->update_status(); //订单过期判断错误，未修正
    $status = _get('status', 0, 'intval');

    $where = array();
    $where['status'] = $status;
    $orderby = $status == 1 ? array('exchangetime'=>'DESC') : array('dateline'=>'DESC');
    $select = '*';
    $offset = 40;
    $start = get_start($_GET['page'], $offset);
    list($total,$list) = $LOG->find($select, $where, $orderby, $start, $offset, true);
    if($total) $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list',array('status'=>$status)));

    $admin->tplname = cptpl('log_list', MOD_FLAG);
}
?>