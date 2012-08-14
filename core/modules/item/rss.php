<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'rss');

$R =& $_G['loader']->model(':review');
$IB =& $_G['loader']->model('item:itembase');
if($catid = isset($_GET['catid']) ? (int)$_GET['catid'] : 0) {
    $IB->get_category($catid);
    if(!$pid = $IB->category['catid']) {
        redirect('item_cat_empty');
    }
}

$where = array();
if($pid) $where['pcatid'] = $pid;
$where['r.city_id'] = $_CITY['aid'];
$where['r.idtype'] = 'item_subject';
$where['r.status'] = 1;
$offset = 25;
$start = get_start($_GET['page'], $offset);
$select = 'r.*';
list(,$list) = $R->find($select, $where, array('r.posttime'=>'DESC'), $start, $offset, FALSE, false, FALSE);

$xmlhead = 1;
@header("Content-Type: application/xml");
$link = url("item/list/catid/$pid",0,1);
$xml = ($xmlhead) ? '<?xml version="1.0" encoding="'.$_G['charset'].'"?>'."\r\n" : '';
$xml.="<rss version=\"2.0\">\r\n";
$xml.="<channel>\r\n";
$xml.="<title>$_CFG[sitename]</title>\r\n";
$xml.="<description>[modoer]".lang('item_rss_title')."</description>\r\n";
$xml.="<link>$link</link>\r\n";
$xml.="<copyright>(C) 2007 - 2010 Moufer studio</copyright>\r\n";
$xml.="<docs>http://backend.userland.com/rss</docs>\r\n";
if($list) while($data = $list->fetch_array()){
    $xml.="<item>\r\n";
    $xml.="<title>{$data[username]}£º{$data['subject']}</title>\r\n";
    $xml.="<link>".url("item/detail/id/$data[id]",0,1)."</link>\r\n";
    $xml.="<author>{$data['username']}</author>\r\n";
    $xml.="<description>\r\n";
    $xml.="<![CDATA[ \r\n";
    $xml.="$data[content]";
    $xml.="]]> \r\n";
    $xml.="</description>\r\n";
    $xml.="<pubDate>".date('Y-m-d H:i:s',$data['posttime'])."</pubDate>\r\n";
    $xml.="</item>\r\n";
}
$xml.="</channel>\r\n";
$xml.="</rss>\r\n";

echo $xml;