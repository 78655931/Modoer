<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ucenter_friend extends ms_model {

    var $table = 'dbpre_friends';
    var $key = 'fid';
    var $model_flag = 'member';

    function __construct() {
        parent::__construct();
    }

    function msm_ucenter_friend() {
        $this->__construct();
    }

    function friend_ls($uid, $page=1, $pagesize=10) {
        $result = array(0,array());
        if($result[0] = uc_friend_totalnum($uid)) {
            $result[1] = uc_friend_ls($uid, $page, $pagesize);
            foreach($result[1] as $k => $v) {
                $v['friend_uid'] = $v['friendid'];
                $result[1][$k] = $v;
            }
        }
        return $result;
    }

    function save($uid, $friend_uid) {
        return uc_friend_add($uid , $friend_uid);
    }

    function delete($uid, $friendids) {
        return uc_friend_delete($uid, $friendids);
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