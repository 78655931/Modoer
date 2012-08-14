<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_article extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function admincp_subject_edit_link($sid) {
        $url = cpurl('article','article','list',array('sid'=>$sid));
        return array(
            'flag' => 'article:list',
            'url' => $url,
            'title'=> '资讯管理',
        );
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '资讯';
        return array (
            'flag' => 'article',
            'url' => url('article/list/sid/'.$sid),
            'title'=> $title,
        );
    }

}
?>