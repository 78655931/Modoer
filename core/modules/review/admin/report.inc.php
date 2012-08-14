<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(MOD_FLAG.':report');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'delete':
    $R->delete($_POST['reportids']);
    $forward = get_forward();
    redirect('global_op_succeed', $forward ? $forward : cpurl($module,$act));
    break;
default:
    $where = array();
    if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
    $where['disposal'] = (int) $_GET['disposal'];
    $select = 'rt.*,r.title,r.id,r.idtype,r.subject';
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $R->find($select, $where, $start, $offset);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('disposal' => $_GET['disposal'])));

    $admin->tplname = cptpl('report', MOD_FLAG);
 }