<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_discussion_topic');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
//if(!$admin->is_founder) $_G['db']->where('city_id', $_CITY['aid']);
$_G['db']->where('status', 0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('discussion_cphome_topic_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('discussion','topic','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

$_G['db']->from('dbpre_discussion_reply');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
$_G['db']->where('status', 0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('discussion_cphome_reply_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('discussion','reply','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

unset($total,$check);
?>