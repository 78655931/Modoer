<?php
!defined('IN_MUDDER') && exit('Access Denied');
// 标识，唯一值
$newmodule['flag'] = 'sms';
// 名称
$newmodule['name'] = '短信发送';
// 依赖,建立于某够模块之上,核心模块除外，这里写其他模块的标识
$newmodule['reliant'] = '';
// 介绍
$newmodule['introduce'] = '短信发送模块';
// 作者
$newmodule['author'] = 'moufer';
// 网址
$newmodule['siteurl'] = 'http://www.modoer.com';
// 邮件
$newmodule['email'] = 'moufer@163.com';
// 版权
$newmodule['copyright'] = 'Moufer Studio';
// 版本
$newmodule['version'] = '1.0';
// 发布事件
$newmodule['releasetime'] = '2011-12-6';
// 版本检测 返回一个最新版本号，用于比较当前的版本
$newmodule['checkurl'] = 'http://www.modoer.com/info/module/sms.php';
// 是否支持多城市
$newmodule['support_mc'] = 1;
?>