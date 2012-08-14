<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

class msm_announcement extends ms_model {

	var $table = 'dbpre_announcements';
    var $key = 'id';

	function __construct() {
		parent::__construct();
		$this->init_field();
	}

    function msm_announcement() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('title,city_id,orders,available,content');
		$this->add_field_fun('city_id,orders,available', 'intval');
        $this->add_field_fun('title', '_T');
        $this->add_field_fun('content', '_HTML');
	}

    function save(& $post, $id=NULL, $cache=TRUE) {
        $this->check_post($post);
        if(!$id) {
            $post['author'] = $this->global['admin']->adminname;
            $post['dateline'] = $this->global['timestamp'];
        }
        return parent::save($post, $id, $cache, FALSE);
    }

    function update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $val['available'] = (int) $val['available'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('id',$id);
            $this->db->update();
        }
    }

    function check_post($post,$is_edit=false) {
        if(!$post['title']) redirect('admincp_ann_title_empty');
        if(!$post['content']) redirect('admincp_ann_content_empty');
    }
}
?>