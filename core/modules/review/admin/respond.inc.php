<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(MOD_FLAG.':respond');
$op = _input('op');
$forward = get_forward(cpurl($module,$act));

switch ($op) {
     case 'delete':
        $R->delete($_POST['respondids'], TRUE, $_POST['delete_point']);
        redirect('global_op_succeed', $forward );
        break;
    case 'checkup':
        $R->checkup($_POST['respondids']);
        redirect('global_op_succeed', $forward);
        break;
    case 'checklist':
        $where = array();
        if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
        $where['rp.status'] = 0;
        $select = 'rp.*,r.title,r.id,r.idtype';
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset, TRUE, TRUE);
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
        $admin->tplname = cptpl('respond_check', MOD_FLAG);
        break;
    default:
        $where = array();
        if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
        if($rid = (int)$_GET['rid']) {
            $where['rp.rid'] = $rid;
        }
        $where['rp.status'] = 1;
        $select = 'rp.*,r.title,r.id,r.idtype';
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset, TRUE, TRUE);
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
        $admin->tplname = cptpl('respond_list', MOD_FLAG);
 }