<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_exchange_lottery extends ms_model {

    var $table = 'dbpre_exchange_lottery';
    var $gift_table = 'dbpre_exchange_gifts';
    var $key = 'lid';
    var $model_flag = 'exchange';
	
    function __construct() {
        parent::__construct();
        $this->model_flag = 'exchange';
        $this->modcfg = $this->variable('config');
    }

    function msm_exchange_lottery() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('giftid,uid,lotterycode,status,dateline');
		$this->add_field_fun('giftid,uid,lotterycode,status,dateline', 'intval');
	}

    function makecode() {
        $lid = 'GIFT'.time().mt_rand(100000,999999);
        return $lid;
    }

	function read($lid,$giftid) {
        $this->db->from($this->table);
        $this->db->select('*');
        $this->db->where('lid',$lid);
        $this->db->where('giftid',$giftid);
        $this->db->where('uid',$this->global['user']->uid);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=20, $total=FALSE, $join_select='') {
	    if($join_select) {
            $this->db->join($this->table, 'l.giftid', $this->gift_table, 'g.giftid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'l');
        }
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        $join_select && $this->db->select($join_select);
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

	function save($post) {
        $post['dateline'] = $this->global['timestamp'];
        $lid = parent::save($post,null);
        return $lid;
    }

    function delete($lids) {
        $ids = parent::get_keyids($lids);
        parent::delete($ids);
    }

    function delete_gift($giftid) {
        $ids = parent::get_keyids($giftid);
        $this->db->from($this->table)->where('giftid', $ids)->delete();
    }

    //检测是否中奖和是否已兑奖
    function check_exists($rcode,$status=FALSE) {
        $this->db->from($this->table);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->where('lotterycode', $rcode);
        if($status) $this->db->where('status', '1');
        return $this->db->count() > 0;
    }
}
?>