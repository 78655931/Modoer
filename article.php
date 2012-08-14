<?php
/**
* 文章模块前台入口
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}
$_G['m'] = 'article';
require MUDDER_MODULE . 'article/common.php';
?>