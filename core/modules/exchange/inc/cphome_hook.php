<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_exchange_log');
$_G['db']->where('status',1);
$total = $_G['db']->count();
$system[] = array(
    'name' => lang('exchangecp_cphome_title'),
    'content' => '<a href="' . cpurl('exchange','exchange','list',array('status'=>1)) . '">'.lang('exchangecp_cphome_apply_title').'</a>: ' . $total,
);
unset($total,$check);
?>