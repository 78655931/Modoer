<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['assistant_menu']['member'][] = '网站任务,member/index/ac/task';
$_G['assistant_menu']['member'][] = '我的积分,member/index/ac/point';
$_G['assistant_menu']['member'][] = '我的短信箱,member/index/ac/pm';
$_G['assistant_menu']['member'][] = '我的好友,member/index/ac/friend';
$_G['assistant_menu']['member'][] = '升级会员组,member/index/ac/group';
//$_G['assistant_menu']['member'][] = '我的留言板,member/space/ac/gbook';

$_G['assistant_menu']['myset'][] = '我的设置,member/index/ac/myset';
$_G['assistant_menu']['myset'][] = '空间设置,space/member/ac/space';
$_G['assistant_menu']['myset'][] = '头像设置,member/index/ac/face';
$_G['assistant_menu']['myset'][] = '账号绑定,member/index/ac/passport';
$_G['assistant_menu']['myset'][] = '收货地址,member/index/ac/address';
?>