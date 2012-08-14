<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_review_report extends ms_model {

    var $table = 'dbpre_reports';
	var $key = 'reportid';

    var $modcfg = null;

	function __construct() {
		parent::__construct();
        $this->model_flag = 'review';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_review_report() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('rid,username,email,sort,reportcontent');
		$this->add_field_fun('rid,sort', 'intval');
        $this->add_field_fun('username,email', '_T');
        $this->add_field_fun('reportcontent', '_TA');
	}

	function find($select, $where, $start, $offset, $total = TRUE) {
	    $this->db->join($this->table,'rt.rid','dbpre_review','r.rid','LEFT JOIN');
		$this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
		$this->db->select($select ? $select : '*');
        $this->db->order_by('rt.posttime', 'DESC');
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
        $post['reportremark'] = '';

		$fid = parent::save($post, null, FALSE);

        return $fid;
	}

	function check_post(& $post, $isedit = FALSE) {
        if($isedit && !is_numeric($post['rid'])) {
            redirect(lang('global_sql_keyid_invalid', 'rid'));
        } elseif(!is_numeric($post['sort'])) {
            redirect('review_report_empty_sort');
        } elseif(!$post['reportcontent']) {
            redirect('review_report_empty_content');
        }
        if(!$is_edit) {
            $R =& $this->loader->model(':review');
            if(!$review = $R->read($post['rid'])) {
                redirect(lang('review_empty'));
            }
        }
	}

    function disposal($post, $reportid) {
        if(!$detail = $this->read($reportid,'rid,uid,username,posttime')) {
            redirect('review_report_empty');
        }
        $data = array(
            'disposal' => 1,
            'disposaltime' => $this->global['timestamp'], 
            'reportremark' => _T($post['reportremark'])
        );
        if($post['update_point'] && $detail['uid'] > 0 && !$detail['update_point']) {
            $data['update_point'] = (int) $post['update_point'];
        }

        $this->db->from($this->table);
        $this->db->set($data);
        $this->db->where('reportid', $reportid);
        $this->db->update();

        if($post['delete']) {
            $R =& $this->loader->model(':review');
            $R->delete($detail['rid']);
        }
        if($data['update_point'] && $detail['uid'] > 0) {
            $P =& $this->loader->model('member:point');
            $P->update_point($detail['uid'], 'report_review', 0);
        }
    }

	function delete($reportids) {
		if(empty($reportids)) redirect('global_op_unselect');
		if(!is_array($reportids)) $reportids = array((int)$reportids);
		$this->db->from($this->table);
		$this->db->where_in('reportid', $reportids);
		$this->db->delete();
	}
}
?>