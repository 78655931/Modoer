<?php
/**
* 个人空间入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'space';
require MUDDER_MODULE . 'space/common.php';
?>