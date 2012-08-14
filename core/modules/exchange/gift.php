<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'exchange');

if(!$giftid = (int)$_GET['id']) redirect(lang('global_sql_keyid_invalid', 'id'));

$GT =& $_G['loader']->model(MOD_FLAG.':gift');
if(!$detail = $GT->read($giftid)) redirect('exchange_giftid_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($detail['city_id'])) location(url("city:$detail[city_id]/exchange/gift/id/$giftid"));

$GT->pageview($giftid);

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('exchange_gift');
?>