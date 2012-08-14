<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_category extends ms_model {

    var $model_flag = 'product';
    var $table = 'dbpre_product_category';
    var $key = 'catid';

    function __construct() {
        parent::__construct();
    }

    function msm_product_category() {
        $this->__construct();
    }

    function get_list($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->order_by('num','DESC');
        if(!$r = $this->db->get()) return;
        while($v=$r->fetch_array()) {
            $result[$v['catid']] = $v;
        }
        return $result;
    }

    function create($sid,$catname) {
        if(!$sid) redirect(lang('global_sql_keyid_invalid', 'sid'));
        $S =& $this->loader->model('item:subject');
        if(!$this->in_admin && !$S->is_mysubject($sid, $this->global['user']->uid)) redirect('global_op_access');
        if(!$catname) redirect('product_category_name_empty');
        if(defined('IN_AJAX') && $this->global['charset'] != 'utf-8') {
            $this->loader->lib('chinese',null,0);
            $chs = new ms_chinese('utf-8', $this->global['charset']);
            $catname = $chs->Convert($catname);
            unset($chs);
        }
        $catname = _T($catname);

        if($this->check_exists($sid,$catname)) redirect('product_category_name_exists');
        $this->db->from($this->table);
        $this->db->set('sid',$sid);
        $this->db->set('name',$catname);
        $this->db->insert();
        return $this->db->insert_id();
    }

	function rename($catid,$catname) {
		if(!$catid) redirect(lang('global_sql_keyid_invalid', 'catid'));
		if(!$detail = $this->read($catid)) redirect('product_category_empty');
		$sid = $detail['sid'];
        $S =& $this->loader->model('item:subject');
        if(!$this->in_admin && !$S->is_mysubject($sid, $this->global['user']->uid)) redriect('global_op_access');
        if(!$catname) redirect('product_category_name_empty');
        if(defined('IN_AJAX') && $this->global['charset'] != 'utf-8') {
            $this->loader->lib('chinese',null,0);
            $chs = new ms_chinese('utf-8', $this->global['charset']);
            $catname = $chs->Convert($catname);
            unset($chs);
        }
        $catname = _T($catname);

        if($this->check_exists($sid,$catname,$catid)) redirect('product_category_name_exists');
        $this->db->from($this->table);
        $this->db->where('catid',$catid);
        $this->db->set('name',$catname);
        $this->db->update();
	}

    function delete($catid) {
        if(!is_numeric($catid)||$catid<0) redirect('global_op_unselect');
        $P =& $this->loader->model(':product');
        $P->delete_catid($catid);
        parent::delete(array($catid));
    }

    function check_exists($sid,$catname,$outcatid=null) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('name',$catname);
		if($outcatid>0) $this->db->where_not_equal('catid',$outcatid);
        return $this->db->count() > 0;
    }

}
?>