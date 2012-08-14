<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_G['db']->query("ALTER TABLE ".$_G['dns']['dbpre']."subject ADD coupons MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0' AFTER pageviews");
?>