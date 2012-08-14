<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_comment');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('commentcp_cphome_comment_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('comment','comment_list','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

unset($total,$check);
?>