<?php
/**
* 评论模块入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'comment';
require MUDDER_MODULE . 'comment/common.php';
?>