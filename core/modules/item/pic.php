<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$sid = _get('sid',null,'intval');
location(url("item/album/sid/$sid"));