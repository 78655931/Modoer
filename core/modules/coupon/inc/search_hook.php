<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//全局搜索的一些默认参数配置
$get = array();
$get['keyword'] = _T($_GET['keyword']);
//跳转到自己的搜索页面
$search_file = get_url('coupon', 'index', $get,'',1,0);
location($search_file);
exit;
?>