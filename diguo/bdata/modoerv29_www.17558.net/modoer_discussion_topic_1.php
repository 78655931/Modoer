<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_discussion_topic`;");
E_C("CREATE TABLE `modoer_discussion_topic` (
  `tpid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `replytime` int(10) unsigned NOT NULL DEFAULT '0',
  `isownerpost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pictures` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`tpid`),
  KEY `sid` (`sid`,`replytime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>