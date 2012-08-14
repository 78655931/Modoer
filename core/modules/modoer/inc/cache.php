<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
inc_cache_modoer();

function inc_cache_modoer() {
    $loader =& _G('loader');
    $cachelist = array('config','module','area','template','menu','datacall','bcastr','word');
    foreach ($cachelist as $value) {
        $tmp =& $loader->model($value);
        $tmp->write_cache(); 
    }
}

?>