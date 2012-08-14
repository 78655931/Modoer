<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$LOG =& $_G['loader']->model(MOD_FLAG.':subjectlog');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'delete':
    $LOG->delete($_POST['upids']);
    $forward = get_forward();
    redirect('global_op_succeed', $forward ? $forward : cpurl($module,$act));
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
    $where['disposal'] = (int) $_GET['disposal'];
    $select = 'l.*,s.pid,catid,name,subname';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $LOG->find($select, $where, $start, $offset);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('disposal' => $_GET['disposal'])));

    $admin->tplname = cptpl('subject_log', MOD_FLAG);
 }