<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_album extends msm_item_itembase {

    var $table = 'dbpre_album';
	var $key = 'albumid';

	function __construct() {
		parent::__construct();
		$this->model_flag = 'item';
        $this->modcfg = $this->variable('config');
		$this->init_field();
	}

    function msm_item_picture() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('city_id,sid,name,thumb,des');
		$this->add_field_fun('city_id,sid', 'intval');
		$this->add_field_fun('name,thumb,des', '_T');
	}

	function find($select, $where, $orderby, $start=0, $offset=0, $total = TRUE, $join_subject = FALSE) {
		if($where['pid']) $join_subject = TRUE;
		if($join_subject) {
			$this->db->join($this->table, 'a.sid', $this->subject_table, 's.sid', MF_DB_LJ);
		} else {
			$this->db->from($this->table,'a');
		}
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

		$this->db->select($select ? $select : '*');
		if($join_subject) $this->db->select($join_subject);
        $this->db->order_by($orderby);
        if($offset>0)$this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save($post, $albumid = null, $auto_create=FALSE) {
        $edit = $albumid > 0;
		if($edit) {
			unset($post['sid']);
			if(!$detail = $this->read($albumid)) redirect('item_album_empty');
			$access = $auto_create || $this->in_admin || $this->check_alubm_access($detail['sid']);
		} else {
			$S =& $this->loader->model('item:subject');
			$subject = $S->read($post['sid'],'*',false);
			$post['city_id'] = $subject['city_id'];
			$post['lastupdate'] = $this->global['timestamp'];
			$access = $auto_create || $this->in_admin || $this->check_alubm_access($post['sid']);
		}
		if(!$access) redirect('global_op_access');
		$albumid = parent::save($post,$detail?$detail:$albumid);
        return $albumid;
	}

	//新建一个默认的主题相册
	function create_normal($sid,$name='',$thumb='') {
		$post = array();
		$post['sid'] = $sid;
		$post['name'] = $name?$name:lang('item_album_normal');
        $post['thumb'] = $thumb?$thumb:'';
		$post['lastupdate'] = $this->global['timestamp'];
		return $this->save($post,null,TRUE);
	}

	//更新最后上传时间
	function lastupdate($albumid) {
		$this->db->from($this->table);
		$this->db->where('albumid',$albumid);
		$this->db->set('lastupdate',$this->global['timestamp']);
		$this->db->update();
	}

	//设置封面,
	function set_thumb($picid, $empty_set = FALSE) {
		$P =& $this->loader->model('item:picture');
		if(!$detail = $P->read($picid)) redirect('item_picture_empty');
		if(!$this->check_alubm_access($detail['sid'])) redirect('global_op_access');
		$this->db->from($this->table);
		$this->db->where('albumid',$detail['albumid']);
		$this->db->set('thumb',$detail['thumb']);
		$this->db->update();
	}

    //当相册封面为空或者设置的图片不存在时才设置当前图片为封面
    function set_thumb_empty($albumid, $thumb) {
        if(!$thumb or !is_file(MUDDER_ROOT . $thumb)) return;
        $album = $this->read($albumid);
        if(!$album) return false;
        if($album['thumb'] && is_file(MUDDER_ROOT . $album['thumb'])) return false;
        $this->db->from($this->table);
        $this->db->where('albumid', $albumid);
        $this->db->set('thumb', $thumb);
        $this->db->update();
    }

    //更新相册数量和封面
    function album_total($albumid,$num=1,$thumb='') {
        if(!$albumid) return;
        //封面判断
        if($thumb && is_file(MUDDER_ROOT . $thumb)) {
            $album = $this->read($albumid);
            if(!$album) return;
            //图片不存在时  
            if($album['thumb'] && is_file(MUDDER_ROOT . $thumb)) {
                $thumb = '';
            }
        } else {
            $thumb = '';
        }
        if(!$num && !$thumb) return;
        //更新
        $this->db->from('dbpre_album');
        if($num) {
            $set = $num > 0 ? 'set_add' : 'set_dec';
            $this->db->$set('num', abs($num));
        }
        if($thumb) $this->db->set('thumb', $thumb);
        $this->db->set('lastupdate',$this->global['timestamp']);
        $this->db->where('albumid',$albumid);
        $this->db->update();
    }

    // 更新浏览量
    function pageview($albumid, $num=1) {
        $num = intval($num);
        if(empty($num)) return;
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('albumid', $albumid);
        $this->db->update();
    }

    function delete($albumids) {
		$ids = parent::get_keyids($albumids);
		$this->db->from($this->table);
		$this->db->where(albumid,$ids);
		if(!$q = $this->db->get()) return;
		$albumids = array();
		while($v=$q->fetch_array()) {
			if($this->in_admin || $this->check_alubm_access($v['sid'])) {
				$albumids[] = $v['albumid'];
			}
		}
		$q->free_result();
		if(!$albumids) return;
		$P =& $this->loader->model('item:picture');
		$P->delete_album($albumids);
		parent::delete($albumids);
	}

	function delete_subject($sids) {
		$ids = parent::get_keyids($sids);
		$this->db->from($this->table);
		$this->db->where('sid', $sids);
		$this->db->delete();
	}

	function check_post(& $post, $isedit = FALSE) {
		if(!$post['name']) redirect('item_album_name_empty');
	}

	function getlist($sid,$num=0) {
		$this->db->from($this->table);
		$this->db->where('sid',$sid);
		if($num>0) $this->db->limit(0,1);
		$this->db->order_by('lastupdate','DESC');
		if(!$q=$this->db->get())return false;
		if($num=='1') return $q->fetch_array();
		$result = array();
		while($v=$q->fetch_array()) {
			$result[] = $v;
		}
		$q->free_result();
		return $result;
	}

	function update($post) {
		if(!$post || !is_array($post)) redirect('global_op_unselect');
		foreach($post as $albumid=>$val) {
			$this->check_post($val);
			$this->db->from($this->table);
			$this->db->set($val);
			$this->db->where('albumid',$albumid);
			$this->db->update();
		}
	}

	function check_alubm_access($sid) {
		$S =& $this->loader->model('item:subject');
		return defined('IN_ADMIN') || $S->is_mysubject($sid,$this->global['user']->uid);
	}

	//重建相册数量
	function rebuild($albumids=null,$start=0,$offset=100) {
		$this->db->from($this->table);
		if($albumids) $this->db->where('albumid',$albumids);
		$this->db->order_by('albumid','ASC');
		if(!$albumids) $this->db->limit($start, $offset);
		if(!$q = $this->db->get()) return false;
		$P =& $this->loader->model('item:picture');
		while($v=$q->fetch_array()) {
			$num = (int) $P->count_album($v['albumid']);
			$this->db->from($this->table);
			$this->db->where('albumid',$v['albumid']);
			$this->db->set('num',$num);
			$this->db->update();
		}
		$q->free_result();
		return true;
	}

	//改改城市
	function change_city($sid,$new_cityid) {
		$this->db->from($this->table);
		$this->db->where('sid',$sid);
		$this->db->set('city_id',$new_cityid);
		$this->db->update();
		//picture
		$this->db->from('dbpre_pictures');
		$this->db->where('sid',$sid);
		$this->db->set('city_id',$new_cityid);
		$this->db->update();
	}

    //item_create_album
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='item_create_album') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                redirect('item_access_alow_create_album');
            }
        }
        return TRUE;
    }
}
?>