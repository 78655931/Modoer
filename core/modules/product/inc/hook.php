<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_product extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function admincp_subject_edit_link($sid) {
        $S =& $this->loader->model('item:subject');
        $subject = $S->read($sid);
        $category = $S->get_category($subject['pid']);
        if(!$category['config']['product_modelid']) return;
        $url = cpurl('product','product_list','',array('sid'=>$sid));
        return array(
            'flag' => 'product:list',
            'url' => $url,
            'title'=> '产品管理',
        );
    }

    function subject_detail_link(&$params) {
        extract($params);
        $title = '产品';
        return array (
            'flag' => 'product',
            'url' => url('product/list/sid/'.$sid),
            'title'=> $title,
        );
    }


    function footer() {
    }

}
?>