<?php
/**
* @author ��<service@cmsky.org>
* @copyright (c)2009-2012 ������
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$in_ajax = 1;
$do = trim(_T($_GET['do']));
$op = trim(_T($_GET['op']));

// ����Ĳ�����Ϊ
$allowacs = array( 'randcode');
// ��Ҫ��¼�Ĳ���
$loginacs = array( 'compare_randcode' );
// �ɷ��ص�ַ
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>