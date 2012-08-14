<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_modules`;");
E_C("CREATE TABLE `modoer_modules` (
  `moduleid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `flag` varchar(30) NOT NULL DEFAULT '',
  `iscore` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(60) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `disable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  `version` varchar(60) NOT NULL DEFAULT '',
  `releasetime` date NOT NULL DEFAULT '0000-00-00',
  `reliant` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(255) NOT NULL DEFAULT '',
  `introduce` text NOT NULL,
  `siteurl` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `copyright` varchar(255) NOT NULL DEFAULT '',
  `checkurl` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`moduleid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk");
E_D("replace into `modoer_modules` values('1','member','1','8','会员','member','0','','1.1','2008-09-30','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('2','item','1','1','主题','item','0','','2.5','2011-05-24','','Moufer Studio',' ','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('3','space','1','9','个人空间','space','0','','1.1','2008-09-30','','Moufer Studio','','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('4','link','0','10','友情链接','link','0','','2.0','2010-05-04','','moufer','友情链接模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php');");
E_D("replace into `modoer_modules` values('5','product','0','2','主题产品','product','0','','1.1','2010-03-27','','moufer','用于商铺类主题的产品列表','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/product.php');");
E_D("replace into `modoer_modules` values('6','comment','0','6','会员评论','comment','0','','1.0','2010-04-01','','moufer','评论模块可用于其他需要进行评论的模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/comment.php');");
E_D("replace into `modoer_modules` values('7','exchange','0','5','礼品兑换','exchange','0','','3.0','2012-05-01','','moufer,轩','使用网站金币兑换礼品，消费金币','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/exchange.php');");
E_D("replace into `modoer_modules` values('8','article','0','3','新闻资讯','article','0','','2.0','2010-04-14','','moufer','文章信息，发布网站信息和主题资讯','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/article.php');");
E_D("replace into `modoer_modules` values('9','card','0','7','会员卡','card','0','','2.0','2010-05-06','item','moufer','会员卡模块用户管理消费类主题提供优惠折扣信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/card.php');");
E_D("replace into `modoer_modules` values('10','coupon','0','4','优惠券','coupon','0','','2.0','2010-05-10','','moufer','优惠券模块，提供分享和打印折扣和优惠信息','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/coupon.php');");
E_D("replace into `modoer_modules` values('11','adv','0','10','广告','adv','0','','2.0','2010-12-13','','moufer','自定义广告模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/adv.php');");
E_D("replace into `modoer_modules` values('12','review','1','2','点评','review','0','','2.5','2011-05-24','','Moufer Studio','点评模块','http://www.modoer.com','moufer@163.com','Moufer Studio','');");
E_D("replace into `modoer_modules` values('13','sms','0','10','短信发送','sms','0','','1.0','2011-12-06','','moufer','短信发送模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/sms.php');");
E_D("replace into `modoer_modules` values('14','pay','0','10','在线充值','pay','0','','2.2','2012-03-30','','moufer','在线积分充值模块','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/pay.php');");
E_D("replace into `modoer_modules` values('15','discussion','0','10','讨论组','discussion','0','','1.0','2012-03-10','','moufer','用于主题内的讨论话题交流','http://www.modoer.com','moufer@163.com','Moufer Studio','http://www.modoer.com/info/module/discuss.php');");

require("../../inc/footer.php");
?>