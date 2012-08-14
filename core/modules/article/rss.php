<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'rss');

$A =& $_G['loader']->model(':article');
@header("Content-Type: application/xml");
echo $A->rss((int)$_GET['catid']);
output();
?>