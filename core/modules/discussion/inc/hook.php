<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_discussion extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function admincp_subject_edit_link($sid) {
        $url = cpurl('discussion','topic','list',array('sid'=>$sid));
        return array(
            'flag' => 'discussion:topic',
            'url' => $url,
            'title'=> '交流话题管理',
        );
    }

    function subject_detail_link(&$params) {
        extract($params);
        $result = array();
        $result[] = array (
            'flag' => 'discussion',
            'url' => url('discussion/list/sid/'.$sid),
            'title'=> lang('discussion_title'),
        );
        return $result;
    }

}
?>