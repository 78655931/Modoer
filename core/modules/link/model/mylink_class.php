<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_link_mylink extends ms_model {

    var $table = 'dbpre_mylinks';
	var $key = 'linkid';
    var $model_flag = 'link';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'link';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_link_mylink() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('title,link,logo,des,displayorder,ischeck');
		$this->add_field_fun('displayorder', 'intval');
        $this->add_field_fun('title,link,logo,des', '_T');
	}

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
        if($where && isset($where['nq_logo'])) {
            $this->db->where_not_equal('logo','');
            unset($where['nq_logo']);
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

    function save($post, $linkid = NULL) {
        $edit = $linkid != null;
        if($edit) {
            if(!$detail = $this->read($linkid)) redirect('link_empty');
        } else {
            if(!$this->in_admin && !$this->modcfg['open_apply']) redirect('link_post_apply_disabled');
            if(!$this->in_admin) $post['ischeck'] = 0;
        }
        $linkid = parent::save($post,$linkid);
        return $linkid;
    }

    function checkup($linkids) {
        $ids = parent::get_keyids($linkids);
        $this->db->from($this->table);
        $this->db->set('ischeck',1);
        $this->db->where_in('linkid',$linkids);
        $this->db->update();
        $this->write_cache();
    }

    function delete($linkids) {
        $ids = parent::get_keyids($linkids);
        parent::delete($ids);
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $linkid => $val) {
            parent::save($val,$linkid,FALSE,TRUE,TRUE);
        }
        $this->write_cache();
    }

    function check_post(& $post, $edit = false) {
        if(!$post['title']) redirect('link_post_title_empty');
        if(!$post['link']) redirect('link_post_link_empty');
    }

    function get_check_count() {
        $this->db->from($this->table);
        $this->db->where('ischeck',0);
        return $this->db->count();
    }

    function write_cache() {
        $result = array();
        $result['logo'] = $this->_get_link_logo();
        $result['char'] = $this->_get_link_char();
        write_cache('list', arrayeval($result), $this->model_flag);
    }

    function _get_link_logo() {
        $num_logo = $this->modcfg['num_logo'] > 0 ? $this->modcfg['num_logo'] : 5;
        $result = array();
        $select = 'title,link,logo,des';
        $where = array('ischeck'=>1);
        $where['nq_logo'] = 1;
        list(,$list) = $this->find($select,$where,'displayorder',0,$num_logo,false);
        if($list) {
            while($v=$list->fetch_array()) {
                $result[] = $v;
            }
            $list->free_result();
        }
        return $result;
    }

    function _get_link_char() {
        $num_char = $this->modcfg['num_char'] > 0 ? $this->modcfg['num_char'] : 20;
        $result = array();
        $select = 'title,link,logo,des';
        $where = array('ischeck'=>1,'logo'=>'');
        list(,$list) = $this->find($select,$where,'displayorder',0,$num_char,false);
        if($list) {
            while($v=$list->fetch_array()) {
                $result[] = $v;
            }
            $list->free_result();
        }
        return $result;
    }
}
?>