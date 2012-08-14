<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subjectlog extends msm_item_itembase {

    var $table = 'dbpre_subjectlog';
	var $key = 'upid';

	function __construct() {
		parent::__construct();
		$this->init_field();
	}

    function msm_item_subjectlog() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('sid,username,email,ismappoint,upcontent');
		$this->add_field_fun('sid,ismappoint', 'intval');
        $this->add_field_fun('username,email', '_T');
        $this->add_field_fun('upcontent', '_TA');
	}

	function find($select, $where, $start, $offset, $total = TRUE) {
	    $this->db->join($this->table,'l.sid',$this->subject_table,'s.sid','LEFT JOIN');
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
		$this->db->select($select ? $select : '*');
        $this->db->order_by('l.posttime', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
	}

	function save() {

        $post = $this->get_post($_POST);
        if($this->global['user']->isLogin) {
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['email'] = $this->global['user']->email;
        }
        $post['posttime'] = $this->global['timestamp'];
        $post['disposal'] = 0;
        $post['upremark'] = '';

		$upid = parent::save($post, null, FALSE);

        return $upid;
	}

	function check_post(& $post, $isedit = FALSE) {
        if(!$post['upcontent']) {
            redirect('item_log_empty_content');
        }
	}

    function disposal($post, $upid) {

        if(!$detail = $this->read($upid,'sid,uid,username,update_point')) {
            redirect('itemcp_log_empty');
        }

        $data = array(
            'disposal' => 1,
            'disposaltime' => $this->global['timestamp'], 
            'upremark' => _T($post['upremark']),
        );
        if($post['update_point'] && $detail['uid'] > 0 && !$detail['update_point']) {
            $data['update_point'] = (int) $post['update_point'];
        }

        $this->db->from($this->table);
        $this->db->set($data);
        $this->db->where('upid', $upid);
        $this->db->update();
        /*
        if($_POST['delete_item']) {
            $I =& $this->loader->model('item:subject');
            $I->delete($detail['sid']);
        }
        */
        //更新积分
        if($data['update_point'] && $detail['uid'] > 0) {
            $P =& $this->loader->model('member:point');
            $P->update_point($detail['uid'], 'update_subject', 0);
        }
    }

	function delete($upids) {
        if(is_numeric($upids) && $upids > 0) $upids = array($upids);
		if(empty($upids) || !is_array($upids)) redirect('global_op_unselect');
		$this->db->from($this->table);
		$this->db->where_in('upid', $upids);
		$this->db->delete();
	}

}
?>