<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_subject');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
if(!$admin->is_founder) $_G['db']->where('city_id',$_CITY['aid']);
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_subject_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('item','subject_check') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

$_G['db']->from('dbpre_pictures');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
if(!$admin->is_founder) $_G['db']->where('city_id',$_CITY['aid']);
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_picture_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('item','picture_check') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

$_G['db']->from('dbpre_guestbook');
$total = $_G['db']->count();
$_G['db']->sql_roll_back('from');
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_guestbook_title'),
    'content' => $total . '&nbsp;<a href="' . cpurl('item','guestbook_check') . '">'.lang('admincp_cphome_check_title').'</a>: ' . $check,
);

$_G['db']->from('dbpre_favorites');
$total = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_favorite_title'),
    'content' => $total,
);

$_G['db']->from('dbpre_subjectapply');
$_G['db']->where('status',0);
$total = $_G['db']->count();
$system[] = array(
    'name' => lang('itemcp_cphome_apply_title'),
    'content' => '<a href="'.cpurl('item','subject_apply').'">'.lang('admincp_cphome_check_title').'</a>:'. $total,
);

unset($total,$check);
?>