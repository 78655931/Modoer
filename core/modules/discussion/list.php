<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'discussion');

$TP = $_G['loader']->model('discussion:topic');
$S =& $_G['loader']->model('item:subject');

$sid=_get('sid',null,MF_INT_KEY);
if(!$subject = $S->read($sid)) redirect('item_empty');

//获取数据
$where = array();
$where['sid'] = $sid;
$where['status'] = 1;
$select = 'tpid,subject,uid,username,replies,replytime,isownerpost,dateline';
$orderby = array('replytime'=>'DESC');
$offset = 20;
$start = get_start($_GET['page'],$offset);
list($total,$list) = $TP->find($select, $where, $orderby, $start, $offset, TRUE);
if($total > 0) {
	$multipage = multi($total, $offset, $_GET['page'], url("discussion/list/sid/$sid/page/_PAGE_"));
}

$modelid = $S->get_modelid($subject['pid']);
$model = $S->variable('model_' . $modelid);
$fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
//侧边栏主题信息
$subject_field_table_tr = $S->display_sidefield($subject);

//面包屑
$urlpath = array();
$urlpath[] = url_path($fullname, url("item/detail/sid/$sid"));
$urlpath[] = url_path(lang('discussion_title'), url("discussion/list/sid/$sid"));

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','discussion');

//取模版
$category = $S->get_category($subject['catid']);
if(!$subject['templateid'] && $category['config']['templateid']>0) {
    $subject['templateid'] = $category['config']['templateid'];
}

//表情
$smilies = array();
for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

if($subject['templateid']) {
	include template('discussion_list', 'item', $subject['templateid']);
} else {
	include template('discussion_list');
}

?>