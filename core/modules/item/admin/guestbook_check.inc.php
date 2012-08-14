<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$GB =& $_G['loader']->model(MOD_FLAG.':guestbook');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
$forward = get_forward(cpurl($module,$act));

switch ($op) {
case 'delete':
    $GB->delete($_POST['guestbookids'], TRUE, $_POST['delete_point']);
    redirect('global_op_succeed', $forward);
    break;
case 'checkup':
    $GB->checkup($_POST['guestbookids']);
    redirect('global_op_succeed', $forward);
    break;
case 'edit':
    if($_POST['dosubmit']) {
        $guestbookid = (int)$_POST['guestbookid'];
        $GB->save($_POST['guestbook'], $guestbookid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
    } else {
        $guestbookid = (int)$_GET['guestbookid'];
        $detail = $GB->read($guestbookid);
        $admin->tplname = cptpl('guestbook_save', MOD_FLAG);
    }
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['s.city_id'] = $_CITY['aid'];
    if($sid = (int)$_GET['sid']) {
        $where['g.sid'] = $sid;
    }
    $pid = isset($_GET['pid']) ? $_GET['pid'] : 0;
    $pid && $where['s.pid'] = $pid;
    $where['g.status'] = 0;
    $select = 'g.*,s.city_id,s.pid,s.catid,s.name,s.subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $GB->find($select, $where, array('dateline'=>'DESC'), $start, $offset, TRUE, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));

    $admin->tplname = cptpl('guestbook_check', MOD_FLAG);
 }