<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_usergroup extends ms_model {

    var $table = 'dbpre_usergroups';
    var $key = 'groupid';
    var $model_flag = 'member';
    var $cache_name = 'usergroup';
    var $auto_check_write = TRUE;

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_member_usergroup() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('groupname,point,color,access,price');
        $this->add_field_fun('groupname,color', '_T');
        $this->add_field_fun('point,price', 'intval');
    }

    function read($groupid) {
        $result = parent::read($groupid);
        $result['access'] = $result['access'] ? unserialize($result['access']) : array();
        return $result;
    }

    function read_all($type=null) {
        $this->db->from($this->table);
        if($type) {
            $this->db->where('grouptype', $type);
            if($type=='member') {
                $this->db->order_by('point');
            }
        }
        $this->db->select('groupid,grouptype,groupname,point,color,price');
        return $this->db->get();
    }

    function update(& $post) {
        if(!is_array($post)) return;
        foreach($post as $key => $val) {
            $this->save($val, $key, FALSE);
        }
        $this->write_cache();
    }

    function save($post,$groupid=null) {
        $edit = $groupid!=null;
        if($edit) {
            if(!is_numeric($groupid) || $groupid < 1) redirect(lang('global_sql_keyid_invalid','groupid'));
            if(!$detail=$this->read($groupid)) redirect('member_usergroup_empty');
            isset($post['access']) && $post['access'] = $post['access'] ? serialize($post['access']) : '';
        }
        $groupid = parent::save($post,$groupid);
        return $groupid;
    }

    function & point_by_usergroup($point) {
        if(!isset($this->global['usergroup'])) {
            $this->global['usergroup'] = $this->variable('usergroup');
            if(!$this->global['usergroup']) return;
        }
        $groupid = 10;
        foreach($this->global['usergroup'] as $key => $val) {
            if($val['grouptype'] != 'member') continue;
            if($point > $val['point']) $groupid = $key;
        }
        return $groupid;
    }

    function group_upgrade() {
        $day = _input('day',0,MF_INT_KEY);
        $groupid = _input('groupid', 0, MF_INT_KEY);
        $useday = $day * 30;
        if(empty($useday)) redirect('member_group_upgrade_day_invalid');
        
        $group = $this->read($groupid);
        if(empty($group)) redirect('member_group_upgrade_empty');
        if($group['grouptype']!='special') redirect('member_group_upgrade_invalid');

        if($this->global['user']->groupid == $groupid && empty($this->global['user']->nexttime)) {
            redirect(lang('member_group_upgrade_do_without',display('member:group',"groupid/$groupid")));
        }
        
        $pt = $this->modcfg['sellgroup_pointtype'];
        if(!in_array($pt,array('point1','point2','point3','point4','point5','point6'))) redirect('global_op_function_disabled');
        $useday = $this->modcfg['sellgroup_useday'] > 0 ? $this->modcfg['sellgroup_useday'] : 30;
        $useday *= $day;
        $price = $group['price'] * $day;
        $myprice = $this->global['user']->$pt;
        if($myprice < $price) redirect('member_group_upgrade_insufficient_funds');

        //update point
        $point =& $this->loader->model('member:point');
        $point->update_point2($this->global['user']->uid, $pt, -$price, 
            lang('member_group_upgrade_des',array(display('member:group',"groupid/$groupid/color/0"),$useday)));
        //update group
        $usetime = $useday*$day*3600*24;
        $set = array();
        if($this->global['user']->groupid==$groupid) {
            $set['nexttime']=$this->global['user']->nexttime+$usetime;
        } elseif($this->global['user']->nexttime) { 
            $set['nextgroupid'] = 10;
        } else {
            $set['nextgroupid'] = $this->global['user']->groupid;
        }
        $set['groupid'] = $groupid;
        if(empty($set['nexttime'])) $set['nexttime'] = $this->timestamp + $usetime; //time
        $this->db->from('dbpre_members')->where('uid',$this->global['user']->uid)->set($set)->update();
    }

    function write_cache() {
        $result = $access  =  array();
        $this->db->from($this->table);
        $r=$this->db->get();
        while($value = $r->fetch_array()) {
            $value['access'] = $value['access'] ? unserialize($value['access']) : array();
            write_cache($this->cache_name.'_'.$value['groupid'], arrayeval($value), $this->model_flag);
            unset($value['access']);
            $result[$value['groupid']] = $value;
        }
        $r->free_result();
        write_cache($this->cache_name, arrayeval($result), $this->model_flag);
    }

    function write_cache_access($groupid) {
        if(!$result = $this->read($groupid)) return;
        write_cache($this->cache_name.'_'.$groupid, arrayeval($result), $this->model_flag);
    }

    function check_post(& $post, $edit = FALSE) {
        if($edit && isset($post['groupname']) && !$post['groupname']) redirect('membercp_usergroup_empty_name');
        if(!$edit && !$post['groupname']) redirect('membercp_usergroup_empty_name');
        if(!$edit && !in_array($post['grouptype'],array('member','special','system'))) redirect('membercp_usergroup_empty_type');
        if(!$edit && $post['grouptype']=='member' && !is_numeric($post['point'])) redirect('membercp_usergroup_empty_point');
    }
}
?>