<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_att_data extends ms_model {
    
    var $table = 'dbpre_subjectatt';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    function msm_item_att_data() {
        $this->__construct();
    }

    function check_atts($catid, $attids) {
        $cats = $this->loader->variable('att_cat','item');
        if(!isset($cats[$catid])) return '';
        $atts = $this->loader->variable('att_list_'.$catid, 'item');
        if(!is_array($attids)) $attids = array($attids);
        $result = array();
        foreach($attids as $attid) {
            if(isset($atts[$attid])) $result[] = $attid;
        }
        return !$result ? '' : implode(',', $result);
    }

    function get_list($sid,$type='att') {
        $q=$this->db->from($this->table)->where(array('sid'=>$sid,'type'=>$type))->get();
        if(!$q) return false;
        $r = array();
        while($v=$q->fetch_array()) {
            $r[$v['attid']] = $v;
        }
        $q->free_result();
        return $r;
    }

    function save($sid, $attid, $type='att') {
        //if(!$sid) redirect(lang('global_sql_keyid_invalid','sid'));
        if(!$sid) return;
        if(is_array($attid)) {
            foreach($attid as $_attid) $this->save($sid,$_attid,$type);
        } else {
            //if(!$attid) redirect(lang('global_sql_keyid_invalid','attid'));
            if(!$attid) return;
            if($this->exists($sid,$attid)) return;
            $this->db->from($this->table);
            $this->db->set('sid', $sid);
            $this->db->set('attid', $attid);
            $this->db->set('type', $type);
            //$this->db->set('att_catid', $catid);
            $this->db->insert();
            return $this->db->insert_id();
        }
    }

    function add($catid,$sid,$attids,$type='att') {
        if(!$attids) return;
        if(!is_array($attids)) {
            if(!$attids = explode(',', $attids))return;
        }
        foreach($attids as $attid) {
            $this->db->from($this->table);
            $this->db->set('att_catid',$catid);
            $this->db->set('sid',$sid);
            $this->db->set('attid',$attid);
            $this->db->set('type', $type);
            $this->db->insert();
        }
        return $attids;
    }

    function replace($catid, $sid, $newatts, $oldatts) {
        $new = $old = true;
        if(!$newatts) $new = false;
        if(!$oldatts) $old = false;
        if(!$new && !$old) return;
        if(!$new && $old) {
            $this->delete($catid, $sid, $oldatts);
            return;
        } elseif($new && !$old) {
            $this->add($catid, $sid, $newatts);
            return;
        }
        //对比新的和旧的是否有存在相同的，如果存在相同则不删除也不添加
        $newatts = explode(',', $newatts);
        $oldatts = explode(',', $oldatts);
        $adds = $dels = $keeps = array();
        foreach($newatts as $_val) {
            if(!in_array($_val, $oldatts)) {
                $adds[] = $_val;
            }
        }
        foreach($oldatts as $_val) {
            if(!in_array($_val, $newatts)) {
                $dels[] = $_val;
            }
        }
        //如果没有变化，则返回旧数据
        if(empty($dels) && empty($adds)) {
            return implode(',',$oldatts);
        }
        $this->delete( $catid, $sid, $dels);
        $result = $this->add($catid, $sid, $adds);
    }

    //$catid第一参数去除
    function delete($sid, $attids, $type='att') {
        if(!$attids) return;
        if(!is_array($attids)) {
            if(!$attids = explode(',', $attids))return;
        }
        $this->db->from($this->table);
        //$this->db->where('att_catid',$catid);
        $this->db->where('sid',$sid);
        $this->db->where_in('attid', $attids);
        $this->db->where('type', $type);
        $this->db->delete();
    }

    function exists($sid, $attid) {
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->where('attid', $attid);
        return $this->db->count() > 0;
    }

    function delete_sid_catid($sid,$catid,$type='att') {
        if(!$sid) return;
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->where('att_catid', $catid);
        $this->db->where('type', $type);
        $this->db->delete();
    }

    function delete_sid($sid,$type='att') {
        if(!$sid) return;
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        $this->db->where('type', $type);
        $this->db->delete();
    }

    function delete_attid($attid) {
        if(!$attid) return;
        $this->db->from($this->table);
        $this->db->where('attid', $attid);
        $this->db->delete();
    }

}
?>