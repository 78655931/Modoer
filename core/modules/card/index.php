<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'card');

$category = $_G['loader']->variable('category','item');
if(isset($_GET['catid'])) {
    $catid = (int) $_GET['catid'];
}

$DC =& $_G['loader']->model('card:discount');

$where = array();
$where['city_id'] = array(0,$_CITY['aid']);
if($catid>0) $where['pid'] = $catid;
$where['available'] = 1;

$offset = $MOD['offset'] > 0 ? $MOD['offset'] : 10;
$start = get_start($_GET['page'], $offset);
$orderby = array('c.addtime'=>'DESC');

$select = 'c.*';
$subject_select = 's.city_id,s.catid,s.name,s.subname,s.thumb,s.pageviews,s.reviews,s.pictures';

list($total,$list) = $DC->find($select,$where,$orderby,$start,$offset,TRUE,$subject_select);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("card/index/catid/$catid/page/_PAGE_"));
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("card/index"));
if($catid>0 && isset($category[$catid])) {
    $urlpath[] = url_path($category[$catid]['name'], url("card/index/catid/$catid"));
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('card_index');
?>