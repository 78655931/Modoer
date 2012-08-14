<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'article');

$_G['in_ajax'] = 1;
$op = _get('op','',MF_TEXT);

// 允许的操作行为
$allow_ops = array( 'category' );
// 需要登录的操作
$login_ops = array( );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(in_array($op, $login_ops) && !$user->isLogin) {
    $_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];
    redirect('member_not_login');
}

switch($op) {
case 'category':
    $pid = (int)$_GET['pid'];
    $select = (int)$_GET['select'];
    $_G['loader']->helper('form', 'article');
    $content = form_article_category($select);
    echo $content;
    output();
default:
    redirect('global_op_unkown');
}
?>