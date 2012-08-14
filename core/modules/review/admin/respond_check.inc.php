<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(MOD_FLAG.':respond');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
$forward = get_forward(cpurl($module,$act));

switch ($op) {
case 'checkup':
    $R->checkup($_POST['respondids']);
    redirect('global_op_succeed', $forward);
    break;
case 'delete':
    $R->delete($_POST['respondids']);
    redirect('global_op_succeed', $forward);
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
    $where['rp.status'] = 0;
    $select = 'rp.*,r.title,r.sid';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset, TRUE, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));

    $admin->tplname = cptpl('respond_check', MOD_FLAG);
 }