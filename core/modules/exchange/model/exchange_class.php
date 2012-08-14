<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_exchange extends ms_model {

    var $table = 'dbpre_exchange_log';
	var $key = 'exchangeid';
    var $model_flag = 'exchange';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'exchange';
        $this->modcfg = $this->variable('config');
        $this->init_field();
	}

    function msm_exchange() {
        $this->__construct();
    }

	function init_field() {
        $this->add_field('giftid,pay_style,number,linkman,contact,postcode,address,des');
        $this->add_field_fun('giftid,pay_style,number', 'intval');
        $this->add_field_fun('linkman,contact,postcode,address,des', '_T');
    }

    function save($post) {
        $gift = $this->check_exchange((int)$post['giftid']);
		$post['city_id'] = $gift['city_id'];
        $post['giftname'] = $gift['name'];
        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['exchangetime'] = $this->global['timestamp'];
        $post['status'] = 1;
        //兑换总量
	    if($post['pay_style']==1 && $gift['pattern']!=2){
        	$post['price'] = $gift['price'];
        	$post['pointtype'] = $gift['pointtype'];
        	$total_price = $gift['price'] * (int) $post['number'];
        	$pointtype = $gift['pointtype'];
        	$pointname = template_print('member','point',array('point'=>$gift['pointtype']));
        }elseif($post['pay_style']==2 && $gift['pattern']!=2){
        	$post['price'] = $gift['point'];
        	$post['pointtype'] = $gift['pointtype2'];
        	$total_price = $gift['point'] * (int) $post['number'];
        	$pointtype = $gift['pointtype2'];
        	$pointname = template_print('member','point',array('point'=>$gift['pointtype2']));
        }elseif($post['pay_style']==3 && $gift['pattern']!=2){
        	$post['price'] = $gift['point3']+$gift['point4'];
        	$post['pointtype'] = $gift['pointtype3'].'+'.$gift['pointtype4'];
        	$total_price = $gift['point3'] * (int) $post['number'];
        	$total_price2 = $gift['point4'] * (int) $post['number'];
        	$pointtype = $gift['pointtype3'];
        	$pointtype2 = $gift['pointtype4'];
        	$pointname = template_print('member','point',array('point'=>$gift['pointtype3']));
        	$pointname2 = template_print('member','point',array('point'=>$gift['pointtype4']));
        }
	   	if($post['pay_style']==3 && $gift['pattern']!=2){
	   		if($this->global['user']->$pointtype < $gift['point3'] * (int) $post['number']) {
        		redirect(lang('exchange_check_price_less',$pointname));
        	}
        	if($this->global['user']->$pointtype2 < $gift['point4'] * (int) $post['number']) {
        		redirect(lang('exchange_check_price_less',$pointname2));
        	}
        }elseif($post['pay_style']!=3 && $gift['pattern']!=2){
        	if($this->global['user']->$pointtype < $total_price) {
        		redirect(lang('exchange_check_price_less',$pointname));
        	}
        }
        if($gift['num'] < $post['number']) redirect('exchange_check_stockout2');
        $this->check_post($post, $gift['pattern'], false, $gift['sort']=='2');
		if($gift['sort'] != '2') {
	        $out_arr = array('linkman','contact','postcode','address','des');
	        $params = array();
	        foreach($out_arr as $k) {
	            $params[] = $post[$k];
	            unset($post[$k]);
	        }
	        $post['contact'] = lang('exchange_contact_format',$params);
		} else {
			$post['contact'] = $post['des'];
			unset($post['des']);
		}
		//虚拟卡库存监测
		if($gift['sort']=='2') {
			$sr =& $this->loader->model('exchange:serial');
			$serial_ids = $sr->get_serial($gift['giftid'], $post['number']);
			if(!$serial_ids || count($serial_ids)<$post['number']) redirect('exchange_check_stockout2');
		}
        //提交
        $exchangeid = parent::save($post,null,false,false,false);
        if($_GET['lid']&&$gift['pattern']==2) $this->up_status($_GET['lid'],$this->global['user']->uid);
        //会员积分变化
        if($gift['pattern']==1){
        	if($post['pay_style']==3){
        		$this->member_coin($this->global['user']->uid, -$total_price, $pointtype, $gift['name']);
        		$this->member_coin($this->global['user']->uid, -$total_price2, $pointtype2, $gift['name']);
        	}else{
        		$this->member_coin($this->global['user']->uid, -$total_price, $pointtype, $gift['name']);
        	}
        }
        
        //较少库存
        $GT =& $this->loader->model($this->model_flag.':gift');
        $GT->salevolume($gift['giftid'], $post['number']);
		//处理虚拟礼品
		if($gift['sort']=='2') {
			$sr =& $this->loader->model('exchange:serial');
			$sr->update_serial($serial_ids, $exchangeid);
			$this->update($exchangeid,3,'');
			//发短信息
			$subject = lang('exchange_message_subject');
			$content = lang('exchange_message_content',array($exchangeid,$gift['name']));
			$MSG =& $this->loader->model('member:message');
			$MSG->send(0, $this->global['user']->uid, $subject, $content);
		}
        //添加兑换事件
        $this->_feed($gift);
        return $exchangeid;
    }

    //更新状态
    function update($exchangeid,$status,$des='') {
        if(!$exchangeid) return;
        if($status == '4') $this->refund($exchangeid); //退款
        $this->db->from($this->table);
        $this->db->set('status',$status);
        $this->db->set('status_extra',$des);
        $this->db->set('checker',$this->global['admin']->adminname);
        $this->db->where('exchangeid',$exchangeid);
        $this->db->update();
    }

    //退款
    function refund($exchangeid) {
        if(!$exchangeid) return;
        if(!$detail = $this->read($exchangeid)) return;
        //会员积分返还
        $price = $detail['price'] * $detail['number'];
        $this->member_coin($detail['uid'], $price, $detail['pointtype'], $detail['giftname']);
        //累计库存
        $GT =& $this->loader->model($this->model_flag.':gift');
        $GT->salevolume($detail['giftid'], -$detail['number']);
    }

    //删除
    function delete($exids, $return_point = false,$return_gift = false) {
        if(is_numeric($exids) && $exids > 0) $exids = array($exids);
        if(!is_array($exids) || !$exids) redirect('global_op_unselect');
        if(!$return_point && !$return_gift) {
            parent::delete($exids);
            return;
        }
        $this->db->from($this->table);
        $this->db->where_in('exchangeid', $exids);
        $this->db->select('exchangeid,uid,number,price,pointtype,status,giftid,giftname');
        if(!$q=$this->db->get()) return;
        $dels = array();
        if($return_gift) $GT =& $this->loader->model($this->model_flag.':gift');
        while($v=$q->fetch_array()) {
            $dels[] = $v['exchangeid'];
            //会员积分返还
            if($return_point) $this->member_coin($v['uid'], $v['price'] * $v['number'], $v['pointtype']);
            //还原库存可销售
            if($return_gift) $GT->salevolume($v['giftid'], -$v['number']);
        }
        parent::delete($dels);
    }

    function check_post(& $post, $pattern, $edit = false, $virtual = false) {
		if($pattern==1 && !is_numeric($post['pay_style'])) {
            redirect('您未选择支付此次兑换的积分，请返回选择。');
        }
		if(!is_numeric($post['number']) || $post['number'] < 1) {
            redirect('exchange_post_number_less');
        }
		if($virtual) return;
        if(!$post['linkman']) {
            redirect('exchange_post_linkman_empty');
        }
		if(!$post['contact']) {
            redirect('exchange_post_contact_empty');
        }
		if(!preg_match('/^[0-9]+$/', $post['postcode'])) {
            redirect('exchange_post_postcode_format_error');
        }
		if(!$post['address']) {
            redirect('exchange_post_address_empty');
        }
    }

    function check_exchange($giftid) {
        $GT =& $this->loader->model($this->model_flag.':gift');
        if(!$gift = $GT->read($giftid)) redirect('exchange_gift_empty');
        if(!$gift['available']) redirect('exchange_check_invalid');
        if($gift['num'] <= 0) redirect('exchange_check_stockout');

        $pointtype = $gift['pointtype'];
        $pointname = template_print('member','point',array('point'=>$gift['pointtype']));
        $pointtype2 = $gift['pointtype2'];
        $pointname2 = template_print('member','point',array('point'=>$gift['pointtype2']));

        if($gift['price'] && $gift['point']){
        	if($this->global[user]->$pointtype < $gift['price'] && $this->global[user]->$pointtype2 < $gift['point']) redirect(lang('exchange_check_price_less',$pointname.' 和 '.$pointname2));
        }else{
        	if($this->global[user]->$pointtype < $gift['price']) redirect(lang('exchange_check_price_less',$pointname));
        }
        return $gift;
    }

    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key == 'exchange_disable') {
            $value = (int) $value;
            if($value) {
                if(!$jump) return FALSE;
                redirect('exchange_access_disable');
            }
        }
        return TRUE;
    }

    function up_status($lid,$uid,$num=1) {
        if(!$lid || !$num) return;
        $this->db->from('dbpre_exchange_lottery');
        $this->db->set('status',$num);
        $this->db->where('lid',$lid);
        $this->db->where('uid',$uid);
        $this->db->update();
    }

    function status_total($uid=null,$city_id=null) {
        $this->db->from($this->table);
		if($city_id) $this->db->where('city_id', $city_id);
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

    //会员积分变化
    function member_coin($uid,$point,$type,$title) {
        $P =& $this->loader->model('member:point');
        $act = $point>=0?'add':'dec';
        $P->update_point2($uid, $type, $point, lang('exchange_point_'.$act.'_des',$title));
    }

    function count_total($giftid) {
    	$begintime = mktime(date('H',$this->global['timestamp']),0,0,date('m',$this->global['timestamp']),date('d',$this->global['timestamp']),date('Y',$this->global['timestamp']));
    	$endtime = mktime(date('H',$this->global['timestamp']),59,0,date('m',$this->global['timestamp']),date('d',$this->global['timestamp']),date('Y',$this->global['timestamp']));
    	$this->db->from('dbpre_exchange_lottery');
    	$this->db->where('giftid',$giftid);
    	$this->db->where_between_and('dateline',$begintime,$endtime);
        return $this->db->count();
    }

    function _feed($giftid) {
        if(!$this->global['user']->uid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        $GT =& $this->loader->model('exchange:gift');
        $detail = is_numeric($giftid) ? $GT->read($giftid) : $giftid;
        if(!$detail) return;

        $feed = array();
        $feed['icon'] = lang('exchange_feed_icon');
        $feed['title_template'] = lang('exchange_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/".$this->global['user']->uid).'">' . $this->global['user']->username . '</a>',
        );
        $feed['body_template'] = lang('exchange_feed_body_template');
        $feed['body_data'] = array (
            'name' => '<a href="'.url("exchange/gift/id/$detail[giftid]").'">'.$detail['name'].'</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $this->global['user']->uid, $this->global['user']->username, $feed);
    }
}
?>