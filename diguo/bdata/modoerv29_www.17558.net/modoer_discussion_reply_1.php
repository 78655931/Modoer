<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_discussion_reply`;");
E_C("CREATE TABLE `modoer_discussion_reply` (
  `rpid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tpid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '10',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isownerpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`rpid`),
  KEY `tpid` (`tpid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>