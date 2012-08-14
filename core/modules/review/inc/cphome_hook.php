<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_review');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
if(!$admin->is_founder) $_G['db']->where('city_id',$_CITY['aid']);
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_review_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('review','review','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

$_G['db']->from('dbpre_responds');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_respond_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('review','respond','checklist') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

unset($total,$check);
?>