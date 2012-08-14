<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_responds`;");
E_C("CREATE TABLE `modoer_responds` (
  `respondid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `rid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `posttime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `ip` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`respondid`),
  KEY `uid` (`uid`,`status`),
  KEY `reviewid` (`rid`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>