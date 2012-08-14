<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

inc_cache_product();

function inc_cache_product() {
    $loader =& _G('loader');

    $tmp =& $loader->model('config');
    $tmp->write_cache('product');

    $cachelist = array('product:model','product:field');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model($value);
        $tmp->write_cache();
    }
}
?>