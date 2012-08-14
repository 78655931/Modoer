<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//$sids
$PDT =& $this->loader->model(':product');
$PDT->delete_sids($sids);
unset($PDT);
?>