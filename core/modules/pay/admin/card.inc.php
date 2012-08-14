<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$PC =& $_G['loader']->model('pay:card');
$op = _input('op','list');

$_G['loader']->helper('form','pay');

switch($op) {
case 'create':
    if(check_submit('createsubmit')) {
        $PC->batch_create(_post('create'));
        redirect('pay_card_create_succeed', cpurl($module,$act));
    } else {
        $cz_enable = true;
        if(!$cz_type = @unserialize($MOD['cz_type'])) {
            $cz_enable = false;
        }
        if(!$cz_enable) redirect('pay_payment_empty');
        $admin->tplname = cptpl('card_create', MOD_FLAG);
    }
    break;
case 'export':
    $status = $_GET['status'] = _get('status',1,'intval');
    $status < 1 && $status = 1;
    $where = array();
    $where['status'] = $status;
    $PC->export($where);
    break;
case 'delete':
    $PC->delete($_POST['cardids']);
    redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)),1);
    break;
default:
    $PC->update_status();

    $status = $_GET['status'] = _get('status',1,'intval');
    $status < 1 && $status = 1;

    $where = array();
    $where['status'] = $status;
    $orderby = $status != 2 ? array('dateline'=>'DESC') : array('usetime'=>'DESC');
    $select = '*';
    $offset = 40;
    $start = get_start($_GET['page'], $offset);
    list($total,$list) = $PC->find($select, $where, $orderby, $start, $offset, true);
    if($total) $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list',array('status'=>$status)));

    $admin->tplname = cptpl('card_list', MOD_FLAG);
}
?>