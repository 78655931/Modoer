<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_favorite extends ms_model {

    var $table = 'dbpre_favorites';
    var $key = 'fid';
    var $model_flag = 'member';

    function __construct() {
        parent::__construct();
        $this->init_field();
    }

    function init_field() {
        $this->add_field('id');
        $this->add_field_fun('id', 'intval');
    }

    function get_follower($uid, $start, $offset=20, $total = TRUE) {
        $this->db->join($this->table,'f.id','dbpre_members','m.uid','LEFT JOIN');
        $this->db->where('f.idtype','member');
        $this->db->where('f.uid', $uid);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select?$select:'f.fid,f.id,f.addtime,m.uid,m.username,m.follow,m.fans,reviews,groupid');
        $this->db->order_by('f.addtime', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function get_fans($uid, $start, $offset=20, $total = TRUE) {
        $this->db->join($this->table,'f.uid','dbpre_members','m.uid','LEFT JOIN');
        $this->db->where('f.idtype','member');
        $this->db->where('f.id', $uid);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select?$select:'f.fid,f.id,f.addtime,m.uid,m.username,m.follow,m.fans,reviews,groupid');
        $this->db->order_by('f.addtime', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function get_uids($uid) {
        $r = $this->db->from($this->table)
            ->where('idtype','member')
            ->where('id',$uid)->get();
        if(!$r) return;
        $result = array();
        while($val=$r->fetch_array()) {
            $result[] = $val['uid'];
        }
        $r->free_result();
        return $result;
    }

    function save($post) {
        $this->check_post($post);
        if($this->submitted($this->global['user']->uid, $post['id'])) {
            redirect('member_follow_submitted');
        }

        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['idtype'] = 'member';
        $post['addtime'] = $this->global['timestamp'];

        $fid = parent::save($post, null, FALSE, FALSE, FALSE);
        $this->member_total($this->global['user']->uid, $post['id']);
        $this->_notice_follow($post['id']);
        return $fid;
    }

    function unfollow($follow_uid, $myuid=null) {
        $detail = $this->db->from($this->table)->where('idtype', 'member')->where('id', $follow_uid)->get_one();
        if(!$myuid) $myuid = $this->global['user']->uid;
        if($detail['uid'] != $myuid) redirect('member_unfollow_access');
        parent::delete($detail['fid']);
        $this->member_total($detail['uid'], $detail['id'], -1);
    }

    function submitted($uid, $id) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('idtype', 'member');
        $this->db->where('id', $id);
        return $this->db->count() >= 1;
    }

    function check_self($uid) {
        return $this->global['user']->uid == $uid;
    }

    function check_post(& $post, $isedit = FALSE) {
        if($isedit && !is_numeric($post['id'])) {
            redirect(lang('global_sql_keyid_invalid', 'id'));
        }
        if($this->check_self($post['id'])) redirect('member_follow_self');
    }

    function delete($fids, $update_total = TRUE) {
        $fids = parent::get_keyids($fids);
        $where = array('fid' => $fids);
        $this->_delete($where, $update_total);
    }

    function delete_uids($uids, $update_total = FALSE) {
        $uids = parent::get_keyids($uids);
        $where = array('id'=>$uids);
        $this->_delete($where, $update_total);
    }

    function member_total($myuid, $fwuid, $num=1) {
        if(!$num) return;
        $fun = $num > 0 ? 'set_add' : 'set_dec';
        $num = abs($num);
        //对方增加或减少粉丝
        $this->db->from('dbpre_members');
        $this->db->where('uid', $fwuid);
        $this->db->$fun('fans',$num);
        $this->db->update();
        //我增加或减少关注
        $this->db->from('dbpre_members');
        $this->db->where('uid', $myuid);
        $this->db->$fun('follow',$num);
        $this->db->update();
    }

    function _delete($where, $update_total=TRUE) {
        $this->db->from($this->table);
        $this->db->where('idtype','member');
        $this->db->where($where);
        if(!$q = $this->db->get()) return ;
        $delids = $ids = array();
        while($v=$q->fetch_array()) {
            $delids[] = $v['fid'];
            if(!$update_total) continue;
            $this->member_total($v['uid'], $v['id'], -1);
        }
        $q->free_result();
        $this->db->from($this->table);
        $this->db->where('fid', $delids);
        $this->db->delete();
    }

    //提醒
    // xxx 成为了您的粉丝
    function _notice_follow($uid) {
        $c_username = '<a href="'.url("space/index/uid/".$this->global['user']->uid).'" target="_blank">'.$this->global['user']->username.'</a>';
        $note = lang('member_notice_follow', $c_username);
        $this->loader->model('member:notice')->save($uid, $this->model_flag, 'follow', $note);
    }

}
?>