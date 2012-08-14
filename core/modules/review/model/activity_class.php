<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_review_activity extends ms_model {

    var $table = 'dbpre_activity';
	var $key = 'aid';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'review';
        $this->modcfg = $this->variable('config');
		$this->init_field();
	}

    function msm_item_activity() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('uid');
		$this->add_field_fun('uid', 'intval');
	}

	function save($uid,$username,$num=1) {
        $time = date('Ymd', $this->global['timestamp']);
        if(!$uid || !$username || !$num) return;
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('dateline', $time);
        $this->db->select('aid');
        $aid = $this->db->get_value();
        $this->db->sql_roll_back('from');
        if($aid) {
            $this->db->where('aid', $aid);
            $this->db->set_add('reviews', $num);
            $this->db->update();
        } else {
            $post = array(
                'dateline' => $time,
                'uid' => $uid,
                'username' => $username,
                'reviews' => $num,
            );
            $this->db->set($post);
            $this->db->insert();
            $aid = $this->db->insert_id();
        }
        return $fid;
	}
}
?>