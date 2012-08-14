<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_card_apply');
$_G['db']->where('status',0);
$check = $_G['db']->count();
$system[] = array(
    'name' => lang('cardcp_cphome_apply_title'),
    'content' => '<a href="' . cpurl('card','apply') . '">'.$check.'</a>',
);

unset($check);
?>