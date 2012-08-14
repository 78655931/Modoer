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

// 允许的操作行为
$allowacs = array( 'respond', 'review');
// 需要登录的操作
$loginacs = array( 'post_respond', 'delete_respond', 'add_flower' );
// 可返回地址
$_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

$act = empty($do) || !in_array($do, $allowacs) ? '' : $do;

if(!$do) redirect('global_op_unkown');

include MOD_ROOT . 'ajax' . DS . $do . '.php';
?>