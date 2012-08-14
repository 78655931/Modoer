<?php
!defined('IN_MUDDER') && exit('Access Denied');

$_G['dns'] = array();
// 数据库服务器名字(host:port)
$_G['dns']['dbhost'] = 'localhost';
// 数据库帐号
$_G['dns']['dbuser'] = 'root';
// 数据库密码
$_G['dns']['dbpw'] = 'root';
// 数据库名字
$_G['dns']['dbname'] = 'modoer';
// 数据表前缀
$_G['dns']['dbpre'] = 'modoer_';
// 数据库持久连接 0=关闭, 1=打开
$_G['dns']['pconnect'] = 0;
// 数据库编码
$_G['dns']['dbcharset'] = 'gbk';
// 查询数据库前进行Ping操作，解决数据库连接丢失问题
$_G['dns']['ping'] = FALSE;
// 网页编码
$_G['charset'] = 'gb2312';
// Cookie前缀
$_G['cookiepre'] = 'ATWXU_';
// Cookie有效路径
$_G['cookiepath'] = '/';
// Cookie作用域
$_G['cookiedomain'] = '';
// 可防止大量的非正常请求造成的拒绝服务攻击
$_G['attackevasive'] = 0;
// 与格林时间的相差时间，北京时间为8，仅 PHP5 有效
$_G['timezone'] = 8;
// 0=关闭, 1=cookie 刷新限制, 2=限制代理访问, 3=cookie+代理限制
$_G['admincp'] = array();
// 后台管理操作是否验证管理员的IP,1=是[安全],0=否仅在管理员无法登陆后台时设置0。
$_G['admincp']['checkip'] = 1;
// 后台核心文件所在文件夹
$_G['admincp']['dir'] = 'admin';
// 默认实用语言包
$_G['lang_directory'] = 'CH';
// 是否允许在线修改模板
$_G['modify_template'] = FALSE;
