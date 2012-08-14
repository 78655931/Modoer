<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_point_log extends ms_model {

	var $table = 'dbpre_member_point_log';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
    }

	function getlist($uid, $start, $offset) {
		$result = array(0,null);
		$this->db->from($this->table);
		$this->db->where('out_uid', $uid);
		$this->db->where('in_uid', $uid, 'OR');
		if($result[0] = $this->db->count()) {
			$this->db->sql_roll_back('from,where');
			$this->db->order_by('id','DESC');
			$this->db->limit($start,$offset);
			$result[1] = $this->db->get();
		}
		return $result;
	}

}