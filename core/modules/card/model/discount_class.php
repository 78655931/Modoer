<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_card_discount extends ms_model {

    var $table = 'dbpre_card_discounts';
	var $key = 'sid';
    var $model_flag = 'card';
    var $subject_table = 'dbpre_subject';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'card';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_card_discount() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('sid,cardsort,discount,largess,exception,available,finer');
		$this->add_field_fun('sid,available,finer', 'intval');
        $this->add_field_fun('largess,exception', '_T');
	}

    function find($select, $where, $orderby, $start, $offset, $total = TRUE, $join_subject_select = '') {
        if($where['pid'] && !$join_subject_select) $join_subject_select = 's.name,s.subname';
        if($join_subject_select) {
	        $this->db->join($this->table,'c.sid',$this->subject_table,'s.sid','LEFT JOIN');
        } else {
            $this->db->from($this->table);
        }
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

		$this->db->select($select ? $select : 'c.*');
        $join_subject_select && $this->db->select($join_subject_select);
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save($post, $sid = NULL) {
        $edit = $sid != null;
        if($edit) {
            if(!$detail = $this->read($sid)) redirect('card_empty');
        } else {
            $post['addtime'] = $this->global['timestamp'];
        }
        parent::save($post, $sid);

        $card_msg = $this->_get_card_msg($post);
        $this->save_subject_field($post['sid'], $card_msg);
        if($edit && $detail['sid'] != $post['sid']) {
            $this->delete_subject_field($detail['sid']);
        }
        return $sid;
    }

    function save_subject_field($sid,$discount) {
        $S =& $this->loader->model('item:subject');
        if(!$detail = $S->read($sid,'pid', FALSE)) return;
        if(!$model = $S->get_model($detail['pid'],TRUE)) return;
		$this->loader->helper('sql');
		if(!sql_exists_field($model['tablename'], 'card_msg')) return;
        $this->db->from('dbpre_'.$model['tablename']);
        $this->db->set('card_msg', $discount);
        $this->db->where('sid',$sid);
        $this->db->update();
    }

    function finer($post) {
        if(!$post||!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $finer = (int) $val['finer'];
            $this->db->from($this->table);
            $this->db->set('finer',$finer);
            $this->db->where('sid',$id);
            $this->db->update();
        }
    }

    function delete($sids) {
        $ids = parent::get_keyids($sids);
        parent::delete($ids);
        $this->delete_subject_field($ids);
    }

    //删除某些主题的会员卡信息
    function delete_sids($sids) {
        if(empty($sids)) return;
        $this->db->from($this->table);
        $this->db->where('sid',$sids);
        $this->db->delete();
    }

    //去除主题附加表里的会员卡折扣信息
    function delete_subject_field($sids) {
        $ids = parent::get_keyids($sids);
        $S =& $this->loader->model('item:subject');
        $S->db->from($S->table);
        $S->db->where('sid',$sids);
        $S->db->select('sid,pid');
        if(!$r=$S->db->get()) return;
		$this->loader->helper('sql');
        while($v=$r->fetch_array()) {
            if(!$model = $S->get_model($v['pid'],TRUE)) continue;
			if(!sql_exists_field($model['tablename'], 'card_msg')) continue;
            $this->db->from('dbpre_'.$model['tablename']);
            $this->db->set('card_msg', '');
            $this->db->where('sid',$v['sid']);
            $this->db->update();
        }
    }

/*
    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $sid => $val) {
            parent::save($val,$sid,FALSE,TRUE,TRUE);
        }
        $this->write_cache();
    }
*/

    function update($post) {
        if(!$post||!is_array($post)) redirect('global_op_unselect');
        $sids = array_keys($post);
        $this->db->from($this->table);
        $this->db->where('sid',$sids);
        if(!$q=$this->db->get()) return;
        $delids = array();
        while($v=$q->fetch_array()) {
            $set = array();
            if($post[$v['sid']]['available'] != $v['available']) {
                if(!$post[$v['sid']]['available']) {
                    $delids[] = $val['sid'];
                } else {
                    $card_msg = $this->_get_card_msg($v);
                    $this->save_subject_field($v['sid'],$card_msg);
                }
                $set['available'] = (int)$post[$v['sid']]['available'];
            }
            if($post[$v['sid']]['finer'] != $v['finer']) {
                $set['finer'] = (int)$post[$v['sid']]['finer'];
            }
            if(!$set) continue;
            $this->db->from($this->table);
            $this->db->set($set);
            $this->db->where('sid',$v['sid']);
            $this->db->update();
        }
        $q->free_result();
        if($delids) $this->delete_subject_field($delids);
    }

    function check_post(& $post, $edit = false) {
        if(!$post['sid']) redirect('card_discount_sid_empty');
        if(!$post['cardsort'] || !in_array($post['cardsort'],array('both','largess','discount'))) redirect('card_discount_sort_empty');
        if($post['cardsort'] == 'largess' || $post['cardsort'] == 'both') {
            if(!$post['largess']) redirect('card_discount_largess_empty');
        }
        if($post['cardsort'] == 'discount' || $post['cardsort'] == 'both') {
            if(!$post['discount']) redirect('card_discount_discount_empty');
        }
        if(!$edit) {
            $this->db->from($this->table);
            $this->db->where('sid',$post['sid']);
            if($this->db->count()>0) redirect('card_discount_exists');
        }
    }

    //关联主题模型，设置附加字段
    function relevance($use_modelids) {
        $modelids = $this->loader->variable('model', 'item');
        $modelids = array_keys($modelids);

        $F =& $this->loader->model('item:field');
        $createfile = MOD_ROOT . 'model' . DS . 'fields' . DS . 'createfield.php';
        if(!is_file($createfile)) {
            redirect(lang('global_file_not_exist', 'card/model/fields/createfield.php'));
        }
        $create_field = read_cache(MUDDER_MODULE . 'card' . DS . 'model' . DS . 'fields' . DS . 'createfield.php');
        $this->loader->helper('sql');

        foreach($modelids as $id) {
            $model = $this->loader->variable('model_' . $id, 'item');
            if(is_array($create_field)) foreach($create_field as $key => $val) {
                $val['modelid'] = $id;
                $val['tablename'] = $model['tablename'];
                if(in_array($id,$use_modelids)) {
                    if(sql_exists_field($val['tablename'], $val['fieldname'])) continue; //判断是否存在
                    $F->add($val, true, false); // 建立新字段，但不更新缓存
                } else {
                    $id = $F->get_fieldid($val['tablename'],$val['fieldname']);
                    if($id) $F->delete($id,false); //删除字段
                }
            }
        }
        $F->write_cache();
    }

    //取得用于填写字段的描述
    function _get_card_msg(&$data) {
        if($data['cardsort'] == 'both') {
            $card_msg = lang('card_format_msg_both', array($data['discount'], $data['largess']));
        } elseif($data['cardsort'] == 'discount') {
            $card_msg = lang('card_format_msg_discount', $data['discount']);
        } else {
            $card_msg = $data['largess'];
        }
        if($data['exception']) $card_msg = $card_msg . '(' . $data['exception'] . ')';
        return $card_msg;
    }
}
?>