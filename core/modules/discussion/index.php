<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'discussion');

$TP =& $_G['loader']->model('discussion:topic');
//获取数据
$where = array();
$where['s.city_id'] = array(0,$_CITY['aid']);
$where['tp.status'] = 1;
$select = 'tp.tpid,tp.subject,tp.uid,tp.username,tp.replies,tp.replytime,tp.isownerpost,tp.dateline,s.sid,
	s.name as subjectname,s.subname';
$orderby = array('tp.replytime'=>'DESC');

$TP->db->join($TP->table,'tp.sid','dbpre_subject','s.sid');
$TP->db->where($where);
$total = $TP->db->count();
if($total > 0) {
	$TP->db->sql_roll_back('from,where');
	$TP->db->select($select);
	$TP->db->order_by($orderby);
	$offset = 30;
	$start = get_start($_GET['page'],$offset);
	$list = $TP->db->get();
	if($total > $offset) {
		$multipage = multi($total, $offset, $_GET['page'], url("discussion/index/page/_PAGE_"));
	}
}
$_HEAD['title'] = $MOD['title']?$MOD['title']:lang('discussion_title');
$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('discussion_index');