<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
class msm_exchange_category extends ms_model {

    var $table = 'dbpre_exchange_category';
	var $key = 'catid';
    var $model_flag = 'exchange';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'exchange';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_exchange_category() {
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

    function delete($catids,$delete_gift = TRUE) {
        $ids = parent::get_keyids($catids);
        if(!$delete_gift) return;
        $EX =& $this->loader->model('exchange:gift');
        $EX->delete_catids($ids);
        unset($EX);
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
        if(!$post['name']) redirect('分类名称不能为空');
    }

    function updatenum($catid,$num=1) {
        $catid = (int) $catid;
        if(!$catid || !$num) return;
        $this->db->from($this->table);
        $this->db->where('catid',$catid);
        $fun = $num > 0 ? 'set_add' : 'set_dec';
        $this->db->$fun('num', abs($num));
        $this->db->update();
    }

    function rebuild($catids = null) {
        $this->db->from($this->table);
        if($catids) $this->db->where_in('catid',$catids);
        if(!$r=$this->db->get()) return;
        while($v=$r->fetch_array()) {
            $this->db->from('dbpre_exchange_gifts');
            $this->db->where('catid',$v['catid']);
            $this->db->where('available',1);
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