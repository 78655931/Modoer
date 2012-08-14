<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_article {

    //取得分类的名称或其它
    //参数 catid,keyname
    function category($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$catid) return '';
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        if(!isset($category[$catid][$keyname])) return '';
        return $category[$catid][$keyname];
    }

}
?>