<?php
/**
* sec code
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'seccode');

$refererhost = parse_url($_SERVER['HTTP_REFERER']);
$refererhost['host'] .= !empty($refererhost['port']) ? (':'.$refererhost['port']) : '';
/*
if($refererhost['host'] != $_SERVER['HTTP_HOST']) {
    exit('Access Denied');
}
*/
$seccode = strtoupper(seccode_random(4));
set_cookie('seccode', create_formhash(strtolower($seccode),'',''));

require_once MUDDER_CORE . 'lib' . DS . 'seccode.php';
ms_seccode_factory::create()
    ->setSeccode($seccode)
    ->setWidth(75)
    ->setHeight(25)
    ->display();

function seccode_random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}
?>
