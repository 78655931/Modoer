<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ucenter_message extends ms_model {

    var $table = 'dbpre_pmsgs';
    var $key = 'pmid';

    function __construct() {
        parent::__construct();
    }
    
    function msm_ucenter_message() {
        $this->__construct();
    }

    function read($uid, $pmid) {
        return uc_pm_viewnode($uid, 0, $pmid);
    }

    // delflag=1 收件箱删除（outbox），delflag=2 发件箱删除（inbox）
    function delete($uid, $folder, $pmids) {
        if(!in_array($folder, array('inbox','outbox'))) return;
        if($pmids && !is_array($pmids)) $ids = array($pmids);
        return uc_pm_delete($uid, $folder, $pmids);
    }

    //$recvuid 多个发送时，不是数组格式，而是用半角逗号分隔
    function send($senduid, $recvuid, $subject, $content, $isusername = FALSE) {
        if(!$senduid) { //公告信息
            $LM =& $this->loader->model('member:message',TRUE,NULL,FALSE);
            return $LM->send($senduid, $recvuid, $subject, $content, $isusername);
        }
        $subject = _T($subject);
        $content = _HTML($content);
        $recvuids = array_unique(explode(',', $recvuid)); //先转化为数组
        $this->check_post($recvuid, $subject, $content);

        $return = uc_pm_send($senduid, implode(',',$recvuids), $subject, $content, 1, 0, $isusername?1:0);
        if($return < 1) redirect('ucenter_pm_send_' . $return);

        return $return;
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

    function get_new_pbulic_num($uid) {
        $this->db->from('dbpre_pmsgs');
        $this->db->where('recvuid',$uid);
        $this->db->where('new',1);
        return $this->db->count();
    }
}

