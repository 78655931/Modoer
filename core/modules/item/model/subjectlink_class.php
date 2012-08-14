<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('MF_SUBJECTLINK_MOD_1','sid=>id'); 
define('MF_SUBJECTLINK_MOD_2','sid');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subjectlink extends msm_item_itembase {

    var $table = 'dbpre_subjectlink';
	var $key = 'id';

    var $s = null;

	function __construct() {
		parent::__construct();
		$this->init_field();
        $this->s =& $this->loader->model('item:subject');
	}

	function init_field() {
		$this->add_field('flagid,flag,sid,midelid,dateline');
		$this->add_field_fun('flagid,sid,midelid,dateline', 'intval');
	}

    function save($sids, $flagid, $flag, $dateline=0) {
        if(!$sids) {
            $this->clear($flagid, $flag); 
            return '';
        }
        $sids = explode(',', $sids);
        foreach($sids as $k=>$v) {
            $sids[$k] = _int_keyid($v);
            if(!$sids[$k]) unset($sids[$k]);
        }
        list(,$list) = $this->s->find('sid,pid,catid,city_id,aid,name,subname',array('sid'=>$sids), 'sid', 0, 0);
        if(empty($list)) {
            $this->clear($flagid, $flag); 
            return '';
        }
        $dels = $this->load($flagid, $flag);
        $adds = $add_sids = array();
        while($val = $list->fetch_array()) {
            if($dels && array_key_exists($val['sid'], $dels)) {
                unset($dels[$val['sid']]);
            } else {
                $add_sids[] = $val['sid'];
                $adds[] = array(
                    'sid' => $val['sid'],
                    'modelid' => $this->get_modelid($val['pid']),
                );
            }
        }
        $list->free_result();
        if($adds) {
            foreach($adds as $set) {
                $set['flagid'] = $flagid;
                $set['flag'] = $flag;
                $set['dateline'] = $dateline;
                parent::save($set);
            }
        }
        if($dels) {
            $ids = array_values($dels);
            parent::delete($ids);
        }
        $add_sids = $add_sids ? implode(',', $add_sids) : '';
        return $add_sids;
    }

    //mod 'sid=>id','sid'
    function load($flagid, $flag, $mod=MF_SUBJECTLINK_MOD_1) {
        $this->db->from($this->table);
        $this->db->where('flagid', $flagid);
        $this->db->where('flag', $flag);
        $q = $this->db->get();
        if(empty($q)) return;
        $r = null;
        while($v = $q->fetch_array()) {
            if($mod == MF_SUBJECTLINK_MOD_2) {
                $r[] = $v['sid'];
            } elseif($mod == MF_SUBJECTLINK_MOD_1) {
                $r[$v['sid']] = $v['id'];
            } else {
                $r[] = $v;
            }
        }
        $q->free_result();
        return $r;
    }

    //ЧхПе
    function clear($flagid, $flag) {
        $this->db->from($this->table);
        $this->db->where('flagid', $flagid);
        $this->db->where('flag', $flag);
        $this->db->delete();
    }

}
?>