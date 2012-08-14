<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//实例化点评类
$R =& $_G['loader']->model(':review');
define('SCRIPTNAV', 'review');

//载入标签组
//$taggroups = $R->variable('taggroup');

if($type = _get('type',null,MF_TEXT)) {
    if($typeinfo = $R->get_type($type)) {
        $OBJ =& $_G['loader']->model($typeinfo['model_name']);
        $category = $OBJ->get_review_category();
        $pid = _get('pid',null,MF_INT_KEY);
    }
}

//排序
$orderby_arr = array(
    'new' => array('posttime' => 'DESC'),
    'enjoy' => array('best' => 'DESC'),
    'flower' => array('flowers' => 'DESC'),
    'respond' => array('responds' => 'DESC'),
);
$orderby = _cookie('list_display_review_list_orderby','new','_T');
!isset($orderby_arr[$orderby]) && $orderby ='new';

$day = _get('day',30,MF_INT_KEY);
$filter = _get('filter','all',MF_TEXT);
//关键字搜索
$keyword = _get('keyword',null,MF_TEXT);

//取得点评数据
$where = array();
$where['posttime'] = array('where_more',array($_G['timestamp'] - $day * 24 * 3600)); //$day;
if($filter=='digest') $where['digest'] = 1;
if($type) $where['idtype'] = $type;
if($pid) $where['pcatid'] = $pid;
if($filter=='best') $where['best'] = 1;
if($filter=='bad') $where['best'] = 0;
if($filter=='pic') $where['havepic'] = 1;
$where['city_id'] = array(0,$_CITY['aid']);
if($keyword) $where['title'] = array('where_like',array("%$keyword%"));
$where['status'] = 1;
//$this->db->where_more($key, $this->global['timestamp'] - $val * 24 * 3600);
//
$offset = 10;
$start = get_start($_GET['page'], $offset);
$select = 'r.*,m.point,m.point1,m.groupid';
list($total, $list) = $R->find($select, $where, $orderby_arr[$orderby], $start, $offset, 1, 0, 1);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("review/list/type/$type/pid/$pid/day/$day/filter/$filter/uid/$uid/keyword/$keyword/page/_PAGE_"));
}

if($uid > 0) {
    $usergroup = $_G['loader']->variable('usergroup','member');
    $member = $user->read($uid);
}

$active = array();
if($pid) $active['category'][$pid] = ' class="selected"';
$active['day'][$day] = ' class="selected"';
$active['filter'][$filter] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

$orderby_arr_lng = array(
    'new' => lang('review_type_new'),
    'enjoy' => lang('review_type_enjoy'),
    'flower' => lang('review_type_flower'),
    'respond' => lang('review_type_respond'),
);
$day_arr_lng = array(
    '3' => lang('review_day_3'),
    '7' => lang('review_day_7'),
    '30' => lang('review_day_30'),
    '365' => lang('review_day_365'),
);
$filter_arr_lng = array(
    'all' => lang('review_filter_all'),
    'best' => lang('review_filter_best'),
    'bad' => lang('review_filter_bad'),
    'pic' => lang('review_filter_pic'),
    'digest' => lang('review_filter_digest'),
);

$urlpath = array();
$urlpath[] = url_path(lang('review_list_title'), url("review/list"));
if($pid) $urlpath[] = url_path($category[$pid], url("review/list/type/$type/pid/$pid"));
$urlpath[] = url_path($day_arr_lng[$day], url("review/list/type/$type/pid/$pid/day/$day"));
$urlpath[] = url_path($filter_arr_lng[$filter], url("review/list/type/$type/pid/$pid/day/$day/filter/$filter"));
if($keyword) $urlpath[] = url_path($keyword, url("review/list/type/$type/pid/$pid/day/$day/filter/$filter/keyword/$keyword"));

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('review_list');
?>