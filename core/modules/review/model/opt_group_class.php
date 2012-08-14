<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_review_opt_group extends ms_model {
    
    var $table = 'dbpre_review_opt_group';
    var $key = 'gid';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'review';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_review_opt_group() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('name,des');
		$this->add_field_fun('name,des', '_T');
	}

    function save($post,$gid=null) {
        $edit = $id > 0;
        if($edit) {
            if(!$detail=$this->read($gid)) redirect('review_opt_group_empty');
        }
        $gid = parent::save($post, $edit?$edit:$gid);
		if(!$edit) {
			$RO =& $this->loader->model($this->model_flag.':opt');
			$RO->create($gid);
		}
		return $gid;
    }

    function delete($gid) {
		if($this->check_used($gid)) redirect('review_opt_group_used');
        $RO =& $this->loader->model($this->model_flag.':opt');
        $RO->delete($gid);
        parent::delete($gid);
    }

    function write_cache() {
        $this->db->from($this->table);
        $result = array();
        if($query = $this->db->get()) {
            while($val = $query->fetch_array()) {
                $result[$val[$this->key]] = $val;
            }
            $query->free_result();
        }
        write_cache('opt_group', arrayeval($result), $this->model_flag);
        if($result) {
            //»º´æÊôÐÔ×é
            $AL =& $this->loader->model($this->model_flag.':opt');
            foreach(array_keys($result) as $catid) {
                $AL->write_cache($catid);
            }
        }
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['name']) redirect('review_optmod_empty_name');
    }

	function check_used($gid) {
		$this->db->from('dbpre_category');
		$this->db->where('review_opt_gid',$gid);
		return $this->db->count()>0;
	}
}
?>