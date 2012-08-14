<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->query("ALTER TABLE ".$_G['dns']['dbpre']."_subject DROP coupons");
?>