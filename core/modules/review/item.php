<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'review');
//实例化点评类
$R =& $_G['loader']->model(':review');
$S =& $_G['loader']->model('item:subject');
if($sid = _get('sid', null, MF_INT_KEY)) {
    $subject = $S->read($sid);
    if(empty($subject)) redirect('item_empty');
    $fullname = $subject['name'] . ($subject['subname']?"($subject[subname])":'');
    $subject_field_table_tr = $S->display_sidefield($subject);
}

$filter = _get('filter','all',MF_TEXT);
//排序
$orderby_arr = array(
    'new' => array('posttime' => 'DESC'),
    'enjoy' => array('best' => 'DESC'),
    'flower' => array('flowers' => 'DESC'),
    'respond' => array('responds' => 'DESC'),
);
$orderby = _cookie('list_display_review_list_orderby','new','_T');
!isset($orderby_arr[$orderby]) && $orderby ='new';

$modelid = $S->get_modelid($subject['pid']);
$model = $S->variable('model_' . $modelid);

//取得点评数据
$where = array();
$where['id'] = $sid;
$where['idtype'] = 'item_subject';
$where['status'] = 1;

$offset = 10;
$start = get_start($_GET['page'], $offset);
$select = 'r.*,m.point,m.point1,m.groupid';
list($total, $list) = $R->find($select, $where, $orderby_arr[$orderby], $start, $offset, TRUE, FALSE, TRUE);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("review/item/sid/$sid/filter/$filter/page/_PAGE_"));
}

//点评行为检测
$review_access = $S->review_access($subject);
$review_enable = $review_access['code'] == 1;

$active = array();
$active['filter'][$filter] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

$orderby_arr_lng = array(
    'new' => lang('review_type_new'),
    'enjoy' => lang('review_type_enjoy'),
    'flower' => lang('review_type_flower'),
    'respond' => lang('review_type_respond'),
);

$filter_arr_lng = array(
    'all' => lang('review_filter_all'),
    'best' => lang('review_filter_best'),
    'bad' => lang('review_filter_bad'),
    'pic' => lang('review_filter_pic'),
    'digest' => lang('review_filter_digest'),
);

$urlpath = array();
$urlpath[] = url_path(lang('review_title'), url("review/list"));
$urlpath[] = url_path($fullname, url("review/item/sid/$sid"));
$urlpath[] = url_path($filter_arr_lng[$filter], url("review/item/sid/$sid/filter/$filter"));

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','review');

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('review_list', 'item', $subject['templateid']);
} else {
    include template('review_item');
}
?>