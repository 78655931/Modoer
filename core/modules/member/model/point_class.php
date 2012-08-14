<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_point extends ms_model {

    var $point = null;
    var $group = null;
    var $allow_del = true; //������ֿ۳�������

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
        $this->_load_point_rule();
    }

    function msm_member_point() {
        $this->__construct();
    }

    function update_point($uid, $sort, $delete=FALSE, $num = 1, $isusername = FALSE, $update = TRUE, $des='') {
        if($uid < 1 || !$uid) return FALSE;
        if(!$this->point) return FALSE;
        if(!isset($this->point[$sort])) return FALSE;
        $points = array();
        $points['point'] = (int) $this->point[$sort]['point'];
        if($this->point[$sort]) foreach($this->point[$sort] as $key => $val) {
            $points[$key] = (int)$val;
        }
        if(!$points) return FALSE;
        //�ж��Ƿ�۵�����
        if($delete && !$this->allow_del) {
            if($this->global['user']->uid == $uid) {
                foreach($points as $key => $val) {
                    if($val > 0 && $this->global['user']->$key < $val) redirect(lang('member_point_less_point_self',$val));
                }
            } else {
                $M =& $this->loader->model(':member');
                if(!$member = $M->read($uid)) return;
                foreach($points as $key => $val) {
                    if($val > 0 && $this->global['user']->$key < $val) redirect(lang('member_point_less_point',array($member['username'], $val)));
                }
            }
        }
        $fun = $delete ? 'set_dec' : 'set_add';
        foreach($points as $key => $val) {
            $this->db->$fun($key, $val * $num);
        }
        $this->db->where($isusername ? 'username' : 'uid', $uid);
        $this->db->from('dbpre_members');
        $update && $this->db->set && $this->db->update();
        return TRUE;
    }

    //���ɿ۳����ǹ̶�ֵ�۳�
    function update_point2($uid, $sort, $point, $des='') {
        if(!$uid || !$point) return FALSE;
        $sorts = array_keys($this->group);
        array_push($sorts,'point','rmb');
        if(!in_array($sort, $sorts)) return FALSE;
        $this->db->from('dbpre_members');
        if($point > 0) {
            $this->db->set_add($sort, $point);
        } else {
            $this->db->set_dec($sort, abs($point));
        }
        $this->db->where('uid', $uid);
        if($this->db->set) $this->db->update();
        if($uid != $this->global['user']->uid) {
            $M =& $this->loader->model(':member');
            if(!$member = $M->read($uid)) return false;
            $uid = $member['uid'];
            $username = $member['username'];
        } else {
            $uid = $this->global['user']->uid;
            $username = $this->global['user']->username;
        }
        //��¼���ֱ仯
		$log =& $this->loader->model('member:point_log');
        if($point > 0) {
            $post['in_uid'] = $uid;
            $post['in_username'] = $username;
            $post['in_point'] = $sort;
            $post['in_value'] = $point;
        } else {
            $post['out_uid'] = $uid;
            $post['out_username'] = $username;
            $post['out_point'] = $sort;
            $post['out_value'] = abs($point);
        }
		$post['dateline'] = $this->global['timestamp'];
		$post['des'] = lang($des);
		$log->save($post);
        return TRUE;
    }

	//���ֶһ�
	function exchange($in_value,$in_point,$out_point) {
		if(!$this->global['user']->isLogin) redirect('�Բ�����δ��¼��');
		if(!$in_value = abs((int)$in_value)) redirect('');
		
		if(!$inpoint = $this->group[$in_point]) redirect('�Բ��𣬲����ڵĶ����������');
		if(!$inpoint['enabled']) redirect('�Բ��𣬶����������δʹ�á�');
		if(!$inpoint['in']) redirect('�Բ��𣬻������Ͳ�������롣');
		if(!$in_rate = $inpoint['rate']) redirect('�Բ��𣬻������Ͷһ�������Ч��');
		
		if(!$outpoint = $this->group[$out_point]) redirect('�Բ��𣬲����ڵĶҳ���������');
		if(!$outpoint['enabled']) redirect('�Բ��𣬶Գ���������δʹ�á�');
		if(!$outpoint['out']) redirect('�Բ��𣬻������Ͳ�����Գ���');
		if(!$out_rate = $outpoint['rate']) redirect('�Բ��𣬻������Ͷһ�������Ч��');

		$needpoint = ceil($in_rate / $out_rate * $in_value);
		if($needpoint > $this->global['user']->$out_point) redirect('�Բ������Ļ��ֲ��㣬�޷���ɱ��ζһ�������');
 
		$this->db->from('dbpre_members');
		$this->db->set_add($in_point, $in_value);
		$this->db->set_dec($out_point, $needpoint);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->update();
		//�һ���¼
		$log =& $this->loader->model('member:point_log');
		$post['out_uid'] = $post['in_uid'] = $this->global['user']->uid;
		$post['out_username'] = $post['in_username'] = $this->global['user']->username;
		$post['out_point'] = $out_point;
		$post['in_point'] = $in_point;
		$post['out_value'] = $needpoint;
		$post['in_value'] = $in_value;
		$post['dateline'] = $this->global['timestamp'];
		$post['des'] = lang('member_point_exchange_des');
		$log->save($post);
		unset($log);
	}

    function _load_point_rule() {
        if($config = $this->variable('config')) {
            $this->point = $config['point'] ? unserialize($config['point']) : '';
            $this->group = $config['point_group'] ? unserialize($config['point_group']) : array();
        }
    }
}