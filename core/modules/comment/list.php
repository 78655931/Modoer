<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'comment');

$CM =& $_G['loader']->model(':comment');

$idtype = _T($_GET['idtype']);
if(!$CM->check_idtype($idtype)) redirect('comment_idype_unkown');
if(!$id = (int) $_GET['id']) redirect(lang('global_sql_keyid_invalid','id'));
$endpage = $_GET['endpage'] > 0;

$where = array();
$where['idtype'] = $idtype;
$where['id'] = $id;
$where['status'] = 1;
$MOD['listorder'] != 'desc' && $MOD['listorder'] = 'asc';
$orderby = array('dateline'=>$MOD['listorder']);
$offset = $MOD['list_num'] > 0 ? $MOD['list_num'] : 10;
$start = $endpage ? -1 : get_start($_GET['page'], $offset);
list($total, $list) = $CM->find($select, $where, $orderby, $start, $offset, TRUE);
if($endpage) $_GET['page'] = ceil($total/$offset);
$multipage = multi($total, $offset, $_GET['page'], url("comment/list/idtype/$idtype/id/$id/page/_PAGE_"), '', "get_comment('$idtype',$id,{PAGE})");

if(!defined('IN_AJAX')) {
    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("comment/list"));

include template('comment_list');
?>