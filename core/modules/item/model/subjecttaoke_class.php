<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_subjecttaoke extends ms_model {

    var $table = 'dbpre_subjecttaoke';
	var $key = 'user_id';

	function __construct() {
		parent::__construct();
	}
	
	function add($user_id,$sid,$nick) {
		if(!$this->exists($user_id)) {
			$this->db->from($this->table);
			$this->db->set('user_id',$user_id);
			$this->db->set('sid',$sid);
			$this->db->set('nick',$nick);
			$this->db->insert();
			return TRUE;
		}
		return FALSE;
	}
	
	function exists($user_id) {
		$this->db->from($this->table);
		$this->db->where('user_id', $user_id);
		if(!$data = $this->db->get_one()) return false;
		return $data;
	}
	
	function exists_list($user_ids) {
		$this->db->from($this->table);
		$this->db->where('user_id', $user_ids);
		if(!$q=$this->db->get()) return;
		$result = array();
		while ($v=$q->fetch_array()) {
			$result[$v['user_id']] = $v;
		}
		return $result;
	}

	function delete_sids($sids) {
		$this->db->from($this->table);
		$this->db->where('sid',$sids);
		$this->db->delete();
	}
	
	function delete_user_ids($user_ids) {
		$this->db->from($this->table);
		$this->db->where('user_id',$user_ids);
		$this->db->delete();
	}
}
?>