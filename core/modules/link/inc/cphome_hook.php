<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_mylinks');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
$_G['db']->where('ischeck',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('linkcp_cphome_link_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('link','link','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

unset($total,$check);
?>