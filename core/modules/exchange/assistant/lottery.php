<?php
/**
* @author Ðù<service@cmsky.org>
* @copyright (c)2009-2012 ·ç¸ñµêÆÌ
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');
$GL =& $_G['loader']->model(MOD_FLAG.':lottery');
$offset = 20;
$start = get_start($_GET['page'], $offset);
$select = 'l.*';
$where = array('uid'=>$user->uid);
list($total,$list) = $GL->find($select, $where, array('lid'=>'DESC'), $start, $offset, TRUE,'g.name,g.giftid,g.pattern');
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url('exchange/member/ac/lottery/page/_PAGE_'));
}
$tplname = 'gift_lottery';
?>