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
        //计算这算的积分
        if($post['cztype'] == 'rmb') {
            $point = $post['czprice'];
        } else {
            $point = $post['czprice'] * $ratio;
        }
        if($point < 1) redirect(lang('pay_point_empty', display('member:point',"point/$post[cztype]")));
        //新建交易订单
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
        //加入数据库，返回订单号
        $orderid = parent::save($insert, $orderid);
         //跳转支付页面
        $payapi = _post('payapi', '', '_T');
        //pay_order_title' => '%s_%s_%s充值',//网站名称,会员名,积分类型
        $post = array(
            //订单标识，用于区别moder内部各个模块的orderid
            'order_flag' => 'pay_recharge',
            //订单号
            'orderid' => $orderid,
            //订单的标题
            'order_name' => lang('pay_order_title', array(_G('cfg','sitename'), 
                $this->global['user']->username, lang('pay_type_point'))),
            //支付用户uid
            'uid' => $this->global['user']->uid,
            //接口标识
            'payment_name' => $payapi,
            //价格单位元
            'price' => $post['czprice'],
            //分润功能（仅支持支付宝） 例如：111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二
            'royalty' => '',
            //此连接用于在支付成功后让关联订单代码执行订单支付后的逻辑（PHP函数get方式服务器端后台执行）
            'notify_url' => url("pay/recharge_notify/orderid/$orderid",'',true,true),
            //此连接用户支付完毕后跳转返回的连接地址（客户端浏览器打开）
            'callback_url' => url("pay/member/ac/pay/op/return/orderid/$orderid", '', true, true),
        );
        //生成支付接口记录，并跳转到支付页面
        $P->create_pay($post);
    }

    //此处由上一个函数里的“notify_url”地址进入时触发
    function pay_succeed($orderid) {
        $P = $this->loader->model(':pay');
        //获取支付接口记录
        $pay = $P->read_ex('pay_recharge', $orderid);
        //判断支付接口记录是否存在和状态
        if(!$pay) redirect("支付记录不存在。(OID:$oid)");
        if(!$pay['pay_status']) redirect("等待支付状态，如果您已经完成支付，请稍后再查看。(OID:$oid)");
        if($pay['my_status']) return; //已经处理过了
        if(!$order = $this->read($orderid)) redirect('pay_order_empty'); //查找订单
        if($order['status'] == 1) return; //已经处理过了（也可以省略，前面pay表的my_status已经接管了）

        $update = array();
        if($order['price'] != $pay['price']) {
            $update['price'] = $price;
            //取得新的兑换比率
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
        //会员增加对应积分
        $this->_czPoint($order['uid'], $point, $order['cztype']);
        //更新订单状态
        $this->db->from($this->table);
        $this->db->set($update);
        $this->db->where('orderid', $orderid);
        $this->db->update();
        //更新记录表自定义状态
        $P->update_mystatus($pay['payid'], 1);
    }

    function update_status($uid=null) {
        $hour = $this->modcfg['staletime'] > 0 ? $this->modcfg['staletime'] : 24;
        $endtime = strtotime(date('Y-m-d',$this->global['timestamp'] - $hour*3600));
        $this->db->from($this->table);
        $uid > 0 && $this->db->where('uid', $uid);
        $this->db->set('status', 2); //表示过期的订单
        $this->db->where('status', 0); //表示未支付的订单 
        $this->db->where_less('dateline',$endtime);
        $this->db->update();
    }

    //支付成功后，给会员充值积分
    function _czPoint($uid, $point, $type='rmb') {
        if(!$uid) return;
        if(!in_array($type, $this->cz_type)) return;
        //给会员充值,因为2.0的会员模块不支持现金字段充值，所以独立写一个
        $this->db->from('dbpre_members');
        $this->db->set_add($type, $point);
        $this->db->where('uid', $uid);
        $this->db->update();
		//充值记录
        $member = $this->loader->model(':member')->read($uid); //此处应该通过uid获得会员，不要使用user类，避免用户在退出登录后无法获得用户数据
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

    //计算汇率
    function _ratio($price, $type) {
        //计算这算的积分
        if($type == 'rmb') {
            $point = $price;
        } else {
            $point = $price * (int) $this->modcfg['ratio_'.$type];
        }
        return $point;
    }
}
?>