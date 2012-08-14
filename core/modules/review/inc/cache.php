<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_review();

function inc_cache_review() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('review');

    $cachelist = array('opt','opt_group');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('review:'.$value);
        $tmp->write_cache();
    }
}