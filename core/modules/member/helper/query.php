<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_member {

    function detail($params=null) {
        extract($params);

        if(!$uid && !$username) return false;

        $loader =& _G('loader');
        $db =& _G('db');
        
        $db->from('dbpre_members');
        $db->select($select?$select:'uid,username,email,groupid,point,reviews,subjects,responds,flowers');
        if($uid) $db->where('uid', $uid);
        if($username) $db->where('username', $username);
        $result[] = $db->get_one();
        return $result;
    }

    function feed($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

        $db->from('dbpre_member_feed');
        $db->select($select?$select:'*');
		if($city_id) $db->where('city_id',explode(',',$city_id));
        if($uid > 0) $db->where('uid', $uid);
        if($module) $db->where('module', $module);
        if($module) $db->where('module', $module);
        if(!$orderby) $orderby = 'id DESC';
        $db->order_by($orderby);
        $db->limit($start, $rows);
        if(!$r = $db->get()) { return null; }

        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    function point_groups($params=null) {
        $loader =& _G('loader');
        $config = $loader->variable('config','member');
        $result = array();
        if($config['point_group']) {
            $result = unserialize($config['point_group']);
        }
        return $result;
    }

}
?>