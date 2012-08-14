<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_coupon();

function inc_cache_coupon() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('coupon');

    $cachelist = array('category');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model('coupon:'.$value);
        $tmp->write_cache();
    }

    unset($tmp);
}
?>