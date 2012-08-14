<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class misc_article {

    function category_catids($id) {
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        if($category[$id]['pid']) {
            return array($id);
        }
        $result = array();
        foreach($category as $val) {
            if($val['pid']  == $id) $result[] = $val['catid'];
        }
        return $result;
    }

    function category_path($catid, $split = '&gt;', $url = '') {
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        if($pid = $category[$catid]['pid']) {
            $root_name = $category[$pid]['name'];
            if($url) $root_name = '<a href="'.str_replace('_CATID_',$pid,$url).'">'.$root_name.'</a>';
        }
        $sub_name = $category[$catid]['name'];
        if($url) $sub_name = '<a href="'.str_replace('_CATID_',$catid,$url).'">'.$sub_name.'</a>';
        return ($root_name ? ($root_name . $split) : '') . $sub_name;
    }

    function category_name($catid) {
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        return $category[$catid]['name'];
    }
}
?>