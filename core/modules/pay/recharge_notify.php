<?php
/**
 * 在线支付成功后，系统会执行当前文件，根据orderid获取订单状态，完成积分充值
 * 
 * @author moufer<moufer@163.com>
 * @copyright www.modoer.com
 */

!defined('IN_MUDDER') && exit('Access Denied');

$orderid = _input('orderid', null, MF_INT_KEY);

$LOG =& $_G['loader']->model('pay:log');
$LOG->pay_succeed($orderid);

echo 'succeed';
exit;