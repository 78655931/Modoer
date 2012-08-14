<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

class msm_menu extends ms_model {

	var $table = 'dbpre_menus';
    var $key = 'menuid';

    function __construct() {
        parent::__construct();
    }

    function msm_menu() {
		$this->__construct();
    }

    function read_all($parentid=null,$isfolder=FALSE) {
        $parentid = (int) $parentid;

        $this->db->where('parentid', $parentid);
        if(!$parentid || $isfolder) {
            $this->db->where('isfolder', '1');
        }
        $this->db->from($this->table);
		$this->db->order_by('listorder');
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;
        while($value=$row->fetch_array()) {
            $result[] = $value;
        }
        return $result;
    }

    function save($post, $id=null, $upcache=TRUE) {
        if($id && $id == $post['parentid']) redirect('admincp_menu_parentid_menuid_invalid');
        $id = parent::save($post, $id, $upcache);
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $menuid => $val) {
            $val['isclosed'] = (int) $val['isclosed'];
            $this->save($val, $menuid, FALSE);
        }
        $this->write_cache();
    }

    function delete($menuids) {
        if(!$menuids) redirect('global_op_unselect');
        if(is_array($menuids)) {
            foreach($menuids as $meunid) {
                if(!$meunid) continue;
                $this->delete($meunid);
            }
        } else {
            $list = array();
            $this->_read_child_row($menuids, $list);
            $list[] = $menuids;
            parent::delete($list, FALSE);
        }
        $this->write_cache();
    }

    function read_child_list() {
        $this->db->where('isfolder', 1);
        $this->db->from($this->table);
        $row = $this->db->get();
        while($v=$row->fetch_array()) {
            $result[$v['parentid']][$v['menuid']] = $v['title'];
        }
        return $result;
    }

    function create_leve_option($menuid,$prentid=0,$level=0) {
        global $menulist;
        $content = '';
        foreach($menulist[$prentid] as $key => $val) {
            $selected = $menuid == $key ? ' selected' : '';
            $content .= "<option value=\"$key\"$selected>".str_repeat('&nbsp;&nbsp;',$level+1)."©¸".$val."</option>";
            if($menulist[$key]) {
                $content .= $this->create_leve_option($menuid,$key, $level+1);
            }
        }
        return $content;
    }

    function _read_child_row($parentid, & $list) {
        $this->db->where('parentid', $parentid);
        $this->db->from($this->table);
        $this->db->select('menuid,parentid,isfolder');
        $row = $this->db->get();
        if(!$row) return FALSE;
        while($value=$row->fetch_array()) {
            $list[] = $value['menuid'];
            if($value['isfolder']) {
                if($res = $this->_read_child_row($value['menuid'], $list)) {
                    $result = array_merge($list, $res);
                }
            }
        }
        return $list;
    }

    function check_post(& $post, $edit) {
        if(!$post['title']) redirect('admincp_menu_empty_title');
        if(!$post['isfolder'] && !$post['url']) redirect('admincp_menu_empty_url');
    }

    function write_cache() {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('parentid', 0);
        $this->db->where('isclosed', 0);
        $this->db->order_by('listorder');  
        if($row = $this->db->get()) {
            while($val=$row->fetch_array()) {
                $result[$val['menuid']] = $val;
                $this->_write_cache($val['menuid']);
            }
        }
        write_cache('menus', arrayeval($result), $this->model_flag);  
    }

    function _write_cache($pid) {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('parentid', $pid);
        $this->db->where('isclosed', 0);
        $this->db->order_by('listorder');  
        if($row = $this->db->get()) {
            while($val=$row->fetch_array()) {
                $result[$val['menuid']] = $val;
            }
        }
        write_cache('menu_' . $pid, arrayeval($result), $this->model_flag); 
    }
}
?>