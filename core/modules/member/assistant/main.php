<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
* @version $Id 3 2008-08-25 18:35 $
*/
!defined('IN_MUDDER') && exit('Access Denied');

//хннЯ
$tasklist = $_G['loader']->model('member:task')->mytask(0);
?>