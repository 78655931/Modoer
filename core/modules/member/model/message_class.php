<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_message extends ms_model {

    var $table = 'pmsgs';
    var $key = 'pmid';

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->table = $this->global['dns']['dbpre'] . $this->table;
    }
    
    function msm_member_message() {
        $this->__construct();
    }

    function read($uid, $pmid) {
        $result = NULL;
        if(!$result = parent::read($pmid)) return $result;

        if($result['new'] && !empty($result['recvuid']) && $uid == $result['recvuid']) {
            $this->db->from($this->table);
            $this->db->set('new', 0);
            $this->db->where('pmid', $pmid);
            $this->db->update();
            // update newmsg field
            $this->db->from($this->global['dns']['dbpre'] . 'members');
            $this->db->set_dec('newmsg');
            $this->db->where('uid', $result['recvuid']);
            $this->db->update();
        }
        return $result;
    }

    // delflag=1 �ռ���ɾ����outbox����delflag=2 ������ɾ����inbox��
    function delete($uid, $folder, $pmids) {

        if(!in_array($folder, array('inbox','outbox'))) return;
        if($pmids && !is_array($pmids)) $ids = array($pmids);

        $this->db->where($folder=='inbox' ? 'recvuid' : 'senduid', $uid);
        $this->db->where_in('pmid', $pmids);
        $this->db->from($this->table);
        if(!$row = $this->db->get()) return;

        $newmsg = $delids = $flagids = '';
        while($value = $row->fetch_array()) {
            // delflag ����ʱ��˵��һ���Ѿ�ɾ������ʼ���������ɾ���������ϵͳ������Ϣ������ɾ��
            if($value['delflag'] > 0 || empty($value['senduid'])) {
                $delids[] = $value['pmid'];
            } else {
                $flagids[] = $value['pmid'];
            }
            // ���ʼ� �� ɾ�����ռ��� �����һ�����ʼ����Ա������»�Ա����Ϣ��
            if($value['new'] && $folder == 'inbox') $newmsg++;
        }
        $row->free_result();

        // ɾ���ռ��䣬����¸û�Ա�������ʼ�����
        if($folder=='inbox' && $newmsg > 0) {
            $this->db->from($this->global['dns']['dbpre'] . 'members');
            $this->db->set_dec('newmsg', $newmsg);
            $this->db->where('uid', $uid);
            $this->db->update();
        }
        // ����ɾ���ʼ�
        if($delids) {
            $this->db->where_in('pmid', $delids);
            $this->db->from($this->table);
            $this->db->delete();
        }
        // ɾ��һ��������,�����ֶ�
        if($flagids) {
            $this->db->from($this->table);
            $this->db->set('delflag', $folder=='inbox' ? 1 : 2);
            $this->db->where_in('pmid', $flagids);
            $this->db->update();
        }
    }

    //$recvuid �������ʱ�����������ʽ�������ð�Ƕ��ŷָ�
    function send($senduid, $recvuid, $subject, $content, $isusername = FALSE) {
        $subject = _T($subject);
        $content = _HTML($content);
        $recvuids = array_unique(explode(',', $recvuid)); //��ת��Ϊ����
        $this->check_post($recvuid, $subject, $content);

        $this->db->from($this->global['dns']['dbpre'] . 'members');
        $this->db->where_in($isusername ? 'username' : 'uid', $recvuids);
        $this->db->select('uid,username');
        if(!$row = $this->db->get()) redirect('member_pm_empty_member');

        $uids = '';
        while($value = $row->fetch_array()) {
            $uids[] = $value['uid'];
            //send message
            $post = array(
                'senduid' => $senduid,
                'recvuid' => $value['uid'],
                'subject' => $subject,
                'content' => $content,
                'posttime' => $this->global['timestamp'],
                'new' => 1,
                'delflag' => 0,
            );
            parent::save($post, null, null, null);
        }
        $row->free_result();

        if($uids) {
            $this->db->from($this->global['dns']['dbpre'] . 'members');
            $this->db->where_in('uid', $uids);
            $this->db->set_add('newmsg');
            $this->db->update();
        }
        return $uids; // ���ط��͵��û� uid �б�
    }

    function find($uid, $folder, $order_by, $start, $offset, $total=TRUE) {
        if($folder == 'inbox') {
            $w_uid = 'senduid';
            $this->db->where('recvuid', $uid);
        } else {
            $w_uid = 'recvuid';
            $this->db->where('senduid', $uid);
        }
        $this->db->select('p.*');
        $this->db->select('m.username');
        $this->db->select($w_uid, 'uid');
        $this->db->where_not_equal('delflag', $folder=='inbox' ? 1 : 2);
        $this->db->join($this->table ,"p.$w_uid", $this->global['dns']['dbpre'].'members', 'm.uid', 'LEFT JOIN');
        $result = array();
        if($total) {
            $result[] = $this->db->count();
            $this->db->sql_roll_back('from,where,select');
        }
        if(!$total || $result[0]) {
            $this->db->limit($start, $offset);
            $this->db->order_by($order_by);
            $result[] = $this->db->get();
        }
        $this->db->clear();
        return $result;
    }

    function check_post(& $recvuid, & $subject, & $content) {
        if(!$recvuid) {
            redirect('member_pm_empty_recv');
        } elseif(!defined("IN_ADMIN") && count($recvuid) > 5) {
            redirect(sprintf(lang('member_pm_send_total'), 5));
        } elseif(!$subject) {
            redirect('member_pm_empty_subject');
        } elseif(!$content) {
            redirect('member_pm_empty_content');
        } elseif(strlen($subject) > 255) {
            redirect(sprintf(lang('member_pm_strlen_subject'), 255));
        } elseif(strlen($content) > 5000) {
            redirect(sprintf(lang('member_pm_strlen_content'), 5000));
        }
    }

    function clear_new_record($uid) {
        if(!$uid) return;
        $this->db->from($this->table);
        $this->db->set('newmsg', 0);
        $this->db->where('uid', $uid);
        $this->db->update();
    }
}

