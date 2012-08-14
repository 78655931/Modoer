<?php
/**
* 讨论组入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'discussion';
require MUDDER_MODULE . 'discussion/common.php';
?>