<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_tag');

$TAG =& $_G['loader']->model('item:tag');
$tagid = (int) $_GET['tagid'];
$tagname = _T($_GET['tagname']);
if($tagid || $tagname) {

    if($tagid) {
        $tag = $TAG->read($tagid, array(0,(int) $_CITY['aid']));
    } elseif($tagname) {
        $tag = $TAG->read($tagname, array(0,(int) $_CITY['aid']), TRUE);
    }
    if(!$tag) redirect('item_tag_empty');
    if($tag['closed']) redirect('item_tag_closed');

    $tagid = $tag['tagid'];
    $tagname = $tag['tagname'];

    $where = array();
    $where['city_id'] = array(0,(int) $_CITY['aid']);
    $where['status'] = 1;
    $I =& $_G['loader']->model(MOD_FLAG.':subject');
    $urlpath[] = url_path('Tag:' . $tag['tagname'], url("item/tag/tagid/$tagid"));
    // 显示数量
    $numlist = array (10, 20, 40);
    $num = in_array($_GET['num'], $numlist) ? (int)$_GET['num'] : (int) $MOD['list_num'];
    $start = get_start($_GET['page'], $num);
    $select = 's.sid,pid,catid,name,subname,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,pageviews,reviews,pictures,thumb';
    list($total, $list) = $I->find_tag($tagid, $where, $select, array('reviews'=>'DESC'), $start, $num);
    if($total == 1) {
        //遇到只有1个主题时，就跳转到主题页面
        $detail = $list->fetch_array();
        $list->free_result();
        location(url("item/detail/id/$detail[sid]",'',1));
    } elseif($total > 1) {
        $multipage = multi($total, $num, $_GET['page'], url("item/tag/tagid/$tagid/page/_PAGE_"));
    }

    $category = $_G['loader']->variable('category','item');

    $tplname = 'item_tag_subject';

} else {
    
    $where = array();
    $where['city_id'] = array(0,(int) $_CITY['aid']);
    list(,$list) = $TAG->find($where);
    $tplname = 'item_tag_list';

}

include template($tplname);
?>