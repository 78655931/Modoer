<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$catid = isset($_GET['catid']) ? (int)$_GET['catid'] : (int)$MOD['pid'];
!$catid and redirect('item_empty_default_pid');

//实例化主题类
$I =& $_G['loader']->model('item:subject');
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
$category = $_G['loader']->variable('category_' . $pid, 'item');
//判断子分类是否禁用
if(!$category[$catid]['enabled']) redirect('item_cat_disabled');
//当前catid的级别
$category_level = $category[$catid]['level'];
$subcats = $category[$catid]['subcats'];
$urlpath = array();
$urlpath[] = url_path($category[$pid]['name'], url("item/list/catid/$pid"));
if($catid != $pid) {
    if($category_level > 2) {
        $urlpath[] = url_path($category[$category[$catid]['pid']]['name'], url("item/list/catid/{$category[$catid]['pid']}"));
    }
    $urlpath[] = url_path($category[$catid]['name'], url("item/list/catid/$catid"));
	$attcats = $category[$catid]['config']['attcat'];
} else {
	$attcats = $catcfg['attcat'];
}

//兼容以前的版本
$pcat =& $category;
$where = array();
//属性组处理
$atts = array();
//分类处理
$atts['category'] = $I->get_attid($catid);
//使用了地图功能
if($model['usearea']) {
    $aid = (int) $_GET['aid'];
	//载入地区
	$area = $_G['loader']->variable('area_'.$_CITY['aid'],null,false);
    //地区级别
    $area_level = $area[$aid]['level'];

    if($area_level == 2) {
        $paid = 0;
    } else {
        $paid = $area[$aid]['pid'];
    }

    if($paid) {
        $urlpath[] = url_path($area[$paid]['name'], url("item/list/catid/$pid/aid/$paid"));
    }
    if($paid != $aid) {
        $urlpath[] = url_path($area[$aid]['name'], url("item/list/catid/$pid/aid/$aid"));
    }

	$boroughs = $streets = '';
	if($area) foreach($area as $key => $val) {
		if($val['level'] == 2) $boroughs[$key] = $val['name'];
		if($val['level'] == 3 && ($aid==$val['pid']||$paid==$val['pid'])) $streets[$key] = $val['name'];
	}

    $area =& $_G['loader']->model('area');
    if($aid) {
        $area_attid = $area->get_attid($aid);
    } else {
        $area_attid = $area->get_attid($_CITY['aid']);
    }
    if($area_attid) $atts['area'] = $area_attid;
}
if($att = _get('att',null,'_T')) {
    if(!preg_match("/^[0-9\.\_]+$/i", $att)) {
        $att = $_GET['att'] = '';
    } else {
        $att = explode('_', $att);
        foreach($att as $att_v) {
            list($att_catid, $att_id) = explode('.', $att_v);
            if(!$att_catid || !$att_id) continue;
            $atts[$att_catid] = $att_id;
            $_attlist = $_G['loader']->variable('att_list_'.$_attcatid, 'item', false);
            $seo_tags['att_name_' . $_attcatid] = $_attlist[$_attid];
        }
    }
}
if($atts) $where['attid'] = $atts;
/*
if($catid != $pid) {
    $where['catid'] = array_merge((array)$catid, $I->get_sub_catids($catid));
} else {
    $where['pid'] = (int) $pid;
}
*/
//$where['status'] = 1;
// 显示数量
$numlist = array (10, 20, 40);
// 显示方式
$typelist = lang('item_list_displytype');
$type = _cookie('list_display_item_subject_type', $catcfg['displaytype'], '_T');
$type = isset($typelist[$type]) ? $type : 'normal';
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

if($catcfg['useprice']) {
    $orderlist['price'] = $catcfg['useprice_title'];
}
$orderby = _cookie('list_display_item_subject_orderby',$catcfg['listorder'],'_T');
if(!$orderby || !isset($order_arr[$orderby])) {
	$orderby = $catcfg['listorder'];
}

$fields = $I->variable('field_' . $modelid);
$select = 's.sid,pid,catid,domain,name,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,finer,pageviews,reviews,pictures,favorites,thumb';
if($model['usearea']) {
    if(false == strpos($select, 'aid')) $select .= ',aid';
    if(false == strpos($select, 'map_lat')) $select .= ',map_lat';
    if(false == strpos($select, 'map_lng')) $select .= ',map_lng';
}
if($catcfg['useprice']) {
    if(false == strpos($select, 'avgprice')) $select .= ',avgprice';
}
$select_arr = explode(',', $select);
$custom_field = array();
foreach($fields as $val) {
    if(!$val['show_list']) continue;
	$ver_field = array('mappoint');
    if(!in_array($val['fieldname'], $select_arr) && !in_array($val['fieldname'], $ver_field)) {
        $select .= ',' . $val['fieldname'];
    }
    if(!in_array($val['fieldname'], array('name','subname','mappoint','owner','status','templateid','listorder'))) {
        $custom_field[] = $val;
    }
}
unset($select_arr, $val);

$num = abs(_cookie('list_display_item_subject_num', (int)$MOD['list_num'], 'intval'));
if(!$num||$num<5)  $num = 20;
if($type == 'pic') {
    $offset = $num;
    $wf_count = 5;
    $wf_offset = $num * $wf_count;
    $wf_page = _get('wfpage', 1, MF_INT_KEY);
    $start = get_start(($wf_page-1)*$wf_count+$_GET['page'], $num);
    $tplname = 'item_subject_waterfall';
} else {
    $start = get_start($_GET['page'], $num);
    $tplname = $model['tplname_list'];
}

if(_input('waterfall') == 'Y' && $_G['in_ajax']) {
    $tplname = 'item_subject_waterfall_li';
}

$total = _get('total',null,MF_INT);
if($total > 0 && $type != 'pic') {
    list(, $list) = $I->getlist($pid, $select, $where, $order_arr[$orderby], $start, $num, false);
	//list(, $list) = $I->find($select, $where, $order_arr[$orderby], $start, $num, false, $pid, $atts);
} else {
    list($total, $list) = $I->getlist($pid, $select, $where, $order_arr[$orderby], $start, $num, true);
	//list($total, $list) = $I->find($select, $where, $order_arr[$orderby], $start, $num, TRUE, $pid, $atts);
}
$atturl = item_att_url();
if($total) {
    $multipage = multi($total, $num, $_GET['page'], url("item/list/catid/$catid/aid/$aid/order/$order/type/$type/att/$atturl/num/$num/total/$total/wfpage/$wf_page/page/_PAGE_"));
    $wf_multipage = multi($total, $wf_offset, $wf_page, url("item/list/catid/$catid/aid/$aid/order/$order/type/$type/att/$atturl/num/$num/total/$total/wfpage/_PAGE_"));
}

if($total) {
    //内容
    $FD =& $_G['loader']->model(MOD_FLAG.':fielddetail');
    $FD->pagemod = 'list';
    //样式设计
    $FD->td_num = 1; //表只有1个td
    $FD->class = "";
    $FD->width = "";
    $FD->align = "left";
}

define('SUBJECT_CATID', $catid);

$active = array();
$active['type'][$type] = ' class="selected"';
$active['num'][$num] = ' class="selected"';
$active['orderby'][$orderby] = ' class="selected"';

// 最近的浏览COOKIE
$cookie_subjects = $I->read_cookie($pid);

//seo setting
$seo_tags = get_seo_tags();
$seo_tags['root_area_name'] = $paid?display('modoer:area',"aid/$paid"):'';
$seo_tags['area_name'] = $aid?display('modoer:area',"aid/$aid"):'';
$seo_tags['root_category_name'] = display('item:category',"catid/$pid");
$seo_tags['current_category_name'] = display('item:category',"catid/$catid");
$seo_tags['page'] = $_GET['page'];
$seo_tags['root_category_keywords'] = $catcfg['meta_keywords'];
$seo_tags['root_category_description'] = $catcfg['meta_description'];

if($att) {
    foreach($att as $_att_v) {
        list($_attcatid, $_attid) = explode('.', $_att_v);
        $_attcatid = (int) $_attcatid;
        $_attid = (int) $_attid;
        if($_attcatid > 0 && $_attid > 0) {
            $_attlist = $_G['loader']->variable('att_list_'.$_attcatid, 'item');
            $seo_tags['att_name_'.$_attcatid] = $_attlist[$_attid]['name'];
        }
    }
}

!$MOD['seo_list_title'] && $MOD['seo_list_title'] = '{root_category_name},{area_name},{city_name}';
!$MOD['seo_list_keywords'] && $MOD['seo_list_keywords'] = '{root_category_keywords}';
!$MOD['seo_list_description'] && $MOD['seo_list_description'] = '{root_category_description}';

$_HEAD['title'] = parse_seo_tags($MOD['seo_list_title'], $seo_tags);
$_HEAD['keywords'] = parse_seo_tags($MOD['seo_list_keywords'], $seo_tags);
$_HEAD['description'] = parse_seo_tags($MOD['seo_list_description'], $seo_tags);

$_G['show_sitename'] = FALSE;

include template($tplname);

function item_att_url($catid=null, $attid=null, $del=false) {
    global $atts;
    
    $myatts = $atts;
    if($catid) {
        if($del) {
            unset($myatts[$catid]);
        } else {
            $myatts[$catid] = $attid;
        }
    }
    $url = $split = '';
    foreach($myatts as $catid=>$attid) {
        if(!$catid || !is_numeric($catid)) continue;
        $url .= $split . $catid .'.'.$attid;
        $split = '_';
    }
    return $url;
}
?>