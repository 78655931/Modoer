<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subjectapply extends msm_item_itembase {

    var $table = 'dbpre_subjectapply';
    var $key = 'applyid';

    function __construct() {
        parent::__construct();
        $this->init_field();
    }

    function msm_item_subjectapply() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('sid,applyname,contact,content');
        $this->add_field_fun('sid', 'intval');
        $this->add_field_fun('applyname,contact', '_T');
        $this->add_field_fun('content', '_TA');
    }

    function & read($applyid, $select = '', $join = TRUE) {
        $this->db->select($select ? $select : 'a.*');
        if($join) {
            $this->db->select('s.pid,s.catid,name,subname,owner');
            $this->db->join($this->table,'a.sid',$this->subject_table,'s.sid','LEFT JOIN');
        } else {
            $this->db->from($this->table, 'a');
        }
        $this->db->where('a.applyid', $applyid);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select, $where, $start, $offset, $total = TRUE) {
        $this->db->join($this->table,'a.sid',$this->subject_table,'s.sid','LEFT JOIN');
        $this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

        $this->db->select($select ? $select : '*');
        $this->db->order_by('a.dateline', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save() {

        if(!$this->global['user']->isLogin) {
            redirect('member_not_login');
        }
        $post = $this->get_post($_POST);
        $this->check_post($post);

        $detail = $this->check_post_before($post['sid']);
        $cfg =& $detail['catcfg'];
        if($cfg['subject_apply_uppic']) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('pic', $this->global['cfg']['picture_ext']);
            $this->upload($img);
            $post['pic'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
        }

        //$post['modelid'] = $this->get_modelid($post['catid']);
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['dateline'] = $this->global['timestamp'];
        $post['returned'] = '';

        $applyid = parent::save($post, null, FALSE);

        return $applyid;
    }

    function check_post_before($sid, $isedit = FALSE) {
        $I =& $this->loader->model(MOD_FLAG.':subject');

        if(!$detail = $I->read($sid, 'sid,pid,catid,name,subname,status')) {
            redirect('item_empty');
        } elseif($detail['status'] != 1) {
            redirect('item_apply_status_invalid');
        } elseif($detail['owner'] == $this->global['user']->username) {
            redirect('item_apply_owner');
        }

        $this->get_category($detail['catid']);
        $catcfg = $this->category['config'];

        if(!$catcfg['subject_apply']) {
            redirect('item_apply_disable');
        }

        if($this->submitted($this->global['user']->uid, $sid)) {
            redirect('item_apply_wait');
        }

        $detail['catcfg'] = $catcfg;

        return $detail;
    }

    function check_post(& $post, $isedit = FALSE) {
        if(!is_numeric($post['sid'])) {
            redirect(lang('global_sql_keyid_invalid', 'sid'));
        } elseif(!$post['applyname']) {
            redirect('item_apply_empty_applyname');
        } elseif(!$post['contact']) {
            redirect('item_apply_empty_contact');
        } elseif(!$post['content']) {
            redirect('item_apply_empty_content');
        }
    }

    function upload(& $img) {
        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->upload('itemapply', null);
    }

    function submitted($uid, $sid) {
        $this->db->from($this->table);
        $this->db->select('status,dateline,checker');
        $this->db->where('sid', $sid);
        $this->db->where('uid', $uid);
        if(!$result = $this->db->get_one()) return FALSE;
        $dateline = $this->global['timestamp'] - 3 * 24 *3600;
        //申请失败后第三天才可进行下一次申请
        if($result['status'] > 0 && $result['dateline'] > $dateline) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function check($post, $applyid) {
        if(!$applyid = (int) $applyid) redirect(lang('global_sql_keyid_invalid','applyid'));
        if(!$detail = $this->read($applyid)) {
            redirect('item_apply_empty');
        }

        $data = array(
            'status' => ($post['status'] ? '1' : '2'),
            'checker' => $this->global['admin']->adminname, 
            'returned' => _T($post['returned']),
        );

        if($data['status'] == '1' && $detail['uid'] > 0) {
            $S =& $this->loader->model('item:subject');
            $S->set_owner($detail['sid'], $detail['uid'], $post['expirydate'], true, true, false);
            unset($S);
        }

        $this->db->from($this->table);
        $this->db->set($data);
        $this->db->where('applyid', $applyid);
        $this->db->update();

        if($post['pm'] && $detail['uid'] > 0) {
            $fullname = $detail['name'] . ($detail['subname']?"($detail[subname])":'');
            $c_subject = '<a href="'.url("item/detail/id/$detail[sid]").'" target="_blank">'.$fullname.'</a>';
            if($data['status'] == '1') {
                $note = lang('item_notice_subjectapply_succeed', $c_subject);
            } else {
                $note = lang('item_notice_subjectapply_lost', array($c_subject, $data['returned']));
            }
            $this->loader->model('member:notice')->save($detail['uid'],'item','apply',$note);
            /*
            $MSG =& $this->loader->model('member:message');
            $fullname = $detail['name'] . $detail['subname'];
            $subject = lang('item_apply_pm_subject_'.$data['status']);
            $message = lang('item_apply_pm_message', array(date('Y-m-d H:i', $this->global['timestamp']), $fullname));
            if($data['returned']) {
                $message .= '<br />' . lang('item_apply_pm_message_2') . nl2br($data['returned']);
            }
            $MSG->send(0, $detail['uid'], $subject, $message);
            */
        }
    }

    function delete($applyids) {
        if(is_numeric($applyids) && $applyids > 0) $applyids = array($applyids);
        if(empty($applyids) || !is_array($applyids)) redirect('global_op_unselect');
        $this->db->from($this->table);
        $this->db->where_in('applyid', $applyids);
        if(!$r = $this->db->get()) return;
        while($v = $r->fetch_array()) {
            if(strlen($v['pic']) > 10) @unlink(MUDDER_ROOT . $v['pic']);
        }
        $r->free_result();
        $this->db->sql_roll_back('from,where');
        $this->db->delete();
    }
}
?>