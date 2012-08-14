<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

// 最大点评项数量，此处修改需要同步增加 item_total 里的点评项字段数量
define('MODOER_REVIEW_OPTION_NUM', 8);

class msm_review_opt extends ms_model {
    
    var $table = 'dbpre_review_opt';
    var $key = 'id';

    function __construct() {
        parent::__construct();
		$this->model_flag = 'review';
        $this->modcfg = $this->variable('config');
    }

    function msm_review_opt() {
        $this->__construct();
    }

    function & getlist($gid, $filter_disable = FALSE) {
        $this->db->from($this->table);
        $this->db->where('gid', $gid);
		if($filter_disable) $this->db->where('enable', 1);
        $this->db->order_by('listorder');
        $this->db->limit(0, MODOER_REVIEW_OPTION_NUM);
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value=$row->fetch_array()) {
            $result[] = $value;
        }
        return $result;
    }

    function save($opts, $gid) {
        if(empty($gid)||empty($opts)) return;
        foreach($opts as $id => $data) {
            $data['listorder'] = intval($data['listorder']);
            $data['enable'] = intval($data['enable']);
            $this->db->set($data);
            $this->db->from($this->table);
            $this->db->where('id', (int) $id);
            $this->db->where('gid', $gid);
            $this->db->update();
        }

        $this->write_cache();
    }

    function create($gid) {
        $this->db->from($this->table);
        $this->db->where('gid', $gid);
        
        $total = $this->db->count();
        if($total == MODOER_REVIEW_OPTION_NUM) {
            return;
        }
        $total > 0 and $this->delete($gid);

        for($i = 1 ; $i <= MODOER_REVIEW_OPTION_NUM; $i++) {
            $inlist = array();
            $inlist['gid'] = $gid;
            $inlist['flag'] = 'sort'.$i;
            $inlist['name'] = 'R'.$i;
            $inlist['listorder'] = $i;

            $this->db->set($inlist);
            $this->db->from($this->table);
            $this->db->insert();
        }
        $this->write_cache();
    }

    function delete($gid) {
        $this->db->where('gid', $gid);
        $this->db->from($this->table);
        $this->db->delete();
    }

    function write_cache($gid = null) {
        if($gid) {
            $this->_write_cache($gid);
            return;
        }
        $this->db->from('dbpre_review_opt_group');
        if(!$row = $this->db->get()) return;
        while($value = $row->fetch_array()) {
            $this->_write_cache($value['gid']);
        }
    }

    function _write_cache($gid) {
        $this->db->from($this->table);
        $this->db->where('gid', $gid);
		$this->db->where('enable', 1);
        $this->db->order_by(array('listorder'=>'ASC', 'id'=>'ASC'));
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value = $row->fetch_array()) {
            $result[$value['flag']] = $value;
        }
        $row->free_result();
        write_cache('opt_' . $gid, arrayeval($result), $this->model_flag);
    }
}
?>