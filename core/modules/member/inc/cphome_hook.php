<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->from('dbpre_members');
$total = $_G['db']->count();
$system[] = array(
    'name' => lang('membercp_cphome_member_title'),
    'content' => $total,
);

$_G['db']->from('dbpre_pmsgs');
$total = $_G['db']->count();
$system[] = array(
    'name' => lang('membercp_cphome_pmsg_title'),
    'content' => $total,
);

unset($total,$check);
?>