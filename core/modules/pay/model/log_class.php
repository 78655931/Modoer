<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_log extends ms_model {
    
    var $table = 'dbpre_pay_log';
    var $key = 'orderid';
    var $model_flag = 'pay';

    var $modcfg = array();
    var $cz_type = array();

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->cz_type = @unserialize($this->modcfg['cz_type']);
    }

    function create($post, $orderid=null) {
        $P = $this->loader->model(':pay');
        if(!$P->cz_enable) redirect('pay_disabled');
        $edit = $orderid != null;
        if($edit) {
            if(!$detail = $this->read($orderid)) redirect('pay_order_empty');
            if($detail['uid'] != $this->global['user']->uid) redirect('pay_order_owner_invalid');
            if($order['status'] == 1) redirect('pay_order_error_status_1');
            if($order['status'] == 2) redirect('pay_order_error_status_2');
        }

        if(!$this->cz_type) redirect('pay_disabled');
        if(!is_numeric($post['czprice']) || $post['czprice'] <= 0 || $post['czprice'] < $this->modcfg['czmin']) {
            redirect(lang('pay_price_min', $this->modcfg['czmin']));
        } elseif($czprice > $this->modcfg['czmax']) {
            redirect(lang('pay_price_max', $this->modcfg['czmax']));
        } elseif(!$post['cztype'] || !in_array($post['cztype'], $this->cz_type)) {
            redirect('pay_cztype_empty');
        }
        if($post['cztype']=='rmb') {
            $ratio = 1;
        } else {
            $ratio = $this->modcfg['ratio_'.$post['cztype']];
        }
        if(!is_numeric($ratio) || $ratio <= 0) {
            redirect('pay_ratio_empty');
        }
        //��������Ļ���
        if($post['cztype'] == 'rmb') {
            $point = $post['czprice'];
        } else {
            $point = $post['czprice'] * $ratio;
        }
        if($point < 1) redirect(lang('pay_point_empty', display('member:point',"point/$post[cztype]")));
        //�½����׶���
        $insert = array();
        !$edit && $insert['uid'] = $this->global['user']->uid;
        !$edit && $insert['username'] = $this->global['user']->username;
        $insert['price'] = $post['czprice'];
        $insert['point'] = $point;
        $insert['cztype'] = $post['cztype'];
        !$edit && $insert['dateline'] = $this->global['timestamp'];
        $insert['exchangetime'] = 0;
        $insert['status'] = 0;
        $insert['ip'] = $this->global['ip'];
        //�������ݿ⣬���ض�����
        $orderid = parent::save($insert, $orderid);
         //��ת֧��ҳ��
        $payapi = _post('payapi', '', '_T');
        //pay_order_title' => '%s_%s_%s��ֵ',//��վ����,��Ա��,��������
        $post = array(
            //������ʶ����������moder�ڲ�����ģ���orderid
            'order_flag' => 'pay_recharge',
            //������
            'orderid' => $orderid,
            //�����ı���
            'order_name' => lang('pay_order_title', array(_G('cfg','sitename'), 
                $this->global['user']->username, lang('pay_type_point'))),
            //֧���û�uid
            'uid' => $this->global['user']->uid,
            //�ӿڱ�ʶ
            'payment_name' => $payapi,
            //�۸�λԪ
            'price' => $post['czprice'],
            //�����ܣ���֧��֧������ ���磺111@126.com^0.01^����עһ|222@126.com^0.01^����ע��
            'royalty' => '',
            //������������֧���ɹ����ù�����������ִ�ж���֧������߼���PHP����get��ʽ�������˺�ִ̨�У�
            'notify_url' => url("pay/recharge_notify/orderid/$orderid",'',true,true),
            //�������û�֧����Ϻ���ת���ص����ӵ�ַ���ͻ���������򿪣�
            'callback_url' => url("pay/member/ac/pay/op/return/orderid/$orderid", '', true, true),
        );
        //����֧���ӿڼ�¼������ת��֧��ҳ��
        $P->create_pay($post);
    }

    //�˴�����һ��������ġ�notify_url����ַ����ʱ����
    function pay_succeed($orderid) {
        $P = $this->loader->model(':pay');
        //��ȡ֧���ӿڼ�¼
        $pay = $P->read_ex('pay_recharge', $orderid);
        //�ж�֧���ӿڼ�¼�Ƿ���ں�״̬
        if(!$pay) redirect("֧����¼�����ڡ�(OID:$oid)");
        if(!$pay['pay_status']) redirect("�ȴ�֧��״̬��������Ѿ����֧�������Ժ��ٲ鿴��(OID:$oid)");
        if($pay['my_status']) return; //�Ѿ��������
        if(!$order = $this->read($orderid)) redirect('pay_order_empty'); //���Ҷ���
        if($order['status'] == 1) return; //�Ѿ�������ˣ�Ҳ����ʡ�ԣ�ǰ��pay���my_status�Ѿ��ӹ��ˣ�

        $update = array();
        if($order['price'] != $pay['price']) {
            $update['price'] = $price;
            //ȡ���µĶһ�����
            $update['point'] = $this->_ratio($pay['price'], $order['cztype']);
            $point = $update['point'];
        } else {
            $point = $order['point'];
        }
        if($port_orderid) {
            $update['port_orderid'] = $port_orderid;
        }
        $update['exchangetime'] = $this->global['timestamp'];
        $update['status'] = 1;
        //��Ա���Ӷ�Ӧ����
        $this->_czPoint($order['uid'], $point, $order['cztype']);
        //���¶���״̬
        $this->db->from($this->table);
        $this->db->set($update);
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //���¼�¼���Զ���״̬
        $P->update_mystatus($pay['payid'], 1);
    }

    function update_status($uid=null) {
        $hour = $this->modcfg['staletime'] > 0 ? $this->modcfg['staletime'] : 24;
        $endtime = strtotime(date('Y-m-d',$this->global['timestamp'] - $hour*3600));
        $this->db->from($this->table);
        $uid > 0 && $this->db->where('uid', $uid);
        $this->db->set('status', 2); //��ʾ���ڵĶ���
        $this->db->where('status', 0); //��ʾδ֧���Ķ��� 
        $this->db->where_less('dateline',$endtime);
        $this->db->update();
    }

    //֧���ɹ��󣬸���Ա��ֵ����
    function _czPoint($uid, $point, $type='rmb') {
        if(!$uid) return;
        if(!in_array($type, $this->cz_type)) return;
        //����Ա��ֵ,��Ϊ2.0�Ļ�Աģ�鲻֧���ֽ��ֶγ�ֵ�����Զ���дһ��
        $this->db->from('dbpre_members');
        $this->db->set_add($type, $point);
        $this->db->where('uid', $uid);
        $this->db->update();
		//��ֵ��¼
        $member = $this->loader->model(':member')->read($uid); //�˴�Ӧ��ͨ��uid��û�Ա����Ҫʹ��user�࣬�����û����˳���¼���޷�����û�����
		$log =& $this->loader->model('member:point_log');
		$post['out_uid'] = 0;
        $post['in_uid'] = $uid;
		$post['out_username'] = '';
        $post['in_username'] = trim($member['username']);
		$post['out_point'] = '';
		$post['in_point'] = $type;
		$post['out_value'] = 0;
		$post['in_value'] = $point;
		$post['dateline'] = $this->global['timestamp'];
		$post['des'] = lang('member_point_pay_des');
		$log->save($post);
    }

    //�������
    function _ratio($price, $type) {
        //��������Ļ���
        if($type == 'rmb') {
            $point = $price;
        } else {
            $point = $price * (int) $this->modcfg['ratio_'.$type];
        }
        return $point;
    }
}
?>