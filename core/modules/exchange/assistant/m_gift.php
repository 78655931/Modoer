<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$GT =& $_G['loader']->model(MOD_FLAG.':gift');
$EX =& $_G['loader']->model(MOD_FLAG.':exchange');

if($exchangeid=(int)$_GET['exchangeid']) {
    $op = 'detail';
}

$status_name = array();
for($i=1;$i<=4;$i++) {
    $status_name[$i] = lang('exchange_status_'.$i);
}

switch($op) {
case 'detail':
    if(!$detail = $EX->read($exchangeid)) redirect('exchange_empty');
	$gift = $GT->read($detail['giftid']);
	if($gift['sort']=='2') {
		$serial = $_G['loader']->model('exchange:serial')->getlist($exchangeid,$user->uid);
	}
    $tplname = 'gift_detail';
    break;
default:
    if(!$status=(int)$_GET['status']) $status = 1;
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    $select = 'exchangeid,giftid,giftname,price,pointtype,number,status,exchangetime';
    $where = array('uid'=>$user->uid, 'status'=>$status);
    list($total,$list) = $EX->find($select, $where, array('exchangetime'=>'DESC'), $start, $offset, TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], url('exchange/member/ac/m_gift/status/'.$status.'/page/_PAGE_'));
    }
    $status_group = $EX->status_total($user->uid);
    $tplname = 'gift_list';
}
?>