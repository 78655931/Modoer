<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$F =& $_G['loader']->model(MOD_FLAG.':field');

if(!$fieldid = _get('fid',null,MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid','fid'));
if(!$sid = _get('id',null,MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid','id'));

if(!$field = $F->read($fieldid)) redirect('admincp_field_invalid');
if(!$subject = $I->read($sid)) redirect('item_empty');

$pid = $subject['pid'];
$catid = $subject['catid'];
//载入配置信息
$category = $_G['loader']->variable('category_' . $subject['pid'], 'item');
$I->get_category($subject['catid']);
$catcfg =& $I->category['config'];
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];

//载入模型
$model = $I->variable('model_' . $modelid);

// 排序数组
$orderlist = lang('item_list_orderlist');
//查询的数组
$order_arr  = array(
    'finer' => array('finer'=>'DESC'),
    'avgsort' => array('avgsort'=>'DESC'),
    'reviews' => array('reviews'=>'DESC'),
    'enjoy' => array('best'=>'DESC'),
    'price' => array('avgprice'=>'DESC'),
    'price_s' => array('avgprice'=>'ASC'),
    'picture' => array('pictures'=>'DESC'),
    'picture_s' => array('pictures'=>'ASC'),
    'addtime' => array('addtime'=>'DESC'),
    'pageviews' => array('pageviews'=>'DESC'),
);
$orderby = _cookie('list_display_item_subject_orderby',$catcfg['listorder'],'_T');
if(!$orderby || !isset($order_arr[$orderby])) {
	$orderby = $catcfg['listorder'];
}

// 显示数量
$numlist = array (10, 20, 40);
$num = abs(_cookie('list_display_item_subject_num', (int)$MOD['list_num'], 'intval'));
(!$num || !in_array($num, $numlist)) && $num = 20;
$start = get_start($_GET['page'], $num);

//获取关联字段的主题
$_G['db']->join('dbpre_subjectrelated','sr.r_sid','dbpre_subject','s.sid');
$_G['db']->where('sr.fieldid',$fieldid);
$_G['db']->where('sr.sid',$sid);
if($total = $_G['db']->count()) {
    $_G['db']->sql_roll_back('from,where');
    $_G['db']->order_by($order_arr[$orderby]);
    $_G['db']->limit($start,$num);
    $list = $_G['db']->get();
    $multipage = multi($total, $num, $_GET['page'], url("item/related/fid/$fieldid/id/$sid/page/_PAGE_"));
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("item/category"));
$urlpath[] = url_path($I->get_subject($subject), url("item/detail/id/$sid"));
$urlpath[] = url_path($field['title'], url("item/related/fid/$fieldid/id/$sid"));

$active = array();
$active['type'][$type] = ' class="selected"';
$active['num'][$num] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

// 最近的浏览COOKIE
$cookie_subjects = $I->read_cookie($pid);

$_HEAD['keywords'] = $I->get_subject($subject) . ',' . $field['title'] . ',' . $catcfg['meta_keywords'];
$_HEAD['description'] = trimmed_title(strip_tags(str_replace("\r\n",'',$subject['content']), 100)) . ',' . $catcfg['meta_description'];
include template('item_subject_related');