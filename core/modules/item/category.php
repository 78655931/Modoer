<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'item_category');

$seo_tags = get_seo_tags();
$_HEAD['title'] = $MOD['seo_category_title'] ? parse_seo_tags($MOD['seo_category_title'], $seo_tags) : ($_CITY['name'] . lang('item_title_category'));
$_HEAD['keywords'] = parse_seo_tags($MOD['seo_category_keywords'], $seo_tags);
$_HEAD['description'] = parse_seo_tags($MOD['seo_category_description'], $seo_tags);

$_G['show_sitename'] = FALSE;
include template('item_category');
?>