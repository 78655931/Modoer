<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and redirect('item_empty_default_pid');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$I->get_category($catid);
if(!$pid = $I->category['catid']) {
    redirect('item_cat_empty');
}
define('SCRIPTNAV', 'item_'.$pid);

//载入配置信息
$catcfg =& $I->category['config'];
$modelid = $I->category['modelid'];
//载入模型
$model = $I->variable('model_' . $modelid);
//分类分级变量1主2子
$category = $I->variable('category');

$urlpath = array();
$urlpath[] = url_path(lang('item_allpic_title'), url("item/allpic"));
$urlpath[] = url_path($category[$pid]['name'], url("item/allpic/catid/$pid"));

$where = array();
if($model['usearea']) {
    $where['city_id'] = (int)$_CITY['aid'];
}
$where['pid'] = (int) $pid;
$where['status'] = 1;

$sort = 'all';
$order_arr = array('all'=>array('avgsort'=>'DESC'));

$offset = 30;
$start = get_start($_GET['page'], $offset);

list($total, $list) = $I->find_picture($where, $order_arr[$order], $start, $offset, TRUE);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("item/allpic/catid/$pid/page/_PAGE_"));
}

$active = array();
$active[$sort] = ' class="selected"';

$_HEAD['keywords'] = $catcfg['meta_keywords'];
$_HEAD['description'] = $catcfg['meta_description'];
include template('item_allpic');