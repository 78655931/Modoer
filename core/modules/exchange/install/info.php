<?php
!defined('IN_MUDDER') && exit('Access Denied');
// 标识，唯一值
$newmodule['flag'] = 'exchange';
// 名称
$newmodule['name'] = '礼品兑换';
// 依赖,建立于某够模块之上,核心模块除外，这里写其他模块的标识
$newmodule['reliant'] = '';
// 介绍
$newmodule['introduce'] = '使用网站金币兑换礼品，抽奖，刺激玩家的积极性，消费金币';
// 作者
$newmodule['author'] = 'moufer，轩';
// 网址
$newmodule['siteurl'] = 'http://www.modoer.com';
// 邮件
$newmodule['email'] = 'moufer@163.com';
// 版权
$newmodule['copyright'] = 'Moufer Studio，风格店铺';
// 版本
$newmodule['version'] = '3.0';
// 发布事件
$newmodule['releasetime'] = '2012-5-1';
// 版本检测 返回一个最新版本号，用于比较当前的版本
$newmodule['checkurl'] = 'http://www.modoer.com/info/module/exchange.php';
// 是否支持多城市
$newmodule['support_mc'] = 1;
?>