<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'discussion');

$op = _input('op',null,MF_TEXT);
if($op == 'reload') {
	$rpid = _input('rpid',null,MF_INT_KEY);
	$reply = $_G['loader']->model('discussion:reply')->read($rpid);
	if($reply) {
		$_G['loader']->helper('msubb');
	}
	echo msubb::pares($reply['content']);
	output();
}

$tpid=_get('id',null,MF_INT_KEY);
$TP = $_G['loader']->model('discussion:topic');
$topic = $TP->read($tpid);
if(empty($topic)) redirect('discussion_topic_empty');
if(!$topic['status'] && $topic['uid'] != $user->uid) redirect('discussion_topic_not_audit');

$sid = $topic['sid'];
$S = $_G['loader']->model('item:subject');
if(!$subject = $S->read($sid)) redirect('item_empty');

//获取话题回应
$where = array();
$where['tpid'] = $tpid;
$where['status'] = 1;
$orderby = 'dateline';
$select = '*';
$offset = 40;
$start = get_start($_GET['page'],$offset);

$RP = $_G['loader']->model('discussion:reply');
list($total,$list) = $RP->find($select,$where,$orderby,$start,$offset);

$modelid = $S->get_modelid($subject['pid']);
$model = $S->variable('model_' . $modelid);
$fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
$subject_field_table_tr = $S->display_sidefield($subject);

//面包屑
$urlpath = array();
$urlpath[] = url_path($fullname, url("item/detail/sid/$sid"));
$urlpath[] = url_path(lang('discussion_title'), url("discussion/list/sid/$sid"));
if($tpid>0) $urlpath[] = url_path($topic['subject'], url("discussion/topic/id/$tpid"));

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

$_G['loader']->helper('msubb');

if($subject['templateid']) {
	include template('discussion_topic', 'item', $subject['templateid']);
} else {
	include template('discussion_topic');
}

?>