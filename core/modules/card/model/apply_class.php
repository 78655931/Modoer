<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_card_apply extends ms_model {

    var $table = 'dbpre_card_apply';
	var $key = 'applyid';
    var $model_flag = 'card';
    var $subject_table = 'dbpre_subject';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'card';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_card_apply() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('linkman,tel,mobile,address,postcode,num,coin,comment');
		$this->add_field_fun('num,coin', 'intval');
        $this->add_field_fun('linkman,tel,mobile,address,postcode,comment', '_T');
	}

    function save($post, $applyid = NULL) {
        $edit = $applyid != null;
        //判断权限
        $access = $this->in_admin || $this->global['user']->check_access('card_apply', $this, 0);
        if(!$access) redirect('card_card_apply_disable');
        if($edit) {
            if(!$detail = $this->read($applyid)) redirect('card_apply_empty');
        } else {
            if(!$this->modcfg['apply']) redirect('card_apply_disable');
            $need_coin = 0;
            if($pointgroup = $this->modcfg['pointgroup']) {
                $need_coin = intval($this->modcfg['coin']) * intval($post['num']);
                $config = $this->loader->variable('config','member');
                $point_group = $config['point_group'] ? unserialize($config['point_group']) : array();
                if($need_coin > $this->global['user']->$pointgroup) redirect(lang('card_apply_coin_not_enough', array($point_group[$pointgroup]['name'], $need_coin)));
            }
            $this->check_apply_exists($this->global['user']->uid); //检测是否已经申请
            if(!$this->in_admin) $post['status'] = 0;
            $post['coin'] = $need_coin;
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
            $post['dateline'] = $this->global['timestamp'];
            $post['checker'] = '';
            $post['checkmsg'] = '';
        }
        $applyid = parent::save($post, $applyid);

        //扣除用户金币
        if($need_coin > 0) {
            $P =& $this->loader->model('member:point');
            $P->update_point2($this->global['user']->uid,$pointgroup, -$need_coin, lang('card_apply_update_point_dec_des', $post['num']));
            unset($P);
        }

        return $applyid;
    }

    //后台处理申请
    function checkup($applyid,$status,$checkmsg) {
        if(!$detail = $this->read($applyid)) redirect('card_apply_empty');
        if(!$status) redirect('card_apply_status_empty');
        if(!$checkmsg) redirect('card_apply_checkmsg_empty');

        $this->db->from($this->table);
        $this->db->set('status', (int)$status);
        $this->db->set('checker', $this->global['admin']->adminname);
        $this->db->set('checkmsg', _T($checkmsg));
        $this->db->set('checktime', $this->global['timestamp']);
        $this->db->where('applyid', $applyid);
        $this->db->update();

        //返还不通过的积分
        if($status == 2 && $detail['checktime']==0) {
            if($pointgroup = $this->modcfg['pointgroup']) {
                $P =& $this->loader->model('member:point');
                $P->update_point2($detail['uid'],$pointgroup,$detail['coin'],lang('card_apply_update_point_add_des'));
            }
        }

        //短信息发送
        if($_POST['pm']) {
            $status_array = lang('card_status_array');
            $subject = lang('cardcp_apply_checkup_pm_subject', $status_array[$status]);
            $message = nl2br($checkmsg);
            $msg =& $this->loader->model('member:message');
            $msg->send(0, $detail['uid'], $subject, $message);
        }
    }

    function delete($applyids) {
        $ids = parent::get_keyids($applyids);
        parent::delete($ids);
    }

    function check_post(& $post, $edit = false) {
        if(!$post['linkman']) {
            redirect('card_apply_linkman_empty');
        } elseif(!is_numeric($post['num']) || $post['num'] < 1) {
            redirect('card_apply_num_invalid');
        } elseif($post['tel'] && !preg_match('/^[0-9\-]+$/', $post['tel'])) {
            redirect('card_apply_tel_invalid');
        } elseif(!preg_match('/^[0-9\-]+$/', $post['mobile'])) {
            redirect('card_apply_mobile_invalid');
        } elseif(!preg_match('/^[0-9]+$/', $post['postcode'])) {
            redirect('card_apply_postcode_invalid');
        } elseif(!$post['address']) {
            redirect('card_apply_address_empty');
        }
        $maxnum = $this->modcfg['applynum'] > 0 ? $this->modcfg['applynum'] : 1;
        if($post['num'] > $maxnum) redirect(lang('card_apply_mun_overtop',$maxnum));
    }

    //检测是否正在申请中
    function check_apply_exists($uid) {
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        $this->db->select('applyid,dateline,status');
        $this->db->order_by('dateline','DESC');
        if(!$v = $this->db->get_one()) return false;
        if(!$v['status']) redirect('card_apply_exists');
    }

    //会员组权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key == 'card_apply') {
            $value = (int) $value;
            if(!$value || !$this->modcfg['apply']) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('card_card_apply_disable');
            }
        }
        return TRUE;
    }

    //统计状态数量
    function status_total($uid=null) {
        $this->db->from($this->table);
        $this->db->select('status');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $uid && $this->db->where('uid',$uid);
        $this->db->group_by('status');
        if(!$q = $this->db->get())return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['status']] = $v['count'];
        }
        $q->free_result();
        return $result;
    }
}
?>