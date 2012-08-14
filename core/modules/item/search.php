<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_search');

$I =& $_G['loader']->model('item:subject');
$category = $I->variable('category');

if($_GET['searchsubmit']) {

    if($_GET['searchsort'] == 'tag') {
        header("Location:" . url("item/tag/tagname/$_GET[keyword]"));
    }

    //设置搜索城市
    $_GET['city_id'] = array(0,$_CITY['aid']);
    $SEARCH =& $_G['loader']->model('item:search');

    if($_GET['keyword'] && $_G['charset'] != 'utf-8' && $_G['cfg']['utf8url']) {
        $_GET['keyword'] = charset_convert($_GET['keyword'],'utf-8',$_G['charset']);
    }
    $_GET['keyword'] = _get('keyword','',MF_TEXT);

    $num = $MOD['list_num'] > 0 ? $MOD['list_num'] : 40;
    $start = get_start($_GET['page'], $num);
    list($total, $list) = $SEARCH->search($start, $num);
    if($total) {
        if($total == 1 && !$MOD['search_location']) {
            //遇到只有1个主题时，就跳转到主题页面
            $detail = $list->fetch_array();
            $list->free_result();
            location(url("item/detail/id/$detail[sid]",'',1));
        }
        $get = $SEARCH->get_post($_GET);
        $url = 'item/search';
        foreach($get as $key => $val) {
            $url .= '/' . $key . '/' . $val;
        }
        $url .= '/searchsubmit/yes/page/_PAGE_';
        $multipage = multi($total, $num, $_GET['page'], url($url));
    }
    $tplname = 'item_search_result';

} else {
    $tplname = 'item_search';
}

include template($tplname);
?>