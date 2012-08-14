<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_impress extends ms_model {
    
    var $table = 'dbpre_subjectimpress';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_item_impress() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('sid,tags');
		$this->add_field_fun('sid', 'intval');
		$this->add_field_fun('tags', '_T');
	}

    function find($where=null,$orderby=array('total'=>'DESC'),$start=0,$offset=10,$total = FALSE) {
        $this->db->from($this->table);
        $where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select('*');
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    //验证标签，并转换成数组返回
    function check_post($string) {
        if(empty($string)) return;
        if(!$tags = $this->_parse($string)) return;
        $tags = array_unique($tags);
        $result = array();
        foreach($tags as $tagname) {
            if(!$tagname = trim($tagname)) continue;
            if(strlen($tagname) > 20) {
                redirect(lang('item_tag_charlen',array($tagname, 20)));
            }
            $result[] = $tagname;
        }
        return $result;
    }

    //对标签进行解析
    function _parse($string) {
        if(!$string) return;
        $modcfg = $this->variable('config');
        $split = ","; // 标签分隔符号
        $match = "/(，|、|\/|\\\|\||——|=|\s+)/is"; // 过滤正则
        if(!$modcfg['tag_split_sp']) $match = str_replace('|\s+','', $match); //是否兼容空格分隔标签
        $str = preg_replace($match, $split, $string);
        return explode($split, $str);
    }

    //加入标签
    function save($post) {
        if(!$post['sid']) redirect(lang('global_sql_keyid_invalid','sid'));
        if(!$tags = $this->check_post($post['tags'])) redirect('item_impress_empty');
        $S =& $this->loader->model('item:subject');
        if(!$subject = $S->read($post['sid'])) redirect('item_empty');
        $model = $S->get_model($subject['pid'], true);
        if($this->post_exist($model['tablename'], $post['sid'], $this->global['user']->uid)) redirect('item_impress_post_exist');
        $this->_save($post['sid'], $tags);
        $this->_set_post_exist($model['tablename'], $post['sid']);
    }

    //检测是否添加过
    function post_exist($idtype, $sid, $uid) {
        $this->db->from('dbpre_membereffect');
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $sid);
        $this->db->where('uid', $uid);
        if(!$r=$this->db->get_one()) return false;
        return (int) $r['effect3'];
    }

    function _save($sid, $tags) {
        $titles = array();
        if($result = $this->_find_exists($sid, $tags)) {
            foreach($tags as $id => $title) {
                if(!in_array($title, $result)) $titles[] = $title;
            }
            $this->_update_totle(array_keys($result));
        } else {
            $titles = $tags;
        }
        if($titles) $this->_add($sid, $titles);
    }

    function _find_exists($sid, &$tags) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where_in('title',$tags);
        if(!$q = $this->db->get()) return;
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['id']] = $v['title'];
        }
        return $result;
    }

    function _update_totle($ids) {
        $this->db->from($this->table);
        $this->db->where_in('id', $ids);
        $this->db->set_add('total',1);
        $this->db->set('dateline',$this->global['timestamp']);
        $this->db->update();
    }

    function _add($sid, $tags) {
        foreach($tags as $title) {
            $this->db->from($this->table);
            $this->db->set('sid',$sid);
            $this->db->set('title',$title);
            $this->db->set('total',1);
            $this->db->set('dateline',$this->global['timestamp']);
            $this->db->insert();
        }
    }

    function _set_post_exist($idtype, $sid) {
        $M =& $this->loader->model('member:membereffect');
        $M->add_idtype($idtype, 'subject', 'sid');
        $M->save($sid, $idtype, 'effect3', 0);
    }

}
?>