<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_membereffect extends ms_model {

    var $table = 'dbpre_membereffect';
    var $total_table = 'dbpre_membereffect_total';
    var $key = 'id';

    var $idtype = '';
    var $effect = '';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->idtype = array();
        $this->effect = array('effect1','effect2','effect3');
    }
    
    function msm_member_membereffect() {
        $this->__construct();
    }

    function add_idtype($idtype, $tablename, $keyname) {
        $this->idtype[$idtype] = array($tablename, $keyname);
    }

    function save($id, $idtype, $effect, $total = TRUE) {
        if(!$this->global['user']->isLogin) {
            redirect('member_not_login');
        }

        $post = array('id'=>$id, 'idtype'=>$idtype, $effect=>1);
        $post['dateline'] = $this->global['timestamp'];
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;

        $this->check_post($post);

        if($this->submitted($this->global['user']->uid, $id, $idtype, $effect)) {
            redirect('member_effect_submitted');
        }

        $this->db->from($this->table);
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        $this->db->where('uid', $this->global['user']->uid);

        $detail = $this->db->get_one();

        if(!$detail) {
            if(!$detail[$effect]) {
                parent::save($post, null, FALSE, FALSE, FALSE);
            }
        } else {
            $this->db->sql_roll_back('from,where');
            $this->db->set($effect, 1);
            $this->db->update();
        }
        if($total) $this->update_total($id, $idtype, $effect);

        return $id;
    }

    function check_post($post) {
        if(!is_numeric($post['id']) || $post['id'] < 1) {
            redirect('member_effect_empty_id');
        }
        if(isset($this->idtype[$post[$idtype]])) {
            redirect('member_effect_unkown_idtype');
        }
    }

    function total($id, $idtype) {
        $this->db->from('dbpre_membereffect_total');
        $this->db->where('id', $id);
        $this->db->where('idtype', $idtype);
        return $this->db->get_one();
    }

    function get_member($id, $idtype, $effect) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->where('idtype', $idtype);
        $this->db->where($effect, 1);
        return $this->db->get();
    }

    function submitted($uid, $id, $idtype, $effect) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->where('uid', $uid);
        $this->db->where('idtype', $idtype);
        $this->db->where($effect, 1);
        return $this->db->count() >= 1;
    }

    function update_total($id, $idtype, $effect, $num=1) {
        $this->db->from('dbpre_membereffect_total');
        $this->db->where('id', $id);
        $this->db->where('idtype', $idtype);
        $exists = $this->db->count() > 0;
        $this->db->sql_roll_back('from');
        if($exists) {
            $this->db->set_add($effect, $num);
            $this->db->update();
        } else {
            $this->db->set('id', $id);
            $this->db->set('idtype', $idtype);
            $this->db->set($effect, $num);
            $this->db->insert();
        }
    }

    function delete($id, $idtype) {
        if(!$id||!$idtype) return;
        if(is_array($id)) {
            $where_fun = is_array($id) ? 'where_in' : 'where';
        }

        $this->db->from($this->table);
        $this->db->$where_fun('id', $id);
        $this->db->where('idtype',$idtype);
        $this->db->delete();

        $this->db->from($this->total_table);
        $this->db->$where_fun('id', $id);
        $this->db->where('idtype', $idtype);
        $this->db->delete();
    }
}