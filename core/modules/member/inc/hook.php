<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_member extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function init() {
        $cfg = $this->loader->variable('config','member');
        if($cfg['passport_login'] && $cfg['passport_list']) {
            foreach(explode(',',$cfg['passport_list']) as $passport_name) {
                $this->global['passport_apis'][$passport_name] = $cfg['passport_'.$passport_name.'_title'];
            }
            ksort($this->global['passport_apis']);
        }
    }

    function total() {
        $result = array();
        $db =& $this->global['db'];
        $db->from('dbpre_members');
        $result[] = array(
            'name' => lang('membercp_cphome_member_title'),
            'content' => $db->count(),
        );
        $db->from('dbpre_pmsgs');
        $result[] = array(
            'name' => lang('membercp_cphome_pmsg_title'),
            'content' => $db->count(),
        );

        return $result;
    }

    function footer() {
    }

}
?>