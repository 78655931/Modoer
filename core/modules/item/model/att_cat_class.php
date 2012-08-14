<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_att_cat extends ms_model {
    
    var $table = 'dbpre_att_cat';
    var $key = 'catid';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
        $this->init_field();
    }

    function msm_item_att_cat() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('name,des');
		$this->add_field_fun('name,des', '_T');
	}

    function save($post,$catid=null) {
        $edit = $catid > 0;
        if($edit) {
            if(!$detail=$this->read($catid)) redirect('item_att_cat_empty');
        }
        return parent::save($post, $catid);
    }

    function delete($catids) {
        $ids = parent::get_keyids($catids);
        $AL =& $this->loader->model('item:att_list');
        $AL->delete_catid($ids);
        unset($AL);
        parent::delete($ids);
    }

    function write_cache() {
        $this->db->from($this->table);
        $result = array();
        if($query = $this->db->get()) {
            while($val = $query->fetch_array()) {
                $result[$val['catid']] = $val;
            }
            $query->free_result();
        }
        write_cache('att_cat', arrayeval($result), $this->model_flag);
        if($result) {
            //缓存属性组
            $AL =& $this->loader->model('item:att_list');
            foreach(array_keys($result) as $catid) {
                $AL->write_cache($catid);
            }
        }
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['name']) redirect('itemcp_taggeoup_empty_name');
    }

    //导出属性组
    function export($catid) {
        if(!$catid) redirect(lang('global_sql_invalid','catid'));
        if(!$detail = $this->read($catid)) redirect('item_att_empty');
        $L =& $this->loader->model('item:att_list');
        $list = $L->export($catid);
        if($list) $detail['list']=$list;
        return $detail;
    }

    //导入属性组
    function import($array) {
        $replace = array();
        $AL =& $this->loader->model('item:att_list');
        foreach($array as $cat) {
            $catid = $cat['catid'];
            $list = $cat['list'];
            unset($cat['catid'],$cat['list']);
            $newcatid = $this->save($cat);
            $replace[$catid] = $newcatid;
            if($list) {
                $list_str = '';
                foreach($list as $val) {
                    $list_str.= '|' . $val['name'];
                }
                $list_str = substr($list_str,1);
                $AL->save($newcatid,$list_str);
            }
        }
        return $replace;
    }
}
?>