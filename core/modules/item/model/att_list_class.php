<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_att_list extends ms_model {
    
    var $table = 'dbpre_att_list';
    var $key = 'attid';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    function msm_item_att_list() {
        $this->__construct();
    }

    function save($catid, $names, $type='att') {
        if(!$list = explode('|', $names)) return;
        foreach($list as $name) {
            if(!$name) continue;
            $post = array('catid' => $catid,'name' => $name,'type'=>$type,'listorder' => 0);
            $attid = parent::save($post,null,false,false);
        }
        $this->write_cache($catid);
        return $attid;
    }

    function update($post) {
        if(empty($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('attid',$id);
            $this->db->update();
        }
    }

    function delete($attids) {
        $ids = parent::get_keyids($attids);
        $where = array('attid'=>$ids);
        $this->_delete($where);
    }

    function delete_catid($catids,$type='att') {
        $where = array('catid'=>$catids,'type'=>$type);
        $this->_delete($where);
    }

    function write_cache($catid=null) {
        if(!$catid) return;
        $this->db->from($this->table);
        $this->db->where('type', 'att');
        $this->db->where('catid', $catid);
		$this->db->order_by('listorder');
        $result = array();
        if($q = $this->db->get()) {
            while($v=$q->fetch_array()) {
                $result[$v['attid']] = $v;
            }
            $q->free_result();
        }
        write_cache('att_list_' . $catid, arrayeval($result), $this->model_flag);
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['name']) redirect('itemcp_taggeoup_empty_name');
    }

    function export($catid) {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('catid',$catid);
        $this->db->order_by("attid");
        if(!$row = $this->db->get()) return '';
		while ($value = $row->fetch_array()) {
			$split = '';
            unset($value['attid']);
            $value['catid'] = '{catid:'.$value['catid'].'}';
			$result[] = $value;
		}
		return $result;
    }

    function _delete($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$q=$this->db->get()) return;
        $attids = array();
        while($v=$q->fetch_array()) {
            $attids[] = $v['attid'];
        }
        $q->free_result();

        //其他模块关联的HOOK
        foreach(array_keys($this->global['modules']) as $flag) {
            //if($flag == $this->model_flag) continue;
            $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'item_att_delete_hook.php';
            if(is_file($file)) {
                 include $file;
            }
        }

        $this->db->from($this->table);
        $this->db->where_in('attid', $attids);
        $this->db->delete();
    }

}
?>