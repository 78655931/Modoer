<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_coupon {

    //²ÎÊý catid,keyname
    function category($params) {
        extract($params);
        if(!$catid) return '';
        if(!$keyname) $keyname = 'name';
        $loader =& _G('loader');
        $category = $loader->variable('category','coupon');
        if(!isset($category[$catid][$keyname])) return '';
        return $category[$catid][$keyname];
    }

}
?>