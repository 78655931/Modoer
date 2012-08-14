<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

$CO = $_G['loader']->model(':coupon');

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("coupon/index"));

$where = array();
if(is_numeric($_GET['sid']) && $_GET['sid'] > 0) {
    $sid = (int) $_GET['sid'];
    $I =& $_G['loader']->model('item:subject');
    //取得主题信息
    if(!$subject = $I->read($sid)) redirect('item_empty');
    $subject_field_table_tr = $I->display_sidefield($subject);
    $where['c.sid'] = $sid;
    $urlpath[] = url_path(trim($subject['name'].$subject['subname']), url("coupon/list/sid/$sid"));
    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','coupon');
}
$category = $_G['loader']->variable('category',MOD_FLAG);
if(is_numeric($_GET['catid']) && $_GET['catid'] > 0) {
    $catid = (int) $_GET['catid'];
    $where['c.catid'] = $catid;
    $urlpath[] = url_path($category[$catid][name], url("coupon/list/catid/$catid"));
}
$where['c.status'] = 1;
$offset = $MOD['listnum'] > 0 ? $MOD['listnum'] : 10;
$start = get_start($_GET['page'], $offset);
$select = 'c.couponid,c.catid,c.sid,c.thumb,c.subject,c.starttime,c.endtime,c.des,c.comments,c.pageview,c.effect1';
$orders = array('effect1'=>'DESC','pageview'=>'DESC','dateline'=>'DESC','comments'=>'DESC');
$_GET['order'] = isset($_GET['order']) && in_array($_GET['order'],array_keys($orders)) ? $_GET['order'] : 'dateline';
list($total,$list) = $CO->find($select,$where,array('c.'.$_GET['order']=>$orders[$_GET['order']]),$start,$offset,TRUE,'s.name,s.subname,s.reviews');
if($total) $multipage = multi($total, $offset, $_GET['page'], url('coupon/list/sid/'.$sid.'/catid/'.$catid.'/order/'.$_GET['order'].'/page/_PAGE_'));

$active = array();
$catid>0 && $active['catid'][$catid] = ' class="selected"';
$_GET['order'] && $active['order'][$_GET['order']] = ' class="selected"';

$_HEAD['keywords'] = ($subject ? ($subject[name]  . ',' . $subject[subname] . ',') : '') . $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('coupon_list', 'item', $subject['templateid']);
} else {
    include template('coupon_list');
}
?>