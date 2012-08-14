<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_coupon extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function subject_detail_link(&$params) {
        extract($params);
        $IB =& $this->loader->model('item:itembase');
        $model = $IB->get_model($pid,true);
        $title = 'се╩щх╞';
        return array (
            'flag' => 'coupon',
            'url' => url('coupon/list/sid/'.$sid),
            'title'=> $title,
        );
    }

}
?>