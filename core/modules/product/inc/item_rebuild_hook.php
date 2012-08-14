<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$PDT =& $this->loader->model(':product');
return array('products'=>$PDT->get_subject_total($sid));
?>