<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//$sids
$DIS =& $this->loader->model('card:discount');
$DIS->delete_sids($sids);
unset($DIS);
?>