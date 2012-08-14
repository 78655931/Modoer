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
    var $payments = array(); //����ӿ��б�
    var $cz_enable = false; //�ӿڴ���

    var $payid = 0;    //��ǰ���ڴ����֧����¼ID
    var $payment = null; //��ǰ֧���ӿ���

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

    //����֧������ת��֧��ҳ��
    function create_pay($post) {
        if(!$this->cz_enable) redirect('pay_disabled');
        $post['creation_time'] = $this->timestamp;
        if(!$post['order_flag']) redirect('δָ��֧����Դģ�飬����ϵ����Ա��');
        if(!$post['orderid']) redirect('δָ��֧�������ţ�����ϵ����Ա��');

        if($pay = $this->read_ex($post['order_flag'], $post['orderid'])) {
            $pay['pay_status'] and redirect('֧������ɣ������ٴ��ύ��');
            //���Ը�����֧���ӿڼ�¼�������
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
            if(!$post['order_name']) redirect('��δ��д�������ƣ�����ϵ����Ա��');
            if(!$post['payment_name'] && !$this->check_payment($post['payment_name'])) redirect('δ֪��֧���ӿڣ�����ϵ����Ա��');
            if(!$post['price'] && $post['price'] <= 0) redirect('�Բ���δ��д��ȷ��֧����');
            if(!$post['notify_url']) redirect('δ���ö���֧�����֪ͨ��ַ������ϵ����Ա��');
            if(!$post['callback_url']) redirect('δ���ö������ص�ַ������ϵ����Ա��');
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

    //֧���ӿڼ��
    function check_payment($apiname) {
        if(!$apiname || !in_array($apiname, $this->payments)) redirect('pay_payment_unselect');
    }

    //�ӿڷ�����֧����������ؼ�⣬�����¶���״̬
    function pay_notify($api) {
        $this->instante_payment($api);
        $this->get_payid();
        $succeed = $this->payment->notify_check();
        if($succeed) $this->succeed_pay();
        if($this->payment->notify_return) {
            $this->payment->notify_return();
        }
    }

    //֧������
    function pay_return($api) {
        $this->instante_payment($api);
        $this->get_payid();
        $pay = $this->read($this->payid);
        if(empty($pay)) redirect('������Ϣ�����ڡ�', url('member/index'));
        location($pay['callback_url']);
    }

    //����֧����¼
    function succeed_pay() {
        $pay = $this->read($this->payid);
        if(!$pay) {
            log_write('pay', 'payid������'.$this->payid);
            return ; //ҲҪ��¼��
        }
        if($pay['pay_status']) {
            log_write('pay', 'payid�Ѿ�֧�����'.$this->payid);
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
        //�򶩵�����֧���ɹ���֪ͨ���ö���ά���������֧���������
        if($pay['notify_url']) {
            $this->send_notify($pay['notify_url']);
        }
        //�������֧����Ϣ�����
        if($this->payment->notify_end) {
            location($pay['callback_url']);
        }
    }

    //��ִ�ж�������߼�
    function send_notify($notify_url) {
        $content = $this->do_get($notify_url);
        if($content!='succeed') {
            //��¼�²�����succeedʱ�����ݣ������Ų�
            log_write('pay', $content);
        }
    }

    //�����Զ���״̬
    function update_mystatus($payid, $status) {
        $this->db->from($this->table);
        $this->db->where('payid', $payid);
        $this->db->set('my_status', (int)$status);
        $this->db->update();
    }

    //����һ��Ωһ������ʶ
    function create_unique() {
        $s = date('Ymd') . $this->payid;
        return $s;
    }

    //����Ωһ��ʶ���payid����Ҫ�Ƿ�ֹ�ظ���װģ��ʱpayid�����ǰ��װʱ��id��ͬ��
    //���֧��ƽ̨�����̼Ҷ����ų�ͻ��������Ҫһ��Ωһ�ı�ʶ��Ϊ�̼Ҷ����ŷ��͵�֧��ƽ̨��
    function parse_payid($unid) {
        $payid = substr($unid, 8);
        return $payid;
    }

    //��֧��֪ͨ���ȡ������id
    function get_payid() {
        $this->payid = (int)$this->parse_payid($this->payment->get_unid());
    }

    //��֧��֪ͨ���ȡ֧���ӿڵĶ���id,������¼
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
