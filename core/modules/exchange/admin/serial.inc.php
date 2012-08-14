<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$SR =& $_G['loader']->model(MOD_FLAG.':serial');
$op = _input('op');
switch ($op) {
	case 'delete':
		$giftid = _post('giftid',null,MF_INT_KEY);
		if(empty($giftid)) redirect(lang('global_sql_keyid_invalid','giftid'));
		$ids = _post('ids',null);
		$SR->delete($giftid,$ids);
		redirect('global_op_succeed_delete',cpurl($module,$act,'list',array('giftid'=>$giftid)));
		break;
	case 'add':
		$admin->tplname = cptpl('serial_add', MOD_FLAG);
		break;
	case 'save':
		$giftid = _post('giftid',null,MF_INT_KEY);
		$serial = _post('serial',numm,MF_TEXT);
		$SR->save($giftid, $serial);
		redirect('exchangecp_serial_add_succeed',cpurl($module,$act,'list',array('giftid' =>$giftid)));
		break;
	default:
		$giftid = _get('giftid',null,MF_INT_KEY);
		$start = get_start($_GET['page'],$offset=40);
		list($total,$list) = $SR->find($giftid,$start,$offset);
		if($total) {
	        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list'));
	    }
		$admin->tplname = cptpl('serial_list', MOD_FLAG);
		break;
}
?>
