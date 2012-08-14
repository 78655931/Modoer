<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$LOG =& $_G['loader']->model('pay:log');
$PAY =& $_G['loader']->model(':pay');
$op = _input('op');

$_G['loader']->helper('form','pay');

if($op == 'return') {

    $orderid = _get('orderid', 0, 'intval');
    $order = $LOG->read($orderid);
    if(empty($order)) redirect('订单不存在！');
    redirect('充值完毕！', url('member/index/ac/point'));

} elseif(check_submit('paysubmit')) {

    //检测接口
    $payapi = _post('payapi', '', '_T');
    if(isset($_POST['orderid'])) {
        $orderid = _post('orderid',0,'intval');
    } else {
        $orderid = null;
    }
    $LOG->create(_post('pay'), $orderid);

} elseif(check_submit('cardsubmit')) {

    $PC =& $_G['loader']->model('pay:card');
    $card = $PC->recharge(_post('card_no'), _post('card_pw'), _post('no_password',0,'intval'), _post('type'));
	$typename = $card['cztype'] == 'rmb' ? lang('pay_type_rmb') : template_print('member','point',array('point'=>$card['cztype']));
    if($forward = _T(base64_decode(_cookie('pay_return_forward', null)))) {
        del_cookie('pay_return_forward');
        redirect('充值完毕！正在返回团购下单页面...', $forward);
    }
    redirect(lang('pay_card_recharge_succeed',array($card['price'], $typename)), url("pay/member/ac/pay"));

} elseif(isset($_GET['orderid']) && $_GET['orderid'] > 0) {

    $orderid = (int) $_GET['orderid'];
    if(!$order = $LOG->read($orderid)) redirect('pay_order_empty');
    if($order['status'] == 1) redirect('pay_order_error_status_1');
    if($order['status'] == 2) redirect('pay_order_error_status_2');
    $tplname = 'pay';

} else {

     if($forward = _get('forward', null, 'base64_decode')) {
        set_cookie('pay_return_forward', base64_encode($forward));
    }

    if(!$PAY->cz_enable && !$MOD['card']) redirect('pay_payment_empty');
    $select = _get('select');
    $price = _get('price',null,'floatval');
    $tplname = 'pay';
}

?>