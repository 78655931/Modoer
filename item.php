<?php
/**
* 主题点评入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'item';
require MUDDER_MODULE . 'item/common.php';
?>