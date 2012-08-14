<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(':article');

$subtitle = lang('article_title_m_article');
$_G['loader']->helper('misc',MOD_FLAG);

$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = strip_tags(lang('global_status_'.$i));
}
$status = _get('status', 1, MF_INT);
$where = array();
$where['uid'] = $user->uid;
$where['status'] = $status;
$offset = 20;
$start = get_start($_GET['page'], $offset);
list($total,$list) = $A->find('articleid,city_id,subject,catid,comments,pageview,digg,status,dateline',$where,array('dateline'=>'DESC'),$start,$offset,true);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("article/member/ac/$ac/status/$status/page/_PAGE_"));
}
$access_add = $user->check_access('article_post',$A,0);
$access_del = $user->check_access('article_delete',$A,0);
$tplname = 'article_list';
$mymenu = 'menu';
?>