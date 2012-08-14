<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_passport extends ms_model {

    var $table = 'dbpre_member_passport_new';
    var $key = 'uid';
    var $model_flag = 'member';

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
    }

    function msm_member_passport() {
        $this->__construct();
    }

    function get_list($uid) {
        $r = $this->db->from($this->table)->where('uid',$uid)->get();
        if(!$r) return;
        $s = array();
        while ($v=$r->fetch_array()) {
            $s[$v['psname']] = $v['isbind'];
        }
        $r->free_result();
        return $s;
    }

    function get_token_status($uid) {
        $r = $this->db->from($this->table)->where('uid', $uid)->get();
        if(!$r) return;
        $s = array();
        while ($v=$r->fetch_array()) {
            $s[$v['psname']] = $v['expired'] > $this->global['timestamp'];
        }
        $r->free_result();
        return $s;
    }

    function get_uid($psname,$psuid) {
        $this->db->from($this->table);
        $this->db->select('uid');
        $this->db->where('psname',$psname);
        $this->db->where('psuid',$psuid);
        return $this->db->get_value();
    }

    function save($post) {
        $post['access_token'] = _T($this->global['cookie']['passport_'.$post['psname'].'_token']);
        $post['expired'] = (int) $this->global['cookie']['passport_'.$post['psname'].'_expires_in'];
        $this->db->from($this->table)->set($post)->replace();
    }

    function get_token($uid, $psname) {
        return $this->db->from($this->table)->where('uid',$uid)->where('psname',$psname)->get_one();
    }

    function update_access_token($uid, $psname, $access_token, $expires_in) {
        $this->db->from($this->table)
            ->set('access_token', $access_token)
            ->set('expired', $expires_in)
            ->where('uid', $uid)
            ->where('psname', $psname)
            ->update();
    }

    function check_exists($uid) {
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        return $this->db->count() > 0;
    }

    function bind_exists($psname, $psuid) {
        $this->db->from($this->table);
        $this->db->where('psname', $psname);
        $this->db->where('psuid', $psuid);
        return $this->db->count() > 0;
    }

    function bind($uid, $psname, $psuid, $access_token='', $expires_in='') {
        if(!$access_token) $access_token = _T($this->global['cookie']['passport_'.$psname.'_token']);
        if(!$expires_in) $expires_in = (int) $this->global['cookie']['passport_'.$psname.'_expires_in'];
        $this->db->from($this->table)
            ->set('uid', $uid)
            ->set('psname', $psname)
            ->set('psuid', $psuid)
            ->set('access_token', $access_token)
            ->set('expired', $expires_in)
            ->replace();
    }

    function unbind($uid,$psname) {
        if(!$uid||!$psname) return;
        $this->db->from($this->table)->where('uid',$uid)->where('psname',$psname)->delete();
    }
}
?>