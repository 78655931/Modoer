<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_friend extends ms_model {

    var $table = 'dbpre_friends';
    var $key = 'fid';
    var $model_flag = 'member';

    function __construct() {
        parent::__construct();
    }

    function msm_member_friend() {
        $this->__construct();
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $join_member = false) {
        if(strposex($select, 'username')) $join_member = TRUE;
        if($join_member) {
            $this->db->join($this->table,'f.friend_uid', 'dbpre_members', 'm.uid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'f');
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
        $this->db->order_by($order_by);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    function friend_ls($uid, $page=1, $pagesize=10) {
        $result = array(0,array());
        $this->db->join($this->table,'f.friend_uid', 'dbpre_members', 'm.uid', "LEFT JOIN");
        $where = array();
        $where['f.uid'] = $uid;
        $this->db->where($where);
        if($result[0] = $this->db->count()) {
            $start = get_start($page, $pagesize);
            $this->db->sql_roll_back('from,where');
            $this->db->select('f.*,m.username,m.groupid,m.logintime');
            $this->db->limit($start, $pagesize);
            $q = $this->db->get();
            while($v = $q->fetch_array()) {
                $result[1][] = $v;
            }
            $q->free_result();
        }
        return $result;
    }

    function save($uid, $friend_uid) {
        $post = array();
        $post['uid'] = $uid;
        $post['friend_uid'] = $friend_uid;
        $post['addtime'] = $this->global['timestamp'];
        return parent::save($post);
    }

    function delete($uid, $friendids) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where_in('friend_uid', $friendids);
        $this->db->delete();
    }

    function check_post(&$post, $isedit=false) {
        if(!is_numeric($post['uid']) || $post['uid'] < 1) redirect('member_friend_uid_invalid');
        if(!is_numeric($post['friend_uid']) || $post['friend_uid'] < 1) redirect('member_friend_uid_invalid');
        if($post['uid'] == $post['friend_uid']) redirect('member_friend_add_self');
        if(!$member = $this->global['user']->read($post['friend_uid'])) {
            redirect('member_friend_not_found');
        }
        if($this->check_exists($post['uid'], $post['friend_uid'])) {
            redirect('member_friend_exists');
        }
    }

    function check_exists($uid, $friend_uid) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('friend_uid', $friend_uid);
        return $this->db->count() > 0;
    }

}
?>