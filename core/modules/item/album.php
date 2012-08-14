<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_album');

$A =& $_G['loader']->model('item:album');
$op = _input('op',null,'_T');
$albumid = _input('id',null,MF_INT_KEY);
$picid = _input('picid',null,MF_INT_KEY);
$sid = _input('sid',null,MF_INT_KEY);
$catid = _input('catid',null,MF_INT_KEY);
$urlpath = array();

if($albumid > 0 || $picid > 0) {

    if($picid > 0) {
        $IP =& $_G['loader']->model('item:picture');
        $pic = $IP->read($picid);
        $albumid = $pic['albumid'];
    }
    if(!$detail = $A->read($albumid)) redirect('item_album_empty');

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($detail['sid'])) redirect('item_empty');
    $sid = $subject['sid'];
    //城市判断
    if($subject['city_id']>0 && $subject['city_id'] != $_CITY['aid']) {
        $url = get_cityurl($subject['city_id'],"item/album/id/$albumid");
        if(!$_CFG['city_sldomain']) {
            $_CITY = init_city($subject['city_id']);
        } elseif($url) {
            location($url);
        }
    }

    $P =& $_G['loader']->model('item:picture');
    $where = array();
    $where['albumid'] = $albumid;
    list($total, $list) = $P->find('*', $where, array('addtime'=>'DESC'), 0, 0, 1);
    if(!$total) redirect('item_picture_empty');

    $A->pageview($albumid);

    $urlpath[] = url_path($subject['name'].($subject['subname']?"($subject[subname])":''), url("item/detail/id/$subject[sid]"));
    $urlpath[] = url_path(lang('item_album_title'), url("item/album/sid/$subject[sid]"));
    $urlpath[] = url_path($detail['name'], url("item/album/id/$detail[albumid]"));

    $subject_field_table_tr = $S->display_sidefield($subject);

    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];

    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','item/album');

    //预览模式
    if($vtid = _cookie('item_style_preview_'.$sid,null,MF_INT_KEY)) {
        if(is_template($vtid,'item')) {
            $subject['templateid'] = $vtid;
            $is_preview = true;
        }
    }

    $category = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $category['config']['templateid']>0) {
        $subject['templateid'] = $category['config']['templateid'];
    }

    if($subject['templateid']) {
        include template('pic', 'item', $subject['templateid']);
    } else {
        include template('item_pic');
    }

} elseif($sid > 0) {

    if($op == 'selectpic') {
        $_G['in_ajax'] = 1;
        $where = array();
        $where['sid'] = (int) $_GET['sid'];
        $page = (int)$_GET['page'];
        $offset = 6;
        $start = get_start($page, $offset);
        $P =& $_G['loader']->model('item:picture');
        list($total, $list) = $P->find('picid,thumb', $where, array('addtime'=>'DESC'), $start, $offset);
        if($total) {
            echo '<table width="100%">';
            $i = 0;
            while($value = $list->fetch_array()) {
                $i++;
                if($i % 3 == 1) echo '<tr>';
                echo '<td width="125"><img src="'.URLROOT.'/'.$value['thumb'].'" width="120" onclick="insert_subject_thumb(\''.$value['thumb'].'\');" style="cursor:pointer;" /></td>';
                if($i % 3 == 0) echo '</tr>';
            }
            if($x = $i % 3) {
                echo str_repeat('<td width="125">&nbsp;</td>', (3 - $x));
                echo '</tr>';
            }
            echo '</table><br /><center>';
            if($page > 1) echo '<a href="javascript:select_subject_thumb('.$where[sid].','.($page-1).');">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;&nbsp;';
            if($start + $offset < $total) echo '<a href="javascript:select_subject_thumb('.$where[sid].','.($page+1).');">&gt;&gt;</a>';
            echo '</center>';
        }
        output();
    }

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid)) redirect('item_empty');

    $where = array();
    $where['sid'] = $sid;
    list($total, $list) = $A->find('*',$where,array('lastupdate'=>'DESC'),0, 0, 1);

    $urlpath[] = url_path($subject['name'].($subject['subname']?"($subject[subname])":''), url("item/detail/id/$subject[sid]"));
    $urlpath[] = url_path(lang('item_album_title'), url("item/album/sid/$subject[sid]"));

    $subject_field_table_tr = $S->display_sidefield($subject);

    $_HEAD['keywords'] = $MOD['meta_keywords'];
    $_HEAD['description'] = $MOD['meta_description'];

    //其他模块和功能的链接
    $links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
    define('SUB_NAVSCRIPT','item/album');

    //预览模式
    if($vtid = _cookie('item_style_preview_'.$sid,null,MF_INT_KEY)) {
        if(is_template($vtid,'item')) {
            $subject['templateid'] = $vtid;
            $is_preview = true;
        }
    }
    $category = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $category['config']['templateid']>0) $subject['templateid'] = $category['config']['templateid'];
    if($subject['templateid']) {
        include template('album', 'item', $subject['templateid']);
    } else {
        include template('item_subject_album');
    }

} else {

    $mode_arr = array('normal','waterfall');
    $mode = _cookie('list_display_item_album_mode', $MOD['item_album_mode'], '_T');
    $mode = in_array($mode, $mode_arr) ? $mode : 'normal';

    $category = $_G['loader']->variable('category','item');
    $urlpath[] = url_path(lang('item_album_title'), url("item/album"));
    if($catid > 0) $urlpath[] = url_path($category[$catid]['name'], url("item/allpic/catid/$pid"));

    $where = array();
    $keyword = _get('keyword',null,'_T');
    if($keyword) $where['a.name'] = array('where_like',array("%$keyword%"));
    $where['a.city_id'] = array(0,$_CITY['aid']);
    if($catid > 0) $where['s.pid'] = (int) $catid;
    $where['num'] = array('where_more',array(1));

    $orderby = _cookie('list_display_item_album_orderby','normal','_T');
    $orderby_arr = array(
        'normal'=>array('albumid'=>'ASC'),
        'pageview'=>array('pageview'=>'DESC'),
        'num'=>array('num'=>'DESC'),
    );
    !isset($orderby_arr[$orderby]) and $orderby='normal';

    $offset = 16;
    if($mode == 'waterfall') {
        //瀑布流
        $wf_count = 5;
        $wf_offset = $offset * $wf_count;
        $wf_page = _get('wfpage', 1, MF_INT_KEY);
        $start = get_start(($wf_page-1)*$wf_count+$_GET['page'], $offset);
        $tplname = 'item_album_waterfall';
    } else {
        $start = get_start($_GET['page'], $offset);
        $tplname = 'item_album_list';
    }

    list($total, $list) = $A->find('a.*', $where, $orderby_arr[$orderby], $start, $offset, TRUE, 's.name as sname,s.subname,s.pid');
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], url("item/album/catid/$catid/keyword/$keyword/wfpage/$wf_page/page/_PAGE_"));
        $wf_multipage = multi($total, $wf_offset, $wf_page, url("item/album/catid/$catid/keyword/$keyword/wfpage/_PAGE_"));
    }

    if($keyword) $urlpath[] = url_path(lang('item_album_search',$keyword),null);

    $active = array();
    $active['cate'][(int)$catid] = ' class="selected"';
    $active['view'][$view] = ' class="selected"';
    $active['orderby'][$orderby] = ' class="selected"';
    $active['mode'][$mode] =  ' class="selected"';

    //seo设置
    $seo_tags = get_seo_tags();
    $seo_tags['current_category_name'] = display('item:category',"catid/$catid");
    $seo_tags['album_title'] = lang('item_album_title');

    !$MOD['seo_album_title'] && $MOD['seo_album_title'] = '{current_category_name},{album_title},{site_name}';
    !$MOD['seo_album_keywords'] && $MOD['seo_album_keywords'] = '{site_keywords}';
    !$MOD['seo_album_description'] && $MOD['seo_album_description'] = '{site_description}';

    $_HEAD['title'] = parse_seo_tags($MOD['seo_album_title'], $seo_tags);
    $_HEAD['keywords'] = parse_seo_tags($MOD['seo_album_keywords'], $seo_tags);
    $_HEAD['description'] = parse_seo_tags($MOD['seo_album_description'], $seo_tags);

    $_G['show_sitename'] = FALSE;

    if(_input('waterfall') == 'Y' && $_G['in_ajax']) {
        $tplname = 'item_album_waterfall_li';
    }
    include template($tplname);
}

?>