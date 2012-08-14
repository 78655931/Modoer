<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_item extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function admincp_subject_edit_link($sid) {
        $result = array();
        $result[] = array (
            'flag' => 'item:subject_edit',
            'url' => cpurl('item','subject_edit','',array('sid'=>$sid)),
            'title'=> '�༭����',
        );
        $result[] = array (
            'flag' => 'item:subject_guestbook',
            'url' => cpurl('item','guestbook_list','',array('sid'=>$sid)),
            'title'=> '���Թ���',
        );
        $setting = $this->loader->model('item:subjectsetting')->read($sid);
        if($setting['banner']) {
        $result[] = array (
            'flag' => 'item:subject_setting_banner',
            'url' => cpurl('item','setting','banner',array('sid'=>$sid)),
            'title'=> '�������',
        );
        }
        if($setting['bcastr']) {
        $result[] = array (
            'flag' => 'item:subject_setting_bcastr',
            'url' => cpurl('item','setting','bcastr',array('sid'=>$sid)),
            'title'=> '��������',
        );
        }

        return $result;
    }

    function subject_detail_link(&$params) {
        extract($params);
        $result = array();
        $IB =& $this->loader->model('item:itembase');
        $model = $IB->get_model($pid, true);
        $result[0] = array (
            'flag' => 'item/detail',
            'url' => url('item/detail/id/'.$sid),
            'title'=> '��ҳ',
        );
        $result[1] = array (
            'flag' => 'item/album',
            'url' => url('item/album/sid/'.$sid),
            'title'=> '���',
        );
        return $result;
    }

    function subject_manage_link($sid,$catid) {
    }

    function footer() {
    }

}
?>