<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$in_ajax = 1;
$do = trim(_input('do'));
$op = trim(_input('op'));

// ����Ĳ�����Ϊ
$allowacs = array( 'respond', 'review');
// ��Ҫ��¼�Ĳ���
$loginacs = array( 'post_respond', 'delete_respond', 'add_flower' );
// �ɷ��ص�ַ
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>