<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_map');

$op = _input('op','',MF_TEXT);

switch($op) {

case 'detail':
    
    $S = $_G['loader']->model('item:subject');
    $sid = _input('sid', 0, MF_INT_KEY);
    if(!$subject = $S->read($sid)) redirect('item_empty');
    $subject_field_table_tr = $S->display_sidefield($subject);
    include template('item_map_infow');
    output();
    break;

default:

    $aid = _input('aid', 0, MF_INT_KEY);
    $catid = _input('catid', 0, MF_INT_KEY);
    $total = _input('total', null, MF_INT_KEY);

    $S = $_G['loader']->model('item:subject');

    $pcatid = $S->get_pid($catid);
    $model = $S->get_model($pcatid,TRUE);
    if(!$model['usearea']) $catid = 0;

    $where = array();
    if(!$aid) {
        $where['city_id'] = (int) $_CITY['aid'];
    } elseif($aid>0) {
        $where['aid'] = $aid;
    }
    if($catid>0) $where['catid'] = $catid;
    $where['status'] = 1;
    $where['map_lng'] = array('where_not_equal',array('0'));

    $num = 10;
    $start = get_start($_GET['page'], $num);
    if($total>0)
        list(,$list) = $S->find('*', $where, array('addtime'=>'DESC'), $start, $num, false);
    else
        list($total,$list) = $S->find('*', $where, array('addtime'=>'DESC'), $start, $num);

    if($total) {
        $multipage = multi($total, $num, $_GET['page'], url("item/map/catid/$catid/aid/$aid/total/$total/page/_PAGE_"));
    }

    //seoÉèÖÃ
    $seo_tags = get_seo_tags();
    $seo_tags['area_name'] = $aid ? display('modoer:area',"aid/$aid") : '';
    $seo_tags['map_title'] = lang('item_map_title');

    !$MOD['seo_map_title'] && $MOD['seo_map_title'] = '{area_name},{map_title},{site_name}';
    !$MOD['seo_map_keywords'] && $MOD['seo_map_keywords'] = '{site_keywords}';
    !$MOD['seo_map_description'] && $MOD['seo_map_description'] = '{site_description}';

    $_HEAD['title'] = parse_seo_tags($MOD['seo_map_title'], $seo_tags);
    $_HEAD['keywords'] = parse_seo_tags($MOD['seo_map_keywords'], $seo_tags);
    $_HEAD['description'] = parse_seo_tags($MOD['seo_map_description'], $seo_tags);

    $_G['show_sitename'] = FALSE;
    $mapflag = $_G['cfg']['mapflag'] ? $_G['cfg']['mapflag'] : 'baidu';
    $version = 1.3;

    include template('item_map');
}

?>