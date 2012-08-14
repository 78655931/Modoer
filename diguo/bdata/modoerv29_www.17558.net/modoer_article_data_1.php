<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `modoer_article_data`;");
E_C("CREATE TABLE `modoer_article_data` (
  `articleid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`articleid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk");
E_D("replace into `modoer_article_data` values('1','<div class=\"content\">\r\n<h3>商铺功能</h3>\r\n<ul>\r\n<li>可建立多板块的点评，例如（餐饮，旅游，购物，娱乐，服务等）</li>\r\n<li>每个板块可以分类，并按类别输出信息（如餐饮板块可以建立火锅，海鲜等，出行/旅游板块可以建立汽车，旅行社等）</li>\r\n<li>商铺可以设置，商铺名称，分店名称，主营菜系，地址，电话，手机，店铺标签(Tag)，并可增加分店</li>\r\n<li>商家可通过认领功能，来管理自己的点评</li>\r\n<li>商铺自定义风格功能</li>\r\n<li>会员可补充商铺信息</li>\r\n<li>已有商铺可增加分店</li>\r\n<li>商铺可以根据环境，产品或者其他补充图片集展示，图片支持缩略图，水印功能</li>\r\n<li>可自定义设置商铺封面</li>\r\n<li>所有会员的提交信息可自动提交和后台管理审核</li>\r\n<li>自定义城市区域，可以精确到街道</li>\r\n<li>地图标注和地图报错功能</li>\r\n<li>商铺视频功能</li>\r\n<li>会员去过，想去的互动</li>\r\n</ul>\r\n<h3>点评功能</h3>\r\n<ul>\r\n<li>商铺可以针对各个板块可以自定义点评项名称和评分项数量），喜欢程度，人均消费，消费感受，适合类型进行点评，会员并可推荐产品以及设置店铺Tag，其他会员可以对点评进行献花和回应，反馈，举报点评</li>\r\n<li>会员并可推荐产品以及设置店铺 Tag</li>\r\n<li>其他会员可以对点评进行赠送鲜花和回应，反馈</li>\r\n<li>可举报点评</li>\r\n</ul>\r\n<h3>会员卡模块</h3>\r\n<ul>\r\n<li>可自定义设置会员卡名称</li>\r\n<li>可设置会员卡在商铺的折扣或者优惠活动和备注说明</li>\r\n<li>可设置推荐加盟商家</li>\r\n</ul>\r\n<h3>兑奖中心模块</h3>\r\n<ul>\r\n<li>会员可通过点评，登记，回应等一系列互动操作得到金币积分，利用这些积分可对话相应积分的奖品</li>\r\n<li>后台可添加和设置奖品以及奖品说明</li>\r\n</ul>\r\n<h3>优惠券模块</h3>\r\n<ul>\r\n<li>会员可上传优惠券，可供其他会员下载打印优惠券</li>\r\n<li>后台可设置优惠券审核</li>\r\n<li>其他会员可投票是否优惠券是否有用</li>\r\n</ul>\r\n<h3>新闻咨询模块</h3>\r\n<ul>\r\n<li>发布新闻资讯</li>\r\n<li>商家可发布店铺的咨询信息</li>\r\n<li>其他会员可投票是否优惠券是否有用</li>\r\n</ul>\r\n<h3>排行榜功能</h3>\r\n<ul>\r\n<li>餐厅排行（最佳餐厅、口味最佳、环境最佳、服务最佳）</li>\r\n<li>最新餐厅（近一周加入、近一月加入、近三月加入）</li>\r\n</ul>\r\n<h3>会员功能</h3>\r\n<ul>\r\n<li>会员短信功能</li>\r\n<li>个人主页功能（可以设置、更改个人主页名称和风格）</li>\r\n<li>好友设置功能</li>\r\n<li>个人留言版功能</li>\r\n<li>会员积分功能</li>\r\n<li>会员鲜花功能</li>\r\n<li>收藏夹功能</li>\r\n<li>积分等级</li>\r\n</ul>\r\n<h3>模块功能</h3>\r\n<ul>\r\n<li>Modoer系统以模块为基础组成</li>\r\n<li>可以Modoer为平台开发安装模块</li>\r\n</ul>\r\n<h3>高级数据调用</h3>\r\n<ul>\r\n<li>利用内置的函数和SQL调用方式调用数据</li>\r\n<li>可设置每个调用的模板和空数据的模板</li>\r\n<li>调用数据可设置缓存，较少系统数据库资源消耗</li>\r\n<li>支持本地和JS方式调用数据</li>\r\n<li><br />\r\n</li>\r\n</ul>\r\n<h3>插件功能</h3>\r\n<ul>\r\n<li>利用插件接口可丰富系统功能</li>\r\n<li>集成提供多个插件（图片轮换，网站公告）</li>\r\n</ul>\r\n<h3>系统整合</h3>\r\n<ul>\r\n<li>万能整合API，可与任何PHP程序进行整合</li>\r\n<li>整合UCenter（账户，短信，好友，积分兑换，Feed推送，个人空间跳转UCH）</li>\r\n</ul>\r\n<h3>其他功能</h3>\r\n<ul>\r\n<li>词语过滤可设置不同的过滤方式：阻止，替换，审核</li>\r\n<li>菜单管理可自定义模板显示的菜单，不需要再修改模板</li>\r\n<li>伪静态功能优化SEO</li>\r\n</ul>\r\n</div>');");
E_D("replace into `modoer_article_data` values('2','好东西分享 - 网罗精品软件、精品源码、精品网站，网罗一切免费web资源分享给大家！');");

require("../../inc/footer.php");
?>