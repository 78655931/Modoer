<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_link {

    function links($params) {
        extract($params);
        $loader =& _G('loader');
        if(!$list = $loader->variable('list','link')) return array();
        if(!in_array($type,array('char','logo'))) $type = 'char';
        return $list[$type];
    }

}
?>