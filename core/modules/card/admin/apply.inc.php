<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(MOD_FLAG.':apply');
$op = _input('op',null,MF_TEXT);
$status_array = lang('card_status_array');
$config = $_G['loader']->variable('config','member');
$point_group = $config['point_group'] ? unserialize($config['point_group']) : array();

switch($op) {
    case 'delete':
        $A->delete($_POST['applyids']);
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        if($_POST['dosubmit']) {
            $A->checkup($_POST['applyid'],$_POST['status'],$_POST['checkmsg']);
            redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        } else {
            $applyid = (int) $_GET['applyid'];
            if(!$detail = $A->read($applyid)) redirect('card_apply_empty');
            $admin->tplname = cptpl('apply_checkup', MOD_FLAG);
        }
        break;
    default:
        $op = 'list';
        $_GET['status'] = (int) $_GET['status'];
        $offset = 20;
        $start = get_start($_GET['page'],$offset);
        $select = 'applyid,uid,username,mobile,linkman,num,coin,dateline,status';
        $where = array('status'=>$_GET['status']);
        list($total,$list) = $A->find($select,$where,array('dateline'=>'DESC'),$start,$offset,TRUE);
        if($total) {
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,$op,$_GET));
        }
        $status_group = $A->status_total();
        $admin->tplname = cptpl('apply_list', MOD_FLAG);
}
?>