<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$GB =& $_G['loader']->model(MOD_FLAG.':guestbook');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'delete':
    $GB->delete($_POST['guestbookids'], TRUE, $_POST['delete_point']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
case 'edit':
    if($_POST['dosubmit']) {
        $guestbookid = (int)$_POST['guestbookid'];
        $GB->edit($guestbookid, $_POST['guestbook']['content'], $_POST['guestbook']['reply']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
    } else {
        $guestbookid = (int)$_GET['guestbookid'];
        $detail = $GB->read($guestbookid);
        $admin->tplname = cptpl('guestbook_save', MOD_FLAG);
    }
    break;
default:
    $pid = isset($_GET['pid']) ? $_GET['pid'] : $MOD['pid'];
    (!$pid || !$GB->get_category($pid)) and redirect('item_empty_default_pid');
    $where = array();
    if(!$admin->is_founder) $where['s.city_id'] = $_CITY['aid'];
    if($sid = (int)$_GET['sid']) {
        $where['g.sid'] = $sid;
        $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
    } else {
        $where['pid'] = (int)$pid;
    }
    $where['g.status'] = 1;
    $select = 'g.*,s.city_id,s.pid,s.catid,s.name,s.subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $GB->find($select, $where, array('dateline'=>'DESC'), $start, $offset, TRUE, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));

    $admin->tplname = cptpl('guestbook_list', MOD_FLAG);
 }