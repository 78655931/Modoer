<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay extends ms_model {
    
    var $table = 'dbpre_pay';
    var $key = 'payid';
    var $model_flag = 'pay';

    var $modcfg = array();
    var $payments = array(); //保存接口列表
    var $cz_enable = false; //接口打开了

    var $payid = 0;    //当前正在处理的支付记录ID
    var $payment = null; //当前支付接口类

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        foreach(array('alipay','tenpay','chinabank','paypal') as $k) {
            !empty($this->modcfg[$k]) and $this->payments[] = $k;
        }
        $this->cz_enable = !empty($this->payments);
    }

    function read_ex($order_flag, $orderid) {
        if(!$order_flag||!$orderid) return;
        $this->db->from($this->table);
        $this->db->where('order_flag', $order_flag);
        $this->db->where('orderid', $orderid);
        return $this->db->get_one();
    }

    //创建支付并跳转到支付页面
    function create_pay($post) {
        if(!$this->cz_enable) redirect('pay_disabled');
        $post['creation_time'] = $this->timestamp;
        if(!$post['order_flag']) redirect('未指定支付来源模块，请联系管理员。');
        if(!$post['orderid']) redirect('未指定支付订单号，请联系管理员。');

        if($pay = $this->read_ex($post['order_flag'], $post['orderid'])) {
            $pay['pay_status'] and redirect('支付已完成，不能再次提交。');
            //可以更新下支付接口记录里的数据
            $this->db->from($this->table);
            if($post['notify_url']) $this->db->set('notify_url', $post['notify_url']);
            if($post['callback_url']) $this->db->set('callback_url', $post['callback_url']);
            if($post['order_name']) $this->db->set('order_name', $post['order_name']);
            if($post['payment_name']) $this->db->set('payment_name', $post['payment_name']);
            if($post['price']) $this->db->set('price', $post['price']);
            if(isset($post['royalty'])) $post['royalty'] = '';
            $this->db->set('royalty', $post['royalty']);
            $this->db->where('payid', $pay['payid']);
            $this->db->update();
            $this->payid = $pay['payid'];
        } else {
            if(!$post['order_name']) redirect('您未填写订单名称，请联系管理员。');
            if(!$post['payment_name'] && !$this->check_payment($post['payment_name'])) redirect('未知的支付接口，请联系管理员。');
            if(!$post['price'] && $post['price'] <= 0) redirect('对不起，未填写正确的支付金额。');
            if(!$post['notify_url']) redirect('未设置订单支付完成通知地址，请联系管理员。');
            if(!$post['callback_url']) redirect('未设置订单返回地址，请联系管理员。');
            $this->payid = parent::save($post);
        }
        $this->goto_pay($post['payment_name']);
    }

    function instante_payment($payment) {
        if(!$payment || !in_array($payment, $this->payments)) redirect('pay_payment_unselect');
        $this->loader->model('pay:' . $payment, FALSE);
        $classname = 'msm_pay_' . $payment;
        $this->payment = new $classname();
    }

    function goto_pay($payment) {
        $this->instante_payment($payment);
        $this->payment->goto_pay($this->payid, $this->create_unique());
    }

    //支付接口检查
    function check_payment($apiname) {
        if(!$apiname || !in_array($apiname, $this->payments)) redirect('pay_payment_unselect');
    }

    //接口方返回支付情况，本地监测，并更新订单状态
    function pay_notify($api) {
        $this->instante_payment($api);
        $this->get_payid();
        $succeed = $this->payment->notify_check();
        if($succeed) $this->succeed_pay();
        if($this->payment->notify_return) {
            $this->payment->notify_return();
        }
    }

    //支付返回
    function pay_return($api) {
        $this->instante_payment($api);
        $this->get_payid();
        $pay = $this->read($this->payid);
        if(empty($pay)) redirect('订单信息不存在。', url('member/index'));
        location($pay['callback_url']);
    }

    //更新支付记录
    function succeed_pay() {
        $pay = $this->read($this->payid);
        if(!$pay) {
            log_write('pay', 'payid不存在'.$this->payid);
            return ; //也要记录下
        }
        if($pay['pay_status']) {
            log_write('pay', 'payid已经支付完成'.$this->payid);
            if($pay['notify_url']) {
                $this->send_notify($pay['notify_url']);
            }
        }
        $this->db->from($this->table);
        $this->db->where('payid', $this->payid);
        $this->db->set('pay_status', 1);
        $this->db->set('pay_time', $this->timestamp);
        $this->db->set('payment_orderid', $this->get_payment_orderid());
        $this->db->update();
        //向订单发送支付成功的通知，让订单维护代码更新支付后的流程
        if($pay['notify_url']) {
            $this->send_notify($pay['notify_url']);
        }
        //如果更新支付信息后结束
        if($this->payment->notify_end) {
            location($pay['callback_url']);
        }
    }

    //打开执行订单完成逻辑
    function send_notify($notify_url) {
        $content = $this->do_get($notify_url);
        if($content!='succeed') {
            //记录下不返回succeed时的内容，便于排查
            log_write('pay', $content);
        }
    }

    //更新自定义状态
    function update_mystatus($payid, $status) {
        $this->db->from($this->table);
        $this->db->where('payid', $payid);
        $this->db->set('my_status', (int)$status);
        $this->db->update();
    }

    //生成一个惟一订单标识
    function create_unique() {
        $s = date('Ymd') . $this->payid;
        return $s;
    }

    //解析惟一标识里的payid（主要是防止重复安装模块时payid会和以前安装时的id相同，
    //造成支付平台出现商家订单号冲突，所以需要一个惟一的标识作为商家订单号发送到支付平台）
    function parse_payid($unid) {
        $payid = substr($unid, 8);
        return $payid;
    }

    //从支付通知里获取订单的id
    function get_payid() {
        $this->payid = (int)$this->parse_payid($this->payment->get_unid());
    }

    //从支付通知里获取支付接口的订单id,仅作记录
    function get_payment_orderid() {
        $id = _T($this->payment->get_payment_orderid());
        return $id ? $id : '';
    }

    function do_get($url) {
        $url = str_replace('&amp;', '&', $url);
	    if(function_exists('curl_exec')) {
	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_URL, $url);
	        $result =  curl_exec($ch);
	        curl_close($ch);
	        return $result;
	    } elseif (ini_get("allow_url_fopen") == "1") {
	        $result = file_get_contents($url);
	        if($result) return $result;
	         echo "<h3>file_get_contents:failed to open stream!</h3>";
	         exit();
	    } else {
	        echo '<h3>PHP function (curl_exec) does not exist.</h3>';
	        exit();
	    }
    }

	function do_post($url, $params) {
		$fields_string = '';
		foreach($params as $key=>$value){
			$fields_string .="{$key}={$value}&";
		}
		rtrim($fields_string,'&');
		$xurl = $url . '?' . $fields_string;
		if(!function_exists('curl_exec')) {
			echo '<h3>PHP function (curl_exec) does not exist.</h3>'; exit;
		}
		$ch = curl_init ($url."?");
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
		$result = curl_exec($ch);
		curl_close ($ch);  
		return $result;
	}

}
