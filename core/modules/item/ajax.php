<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$in_ajax = 1;
$do = trim(_T($_GET['do']));
$op = trim(_T($_GET['op']));

// ����Ĳ�����Ϊ
$allowacs = array( 'respond', 'review', 'subject', 'picture', 'guestbook');
// ��Ҫ��¼�Ĳ���
$loginacs = array( 'post_respond', 'delete_respond', 'add_flower', 'post_membereffect', 'add_favorite', 'post_guestbook' );
// �ɷ��ص�ַ
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>