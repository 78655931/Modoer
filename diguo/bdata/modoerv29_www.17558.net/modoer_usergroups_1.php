<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_usergroups`;");
E_C("CREATE TABLE `modoer_usergroups` (
  `groupid` smallint(6) NOT NULL AUTO_INCREMENT,
  `grouptype` enum('member','special','system') DEFAULT 'member',
  `groupname` char(16) DEFAULT '',
  `point` int(10) NOT NULL DEFAULT '0',
  `color` varchar(7) NOT NULL DEFAULT '',
  `price` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `access` text NOT NULL,
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_usergroups` values('1','system','游客','0','#808080','0','a:20:{s:16:\"member_forbidden\";s:1:\"0\";s:14:\"tuan_post_wish\";s:1:\"0\";s:23:\"item_allow_edit_subject\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:12:\"article_post\";s:1:\"0\";s:14:\"article_delete\";s:1:\"0\";s:12:\"coupon_print\";s:1:\"0\";s:16:\"exchange_disable\";s:1:\"0\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"1\";s:10:\"card_apply\";s:1:\"0\";s:8:\"ask_post\";s:1:\"0\";s:10:\"ask_delete\";s:1:\"0\";s:10:\"ask_editor\";s:1:\"0\";}');");
E_D("replace into `modoer_usergroups` values('2','system','禁止访问','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"1\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('3','system','禁止发言','0','#808080','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('4','system','等待验证','0','#0bbfb9','0','a:5:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:2:\"-1\";s:12:\"item_reviews\";s:2:\"-1\";s:13:\"item_pictures\";s:2:\"-1\";s:15:\"comment_disable\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('10','member','注册会员','0','','0','a:19:{s:16:\"member_forbidden\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"10\";s:17:\"item_create_album\";s:1:\"1\";s:14:\"tuan_post_wish\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('12','member','青铜会员','100','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:1:\"0\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('13','member','白银会员','500','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:2:\"30\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"0\";}');");
E_D("replace into `modoer_usergroups` values('14','member','黄金会员','1000','','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:0:\"\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');");
E_D("replace into `modoer_usergroups` values('15','special','VIP会员','0','#FF0000','0','a:18:{s:16:\"member_forbidden\";s:1:\"0\";s:11:\"fenlei_post\";s:1:\"0\";s:13:\"fenlei_delete\";s:1:\"0\";s:10:\"party_post\";s:1:\"1\";s:10:\"review_num\";s:0:\"\";s:13:\"review_repeat\";s:1:\"0\";s:8:\"ask_post\";s:1:\"1\";s:10:\"ask_delete\";s:1:\"1\";s:10:\"ask_editor\";s:1:\"1\";s:13:\"item_subjects\";s:0:\"\";s:13:\"item_pictures\";s:3:\"150\";s:17:\"item_create_album\";s:1:\"1\";s:12:\"article_post\";s:1:\"1\";s:14:\"article_delete\";s:1:\"1\";s:12:\"coupon_print\";s:1:\"1\";s:16:\"exchange_disable\";s:1:\"0\";s:15:\"comment_disable\";s:1:\"0\";s:10:\"card_apply\";s:1:\"1\";}');");

require("../../inc/footer.php");
?>