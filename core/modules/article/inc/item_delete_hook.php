<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//$sids ����id����
$A = $this->loader->model(':article');
$A->delete_sids($sids);
unset($A);
?>