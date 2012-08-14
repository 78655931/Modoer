<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'announcement');

$ANN = $_G['loader']->model('announcement');

switch($_GET['do']) {
case 'list':
    $offset = 40;
    $start = get_start($_GET['page'],$offset);
    $where = array();
    $where['city_id'] = array(0,$_CITY['aid']);
    $where['available'] = 1;
    list($total,$list) = $ANN->find('id,city_id,title,orders,author,dateline,available',$where,array('orders'=>'ASC','dateline'=>'DESC'),$start,$offset,true);
    if($total) $multipage = multi($total, $offset, $_GET['page'], url('index/announcement/do/list/page/_PAGE_'));
    break;
default:
    $id = _get('id', null, MF_INT_KEY);
    if(!$detail = $ANN->read($id)) redirect('global_ann_empty');
    //³ÇÊÐURLÅÐ¶Ï
    if(check_use_global_url('index/announcement')) {
        location(url("index/announcement/id/$id"));
    }
}

include template('modoer_announcement');
?>