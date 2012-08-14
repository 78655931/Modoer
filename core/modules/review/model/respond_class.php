<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_review_respond extends msm_item_itembase {

    var $table = 'dbpre_responds';
	var $key = 'respondid';

	function __construct() {
		parent::__construct();
		$this->model_flag = 'review';
        $this->modcfg = $this->variable('config');
		$this->init_field();
	}

    function msm_review_respond() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('rid,content');
		$this->add_field_fun('rid', 'intval');
		$this->add_field_fun('content', '_TA');
	}

	function find($select, $where, $orderby, $start, $offset, $total = TRUE, $join_review = FALSE) {
	    if($join_review) {
            $this->db->join($this->table,'rp.rid',$this->review_table,'r.rid','LEFT JOIN');
        } else {
            $this->db->from($this->table, 'rp');
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
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save($post, $respondid = null) {
        $edit = $respondid != null;
        $W =& $this->loader->model('word');
        if($edit) {
            $detail = $this->read($respondid);
            if(!$detail) {
                redirect('review_respond_empty');
            }
            if(!$this->in_admin && $this->global['user']->uid != $detail['uid']) {
                redirect('global_op_access');
            }
        }

        if(!$this->in_admin) {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['ip'] = $this->global['ip'];
            unset($post['status']);
        }
        if(!$edit) {
            $mod = $this->variable('config');
            $post['status'] = $mod['respondcheck'] ? 0 : ($W->check($post['content']) ? 0 : 1);
            $post['posttime'] = $this->global['timestamp'];
        } else {
            foreach($detail as $key => $val) {
                if(isset($post[$key]) && $post[$key] == $val) unset($post[$key]);
            }
            if(!$post) return $respondid;
        }
        $post['content'] = $W->filter($post['content']);
        $post = $this->convert_post($post);
        $respondid = parent::save($post, $respondid, FALSE);

        if(!$edit && $post['status']) {
            $event = 'ADD';
        } elseif($edit && !$detail['status'] && $post['status']) {
            $event = 'ADD';
        } elseif($edit && $detail['status'] && isset($post['status']) && !$post['status']) {
            $event = 'DEC';
        }

        if($event == 'ADD') {
            $this->review_total_add($post['rid']);
			if($post['uid']) {
                $this->add_user_point($post['uid']);
                $this->_feed($respondid);
            }
            define('RETURN_EVENT_ID', 'global_op_succeed');
        } elseif($event == 'DEC') {
			$this->review_total_dec($post['rid']);
			$post['uid'] && $this->dec_user_point($post['uid']);
        }

        if($event != 'ADD') {
            define('RETURN_EVENT_ID', 'global_op_succeed_check');
        }

        return $respondid;
	}

	function check_post(& $post, $isedit = FALSE) {
		//sid,title,comments
        if(!$isedit && !is_numeric($post['rid'])) {
            redirect(lang('global_sql_keyid_invalid', 'rid'));
        }
		if(!$post['content']) redirect('review_respond_empty_content');
        $mod = $this->variable('config');
        $len = strlen($post['content']);
		if($len > $mod['respond_max'] || $len < $mod['respond_min']) {
            redirect(lang('review_respond_content_charlen', array($mod['respond_min'], $mod['respond_max'])));
        }
	}

	function checkup($respondids) {
		if(empty($respondids)) redirect('global_op_unselect');
        if(!is_array($respondids)) $respondids = array((int)$respondids);
		$this->db->select('respondid,rid,status,uid');
        $this->db->from($this->table);
        $this->db->where_in('respondid', $respondids);
        $this->db->where('status', 0);
        if(!$row = $this->db->get()) return;
        $uids = $upids = array();
        while ($value = $row->fetch_array()) {
			$upids[] = $value['respondid'];
			//更新主题记录
            $this->review_total_add($value['rid']);
			//记录需要增加积分的用户和数量
			if($value['uid']) {
				if(isset($uids[$value['uid']])) {
					$uids[$value['uid']]++;
				} else {
					$uids[$value['uid']] = 1;
				}
                $this->_feed($respondid); //feed
			}
        }
        $row->free_result();
		//更新记录
        if($upids) {
            $this->db->from($this->table);
            $this->db->set('status', 1);
            $this->db->where_in('respondid', $upids);
            $this->db->update();
        }
		//增加用户积分
		if($uids) {
			$P =& $this->loader->model('member:point');
			foreach($uids as $uid => $num) {
				$P->update_point($uid, 'add_respond', 0, $num);
			}
		}
	}

	function delete($respondids, $update_total = TRUE, $delete_point = FALSE, $is_rid = FALSE) {
        if(!$delete_point && !$this->in_admin && !$is_rid) $delete_point = TRUE;

		if(is_numeric($respondids)) $respondids = array($respondids);
        if(empty($respondids) || !is_array($respondids)) redirect('global_op_unselect');

		$this->db->from($this->table);
        $this->db->select('respondid,rid,uid,status');
        if($is_rid) {
            $this->db->where_in('rid', $respondids);
        } else {
		    $this->db->where_in('respondid', $respondids);
        }
        
		if(!$result = $this->db->get()) return;

		$uids = $deleteids = array();
		while($value = $result->fetch_array()) {
            if(!$this->in_admin && $this->global['user']->uid != $value['uid']) {
                redirect('global_op_access');
            }
            $deleteids[] = $value['respondid'];
			if($value['status']) {
				$update_total && $this->review_total_dec($value['rid']);
				if($value['uid'] && $delete_point) {
					if(isset($uids[$value['uid']])) {
						$uids[$value['uid']]++;
					} else {
						$uids[$value['uid']] = 1;
					}
				}
			}
		}

		//删除记录
        if($deleteids) {
            $this->db->from($this->table);
            $this->db->where_in('respondid', $deleteids);
            $this->db->delete();
        }

		//删除用户的对应积分
		if($delete_point && $uids) {
			$P =& $this->loader->model('member:point');
			foreach($uids as $uid => $num) {
				$P->update_point($uid, 'add_respond', TRUE, $num);
			}
		}
	}

	function review_total_add($rid, $num=1) {
		$this->db->from($this->review_table);
		$this->db->set_add('responds');
		$this->db->where('rid', $rid);
		$this->db->update();
	}

	function review_total_dec($rid, $num=1) {
		$this->db->from($this->review_table);
		$this->db->set_dec('responds');
		$this->db->where('rid', $rid);
		$this->db->update();
	}

	function add_user_point($uid, $num = 1) {
        if(!$uid) return;
		$P =& $this->loader->model('member:point');
		$BOOL = $P->update_point($uid, 'add_respond', FALSE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_add('responds', $num);
        $this->db->update();
	}

	function dec_user_point($uid, $num = 1) {
        if(!$uid) return;
		$P =& $this->loader->model('member:point');
		$BOOL = $P->update_point($uid, 'add_respond', TRUE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_dec('responds', $num);
        $this->db->update();
	}

    //想收藏主题的会员发送提薪新点评
    function _notice($respondid) {
        if(!$respondid) return;

        $detail = $this->read($respondid);
        if(!$detail||!$detail['uid']) return;

        $review = $this->loader->model(':review')->read($detail['rid']);
        if(!$review||!$review['uid']) return;

        $c_username = '<a href="'.url("space/index/uid/$detail[uid]").'" target="_blank">'.$detail['username'].'</a>';
        $c_review = url("review/detail/id/$detail[rid]");
        $note = lang('review_notice_new_respond',array($c_username, $c_review));

        $N = $this->loader->model('member:notice');
        $N->save($review['uid'],'review','respond',$note);
    }

    function _feed($respondid) {
        if(!$respondid) return;

        $this->_notice($respondid); //提薪

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        $detail = $this->read($respondid);
        if(!$detail['uid']) return;

		//取得点评数据信息
		$R =& $this->loader->model(':review');
		$review = $R->read($detail['rid']);
		$city_id = (int) $review['city_id'];

        $feed = array();
        $feed['icon'] = lang('review_respond_feed_icon');
        $feed['title_template'] = lang('review_respond_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
        );
        $feed['body_template'] = lang('review_respond_feed_body_template');
        $feed['body_data'] = array (
            'content' => '<a href="'.url("review/detail/id/$detail[rid]").'">'.trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 50).'</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $city_id, $feed['icon'], $detail['uid'], $detail['username'], $feed);
    }
}
?>