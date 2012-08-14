<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_subjectlog`;");
E_C("CREATE TABLE `modoer_subjectlog` (
  `upid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `sid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(60) NOT NULL DEFAULT '',
  `ismappoint` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `upcontent` mediumtext NOT NULL,
  `disposal` tinyint(1) NOT NULL DEFAULT '0',
  `posttime` int(10) NOT NULL DEFAULT '0',
  `upremark` mediumtext NOT NULL,
  `disposaltime` int(10) NOT NULL DEFAULT '0',
  `update_point` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`upid`),
  KEY `sid` (`sid`),
  KEY `disposal` (`disposal`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>