<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_notice extends ms_model {

	var $table = 'dbpre_notice';
    var $key = 'id';

    var $modcfg = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
    }

    function save($uid,$module,$type,$note) {
        if(!$uid) return;
        if(is_array($uid)) {
            foreach($uid as $id) {
                $this->save($id,$module,$type,$note);
            }
        } else {
            $set = array(
                'uid' => $uid,
                'module' => $module,
                'type' => $type,
                'note' => $note,
                'dateline' => $this->timestamp,
                'isread' => 0,
            );
            $this->db->from($this->table)
                ->set($set)->insert();
        }
    }

    function get_count($isread=FALSE) {
        return $this->db->from($this->table)
            ->where('uid',$this->global['user']->uid)
            ->where('isread', (int)$isread)
            ->count();
    }

    function get_list($isread=FALSE,$start,$offset=20) {
        return $this->db->from($this->table)
            ->where('uid',$this->global['user']->uid)
            ->where('isread', (int)$isread)
            ->order_by('dateline','DESC')
            ->limit($start,$offset)
            ->get();
    }

    function update_note($ids) {
        $this->db->from($this->table)
            ->where('uid',$this->global['user']->uid)
            ->set('isread', 1)
            ->update();
    }

}