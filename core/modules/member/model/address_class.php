<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_address extends ms_model {

    var $table = 'dbpre_member_address';
    var $key = 'id';

    var $modcfg = null;
    var $error = array();

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function init_field() {
        $this->add_field('uid,name,addr,postcode,mobile,is_default');
        $this->add_field_fun('uid,is_default', 'intval');
        $this->add_field_fun('name,addr,mobile,postcode', '_T');
    }

    function get_list($uid=null) {
        !$uid && $uid = $this->global['user']->uid;
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        return $this->db->get();
    }

    function save($post, $id=null) {
        $edit = $id!=null;
        if($edit) {
            $detail = $this->read($id);
            if(empty($detail)) redirect('�Բ������༭�ĵ�ַ��Ϣ�����ڡ�');
            if($detail['uid'] != $this->global['user']->uid) redirect('�Բ�����༭�ĵ�ַ��Ϣ����������');
        } else {
            $post['uid'] = $this->global['user']->uid;
        }
        $this->check_post($post, $edit);
        if($this->error) return;
        $id = parent::save($post, $id, $edit);
        if($post['is_default']) $this->cancel_default($id, $post['uid']);
        return $id;
    }

    function check_post(& $post, $isedit) {
        $this->loader->helper('validate');
        $this->error = array();
        if(!$post['name']||strlen($post['addr'])<2) {
            $this->error['name'] = 'δ��д�ջ�������';
        }
        if(!$post['addr']||strlen($post['addr'])<6) {
            $this->error['addr'] = 'δ��д��ϸ�ջ���ַ';
        }
        if(!$post['mobile']) {
            $this->error['mobile'] = 'δ��д�ֻ�����';
        } else if(!validate::is_mobile($post['mobile'])) {
            $this->error['mobile'] = '��д���ֻ������ʽ����';
        }
    }

    function delete($id) {
        $detail = $this->read($id);
        if(!$detail) redirect('�Բ�����༭�ĵ�ַ��Ϣ�����ڡ�');
        if($detail['uid']!=$this->global['user']->uid) redirect('�Բ�����༭�ĵ�ַ��Ϣ����������');
        parent::delete($id);
        return TRUE;
    }

    function cancel_default($out_id,$uid) {
        !$uid && $uid = $this->global['user']->uid;
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        $this->db->where_not_equal('id', $out_id);
        $this->db->set('is_default', 0);
        $this->db->update();
    }

}
/* end */