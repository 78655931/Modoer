<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_exchange_serial extends ms_model {

    var $table = 'dbpre_exchange_serial';
    var $key = 'giftid';
    var $model_flag = 'exchange';
	
    function __construct() {
        parent::__construct();
        $this->model_flag = 'exchange';
        $this->modcfg = $this->variable('config');
    }
	
	function get_gift() {
		return $this->loader->model('exchange:gift');
	}
	
	function find($giftid,$start,$offset) {
		$result = array(0,null);
		$this->db->from($this->table);
		$this->db->where('giftid',$giftid);
		$result[0] = $this->db->count();
		if($result[0]>0) {
			$this->db->sql_roll_back('from,where');
			$this->db->limit($start,$offset);
			$result[1] = $this->db->get();
		}
		return $result;
	}
	
	function getlist($exchangeid,$uid) {
		return $this->db->from($this->table)
			->where('exchangeid',$exchangeid)
			->where('uid',$uid)
			->get();
	}
	
	function save($giftid,$serial,$show_error=true) {
		$gift = $this->get_gift()->read($giftid);
		if(empty($gift)) redirect('exchange_gift_empty');
		if($gift['sort']!='2') return;
		$list = $this->_parser($serial);
		if(empty($list) && $show_error) redirect('exchangecp_serial_add_empty');
		if($list) foreach ($list as $value) {
			$set = array();
			$set['giftid'] = $giftid;
			$set['serial'] = $value;
			$set['status'] = 1;
			$set['dateline'] = $this->global['timestamp'];
			$this->db->from($this->table)
				->set($set)->insert();
		}
		$num = $this->get_num($giftid);
		$this->get_gift()->update_num($giftid, $num);
		return $num;
	}
	
	function get_serial($giftid, $num) {
		$r = $this->db->from($this->table)
			->where('giftid',$giftid)
			->where('status',1)
			->limit(0,$num)
			->order_by('id')
			->get();
		if(!$r) return false;
		$ids = array();
		while ($val = $r->fetch_array()) {
			$ids[]=$val[id];
		}
		$r->fetch_array();
		return $ids;
	}
	
	function update_serial($ids, $exchangeid) {
		$this->db->from($this->table)
			->where('id', $ids)
			->set('status',0)
			->set('exchangeid', $exchangeid)
			->set('uid', $this->global['user']->uid)
			->set('sendtime', $this->timestamp)
			->update();
	}
	
	function delete($giftid, $ids) {
		$ids = parent::get_keyids($ids);
		$this->db->from($this->table)
			->where('id',$ids)
			->where('giftid',$giftid)
			->delete();
		$num = $this->get_num($giftid);
		$this->get_gift()->update_num($giftid, $num);
	}

    function delete_gift($giftid) {
        $ids = parent::get_keyids($giftid);
        $this->db->from($this->table)->where('giftid', $ids)->delete();
    }
	
	function get_num($giftid) {
		return $this->db->from($this->table)
			->where('giftid',$giftid)
			->where('status',1)
			->count();
	}
	
	function _parser($serial) {
		$serial = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/","\n",$serial);
		$list = explode("\n", $serial);
		foreach ($list as $key => $value) {
			if(trim($value)=='') $list[$key];
		}
		return $list;
	}
}