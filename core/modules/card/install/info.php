<?php
!defined('IN_MUDDER') && exit('Access Denied');
// 标识，唯一值
$newmodule['flag'] = 'card';
// 名称
$newmodule['name'] = '会员卡';
// 依赖,建立于某够模块之上,核心模块除外，这里写其他模块的标识
$newmodule['reliant'] = 'item';
// 介绍
$newmodule['introduce'] = '会员卡模块用户管理消费类主题提供优惠折扣信息';
// 作者
$newmodule['author'] = 'moufer';
// 网址
$newmodule['siteurl'] = 'http://www.modoer.com';
// 邮件
$newmodule['email'] = 'moufer@163.com';
// 版权
$newmodule['copyright'] = 'Moufer Studio';
// 版本
$newmodule['version'] = '2.0';
// 发布事件
$newmodule['releasetime'] = '2010-5-6';
// 版本检测 返回一个最新版本号，用于比较当前的版本
$newmodule['checkurl'] = 'http://www.modoer.com/info/module/card.php';
// 是否支持多城市
$newmodule['support_mc'] = 1;
?>