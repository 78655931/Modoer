<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(MOD_FLAG.':apply');

//$subtitle = lang('article_title_m_article');
//$_G['loader']->helper('misc',MOD_FLAG);

$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = strip_tags(lang('global_status_'.$i));
}
$status = (int) $_GET['status'] ? $_GET['status'] : 0;
$where = array();
$where['uid'] = $user->uid;
$where['status'] = $status;
$offset = 20;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $A->find('*',$where,array('dateline'=>'DESC'),$start,$offset,true);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("card/member/ac/$ac/status/$status/page/_PAGE_"));
}

$access_apply = $user->check_access('card_apply',$A,0);
$status_array = lang('card_status_array');
$status_group = $A->status_total($user->uid);

$tplname = 'apply_list';
?>