<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
defined('IN_MUDDER') or exit('Access Denied');

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
$rogid = $I->category['review_opt_gid'];

//载入模型
$model = $I->variable('model_' . $modelid);
//载入点评选项
$reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
//分类分级变量1主2子
$category = $I->variable('category');

$urlpath = array();
$urlpath[] = url_path(lang('item_top_title'), url("item/top"));
$urlpath[] = url_path($category[$pid]['name'], url("item/top/catid/$pid"));

$where = array();
if($model['usearea']) {
    $where['city_id'] = (int)$_CITY['aid'];
}
$where['pid'] = (int) $pid;
$where['status'] = 1;

$sort = _get('sort','all',MF_TEXT);
$order_arr = array('all'=>array('avgsort'=>'DESC'));
foreach($reviewpot as $val) {
    $order_arr[$val['flag']] = array($val['flag'] => 'DESC');
    if($val['flag'] == $sort) {
        $urlpath[] = url_path($val['name'], url("item/top/catid/$catid/sort/all"));
    }
}

if(!isset($order_arr[$sort])) $sort = 'all';
if($sort == 'all') {
    $urlpath[] = url_path(lang('item_top_title_all'), url("item/top/catid/$catid/sort/all"));
}

$offset = 100;
$start = 0;

$select = 's.sid,pid,catid,name,subname,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,pageviews,reviews,pictures,thumb';
list($total, $list) = $I->find($select, $where, $order_arr[$sort], $start, $offset, TRUE, $pid);
if($total = min($total, 100)) {
    $multipage = multi($total, $offset, $_GET['page'], url("item/top/catid/$pid/sort/$sort"));
}

$active = array();
$active['sort'][$sort] = ' class="selected"';
$active['day'][$day] = ' class="selected"';

$_HEAD['keywords'] = $catcfg['meta_keywords'];
$_HEAD['description'] = $catcfg['meta_description'];
include template('item_top');
?>