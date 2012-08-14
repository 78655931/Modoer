<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'exchange');

$GT =& $_G['loader']->model(MOD_FLAG.':gift');
$catid = (int) $_GET['catid'];
$pattern = (int) $_GET['pattern']?(int) $_GET['pattern']:1;
$select = 'giftid,sid,name,price,pointtype,point,point3,point4,pointtype2,pointtype3,pointtype4,num,timenum,allowtime,starttime,endtime,pageview,thumb,salevolume,randomcodelen,randomcode';
$where = array();
$where['city_id'] = array(0,$_CITY['aid']);
if($catid) $where['catid'] = $catid;
$where['pattern'] = $pattern;
$where['available'] = 1;
$start = get_start($_GET['page'],$offset = 20);
list($total,$list) = $GT->find($select, $where, 'displayorder', $start, $offset, true);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("exchange/index/catid/$catid/pattern/$pattern/page/_PAGE_"));
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('exchange_index');
?>