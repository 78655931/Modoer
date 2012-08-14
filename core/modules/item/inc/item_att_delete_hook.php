<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//$sids 主题id集合
$att_data = $this->loader->model('item:att_data');
$att_data->delete_attid($attids);
unset($att_data);
?>