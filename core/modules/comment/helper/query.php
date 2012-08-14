<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_comment {
    //获取主题的分类列表
    function category($params) {
        if(!is_numeric($params['sid']) || $params['sid'] < 1) return;
        $loader =& _G('loader');
        $C =& $loader->model('product:category');
        return $C->get_list($params['sid']);
    }
}
?>