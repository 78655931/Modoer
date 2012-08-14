<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_article();

function inc_cache_article() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('article');

    $cachelist = array('category');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('article:'.$value);
        $tmp->write_cache();
    }
}
?>