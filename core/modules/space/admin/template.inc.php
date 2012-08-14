<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$_GET['type'] = 'space';
$subtitle = lang('spacecp_template_title');

include MUDDER_ADMIN . 'template.inc.php';
?>