<?php
/**
 * ����֧���ɹ���ϵͳ��ִ�е�ǰ�ļ�������orderid��ȡ����״̬����ɻ��ֳ�ֵ
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