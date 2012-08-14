<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(':article');
$S =& $_G['loader']->model('item:subject');

$subtitle = lang('article_title_g_article');
$role = 'owner';
$_G['loader']->helper('misc',MOD_FLAG);

$mysubjects = $S->mysubject($user->uid);

$status_name = array();
for($i=0;$i<=1;$i++) {
    $status_name[$i] = strip_tags(lang('global_status_'.$i));
}
$status = _get('status', 1, MF_INT);
$offset = 20;
$start = get_start($_GET['page'], $offset);
$A->db->join('dbpre_subjectlink', 'sl.flagid', $A->table, 'a.articleid','LEFT JOIN');
$A->db->where('sl.sid', $mysubjects);
$A->db->where('sl.flag', 'article');
$A->db->where('status', $status);
$total = $A->db->count();
if($total > 0) {
    $A->db->sql_roll_back('from,where');
    $A->db->select('articleid,city_id,subject,catid,comments,pageview,digg,status,a.dateline');
    $A->db->group_by('sl.flagid');
    $A->db->order_by('sl.dateline','DESC');
    $A->db->limit($start, $offset);
    $list = $A->db->get();
}
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("article/member/ac/$ac/status/$status/page/_PAGE_"));
}
//$status_group = $A->status_total($user->uid);
$access_add = count($mysubjects)>0;
$access_del = $access_add;
$tplname = 'article_list';
?>