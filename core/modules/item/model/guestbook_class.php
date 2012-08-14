<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_guestbook extends msm_item_itembase {

    var $table = 'dbpre_guestbook';
	var $key = 'guestbookid';

    var $modcfg = '';

	function __construct() {
		parent::__construct();
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_item_guestbook() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('sid,content');
		$this->add_field_fun('sid', 'intval');
		$this->add_field_fun('content', '_TA');
	}

	function find($select, $where, $orderby, $start, $offset, $total = TRUE, $join_subject = FALSE) {
        if($where['pid']) $join_subject = TRUE;
        if($join_subject) {
	        $this->db->join($this->table,'g.sid',$this->subject_table,'s.sid','LEFT JOIN');
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
        
		$this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save($post, $guestbookid = null) {
        if($edit = $guestbookid != null) {
            $detail = $this->read($guestbookid);
            if(!$detail) {
                redirect('item_guestbook_empty');
            }
            if(!$this->in_admin && $this->global['user']->uid != $detail['uid']) {
                redirect('global_op_access');
            }
        }
        $this->check_post($post, $edit);
        if(!$edit) {
            $S =& $this->loader->model($this->model_flag.':subject');
            if(!$subject = $S->read($post['sid'], 'pid,catid,name,subname,owner,status', 0)) {
                redirect('item_empty');
            }
            $is_owner = $this->global['user']->isLogin && $this->global['user']->username == $subject['owner'];
            if($is_owner) redirect('item_guestbook_self');
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $category = $S->get_category($subject['pid']);
            $post['status'] = $category['config']['guestbookcheck'] ? 0 : 1;
            $post['reply'] = '';
        }
        $post['dateline'] = $this->global['timestamp'];

        if(!$this->in_admin) {
            $post['ip'] = $this->global['ip'];
            if($edit) unset($post['status']);
        }

		$post = $this->convert_post($post);

		if(!$edit && $post['status']) {
            $event = 'ADD';
		} elseif($edit && !$detail['status']) {
			$event = 'ADD';
        } elseif($edit && $detail['status'] && isset($post['status']) && !$post['status']) {
            $event = 'DEC';
        }
        
        $sid = $post['sid'];
        $uid = $post['uid'];
        
        if($detail) foreach ($detail as $key => $val) {
        	if(isset($post[$key]) && $val==$post[$key]) {
        		unset($post[$key]);
        	}
        }
        $guestbookid = parent::save($post, $guestbookid, FALSE);

        if($event == 'ADD') {
            $this->subject_total_add($sid);
            $post['uid'] && $this->add_user_point($uid);
            define('RETURN_EVENT_ID', 'global_op_succeed');
            $this->_notice_new($guestbookid); //提醒
        } elseif($event == 'DEC') {
            $this->subject_total_dec($sid);
            $post['uid'] && $this->dec_user_point($uid);
        }
        // 新提交同时未审核
        if(!$event) {
            if(isset($post['status'])) {
                define('RETURN_EVENT_ID', 'global_op_succeed_check');
            } elseif(!isset($post['status']) && $detail['status']) {
                define('RETURN_EVENT_ID', 'global_op_succeed');
            } elseif(!isset($post['status']) && !$detail['status']) {
                define('RETURN_EVENT_ID', 'global_op_succeed_check');
            }
        }
        return $guestbookid;
    }

    function edit($guestbookid, $content, $reply) {
        if(!$guestbookid) redirect(lang('global_sql_keyid_invalid', 'guestbookid'));
        $post = array();
        if(!$post['content'] = _TA($content)) {
            redirect('item_guestbook_empty_content');
        }
        if(!$post['reply'] = _TA($reply)) {
            $post['replytime'] = 0;
        } else {
            $post['replytime'] = $this->global['timestamp'];
        }
        $this->db->from($this->table);
        $this->db->set($post);
        $this->db->where('guestbookid', $guestbookid);
        $this->db->update();
    }

    function reply($guestbookid, $reply) {
        if(!$gb = $this->read($guestbookid)) {
            redirect('item_guestbook_empty');
        }

        if(!$reply = _TA($reply)) redirect('item_guestbook_empty_reply');
        if($gb['reply'] == $reply) return;

        $S =& $this->loader->model($this->model_flag.':subject');
        if(!$subject = $S->read($gb['sid'],'pid,catid,name,subname,status,owner',0)) {
            redirect('item_empty');
        }
        if(!$this->in_admin && $subject['owner'] != $this->global['user']->username) {
            redirect('global_op_access');
        }

        $this->db->from($this->table);
        $this->db->set('reply', $reply);
        $this->db->set('replytime', $this->global['timestamp']);
        $this->db->where('guestbookid', $guestbookid);
        $this->db->update();

        $this->_notice_reply($guestbookid); //提醒
    }

    function checkup($guestbookids) {
        if(is_numeric($guestbookids) && $guestbookids > 0) $guestbookids = array($guestbookids);
        if(!$guestbookids || !is_array($guestbookids)) redirect('global_op_unselect');
        $this->db->from($this->table);
        $this->db->select('guestbookid,sid,uid');
        $this->db->where_in('guestbookid',$guestbookids);
        $this->db->where('status',0);
        if(!$query = $this->db->get()) return;
        $upids = array();
        while($val = $query->fetch_array()) {
            $upids[] = $val['guestbookid'];
            //处理主题统计
            $this->subject_total_add($val['sid']);
            //增加积分
            $this->add_user_point($val['uid']);
            //提醒
            $this->_notice_new($val['guestbookid']); //提醒
        }
        if($upids) {
            $this->db->from($this->table);
            $this->db->set('status',1);
            $this->db->where_in('guestbookid', $upids);
            $this->db->update();
        }
    }

	function check_post(& $post, $isedit = FALSE) {
        
		//sid,title,comments
        if(!$isedit && !is_numeric($post['sid'])) {
            redirect(lang('global_sql_keyid_invalid', 'sid'));
        }
        
		if(!$post['content']) redirect('item_guestbook_empty_content');
        $this->modcfg['guestbook_min'] = $this->modcfg['guestbook_min']>0 ? $this->modcfg['guestbook_min'] : 10;
        $this->modcfg['guestbook_max'] = $this->modcfg['guestbook_max']>0 ? $this->modcfg['guestbook_max'] : 500;
        if(strlen($post['content']) > $this->modcfg['guestbook_max'] || strlen($post['content']) < $this->modcfg['guestbook_min']) {
            redirect(lang('item_guestbook_content_charlen',array($this->modcfg['guestbook_min'],$this->modcfg['guestbook_max'])));
        }
        /*
        $mod = $this->variable('config');
        $len = strlen($post['content']);
		if($len > $mod['respond_max'] || $len < $mod['respond_min']) {
            redirect(lang('review_respond_content_charlen', array($mod['respond_min'], $mod['respond_max'])));
        }
        */
	}

    function delete($guestbookids, $update_total = TRUE, $delete_point = FALSE, $is_sid = FALSE) {
        if(!$delete_point && !$this->in_admin && !$is_sid) $delete_point = TRUE;

        if(is_numeric($guestbookids) && $guestbookids > 0) $guestbookids = array($guestbookids);
        if(!$guestbookids || !is_array($guestbookids)) redirect('global_op_unselect');

		$this->db->from($this->table);
        if($is_sid) {
            $this->db->where_in('sid', $guestbookids);
        } else {
		    $this->db->where_in('guestbookid', $guestbookids);
        }
		$this->db->select('guestbookid,sid,uid,status');
		if(!$result = $this->db->get()) return ;

        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }

		$uids = $delids = $upsubject = array();
		while($value = $result->fetch_array()) {
            //判断权限(后台管理员,留言会员以及主题管理员)
            $access = $this->in_admin || $this->global['user']->uid == $value['uid'] || in_array($value['sid'], $mysubjects);
            !$access && redirect('global_op_access');
            $delids[] = $value['guestbookid'];
			if($value['status']) {
                if($update_total) {
                    if(isset($upsubject[$value['sid']])) {
                        $upsubject[$value['sid']]++;
                    } else {
                        $upsubject[$value['sid']] = 1;
                    }
                }
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
        if($delids) {
            $this->db->from($this->table);
            $this->db->where_in('guestbookid', $delids);
            $this->db->delete();
        }

		//删除用户的对应积分
		if($delete_point && $uids) {
			$P =& $this->loader->model('member:point');
			foreach($uids as $uid => $num) {
				$P->update_point($uid, 'add_guestbook', TRUE, $num);
			}
		}

        //更新主题统计
        if($upsubject) foreach($upsubject as $sid => $num) {
            $this->subject_total_dec($sid, $num);
        }
    }

	function subject_total_add($sid, $num=1) {
		$this->db->from($this->subject_table);
		$this->db->where('sid', $sid);
		$this->db->set_add('guestbooks', $num);
		$this->db->update();
	}

	function subject_total_dec($sid, $num=1) {
		$this->db->from($this->subject_table);
		$this->db->where('sid', $sid);
		$this->db->set_dec('guestbooks', $num);
		$this->db->update();
	}

	function add_user_point($uid, $num = 1) {
        if(!$uid) return;
		$P =& $this->loader->model('member:point');
		$P->update_point($uid, 'add_guestbook', 0, $num);
	}

	function dec_user_point($uid, $num = 1) {
        if(!$uid) return;
		$P =& $this->loader->model('member:point');
		$P->update_point($uid, 'add_guestbook', TRUE, $num);
	}

    //留言提醒
    function _notice_new($guestbookid) {
        if(!$guestbookid) return;

        $detail = $this->read($guestbookid);
        if(!$detail) return;

        $subject = $this->loader->model('item:subject')->read($detail['sid'],'*',false);
        if(!$subject||!$subject['owner']) return;

        $uids = array();
        $members = $this->loader->model('item:subject')->owners($detail['sid']);
        if($members) foreach($members as $val) {
            $uids[] = $val['uid'];
        }
        if(!$uids) return;

        $c_username = '<a href="'.url("space/index/uid/$detail[uid]").'" target="_blank">'.$detail['username'].'</a>';
        $c_subject = '<a href="'.url("item/detail/id/$detail[sid]").'" target="_blank">'.$subject['name'].'</a>';
        $c_url = url("item/detail/id/$detail[sid]/view/guestbook","guestbook");
        $note = lang('item_notice_new_guestbook',array($c_username, $c_subject, $c_url));

        $N = $this->loader->model('member:notice');
        $N->save($uids,'item','guestbook',$note);
    }

    //回复留言提醒
    function _notice_reply($guestbookid) {
        if(!$guestbookid) return;

        $detail = $this->read($guestbookid);
        if(!$detail['sid']) return;

        $subject = $this->loader->model('item:subject')->read($detail['sid'],'sid,name,subname',false);
        if(!$subject) return;

        $c_subject = '<a href="'.url("item/detail/id/$detail[sid]").'" target="_blank">'.$subject['name'].'</a>';
        $c_url = url("item/detail/id/$detail[sid]/view/guestbook","guestbook");
        $note = lang('item_notice_reply_guestbook',array($c_subject, $c_url));

        $N = $this->loader->model('member:notice');
        $N->save($detail['uid'],'item','guestbook',$note);
    }
}
?>