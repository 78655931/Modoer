<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_coupon_category extends ms_model {

    var $table = 'dbpre_coupon_category';
	var $key = 'catid';
    var $model_flag = 'coupon';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'coupon';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_coupon_category() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('name,listorder');
		$this->add_field_fun('listorder', 'intval');
        $this->add_field_fun('name', '_T');
	}

    function save($post) {
        $catid = parent::save($post,null);
        return $catid;
    }

    function delete($catids,$delete_coupon = TRUE) {
        $ids = parent::get_keyids($catids);
        if(!$delete_coupon) return;
        $cop =& $this->loader->model(':coupon');
        $cop->delete_catids($ids);
        unset($cop);
        parent::delete($ids);
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $catid => $val) {
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('catid',$catid);
            $this->db->update();
        }
        $this->write_cache();
    }

    function check_post(& $post, $edit = false) {
        if(!$post['name']) redirect('couponcp_category_name_empty');
    }

    function rebuild($catids = null) {
        $this->db->from($this->table);
        if($catids) $this->db->where_in('catid',$catids);
        if(!$r=$this->db->get()) return;
        while($v=$r->fetch_array()) {
            $this->db->from('dbpre_coupons');
            $this->db->where('catid',$v['catid']);
            $this->db->where('status',1);
            $count = $this->db->count();
            $this->db->from($this->table);
            $this->db->set('num',$count);
            $this->db->where('catid',$v['catid']);
            $this->db->update();
        }
    }

    function write_cache() {
        $this->db->from($this->table);
        $this->db->order_by('listorder');
        $result = array();
        if($r = $this->db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        write_cache('category', arrayeval($result), $this->model_flag);
    }

}
?>