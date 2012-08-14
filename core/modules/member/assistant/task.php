<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$T =& $_G['loader']->model('member:task');

switch($op) {
case 'view':
    $taskid = _get('taskid', null, MF_INT_KEY);
    if(!$detail = $T->read($taskid)) redirect('member_task_empty');
    if($mytask = $T->read_mytask($taskid)) {
        $progress = $T->check_progress($detail);
        if($progress != $mytask['progress']) {
            $T->update_progress($taskid,$progress);
            $mytask['progress'] = $progress;
        }
    } else {
        $access = $T->check_access($detail);
    }
    $tplname = 'task_view';
    break;
case 'apply':
    $taskid = _get('taskid',null,MF_INT_KEY);
    $T->apply($taskid);
    redirect('member_task_apply_succeed',url("member/index/ac/task/op/view/taskid/$taskid"));
    break;
case 'delete':
    $T->cancel_mytask(_get('taskid', null, MF_INT_KEY));
    redirect('member_task_delete_succeed',url("member/index/ac/task"));
    break;
case 'finish':
    $T->finish_task(_get('taskid', null, MF_INT_KEY));
    redirect('member_task_finish_succeed',url("member/index/ac/task/op/done"));
    break;
case 'doing':
    $list = $T->mytask(0);
    break;
case 'done':
    $list = $T->mytask(1);
    break;
case 'failed':
    $list = $T->mytask(-1);
    break;
default:
    $op = 'new';
    $list = $T->newtask();
}

function numtoweek($w,$normal=true) {
    if(!$normal) $w==7 && $w=0;
    $array = array('周日','周一','周二','周三','周四','周五','周六');
    return $array[$w];
}

function nextapplytime($time) {
    global $_G;
    //if($time > 7 * 24 * 3600) {
        return date('Y-n-j G:i', $_G['timestamp'] + $time);
    //} else {
        
    //}
}
?>