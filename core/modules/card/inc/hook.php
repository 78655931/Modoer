<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_card extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function total() {
        $result = array();
        $db =& $this->global['db'];
        $db->from('dbpre_card_apply');
        $_G['db']->where('status',0);
        $result[] = array(
            'name' => lang('cardcp_cphome_apply_title'),
            'content' => '<a href="' . cpurl('card','apply') . '">'.$check.'</a>',
        );
        return $result;
    }

    function admincp_subject_edit_link($sid) {
        $CD =& $this->loader->model('card:discount');
        $discount = $CD->read($sid);
        $S =& $this->loader->model('item:subject');
        $subject = $S->read($sid);
        $modelid = $S->get_modelid($subject['pid'], true);
        $modelids = @unserialize($CD->modcfg['modelids']);
        if(!$modelids || !in_array($modelid,$modelids)) return;

        $url = cpurl('card','discount',($detail?'edit':'add'),array('sid'=>$sid));
        return array(
            'flag' => 'card:discount',
            'url' => $url,
            'title'=> 'Ìí¼Ó/±à¼­»áÔ±¿¨',
        );
    }

    function footer() {
    }

}
?>