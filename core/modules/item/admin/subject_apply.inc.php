<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(MOD_FLAG.':subjectapply');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'check':
    if($_POST['dosubmit']) {
        $A->check($_POST['apply'], $_POST['applyid']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
    } else {
        if(!$applyid = (int) $_GET['applyid']) redirect(lang('global_sql_keyid_invalid','applyid'));
        if(!$detail = $A->read($applyid)) redirect('item_apply_empty');
        $A->get_category($detail['pid']);
        $catcfg = $A->category['config'];
        $admin->tplname = cptpl('subject_apply_detail', MOD_FLAG);
    }
    break;
case 'delete':
    $A->delete($_POST['applyids']);
    redirect('global_op_succeed', get_forward(url($module, $act)));
    break;
default:
    $pid = isset($_GET['pid']) ? $_GET['pid'] : $MOD['pid'];
    (!$pid || !$A->get_category($pid)) and redirect('item_empty_default_pid');
    $where = array();
    if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
    $where['a.status'] = (int) $_GET['status'];
    $select = 'a.*,city_id,catid,name,subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $A->find($select, $where, $start, $offset);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('status' => $_GET['status'])));
    }
    $admin->tplname = cptpl('subject_apply', MOD_FLAG);
 }