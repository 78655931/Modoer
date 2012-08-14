<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$CO =& $_G['loader']->model(':coupon');
$S =& $_G['loader']->model('item:subject');

$subtitle = lang('coupon_title_g_coupon');
$role = 'owner';

$mysubjects = $S->mysubject($user->uid);

$status = (int) $_GET['status'] ? $_GET['status'] : 0;
$where = array();
$where['c.sid'] = $mysubjects;
$where['c.status'] = $status;
$offset = 40;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $CO->find('c.couponid,c.catid,c.sid,c.subject,c.starttime,c.endtime,c.des,c.status',$where,array('c.endtime'=>'DESC'),$start,$offset,TRUE,'s.name,s.subname');
if($total) $multipage = multi($total, $offset, $_GET['page'], url('coupon/member/ac/g_coupon/status/'.$status.'/page/_PAGE_'));

$status_group = $CO->status_total(null,$mysubjects);
$access_add = count($mysubjects)>0 && $MOD['post_item_owner'];
$access_del = TRUE;

$status_array = array();
for($i=0;$i<=2;$i++) {
    $status_array[$i] = strip_tags(lang('coupon_status_'.$i));
}

$tplname = 'coupon_list';
?>