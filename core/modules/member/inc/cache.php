<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_member();

function inc_cache_member() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('member');

    $cachelist = array('usergroup','tasktype');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('member:'.$value);
        $tmp->write_cache();
    }
}
?>